-- ENUMS --
drop table if exists orders;

drop table if exists station_tickets;

drop table if exists train_full_info;

drop table if exists station_list;

drop table if exists city;

drop table if exists train;

drop table if exists admin;

drop table if exists passengers;

drop table if exists users;

drop type if exists seat_type;

create type seat_type as enum ('YZ', 'RZ', 'YW_S', 'YW_Z', 'YW_X', 'RW_S', 'RW_X');

drop type if exists order_status;

create type order_status as enum ('COMPLETE', 'PRE_ORDERED', 'CANCELED');

drop type if exists error_type;

create type error_type as enum ('NO_ERROR', 'ERROR_NOT_FOUND');

drop type if exists error_type__u__;

create type error_type__u__ as enum ('NO_ERROR',
	'ERROR_DUPLICATE_UNAME',
	'ERROR_DUPLICATE_U_TEL_NUM',
	'ERROR_DUPLICATE_AID',
	'ERROR_NOT_FOUND_UNAME',
	'ERROR_NOT_CORRECT_PASSWORD',
    'ERROR_NOT_CORRECT_AUTH');

drop type if exists admin_authority;

create type admin_authority as enum ('ALL');