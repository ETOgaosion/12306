-- Library Function Section --
------------------------------
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