/*
 * There is no lock because in psql lock is in concept named TRANSACTION
 * function is always inside a TRANSACTION
 * so use lock in outside
 * TODO: there will be a file provide such interfaces

 * naming rules: directly get info from table use query [users table is an exception]
 *               use multiple table to form query result use get
 *               if query can be done by add, delete, update, select directly on a table,
                        declare function in table function section
                        call it at requirement accomplishment section
 * TODO: rename function name to clarify and be unique
 *     : error code more scientific
 *     : formalize the declaration of integer/int, boolean/bool, array
 *             : query func use query_(target)[_from_param]__[table for short]__
 */
-- Requirement Function Section --
----------------------------------
-- Requirement 3 --
/* @note: relevant check and encrypt done outside by php */
/* @caller-constraints: use lock outside */
/*                    : lock type - TO_FILL */

drop function if exists user_register cascade;

create or replace function user_register(
    in user_name varchar(20),
    in user_password varchar(20),
    in phone_num integer[11],
    in user_email varchar(20),
    out uid integer,
    out error error_type__u__
)
as
$$
begin
    select * into uid, error from insert_all_info_into__u__(user_name, user_password, phone_num, user_email);
end;
$$ language plpgsql;

drop function if exists user_login cascade;

create or replace function user_login(
    in user_name varchar(20),
    in user_password varchar(20),
    out uid integer,
    out error error_type__u__
)
as
$$
begin
    select * from query_uid_from_uname_password__u__(user_name, user_password) into uid, error;
end;
$$ language plpgsql;

-- Requirement 4 --
/* @note: query specific train */
/*      : we return id also, to help later function */
/* @caller-constraints: use lock outside */
/*                    : lock type - TO_FILL */
/* @TODO: can add param train type and seat type to filter result */

drop function if exists get_train_info cascade;

create or replace function get_train_info(
    in train_name varchar(10),
    in q_date date default get_date_from_now(1)
)
    returns table
            (
                station_order integer,
                station       varchar(20),
                station_id    integer,
                city          varchar(20),
                city_id       integer,
                arrive_time   time,
                leave_time    time,
                stay_time     interval,
                durance       interval,
                distance      integer,
                seat_price    decimal(5, 1)[7],
                seat_num      integer[7]
            )
    language plpgsql
as
$$
declare
    train_id   integer;
    start_time time;
begin
    select query_train_id_from_name__t__(train_name) into train_id;
    select query_start_time_from_id__tfi__(train_id) into start_time;
    return query select tfi_station_order                                                                                              as station_order,
                        s_station_name                                                                                                 as station,
                        s_station_id                                                                                                   as station_id,
                        c_city_name                                                                                                    as city_name,
                        c_city_id                                                                                                      as city_id,
                        tfi_arrive_time                                                                                                as arrive_time,
                        tfi_leave_time                                                                                                 as leave_time,
                        (select *
                         from get_actual_interval_bt_time(tfi_arrive_time, tfi_leave_time, 0))                                         as stay_time,
                        (select *
                         from get_actual_interval_bt_time(start_time, tfi_arrive_time,
                                                          (select query_day_from_departure_from_id__tfi__(train_id, tfi_station_id)))) as durance,
                        tfi_distance                                                                                                   as distance,
                        tfi_price                                                                                                      as seat_price,
                        stt_num                                                                                                        as seat_num
                 from train_full_info tfi
                          left join station_list s on tfi.tfi_station_id = s.s_station_id
                          left join city c on s.s_station_city_id = c.c_city_id
                          left join train t on tfi.tfi_train_id = t.t_train_id
                          left join station_tickets stt on tfi.tfi_station_id = stt.stt_station_id
                 where t_train_id = train_id
                   and stt.stt_date = q_date;
end;
$$;

-- Requirement 5 --
/* @note: query train between 2 cities */
/*      : we return id also, to help later function */
/*      : we ONLY search for tickets that trains can go today */
/*      : result ordered outside */
/* @caller-constraints: use lock outside */
/*                    : lock type - TO_FILL */
/* @TODO: can add param train type and seat type to filter result */
-- currently found effective way to return is through [setof] --
drop table if exists train_info cascade;

create table if not exists train_info
(
    train_name        varchar(10),
    train_id          integer,
    station_from_name varchar(20),
    station_from_id   integer,
    station_to_name   varchar(20),
    station_to_id     integer,
    leave_time        time,
    arrive_time       time,
    durance           interval,
    distance          integer,
    seat_prices       decimal(5, 1)[7],
    seat_nums         integer[7],
    transfer_first    boolean,
    transfer_late     boolean
);
-- check reach table --
drop function if exists check_reach_table cascade;

create or replace function check_reach_table(
    in city_from_id integer,
    in city_to_id integer,
    out reachable boolean
)
as
$$
declare
    reach_table boolean[];
begin
    select c_reach_table into reach_table from city where c_city_id = city_from_id;
    reachable := reach_table[city_to_id];
end;
$$ language plpgsql;

/* @caller-constraints: use lock outside */
/*                    : lock type - TO_FILL */

drop function if exists get_train_bt_cities_directly cascade;

create or replace function get_train_bt_cities_directly(
    in from_city_id integer,
    in to_city_id integer,
    in q_date date,
    in q_time time
    --,
    -- TODO: in train_type varchar(1)
)
    returns setof train_info
as
$$
declare
    --
    city_reachable          boolean;
    --
    train_id_list           integer[];
    train_idi               integer;
    train_namei             varchar(10);
    --
    station_leave_id        integer;
    station_leave_name      varchar(20);
    station_leave_day       integer;
    station_leave_distance  integer;
    station_leave_time      time;
    station_leave_price     decimal(5, 1)[7];
    --
    station_arrive_id       integer;
    station_arrive_name     varchar(20);
    station_arrive_time     time;
    station_arrive_day      integer;
    station_arrive_distance integer;
    station_arrive_price    decimal(5, 1)[7];
    res_price               decimal(5, 1)[7];
    --
    seat_nums               integer[7];
    seat_i                  integer := 1;
    r                       train_info%rowtype;
begin
    select check_reach_table(from_city_id, to_city_id) into city_reachable;
    if city_reachable then
        train_id_list := array(
                select from_city_train.ct_train_id
                from city_train from_city_train
                         join city_train to_city_train on from_city_train.ct_train_id = to_city_train.ct_train_id
                where (select get_ct_priority(from_city_id, from_city_train.ct_train_id)) <
                      (select get_ct_priority(to_city_id, to_city_train.ct_train_id))
            );
        <<scan_train_list>>
        foreach train_idi in array train_id_list
            loop
            -- 2 ways of accomplishment --
            -- leave station --
                select get_station_id_from_cid_tid(from_city_id, train_idi) into station_leave_id;
                select query_station_name_from_id__s__(station_leave_id) into station_leave_name;
                select q_all_info_leave.leave_time,
                       q_all_info_leave.day_from_departure,
                       q_all_info_leave.distance,
                       q_all_info_leave.price
                into station_leave_time, station_leave_day, station_leave_distance, station_arrive_price
                from query_train_all_info_from_tid_sid__tfi__(train_idi, station_leave_id) q_all_info_leave;
                -- check time --
                if station_leave_time < q_time then
                    continue scan_train_list;
                end if;
                select query_train_name_from_id__t__(train_idi) into train_namei;
                -- arrive station --
                select get_station_id_from_cid_tid(to_city_id, train_idi) into station_arrive_id;
                select query_station_name_from_id__s__(station_arrive_id) into station_arrive_name;
                select q_all_info_arrive.leave_time,
                       q_all_info_arrive.day_from_departure,
                       q_all_info_arrive.distance,
                       q_all_info_arrive.price
                into station_arrive_time, station_arrive_day, station_arrive_distance, station_leave_price
                from query_train_all_info_from_tid_sid__tfi__(train_idi, station_arrive_id) q_all_info_arrive;
                -- seats and price calculation --
                for seat_i in 1..7
                    loop
                        select array_set(station_arrive_price, station_arrive_price[seat_i],
                                         station_arrive_price[seat_i] - station_leave_price[seat_i])
                        into res_price;
                    end loop;
                select get_min_seat.seat_num
                into seat_nums
                from get_min_seats(train_idi, q_date, station_leave_id, station_arrive_id,
                                   array ['YZ', 'RZ', 'YW_S', 'YW_Z', 'YW_X', 'RW_S', 'RW_X']) get_min_seat;
                -- return row --
                for r in
                    select train_namei                                                                as train_name,
                           train_idi                                                                  as train_id,
                           station_leave_name                                                         as station_from_name,
                           station_leave_id                                                           as station_from_id,
                           station_arrive_name                                                        as station_to_name,
                           station_arrive_id                                                          as station_to_id,
                           station_leave_time                                                         as leave_time,
                           station_arrive_time                                                        as arrive_time,
                           (select *
                            from get_actual_interval_bt_time(station_leave_time, station_arrive_time,
                                                             station_arrive_day - station_leave_day)) as durance,
                           station_arrive_distance - station_leave_distance                           as distance,
                           res_price                                                                  as seat_price,
                           seat_nums                                                                  as seat_nums,
                           false                                                                      as transfer_first,
                           false                                                                      as transfer_late
                    loop
                        return next r;
                    end loop;
            end loop;
    end if;
    return;
end;
$$ language plpgsql;

/* @caller-constraints: use lock outside */
/*                    : lock type - TO_FILL */

drop function if exists get_train_bt_cities cascade;

create or replace function get_train_bt_cities(
    in city_from varchar(20),
    in city_to varchar(20),
    in q_date date,
    in q_time date,
    -- TODO: in train_type varchar(1),
    in query_transfer boolean
)
    returns setof train_info
as
$$
declare
    --
    from_city_id           integer;
    to_city_id             integer;
    city_reachable         boolean;
    src_city               integer[] := array [from_city_id];
    neighbour_city         integer[];
    passing_trains         integer[];
    current_level_city_num integer   := 0;
    transfer_interval      interval;
    city_i                 integer   := 1;
    r                      train_info%rowtype;
    j                      train_info%rowtype;
begin
    select query_city_id_from_name__c__(city_from) into from_city_id;
    select query_city_id_from_name__c__(city_to) into to_city_id;
    select check_reach_table(from_city_id, to_city_id) into city_reachable;
    if city_reachable then
        if not query_transfer then
            for r in
                select * from get_train_bt_cities_directly(from_city_id, to_city_id, q_date, q_time)
                loop
                    return next r;
                end loop;
        else
            -- first set of transfer trains must be ones passing from city --
            -- so outside loop --
            passing_trains := array(select query_train_id_list_from_cid__ct__(from_city_id));
            while (select array_length(src_city, 1)) > 0 and (select array_position(src_city, to_city_id)) is not null
                loop
                    select array_length(src_city, 1) into current_level_city_num;
                    for city_i in 1..current_level_city_num
                        loop
                            neighbour_city := array(select get_ct_next_city_list(src_city[1], passing_trains));
                            src_city := array(select array_cat(src_city, neighbour_city));
                            -- initially from_city_id was in src_city --
                            -- so remove it first because we have dealt with it --
                            src_city := array(select array_remove_elem(src_city, 1));
                            -- then src_city[1] is middle city to transfer --
                            if (select array_length(src_city, 1)) > 1 then
                                for r in
                                    (select *
                                     from get_train_bt_cities_directly(from_city_id,
                                                                       src_city[1],
                                                                       q_date,
                                                                       q_time))
                                    loop
                                        for j in
                                            (select *
                                             from get_train_bt_cities_directly(src_city[1],
                                                                               to_city_id, q_date,
                                                                               q_time + r.durance +
                                                                               interval '1 hour'))
                                            loop
                                                if r.station_to_id = j.station_from_id then
                                                    select *
                                                    from get_actual_interval_bt_time(r.arrive_time, j.leave_time, 0)
                                                    into transfer_interval;
                                                    if transfer_interval >= interval '1 hour'
                                                        and transfer_interval <= interval '4 hours'
                                                    then
                                                        return next r;
                                                        return next j;
                                                    end if;
                                                else
                                                    if transfer_interval >= interval '2 hours'
                                                        and transfer_interval <= interval '4 hours'
                                                    then
                                                        return next r;
                                                        return next j;
                                                    end if;
                                                end if;
                                            end loop;
                                    end loop;
                            end if;
                        end loop;
                end loop;
            return;
        end if;
    end if;
end;
$$ language plpgsql;

-- Requirement 7 --
/* @param: we use data_process from previous queries, it's php's mission to get and maintain */
/* @return: only seat id */
/* @note: show the orders and order train and seats */
/*      : we view this function as confirmation of order */
/*      : so we assume that php stored all info needed and it can display info itself */
/*      : only seat id, we need to query and atomically change */
/*      : we use three functions:   show train order create order and set status to TO_BE_PAID, return order info  */
/*                                  order take effect, fill in user id and change status  */
/*                                  check status function, if status not changed after some time, cancel it */
/*      : we only return info needed, php can deal with info if exists already */
/* @see also: order_train_seat */
/* @caller-constraints: use lock outside */
/*                    : lock type - TO_FILL */
/* TODO: add account balance function, so there is a new order status: ORDERED, or TO_BE_BOUGHT */

drop function if exists pre_order_train cascade;

create or replace function pre_order_train(
    in train_id integer,
    in station_from_id integer,
    in station_to_id integer,
    in seat_type seat_type,
    in seat_num integer,
    in order_date date,
    in uid_list integer[],
    out succeed boolean,
    out seat_id integer,
    out order_id integer
)
as
$$
declare
    uid  integer;
    uidi integer default 0;
begin
    select succeed, left_seat
    into succeed, seat_id
    from try_occupy_seats(train_id, order_date, station_from_id, station_to_id, seat_type, seat_num);
    if succeed then
        foreach uid in array uid_list
            loop
                insert into orders (o_uid, o_train_id, o_date, o_start_station, o_end_station, o_seat_type, o_seat_id,
                                    o_status, o_effect_time)
                select uid,
                       train_id,
                       order_date,
                       station_from_id,
                       station_to_id,
                       seat_type,
                       seat_id + uidi,
                       'PRE_ORDERED',
                       now();
                uidi := uidi + 1;
            end loop;
        select currval(pg_get_serial_sequence('orders', 'o_oid')) into order_id;
    else
        order_id := 0;
    end if;
end;
$$ language plpgsql;

drop function if exists order_train_seats cascade;

create or replace function order_train_seats(
    in order_id integer,
    in uid_list integer[],
    out succeed boolean[]
)
as
$$
declare
    uid  integer;
    uidi integer default 0;
begin
    foreach uid in array uid_list
        loop
            -- atomically --
            select * from orders where o_oid = order_id + uidi;
            if not found then
                select * into succeed from array_append(succeed, false);
            else
                select * into succeed from array_append(succeed, true);
            end if;
            update orders
            set (o_uid, o_status) = (uid, 'ORDERED')
            where o_oid = order_id + uidi;
            uidi := uidi + 1;
        end loop;
end;
$$ language plpgsql;

-- Requirement 8 and 9 --
-- all used after identity check --
/* @caller-constraints: use lock outside */
/*                    : lock type - TO_FILL */

drop function if exists user_query_info cascade;

create or replace function user_query_info(
    in uid integer,
    out user_name varchar(20),
    out user_real_name varchar(20),
    out user_email varchar(20),
    out user_telnum integer[]
)
as
$$
declare
begin
    select u_user_name,
           p_real_name,
           u_email,
           u_tel_num
    into user_name,
        user_real_name,
        user_email,
        user_telnum
    from users join passengers p on users.u_uid = p.p_pid
    where users.u_uid = uid;
end;
$$ language plpgsql;

drop function if exists user_query_order cascade;

create or replace function user_query_order(
    in uid integer,
    in start_query_date date,
    in end_query_date date
)
    returns table
            (
                order_id       integer,
                train_name     varchar(10),
                train_id       integer,
                station_leave  varchar(20),
                station_id     integer,
                station_arrive varchar(20),
                start_time     time,
                arrive_time    time,
                durance        interval,
                distance       integer,
                seat_type      seat_type,
                seat_id        integer,
                status         order_status,
                price          decimal(5, 1)
            )
as
$$
begin
    return query select o_oid                                                                 as order_id,
                        t_train_name                                                          as train_name,
                        o_train_id                                                            as train_id,
                        s_start.s_station_name                                                as station_leave,
                        o_start_station                                                       as station_id,
                        s_arrive.s_station_name                                               as station_arrive,
                        tfi_start.tfi_leave_time                                              as start_time,
                        tfi_end.tfi_arrive_time                                               as arrive_time,
                        (select *
                         from get_actual_interval_bt_time(tfi_start.tfi_leave_time, tfi_end.tfi_arrive_time,
                                                          tfi_start.tfi_day_from_departure -
                                                          tfi_end.tfi_day_from_departure))    as durance,
                        tfi_end.tfi_distance - tfi_start.tfi_distance                         as distance,
                        o_seat_type                                                           as seat_type,
                        o_seat_id                                                             as seat_id,
                        o_status                                                              as status,
                        tfi_end.tfi_price[o_seat_type] - tfi_start.tfi_price[o_seat_type] + 5 as price
                 from orders
                          left join station_list s_start on orders.o_start_station = s_start.s_station_id
                          left join station_list s_arrive on orders.o_end_station = s_arrive.s_station_id
                          left join train on orders.o_train_id = train.t_train_id
                          left join train_full_info tfi_start on s_start.s_station_id = tfi_start.tfi_station_id
                     and orders.o_train_id = tfi_start.tfi_train_id
                          left join train_full_info tfi_end on s_start.s_station_id = tfi_end.tfi_station_id
                     and orders.o_train_id = tfi_end.tfi_train_id
                 where o_uid = uid
                   and o_date >= start_query_date
                   and o_date <= end_query_date;
end;
$$ language plpgsql;

/* @caller-constraints: use lock outside */
/*                    : lock type - TO_FILL */

drop function if exists user_cancel_order cascade;

create or replace function user_cancel_order(
    in order_id integer,
    out succeed boolean
)
as
$$
declare
    train_id      integer;
    order_date    integer;
    start_station integer;
    end_station   integer;
    seat_type     seat_type;
begin
    select o_train_id, o_date, o_start_station, o_end_station, o_seat_type
    into train_id, order_date, start_station, end_station, seat_type
    from orders
    where o_oid = order_id
      and o_status = 'COMPLETE';
    select release_seats(train_id, order_date, start_station, end_station, seat_type, 1);
    update orders
    set o_status = 'CANCELED'
    where o_oid = order_id;
end;
$$ language plpgsql;

/* @caller-constraints: use lock outside */
/*                    : lock type - TO_FILL */

drop function if exists remove_outdated_order cascade;

create or replace function remove_outdated_order(
) returns void
as
$$
begin
    delete
    from orders
    where (select * from get_actual_interval_bt_time(orders.o_effect_time, now(), 0))
        > interval '30 minutes'
      and orders.o_status = 'PRE_ORDERED';
end;
$$ language plpgsql;

-- 2 views to select top 10 train --
create or replace view top_10_train_tickets(train_id, count_num)
as
select o_train_id as train_id, count(*) as count_num
from orders
where o_status = 'COMPLETE'
group by o_train_id
order by count_num
limit 10;

create or replace view top_10_train_ids(train_id)
as
select train_id
from top_10_train_tickets;


/* @caller-constraints: use lock outside */
/*                    : lock type - TO_FILL */

drop function if exists admin_query_orders cascade;

create or replace function admin_query_orders(
    out total_order_num integer,
    out total_price integer,
    out hot_trains varchar(10)[]
)
as
$$
begin
    select count(*),
           sum(tfi_end.tfi_price[o_seat_type] - tfi_start.tfi_price[o_seat_type] + 5)
    into total_order_num, total_price
    from orders
             left join train_full_info tfi_start on o_start_station = tfi_start.tfi_station_id
        and orders.o_train_id = tfi_start.tfi_train_id
             left join train_full_info tfi_end on o_end_station = tfi_end.tfi_station_id
        and orders.o_train_id = tfi_end.tfi_train_id
        and orders.o_status = 'COMPLETE';
    hot_trains := array(select t_train_name
                        from train
                                 left join top_10_train_ids on train.t_train_id = top_10_train_ids.train_id);
end;
$$ language plpgsql;

/* @caller-constraints: use lock outside */
/*                    : lock type - TO_FILL */

drop function if exists admin_query_users cascade;

create or replace function admin_query_users(
)
    returns table
            (
                uid    integer,
                uname  varchar(20)
            )
as
$$
begin
    return query select p_pid       as uid,
                        u_user_name as uname
                 from passengers
                          left join users u on passengers.p_pid = u.u_uid;
end;
$$ language plpgsql