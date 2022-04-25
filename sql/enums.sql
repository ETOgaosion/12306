-- ENUMS --
drop type if exists seat_type;

create type seat_type as enum ('YZ', 'RZ', 'YW_S', 'YW_Z', 'YW_X', 'RW_S', 'RW_X');

drop type if exists order_status;

create type order_status as enum ('COMPLETE', 'PRE_ORDERED', 'CANCELED');