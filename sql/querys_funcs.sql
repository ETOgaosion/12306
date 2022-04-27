/*
 * There is no lock because in psql lock is in concept named TRANSACTION
 * function is always inside a TRANSACTION
 * so use lock in outside
 * TODO: there will be a file provide such interfaces

 * naming rules: directly get info from table use query
 *               use multiple table to form query result use get
 * TODO: rename function name to clarify and be unique
 *             : query func use query_(target)[_from_param]__[table for short]__
 */
-- rewrite library functions --
create function array_set(
	p_input anyarray, p_index int, p_new_value anyelement
)
	returns anyarray
as
$$
begin
	if p_input is not null then
		p_input[p_index] := p_new_value;
	end if;
	return p_input;
end;
$$ language plpgsql immutable;


create or replace function array_remove_elem(
	anyarray, int
)
	returns anyarray
as $$
begin
	select $1[:$2 - 1] || $1[$2 + 1:];
end
$$ language plpgsql immutable;

-- train --
/* @table: train */
/* @param: train_name */
/* @return: train_id */
/* @note: train_name -> train_id */
create or replace function query_train_id_from_name__t___(
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

/* @table: train */
/* @param: train_id */
/* @return: train_name */
/* @note: train_id -> train_name */
create or replace function query_train_name_from_id__t___(
	in train_id integer,
	out train_name varchar(10)
)
as $$
begin
	select t_train_name
		into train_name
		from train
		where t_train_id = train_id;
end;
$$ language plpgsql;

-- city --
/* @table: city */
/* @param: city_name */
/* @return: city_id */
create or replace function query_city_id_from_name__c___(
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

-- city train --
/* @table: city_train */
/* @param: city_id */
/* @return: train_id_list */
create or replace function query_train_id_list_from_cid__ct__(
	in city_id integer
)
	returns table (
		train_id integer
	)
	language plpgsql
as $$
begin
	return query select ct_train_id as train_id from city_train where ct_city_id = city_id;
end;
$$;

-- station list --
/* @table: station_list */
/* @param: station_id */
/* @return: station_name */
create or replace function query_station_name_from_id__s___(
	in station_id integer,
	out station_name varchar(20)
)
as $$
begin
	select s_station_name
		into station_name
		from station_list
		where s_station_id = station_id;
end;
$$ language plpgsql;

/* @table: station_list */
/* @param: station_id */
/* @return: city_id */
create or replace function query_city_id_from_sid__s___(
	in station_id integer,
	out city_id integer
)
as $$
begin
	select s_station_city_id into city_id from station_list where s_station_id = station_id;
end;
$$ language plpgsql;

-- train full info --
/* @table: train_full_info */
/* @param: train_id */
/* @return: leave_time */
/* @note: train_id in station -> leave time */
create or replace function query_start_time_from_id__tfi__(
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

/* @table: train_full_info */
/* @param: train_id */
/*       : station_id */
/* @return: station_order */
create or replace function query_station_order_from_tid_sid__tfi__(
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
/*       : station_id */
/* @return: all info */
create or replace function query_train_all_info_from_tid_sid__tfi__(
	in train_id integer,
	in station_id integer,
	out station_order integer,
	out arrive_time time,
	out leave_time time,
	out distance integer,
	out price decimal
)
as $$
begin
	select tfi_station_order, tfi_arrive_time, tfi_leave_time, tfi_distance, tfi_price
		into station_order, arrive_time, leave_time, distance, price
		from train_full_info
		where tfi_train_id = train_id
		  and tfi_station_id = station_id;
end;
$$ language plpgsql;

/* @table: train_full_info */
/* @param: train_id */
/*       : station_order */
/* @return: station_id */
create or replace function query_station_id_from_tid_so__tfi__(
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

/* @table: train_full_info */
/* @param: train_id */
/*       : station_id */
/* @return: next_station_id */
create or replace function get_next_station_id(
	in train_id integer,
	in station_id integer,
	out next_station_id integer
)
as $$
declare
	station_order integer;
begin
	select tfi_station_order
		into station_order
		from train_full_info
		where tfi_train_id = train_id
		  and tfi_station_id = station_id;
	station_order := station_order + 1;
	select tfi_station_id
		into next_station_id
		from train_full_info
		where tfi_train_id = train_id
		  and tfi_station_order = station_order;
end;
$$ language plpgsql;

-- station tickets --
/* @table: station_tickets */
/* @param: train_id */
/*       : query_date */
/*       : station_id */
/*       : seat_type_list */
/* @return: table of remain_seat num */
create or replace function query_remain_seats__st__(
	in train_id integer,
	in query_date date,
	in station_id integer,
	in seat_type_list seat_type[]
)
	returns table (
		in_order integer,
		seat_num integer
	)
as $$
declare
	seat_type seat_type;
	seat_nums_tmp integer[7];
	ptr integer := 1;
begin
	foreach seat_type in array seat_type_list
		loop
			select stt_num
				into seat_nums_tmp
				from station_tickets
				where stt_station_id = station_id
				  and stt_train_id = train_id
				  and stt_date = query_date;
			in_order := ptr;
			seat_num := seat_nums_tmp[seat_type];
			ptr := ptr + 1;
			return next;
		end loop;
	return;
end;
$$ language plpgsql;

/* @table: station_tickets */
/* @param: train_id */
/*       : query_date */
/*       : station_from_id */
/*       : station_to_id */
/*       : seat_type_list */
/* @return: min_seats */
/* @note: get min seats num between start city and end */
create or replace function get_min_seats(
	in train_id integer,
	in query_date date,
	in station_from_id integer,
	in station_to_id integer,
	in seat_type_list seat_type[]
)
	returns table (
		in_order integer,
		seat_num integer
	)
as $$
declare
	station_start_order int;
	station_order_ptr int;
	station_end_order int;
	station_id_ptr int := station_from_id;
	station_seat_left integer[7];
	seat_type seat_type;
	min_seat_nums integer[7] := array [5, 5, 5, 5, 5, 5, 5];
	ptr int := 1;
begin
	select query_station_order_from_tid_sid__tfi__(train_id, station_from_id) into station_start_order;
	station_order_ptr := station_start_order;
	select query_station_order_from_tid_sid__tfi__(train_id, station_to_id) into station_end_order;
	select query_remain_seats__st__(train_id, query_date, station_id_ptr, seat_type_list) into station_seat_left;
	while station_order_ptr != station_end_order
		loop
			foreach seat_type in array seat_type_list
				loop
					if station_seat_left[seat_type] < min_seat_nums[seat_type] then
						select array_set(min_seat_nums, seat_type, station_seat_left[seat_type]) into min_seat_nums;
					end if;
				end loop;
			station_order_ptr := station_order_ptr + 1;
			select query_station_id_from_tid_so__tfi__(train_id, station_order_ptr) into station_id_ptr;
			select query_remain_seats__st__(train_id, query_date, station_id_ptr, seat_type_list)
				into station_seat_left;
		end loop;
	foreach seat_type in array seat_type_list
		loop
			in_order := ptr;
			seat_num := min_seat_nums[seat_type];
			ptr := ptr + 1;
			return next;
		end loop;
	return;
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
	in order_date date,
	in station_from_id integer,
	in station_to_id integer,
	in seat_type seat_type,
	in seat_num integer,
	out succeed boolean,
	out left_seat integer
)
as $$
declare
	station_start_order int;
	station_order_ptr int;
	station_end_order int;
	station_id_ptr int := station_from_id;
	min_seat int := 5;
begin
	select query_station_order_from_tid_sid__tfi__(train_id, station_from_id) into station_start_order;
	station_order_ptr = station_start_order;
	select query_station_order_from_tid_sid__tfi__(train_id, station_to_id) into station_end_order;
	-- find min_seat --
	select get_min_seat.seat_num
		into min_seat
		from get_min_seats(train_id, order_date, station_from_id, station_to_id, array [seat_type]) get_min_seat
		where in_order = 1;
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
				set stt_num = (select array_set(stt_num, seat_type, stt_num[seat_type] - seat_num))
					where stt_train_id = train_id
					  and stt_station_id = station_id_ptr
					  and stt_date = order_date;
				station_order_ptr := station_order_ptr + 1;
				select query_station_id_from_tid_so__tfi__(train_id, station_order_ptr) into station_id_ptr;
			end loop;
	end if;
end;
$$ language plpgsql;

/* @table: station_tickets */
/* @param: train_id */
/*       : order_date */
/*       : station_from_id */
/*       : station_to_id */
/*       : seat_type */
/*       : seat_num */
/* @return: succeed or not */
/*        : 0 left_seat if succeed */
/*        : actual left_seat if failed */
create or replace function release_seats(
	in train_id integer,
	in order_date date,
	in station_from_id integer,
	in station_to_id integer,
	in seat_type seat_type,
	in seat_num integer
)
	returns void
as $$
declare
	station_start_order int;
	station_order_ptr int;
	station_end_order int;
	station_id_ptr int := station_from_id;
begin
	select query_station_order_from_tid_sid__tfi__(train_id, station_from_id) into station_start_order;
	station_order_ptr := station_start_order;
	select query_station_order_from_tid_sid__tfi__(train_id, station_to_id) into station_end_order;
	while station_order_ptr != station_end_order
		loop
			update station_tickets
			set stt_num = (select array_set(stt_num, seat_type, stt_num[seat_type] + seat_num))
				where stt_train_id = train_id
				  and stt_station_id = station_id_ptr
				  and stt_date = order_date;
			station_order_ptr := station_order_ptr + 1;
			select query_station_id_from_tid_so__tfi__(train_id, station_order_ptr) into station_id_ptr;
		end loop;
end;
$$ language plpgsql;

-- mixed --
/* @tables: station_list, train_full_info */
/* @param: city_id */
/*       : train_id */
/* @return: station_id */
/* @note: find which station the train stops when arriving the city */
/*      : column is in station list, so we put function here */
/*      : but actually info */
create or replace function get_station_id_from_cid_tid(
	in city_id integer,
	in train_id integer,
	out station_id integer
)
as $$
begin
	select s_station_id
		into station_id
		from train_full_info
			     left join station_list on station_list.s_station_id = train_full_info.tfi_station_id
		where s_station_city_id = city_id
		  and tfi_train_id = train_id;
end;
$$ language plpgsql;

/* @tables: city_train, train_full_info, station_list */
/* @param: city_id */
/*       : train_id */
/* @return: priority */
/* @note: find city priority in this train line */
create or replace function get_ct_priority(
	in city_id integer,
	in train_id integer,
	out priority integer
)
as $$
begin
	select tfi_station_order
		into priority
		from train_full_info
		where tfi_train_id = train_id
		  and tfi_station_id = (select get_station_id_from_cid_tid(city_id, train_id));
end;
$$ language plpgsql;

/* @tables: city_train, train_full_info, station_list */
/* @param: city_id */
/*       : train_id */
/* @return: next_city_list */
/* @note: find one  */
create or replace function get_ct_next_city_list(
	in city_id integer,
	in train_id_list integer[]
)
	returns table (
		in_order     integer,
		next_city_id integer
	)
	language plpgsql
as $$
declare
	train_idi integer;
	station_id integer;
	ptr integer := 1;
begin
	foreach train_idi in array train_id_list
		loop
			in_order := ptr;
			select get_station_id_from_cid_tid(city_id, train_idi) into station_id;
			select query_city_id_from_sid__s___(
						       (select get_next_station_id(train_idi, station_id))
				       )
				into next_city_id;
			return next;
		end loop;
end
$$;

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
	select now() + (days_interval || 'days')::interval into date_then;
end;
$$ language plpgsql;

-- Requirement 4 --
/* @note: query specific train */
/*      : we return id also, to help later function */
/* @TODO: can add param train type and seat type to filter result */
create or replace function get_train_info(
	in train_name varchar(10),
	in q_date date default get_date_from_now(1)
)
	returns table (
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
as $$
declare
	train_id integer;
begin
	select query_train_id_from_name__t___(train_name) into train_id;
	return query select tfi_station_order as station_order,
	                    s_station_name as station,
	                    s_station_id as station_id,
	                    c_city_name as city_name,
	                    c_city_id as city_id,
	                    tfi_arrive_time as arrive_time,
	                    tfi_leave_time as leave_time,
	                    tfi_leave_time - tfi_arrive_time as stay_time,
	                    tfi_arrive_time - (select query_start_time_from_id__tfi__(train_id)) as durance,
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
/*      : we ONLY search for tickets that trains can go today */
/* @TODO: can add param train type and seat type to filter result */
-- currently found effective way to return is through [setof] --
drop table if exists train_info;

create table if not exists train_info (
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
create or replace function check_reach_table(
	in city_from_id integer,
	in city_to_id integer,
	out reachable boolean
)
as $$
declare
	reach_table boolean[];
begin
	select c_reach_table into reach_table from city where c_city_id = city_from_id;
	reachable := reach_table[city_to_id];
end;
$$ language plpgsql;

create or replace function get_train_bt_cities_directly(
	in from_city_id integer,
	in to_city_id integer,
	in q_date date,
	in q_time time
	--,
	-- TODO: in train_type varchar(1)
)
	returns setof train_info
as $$
declare
	--
	city_reachable boolean;
	--
	train_id_list integer[];
	train_idi integer;
	train_namei varchar(10);
	--
	station_leave_id integer;
	station_leave_name varchar(20);
	station_leave_distance integer;
	station_leave_time time;
	station_leave_price decimal(5, 1)[7];
	--
	station_arrive_id integer;
	station_arrive_name varchar(20);
	station_arrive_time time;
	station_arrive_distance integer;
	station_arrive_price decimal(5, 1)[7];
	res_price decimal(5, 1)[7];
	--
	seat_nums integer[7];
	seat_i integer := 1;
	r train_info%rowtype;
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
				select query_station_name_from_id__s___(station_leave_id) into station_leave_name;
				select q_all_info_leave.leave_time, q_all_info_leave.distance, q_all_info_leave.price
					into station_leave_time, station_leave_distance, station_arrive_price
					from query_train_all_info_from_tid_sid__tfi__(train_idi, station_leave_id) q_all_info_leave;
				-- check time --
				if station_leave_time < q_time then
					continue scan_train_list;
				end if;
				select query_train_name_from_id__t___(train_idi) into train_namei;
				-- arrive station --
				select get_station_id_from_cid_tid(to_city_id, train_idi) into station_arrive_id;
				select query_station_name_from_id__s___(station_arrive_id) into station_arrive_name;
				select q_all_info_arrive.leave_time, q_all_info_arrive.distance, q_all_info_arrive.price
					into station_arrive_time, station_arrive_distance, station_leave_price
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
					select train_namei as train_name,
					       train_idi as train_id,
					       station_leave_name as station_from_name,
					       station_leave_id as station_from_id,
					       station_arrive_name as station_to_name,
					       station_arrive_id as station_to_id,
					       station_leave_time as leave_time,
					       station_arrive_time as arrive_time,
					       station_arrive_time - station_leave_time as durance,
					       station_arrive_distance - station_leave_distance as distance,
					       res_price as seat_price,
					       seat_nums as seat_nums,
					       false as transfer_first,
					       false as transfer_late
					loop
						return next r;
					end loop;
			end loop;
	end if;
	return;
end;
$$ language plpgsql;

create or replace function get_train_bt_cities(
	in city_from varchar(20),
	in city_to varchar(20),
	in q_date date,
	in q_time date,
	-- TODO: in train_type varchar(1),
	in allow_transfer boolean
)
	returns setof train_info
as $$
declare
	--
	from_city_id integer;
	to_city_id integer;
	city_reachable boolean;
	src_city integer[] := array [from_city_id];
	neighbour_city integer[];
	passing_trains integer[];
	current_level_city_num integer := 0;
	city_i integer := 1;
	r train_info%rowtype;
	j train_info%rowtype;
begin
	select query_city_id_from_name__c___(city_from) into from_city_id;
	select query_city_id_from_name__c___(city_to) into to_city_id;
	select check_reach_table(from_city_id, to_city_id) into city_reachable;
	if city_reachable then
		for r in
			select * from get_train_bt_cities_directly(from_city_id, to_city_id, q_date, q_time)
			loop
				return next r;
			end loop;
		if allow_transfer then
			-- first set of transfer trains must be ones passing from city --
			-- so outside loop --
			passing_trains := array(select query_train_id_list_from_cid__ct__(from_city_id));
			while (select array_position(src_city, to_city_id)) is not null
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
										 from get_train_bt_cities_directly(from_city_id, src_city[1], q_date, q_time))
									loop
										for j in
											(select *
												 from get_train_bt_cities_directly(src_city[1], to_city_id, q_date,
												                                   q_time + r.durance +
												                                   interval '1' hour))
											loop
												if r.station_to_id = j.station_from_id then
													if j.arrive_time - r.leave_time >= interval '1' hour and
													   j.arrive_time - r.leave_time <= interval '4' hour then
														return next r;
														return next j;
													end if;
												else
													if j.arrive_time - r.leave_time >= interval '2' hour and
													   j.arrive_time - r.leave_time <= interval '4' hour then
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
		from try_occupy_seats(train_id, order_date, station_from_id, station_to_id, seat_type, seat_num);
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
		select currval(pg_get_serial_sequence('orders', 'o_oid')) into order_id;
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
	set (o_uid, o_status) = (uid, 'ORDERED')
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
as $$
begin
	return query select o_oid as order_id,
	                    t_train_name as train_name,
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
	in order_id integer,
	out succeed boolean
)
as $$
declare
	train_id integer;
	order_date integer;
	start_station integer;
	end_station integer;
	seat_type seat_type;
begin
	select o_train_id, o_date, o_start_station, o_end_station, o_seat_type
		into train_id, order_date, start_station, end_station, seat_type
		from orders
		where o_oid = order_id;
	select release_seats(train_id, order_date, start_station, end_station, seat_type, 1);
	update orders
	set o_status = 'CANCELED'
		where o_oid = order_id;
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
	select count(*),
	       sum(tfi_end.tfi_price[o_seat_type] - tfi_start.tfi_price[o_seat_type] + 5)
		into total_order_num, hot_trains
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