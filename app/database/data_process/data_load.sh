path=`pwd`
city_path=$path'/postprocess_data/city.csv'
station_list_path=$path'/postprocess_data/station_list.csv'
train_full_info_path=$path'/postprocess_data/train_full_info.csv'
train_path=$path'/postprocess_data/city.csv'

rm -f data_load.sql

# gen sql
touch data_load.sql
echo "copy train(t_train_id, t_train_type, t_train_name) from '$train_path' delimiter ',' csv header;" >> data_load.sql
echo "copy city(c_city_id, c_city_name, c_reach_table) from '$city_path' delimiter ',' csv header;" >> data_load.sql
echo "copy station_list(s_station_id, s_station_name, s_station_city_id) from '$station_list_path' delimiter ',' csv header;" >> data_load.sql
echo "copy train_full_info(tfi_train_id, tfi_station_id, tfi_station_order, tfi_arrive_time, tfi_leave_time, tfi_day_from_departure, tfi_distance, tfi_price) from '$train_path' delimiter ',' csv header;" >> data_load.sql

# database name to be confirmed
psql -f data_load.sql -d postgres

rm -f data_load.sql
