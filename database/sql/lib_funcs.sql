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
	in days_interval integer,
	out date_then date
)
as $$
begin
	select now() + (days_interval || 'days')::interval into date_then;
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
    in days_added integer,
    out actual_interval interval
)
as $$
begin
    if days_added = 0 and start_time > end_time then
        actual_interval := interval '24 hours' + end_time - start_time;
    else
	    actual_interval := (days_added || 'days')::interval + end_time - start_time;
    end if;

end
$$ language plpgsql;
