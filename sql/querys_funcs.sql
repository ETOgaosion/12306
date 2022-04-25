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
	select si_leave_time
		into leave_time
		from station_info
		where si_train_id = train_id
		  and si_station_order = 0;
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
		station         varchar(20),
		station_id      integer,
		city            varchar(20),
		city_id         integer,
		arrive_time     time,
		leave_time      time,
		stay_time       interval,
		durance         interval,
		distance        integer,
		hard_seat_price decimal(5, 1),
		price_yz        decimal(5, 1),
		num_yz          integer,
		price_rz        decimal(5, 1),
		num_rz          integer,
		price_yw_s      decimal(5, 1),
		num_yw_s        integer,
		price_yw_z      decimal(5, 1),
		num_yw_z        integer,
		price_yw_x      decimal(5, 1),
		num_yw_x        integer,
		price_rw_s      decimal(5, 1),
		num_rw_s        integer,
		price_rw_x      decimal(5, 1),
		num_rw_x        integer
	)
	language plpgsql
as $$
declare
	train_id integer := query_train_id(train_name);
begin
	return query select s_station_name                            as station,
	                    s_station_id                              as station_id,
	                    c_city_name                               as city_name,
	                    c_city_id                                 as city_id,
	                    si_arrive_time                            as arrive_time,
	                    si_leave_time                             as leave_time,
	                    si_leave_time - si_arrive_time            as stay_time,
	                    si_arrive_time - get_start_time(train_id) as durance,
	                    si_distance                               as distance,
	                    si_price_yz                               as price_yz,
	                    stt_num_yz                                as num_yz,
	                    si_price_rz                               as price_rz,
	                    stt_num_rz                                as num_rz,
	                    si_price_yw_s                             as price_yw_s,
	                    stt_num_yw_s                              as num_yw_s,
	                    si_price_yw_z                             as price_yw_z,
	                    stt_num_yw_z                              as num_yw_z,
	                    si_price_yw_x                             as price_yw_x,
	                    stt_num_yw_x                              as num_yw_x,
	                    si_price_rw_s                             as price_rw_s,
	                    stt_num_rw_s                              as num_rw_s,
	                    si_price_rw_x                             as price_rw_x,
	                    stt_num_rw_x                              as num_rw_x
		             from station_info si
			                  left join station_list s on si.si_station_id = s.s_station_id
			                  left join city c on s.s_station_city_id = c.c_city_id
			                  left join train t on si.si_train_id = t.t_train_id
			                  left join station_tickets stt on si.si_station_id = stt.stt_station_id
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
	in train_type varchar(1)
)
	returns table (
		train_name      varchar(10),
		train_id        integer,
		station_leave   varchar(20),
		station_id      integer,
		station_arrive  varchar(20),
		leave_time      time,
		arrive_time     time,
		durance         interval,
		distance        integer,
		hard_seat_price decimal(5, 1),
		price_yz        decimal(5, 1),
		num_yz          integer,
		price_rz        decimal(5, 1),
		num_rz          integer,
		price_yw_s      decimal(5, 1),
		num_yw_s        integer,
		price_yw_z      decimal(5, 1),
		num_yw_z        integer,
		price_yw_x      decimal(5, 1),
		num_yw_x        integer,
		price_rw_s      decimal(5, 1),
		num_rw_s        integer,
		price_rw_x      decimal(5, 1),
		num_rw_x        integer
	)
	language plpgsql
as $$
declare
begin
end;
$$;

-- Requirement 7 --
/* @return: only seat id */
/* @note: show the orders and order train and seats */
/*      : we view this function as confirmation of order */
/*      : so we assume that php stored all info needed and it can display info itself */
/*      : only seat id, we need to query and atomically change */
/*      : we use three functions:   show train order create order and set status to TO_BE_PAID, return order info  */
/*                                  order take effect, only change status  */
/*                                  check status function, if status not changed after some time, cancel it */
/*      : we only return info needed, php can deal with info if exists already */
create or replace function pre_order_train(
	in train_id integer,
	in station_from_id integer,
	in station_to_id integer,
	in seat_type integer,
	in seat_num integer,
	in order_date date,
	in uid integer,
	out seat_id integer
)
as $$
    begin

    end;
$$ language plpgsql;



create or replace function order_train_seats(
	in order_id integer,
	out succeed boolean
)
as $$
begin

end;
$$ language plpgsql;

-- Requirement 8 and 9 --
create or replace function user_query_order()
as $$
begin
end;
$$ language plpgsql;

create or replace function user_delete_order()
as $$
begin
end;
$$ language plpgsql;

create or replace function admin_query_orders()
as $$
begin
end;
$$ language plpgsql;