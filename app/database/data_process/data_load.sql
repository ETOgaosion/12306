copy train(t_train_id, t_train_type, t_train_name) from 'd:\apache\htdocs\12306\app\database\data_process\postprocess_data\train.csv' delimiter ',' csv header;
copy city(c_city_id, c_city_name, c_reach_table) from 'd:\apache\htdocs\12306\app\database\data_process\postprocess_data\city.csv' delimiter ',' csv header;
copy station_list(s_station_id, s_station_name, s_station_city_id) from 'd:\apache\htdocs\12306\app\database\data_process\postprocess_data\station_list.csv' delimiter ',' csv header;
copy train_full_info(tfi_train_id, tfi_station_id, tfi_station_order, tfi_arrive_time, tfi_leave_time, tfi_day_from_departure, tfi_distance, tfi_price) from 'd:\apache\htdocs\12306\app\database\data_process\postprocess_data\train_full_info.csv' delimiter ',' csv header;
copy station_tickets(stt_station_id, stt_train_id, stt_date, stt_num) from 'd:\apache\htdocs\12306\app\database\data_process\postprocess_data\station_tickets.csv' delimiter ',' csv header;
