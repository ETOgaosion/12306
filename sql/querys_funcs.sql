/*
 * There is no lock because in psql lock is in concept named TRANSACTION
 * function is always inside a TRANSACTION
 * so use lock in outside
 * TODO: there will be a file provide such interfaces
 */
-- train --
/* @table: train */
/* @param: train_name */
/* @return: train_id */
/* @note: train_name -> train_id */
create or replace function query_train_id(
	in train_name varchar(10),
	out train_id integer
)
as $$
begin
	select t_train_id
		into train_id
		from train
		where t_train_name = train_name;
end;
$$ language plpgsql;

-- city --
/* @table: city */
/* @param: city_name */
/* @return: city_id */
create or replace function query_city_id(
	in city_name varchar(20),
	out city_id integer
)
as $$
begin
	select c_city_id
		into city_id
		from city
		where c_city_name = city_name;
end;
$$ language plpgsql;

-- station info --
/* @table: station_info */
/* @param: train_id */
/* @return: leave_time */
/* @note: train_id in station -> leave time */
create or replace function get_start_time(
	in train_id integer,
	out leave_time time
)
as $$
begin
	select tfi_leave_time
		into leave_time
		from train_full_info
		where tfi_train_id = train_id
		  and tfi_station_order = 0;
end;
$$ language plpgsql;

-- train full info --
/* @table: train_full_info */
/* @param: train_id */
/*       : station_id */
/* @return: station_order */
create or replace function query_station_order(
	in train_id integer,
	in station_id integer,
	out station_order integer
)
as $$
begin
	select tfi_station_order
		into station_order
		from train_full_info
		where tfi_train_id = train_id
		  and tfi_station_id = station_id;
end;
$$ language plpgsql;

/* @table: train_full_info */
/* @param: train_id */
/*       : station_order */
/* @return: station_id */
create or replace function query_station_id(
	in train_id integer,
	in station_order integer,
	out station_id integer
)
as $$
begin
	select tfi_station_id
		into station_id
		from train_full_info
		where tfi_train_id = train_id
		  and tfi_station_order = station_order;
end;
$$ language plpgsql;

-- station tickets --
/* @table: station_tickets */
/* @param: train_id */
/*       : station_id */
/*       : seat_type_list */
/* @return: table of remain_seat num */
create or replace function query_remain_seats(
	in train_id integer,
	in station_id integer,
	in seat_type_list seat_type[]
)
	returns table (
		seat_num integer
	)
as $$
declare
	seat_type seat_type;
	seat_numbs integer[7];
begin
	foreach seat_type in array seat_type_list
		loop
			select stt_num
				into seat_numbs
				from station_tickets
				where stt_station_id = station_id
				  and stt_train_id = train_id;
			seat_num := seat_numbs[seat_type];
			return next;
		end loop;
end;
$$ language plpgsql;

/* @table: station_tickets */
/* @param: train_id */
/*       : station_from_id */
/*       : station_to_id */
/*       : seat_type */
/*       : seat_num */
/* @return: succeed or not */
/*        : seat id as left_seat if succeed */
/*        : actual left_seat if failed */
create or replace function try_occupy_seats(
	in train_id integer,
	in station_from_id integer,
	in station_to_id integer,
	in seat_type seat_type,
	in seat_num integer,
	out succeed boolean,
	out left_seat integer
)
as $$
declare
	station_start_order int := query_station_order(train_id, station_from_id);
	station_order_ptr int := station_start_order;
	station_end_order int := query_station_order(train_id, station_to_id);
	station_id_ptr int := station_from_id;
	station_seat_left int := query_remain_seats(train_id, station_id_ptr, array [seat_type]);
	min_seat int := 0;
begin
	-- first loop, only find min_seat --
	while station_order_ptr != station_end_order
		loop
			if station_seat_left < min_seat then
				min_seat := station_seat_left;
			end if;
			station_order_ptr := station_order_ptr + 1;
			station_id_ptr := query_station_id(train_id, station_order_ptr);
			station_seat_left := query_remain_seats(train_id, station_id_ptr, array [seat_type]);
		end loop;
	-- reset ptr --
	station_order_ptr := station_start_order;
	station_id_ptr := station_from_id;
	-- check satisfiability --
	if min_seat < seat_num then
		succeed := false;
		left_seat := min_seat;
	else
		succeed := true;
		left_seat := 5 - min_seat;
		-- second loop, update station tickets --
		while station_order_ptr != station_end_order
			loop
				update station_tickets
				set stt_num_yz   = case when seat_type = 'YZ' then stt_num_yz - seat_num else stt_num_yz end,
				    stt_num_rz   = case when seat_type = 'RZ' then stt_num_rz - seat_num else stt_num_rz end,
				    stt_num_yw_s = case when seat_type = 'YW_S' then stt_num_yw_s - seat_num else stt_num_yw_s end,
				    stt_num_yw_z = case when seat_type = 'YW_Z' then stt_num_yw_z - seat_num else stt_num_yw_z end,
				    stt_num_yw_x = case when seat_type = 'YW_X' then stt_num_yw_x - seat_num else stt_num_yw_x end,
				    stt_num_rw_s = case when seat_type = 'RW_S' then stt_num_rw_s - seat_num else stt_num_rw_s end,
				    stt_num_rw_x = case when seat_type = 'RW_X' then stt_num_rw_x - seat_num else stt_num_rw_x end
					where stt_train_id = train_id
					  and stt_station_id = station_id_ptr;
				station_order_ptr := station_order_ptr + 1;
				station_id_ptr := query_station_id(train_id, station_order_ptr);
			end loop;
	end if;
end;
$$ language plpgsql;

/* @table: station_tickets */
/* @param: train_id */
/*       : station_from_id */
/*       : station_to_id */
/*       : seat_type */
/*       : seat_num */
/* @return: succeed or not */
/*        : 0 left_seat if succeed */
/*        : actual left_seat if failed */
create or replace function recover_seats(
	in train_id integer,
	in station_from_id integer,
	in station_to_id integer,
	in seat_type seat_type,
	in seat_num integer
)
as $$
declare
	station_start_order int := query_station_order(train_id, station_from_id);
	station_order_ptr int := station_start_order;
	station_end_order int := query_station_order(train_id, station_to_id);
	station_id_ptr int := station_from_id;
begin
	while station_order_ptr != station_end_order
		loop
			update station_tickets
			set stt_num_yz   = case when seat_type = 'YZ' then stt_num_yz + seat_num else stt_num_yz end,
			    stt_num_rz   = case when seat_type = 'RZ' then stt_num_rz + seat_num else stt_num_rz end,
			    stt_num_yw_s = case when seat_type = 'YW_S' then stt_num_yw_z + seat_num else stt_num_yw_s end,
			    stt_num_yw_z = case when seat_type = 'YW_Z' then stt_num_yw_z + seat_num else stt_num_yw_z end,
			    stt_num_yw_x = case when seat_type = 'YW_X' then stt_num_yw_x + seat_num else stt_num_yw_x end,
			    stt_num_rw_s = case when seat_type = 'RW_S' then stt_num_rw_s + seat_num else stt_num_rw_s end,
			    stt_num_rw_x = case when seat_type = 'RW_X' then stt_num_rw_x + seat_num else stt_num_rw_x end
				where stt_train_id = train_id
				  and stt_station_id = station_id_ptr;
			station_order_ptr := station_order_ptr + 1;
			station_id_ptr := query_station_id(train_id, station_order_ptr);
		end loop;
end;
$$ language plpgsql;

-- outside --
/* @param: days_interval */
/* @return: date_then */
/* @note: NOW + day_interval -> date_then */
create or replace function get_date_from_now(
	in days_interval integer,
	out date_then date
)
as $$
begin
	select now() + (days_interval || 'days')::interval
		into date_then;
end;
$$ language plpgsql;

-- Requirement 4 --
/* @note: query specific train */
/*      : we return id also, to help later function */
/* @TODO: can add param train type and seat type to filter result */
create or replace function query_train_info(
	in train_name varchar(10),
	in q_date date default get_date_from_now(1)
)
	returns table (
		station     varchar(20),
		station_id  integer,
		city        varchar(20),
		city_id     integer,
		arrive_time time,
		leave_time  time,
		stay_time   interval,
		durance     interval,
		distance    integer,
		seat_price  decimal(5, 1)[7],
		seat_num    integer[7]
	)
	language plpgsql
as $$
declare
	train_id integer := query_train_id(train_name);
begin
	return query select s_station_name as station,
	                    s_station_id as station_id,
	                    c_city_name as city_name,
	                    c_city_id as city_id,
	                    tfi_arrive_time as arrive_time,
	                    tfi_leave_time as leave_time,
	                    tfi_leave_time - tfi_arrive_time as stay_time,
	                    tfi_arrive_time - get_start_time(train_id) as durance,
	                    tfi_distance as distance,
	                    tfi_price as seat_price,
	                    stt_num as seat_num
		             from train_full_info si
			                  left join station_list s on si.tfi_station_id = s.s_station_id
			                  left join city c on s.s_station_city_id = c.c_city_id
			                  left join train t on si.tfi_train_id = t.t_train_id
			                  left join station_tickets stt on si.tfi_station_id = stt.stt_station_id
		             where t_train_id = train_id
			           and stt.stt_date = q_date;
end;
$$;

-- Requirement 5 --
/* @note: query train between 2 cities */
/*      : we return id also, to help later function */
/* @TODO: can add param train type and seat type to filter result */
create or replace function query_train_bt_places(
	in city_1 varchar(20),
	in city_2 varchar(20),
	in q_date date,
	in q_time date,
	in train_type varchar(1),
	in allow_switch boolean
)
	returns table (
		train_name     varchar(10),
		train_id       integer,
		station_leave  varchar(20),
		station_id     integer,
		station_arrive varchar(20),
		leave_time     time,
		arrive_time    time,
		durance        interval,
		distance       integer,
		seat_prices    decimal(5, 1)[7],
		seat_numbs     integer[7]
	)
	language plpgsql
as $$
declare
begin
end;
$$;

-- Requirement 7 --
/* @param: we use data from previous queries, it's php's mission to get and maintain */
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
/* TODO: add account balance function, so there is a new order status: ORDERED, or TO_BE_BOUGHT */
create or replace function pre_order_train(
	in train_id integer,
	in station_from_id integer,
	in station_to_id integer,
	in seat_type seat_type,
	in seat_num integer,
	in order_date date,
	out succeed boolean,
	out seat_id integer,
	out order_id integer
)
as $$
declare
begin
	select succeed, left_seat
		into succeed, seat_id
		from try_occupy_seats(train_id, station_from_id, station_to_id, seat_type, seat_num);
	if succeed then
		insert into orders (o_train_id, o_date, o_start_station, o_end_station, o_seat_type, o_seat_id,
		                    o_status, o_effect_time)
		select train_id,
		       order_date,
		       station_from_id,
		       station_to_id,
		       seat_type,
		       seat_id,
		       'PRE_ORDERED',
		       now();
		order_id := currval(pg_get_serial_sequence('orders', 'o_oid'));
	else
		order_id := 0;
	end if;
end;
$$ language plpgsql;

create or replace function order_train_seats(
	in order_id integer,
	in uid integer,
	out succeed boolean
)
as $$
begin
	update orders
	set (o_uid, o_status) = 'ORDERED'
		where o_oid = order_id;
end;
$$ language plpgsql;

-- Requirement 8 and 9 --
-- all used after identity check --
create or replace function user_query_order(
	in uid integer,
	in start_query_date date,
	in end_query_date date
)
	returns table (
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
as $$
begin
	return query select t_train_name as train_name,
	                    o_train_id as train_id,
	                    s_start.s_station_name as station_leave,
	                    o_start_station as station_id,
	                    s_arrive.s_station_name as station_arrive,
	                    tfi_start.tfi_leave_time as start_time,
	                    tfi_end.tfi_arrive_time as arrive_time,
	                    tfi_start.tfi_leave_time - tfi_end.tfi_arrive_time as durance,
	                    tfi_end.tfi_distance - tfi_start.tfi_distance as distance,
	                    o_seat_type as seat_type,
	                    o_seat_id as seat_id,
	                    o_status as status,
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

create or replace function user_delete_order(
)
as $$
begin
end;
$$ language plpgsql;

-- 2 views to select top 10 train --
create or replace view top_10_train_tickets(train_id, count_num)
as
select o_train_id as train_id, count(*) as count_num
	from orders
	group by o_train_id
	order by count_num
	limit 10;

create or replace view top_10_train_ids(train_id)
as
select train_id
	from top_10_train_tickets;


create or replace function admin_query_orders(
	out total_order_num integer,
	out total_price integer,
	out hot_trains varchar(10)[]
)
as $$
begin
	select count(*) as total_order_num,
	       sum(tfi_end.tfi_price[o_seat_type] - tfi_start.tfi_price[o_seat_type] + 5) as hot_trains
		from orders
			     left join train_full_info tfi_start on o_start_station = tfi_start.tfi_station_id
			and orders.o_train_id = tfi_start.tfi_train_id
			     left join train_full_info tfi_end on o_end_station = tfi_end.tfi_station_id
			and orders.o_train_id = tfi_end.tfi_train_id;
	hot_trains := array(select t_train_name
		                    from train
			                         left join top_10_train_ids on train.t_train_id = top_10_train_ids.train_id);
end;
$$ language plpgsql;

create or replace function admin_query_users(
)
	returns table (
		uid    integer,
		uname  varchar(20),
		orders integer[]
	)
as $$
begin
	return query select u_uid as uid,
	                    u_user_name as uname,
	                    array(
			                    select o_oid from orders where o_uid = u_uid
		                    )
		             from users;
end;
$$ language plpgsql