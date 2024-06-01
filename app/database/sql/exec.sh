psql -f enums.sql -U postgres -d postgres

psql -f create_tables.sql -U postgres -d postgres

psql -f lib_funcs.sql -U postgres -d postgres

psql -f table_funcs.sql -U postgres -d postgres

psql -f requirement_funcs.sql -U postgres -d postgres