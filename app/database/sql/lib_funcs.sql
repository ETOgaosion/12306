-- Library Function Section --
------------------------------
-- rewrite library functions --
drop function if exists array_set cascade;

create or replace function array_set(
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


drop function if exists array_remove_elem cascade;

create or replace function array_remove_elem(
	anyarray, int
)
	returns anyarray
as $$
begin
	select $1[:$2 - 1] || $1[$2 + 1:];
end
$$ language plpgsql immutable;

-- outside --
/* @param: days_interval */
/* @return: date_then */
/* @note: NOW + day_interval -> date_then */
drop function if exists get_date_from_now cascade;

create or replace function get_date_from_now(
	in days_interval integer
)
    returns date
as $$
begin
	return now() + (days_interval || 'days')::interval;
end;
$$ language plpgsql;

/* @param: start_time */
/*       : end_time */
/*       : days_added */
/* @return: actual interval */
drop function if exists get_actual_interval_bt_time cascade;

create or replace function get_actual_interval_bt_time(
    in start_time time,
    in end_time time,
    in days_added integer
)
    returns interval
as $$
declare
    actual_interval interval;
begin
    if days_added = 0 and start_time > end_time then
        actual_interval := interval '24 hours' + end_time - start_time;
    else
	    actual_interval := (days_added || 'days')::interval + end_time - start_time;
    end if;
    return actual_interval;
end
$$ language plpgsql;

drop function if exists enum_to_position();

CREATE OR REPLACE FUNCTION enum_to_position(anyenum) RETURNS integer AS $$
SELECT enumpos::integer FROM (
                                 SELECT row_number() OVER (order by enumsortorder) AS enumpos,
                                        enumsortorder,
                                        enumlabel
                                 FROM pg_catalog.pg_enum
                                 WHERE enumtypid = pg_typeof($1)
                             ) enum_ordering
WHERE enumlabel = ($1::text);
$$ LANGUAGE sql STABLE STRICT;

CREATE CAST (admin_authority AS integer) WITH FUNCTION enum_to_position(anyenum);
CREATE CAST (error_type__u__ AS integer) WITH FUNCTION enum_to_position(anyenum);
CREATE CAST (error_type AS integer) WITH FUNCTION enum_to_position(anyenum);
CREATE CAST (order_status AS integer) WITH FUNCTION enum_to_position(anyenum);
CREATE CAST (seat_type AS integer) WITH FUNCTION enum_to_position(anyenum);