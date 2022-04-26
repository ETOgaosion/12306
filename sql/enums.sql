-- ENUMS --
drop table if exists orders;
drop table if exists city_train;
drop table if exists station_tickets;
drop table if exists train_full_info;
drop table if exists station_list;
drop table if exists city;
drop table if exists train;
drop table if exists users;
drop type if exists seat_type;

create type seat_type as enum ('YZ', 'RZ', 'YW_S', 'YW_Z', 'YW_X', 'RW_S', 'RW_X');

drop type if exists order_status;

create type order_status as enum ('COMPLETE', 'PRE_ORDERED', 'CANCELED');