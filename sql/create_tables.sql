-- must in order --
drop table if exists seat_type;
drop table if exists city_train;
drop table if exists station_tickets;
drop table if exists station_info;
drop table if exists orders;
drop table if exists users;
drop table if exists train;
drop table if exists city;
drop table if exists station_list;

-- must in order --
create table if not exists users (
	u_uid       serial primary key,
	u_email     varchar(20) not null,
	u_user_name varchar(20) unique,
	u_password  varchar(20) not null,
	u_real_name varchar(20) not null,
	u_tel_num   varchar(20) unique,
	u_admin     bool        not null
);

create table if not exists train (
	t_train_id   serial primary key,
	t_train_type varchar(1)  not null,
	t_train_name varchar(10) not null
);

create table if not exists city (
	c_city_id   serial primary key,
	c_city_name varchar(20) not null
);

create table if not exists city_train (
	ct_city_id      integer,
	ct_train_id     integer,
	ct_next_city_id integer,
	ct_prior        integer not null,
	primary key (ct_city_id, ct_train_id),
	foreign key (ct_city_id) references city (c_city_id),
	foreign key (ct_train_id) references train (t_train_id),
	foreign key (ct_next_city_id) references city (c_city_id)
);

create table if not exists station_list (
	s_station_id      serial primary key,
	s_station_name    varchar(20) not null,
	s_station_city_id integer     not null,
	foreign key (s_station_city_id) references city (c_city_id)
);

create table if not exists station_info (
	si_train_id      integer,
	si_station_id    integer,
	si_station_order integer       not null,
	si_arrive_time   time          not null,
	si_leave_time    time          not null,
	si_distance      integer       not null,
	si_price_yz      decimal(5, 1) not null default 0,
	si_price_rz      decimal(5, 1) not null default 0,
	si_price_yw_s    decimal(5, 1) not null default 0,
	si_price_yw_z    decimal(5, 1) not null default 0,
	si_price_yw_x    decimal(5, 1) not null default 0,
	si_price_rw_s    decimal(5, 1) not null default 0,
	si_price_rw_x    decimal(5, 1) not null default 0,
	primary key (si_train_id, si_station_id),
	foreign key (si_train_id) references train (t_train_id),
	foreign key (si_station_id) references station_list (s_station_id)
);

create table if not exists station_tickets (
	stt_station_id integer,
	stt_train_id   integer,
	stt_date       date    not null,
	stt_num_yz     integer not null default 0,
	stt_num_rz     integer not null default 0,
	stt_num_yw_s   integer not null default 0,
	stt_num_yw_z   integer not null default 0,
	stt_num_yw_x   integer not null default 0,
	stt_num_rw_s   integer not null default 0,
	stt_num_rw_x   integer not null default 0,
	primary key (stt_station_id, stt_train_id),
	foreign key (stt_station_id) references station_list (s_station_id),
	foreign key (stt_train_id) references train (t_train_id),
	foreign key (stt_station_id, stt_train_id) references station_info (si_station_id, si_train_id)
);

create table if not exists orders (
	o_oid           serial primary key,
	o_uid           integer       not null,
	o_train_id      integer       not null,
	o_date          date          not null,
	o_start_station integer       not null,
	o_end_station   integer       not null,
	o_price         decimal(5, 1) not null,
	o_seat_type     integer       not null,
	o_status        integer       not null,
	o_effect_time   integer       not null,
	foreign key (o_uid) references users (u_uid),
	foreign key (o_train_id) references train (t_train_id),
	foreign key (o_start_station) references station_list (s_station_id),
	foreign key (o_end_station) references station_list (s_station_id)
);

-- DEFINE --
-- enum vars, as table --
create table if not exists seat_type (
	st_type serial primary key,
	st_name varchar(20) not null
);