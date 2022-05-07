#
# TODO: process raw_data into four csv files: train, city, station_list, train_full_info
#   format in csv files:
#       header1,header2,...\n
#       data1-1,data1-2,...\n
#       ...
#
#   id -> index of lists

import os

raw_data_path = './raw_data'
output_path = './postprocess_data'

out_train_path = output_path + '/train.csv'
out_city_path = output_path + '/city.csv'
out_statlist_path = output_path + '/station_list.csv'
out_tfi_path = output_path + '/train_full_info.csv'

# of_train = open(output_path + '/train.csv', 'w')
# of_city = open(output_path + '/city.csv', 'w')
# of_statlist = open(output_path + '/station_list.csv', 'w')
# of_tfi = open(output_path + '/train_full_info.csv', 'w')


# process station info
station_name_list = []
station_city_list = []
city_name_list = []
# read from all-stations.txt
with open(raw_data_path + '/all-stations.txt', 'r') as if_all_stations:
    lines = if_all_stations.readlines()
    for line in lines:
        toks = line.split(',')
        station_name_list.append(toks[1].strip())
        if toks[2] not in city_name_list:
            city_name_list.append(toks[2].strip())
        station_city_list.append(city_name_list.index(toks[2].strip()))
# write station_list.csv
with open(out_statlist_path, 'w') as of_statlist:
    of_statlist.write('s_station_id,s_station_name,s_station_city_id\n')
    for i in range(len(station_name_list)):
        of_statlist.write('%d,%s,%d\n' % (i,
                                          station_name_list[i],
                                          station_city_list[i]))

train_id = 0
train_name_list = []
tfi_train_id_list = []
tfi_station_id_list = []
tfi_station_order_list = []
tfi_arrive_time_list = []
tfi_leave_time_list = []
tfi_day_from_departure_list = []
tfi_distance_list = []
tfi_price_list = []

# check if inc day_from_departure
# @param: cur -> arrive time str in current line
#         lst -> arrive time str in last line
# @return: 1 if passing midnight
#          0 if not
def check_day_from_departure(cur: str, lst: str):
    cur_time = cur.split(':')
    lst_time = lst.split(':')
    if int(cur_time[0]) * 24 + int(cur_time[1]) < int(lst_time[0]) * 24 + int(lst_time[1]):
        return 1
    else:
        return 0

# parse three price strings to one integer list
# @param: hs_seat -> hard/soft seat
#         h_sleeper_hml -> hard sleeper hi/mid/lo
#         s_sleeper_hl -> soft sleeper hi/lo
# all params should be striped
# @return: integer list with length of 7
def parse_price(hs_seat: str, h_sleeper_hml: str, s_sleeper_hl: str):
    price_str_list = []
    price_list = []
    price_str_list.extend(hs_seat.split('/'))
    price_str_list.extend(h_sleeper_hml.split('/'))
    price_str_list.extend(s_sleeper_hl.split('/'))
    for pstr in price_str_list:
        if pstr == '-':
            price_list.append(0)
        else:
            price_list.append(float(pstr))
    return price_list

# as some stations do not sell any tickets, this info line won't help us in web
# if all prices are zero, ignore this line
def check_price(price: list):
    for p in price:
        if p != 0:
            return True
    return False

for child in os.listdir(raw_data_path):
    child_path = os.path.join(raw_data_path, child)
    if os.path.isdir(child_path):
        for train_csv in os.listdir(child_path):
            train_csv_path = os.path.join(child_path, train_csv)
            train_name_list.append(train_csv.split('.')[0])
            day_from_departure = 0
            lst_toks2 = '00:00'
            station_order = 0
            with open(train_csv_path, 'r') as if_train_csv:
                lines = if_train_csv.readlines()
                for line in lines[1:]:
                    toks = line.split(',')
                    # toks: 0: idx, 
                    #       1: station_name, 
                    #       2: arrive_time, 
                    #       3: leave_time, 
                    #       4: stay_time
                    #       5: duration,
                    #       6: distance,
                    #       7: hard/soft seat
                    #       8: hard sleeper hi/mid/lo
                    #       9: soft sleeper hi/lo
                    if station_order != 0:
                        price_list = parse_price(toks[7].strip(), toks[8].strip(), toks[9].strip())
                        if not check_price(price_list):
                            continue
                        tfi_price_list.append(price_list)
                        day_from_departure += check_day_from_departure(toks[2], lst_toks2)
                        lst_toks2 = toks[2]
                    else:
                        tfi_price_list.append([0.0,0,0,0,0,0,0])
                    tfi_train_id_list.append(train_id)
                    tfi_station_id_list.append(station_name_list.index(toks[1].strip()))
                    tfi_station_order_list.append(station_order)
                    station_order += 1
                    tfi_arrive_time_list.append(toks[2].strip())
                    tfi_leave_time_list.append(toks[3].strip())
                    tfi_day_from_departure_list.append(day_from_departure)
                    tfi_distance_list.append(toks[6].strip())
            train_id += 1

def get_train_type(train_name: str):
    if train_name.isdigit():
        return '0'
    else:
        return train_name[0]

# write train.csv
with open(out_train_path, 'w') as of_train:
    of_train.write('t_train_id,t_train_type,t_train_name\n')
    for i in range(len(train_name_list)):
        of_train.write('%d,%s,%s\n' % (i, get_train_type(train_name_list[i]), train_name_list[i]))

def gen_pgsql_array_csv_str(list: list):
    ret_str = '\"{'
    for i in range(len(list)):
        ret_str += str(list[i])
        if i != len(list) - 1:
            ret_str += ','
    return ret_str + '}\"'

# write train_full_info.csv
with open(out_tfi_path, 'w') as of_tfi:
    of_tfi.write('tfi_train_id,tfi_station_id,'
                 'tfi_station_order,tfi_arrive_time,'
                 'tfi_leave_time,tfi_day_from_departure,'
                 'tfi_distance,tfi_price\n')
    for i in range(len(tfi_train_id_list)):
        of_tfi.write('%d,%d,%d,%s,%s,%d,%s,%s\n' % (tfi_train_id_list[i],
                                                  tfi_station_id_list[i],
                                                  tfi_station_order_list[i],
                                                  tfi_arrive_time_list[i],
                                                  tfi_leave_time_list[i],
                                                  tfi_day_from_departure_list[i],
                                                  tfi_distance_list[i],
                                                  gen_pgsql_array_csv_str(tfi_price_list[i])))


# write city.csv
# TODO: city reach table
# array format in csv for pgsql:"{elem0, elem1, ...}"
with open(out_city_path, 'w') as of_city:
    of_city.write('c_city_id,c_city_name,c_city_reach_table\n')
    for i in range(len(city_name_list)):
        of_city.write('%d,%s\n' % (i, city_name_list[i]))
