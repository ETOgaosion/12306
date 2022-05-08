# This is a sample Python script.

# Press Shift+F10 to execute it or replace it with your code.
# Press Double Shift to search everywhere for classes, files, tool windows, actions, and settings.

import csv
import numpy as np

raw_data_path = './postprocess_data'

city_path = raw_data_path + '/city.csv'
station_list_path = raw_data_path + '/station_list.csv'
train_full_info_path = raw_data_path + '/train_full_info.csv'
train_path = raw_data_path + '/train.csv'

'''
def process_data():
    # Use a breakpoint in the code line below to debug your script.
    with open("all-stations.txt", "r", encoding="utf8") as f:
        read = f.readlines()
        analyze = []
        for row in read:
            data = row.strip("\n").split(",")
            analyze_dict = {'station_id': data[0], 'station_name': data[1], 'city_name': data[2]}
            analyze.append(analyze_dict)

    with open("after_trans.csv", "w", encoding="utf8", newline="") as ff:
        field_name = ['station_id', 'station_name', 'city_name']
        writer = csv.DictWriter(ff, field_name)
        writer.writeheader()

        writer.writerows(analyze)
'''

#   get_reach_table:
#   1. get the city_num from city.csv and create matrix
#   2. acquire each train's information from train_full_info.csv
#       focus on the station_id, use i, j to look through the whole csv and get station_id series
#   3. look up these station_id in station_list.csv and find the corresponding city of them
#   4. write 1 into the matrix[i][j] if city_i can reach city_j
#   5. loop this process until all the train's info are processed
#   6. square the matrix itself
#   7. write into city.csv


def get_line_count(file_path):
    cnt = 0
    with open(file_path, "r", encoding="utf8") as f:
        for line in f:
            cnt += 1
        return cnt


def store_info(reach_matrix, pass_station, train_id):
    station_len = len(pass_station)
    #   print("station_len:" + str(station_len))
    with open(station_list_path, "r", encoding="utf8") as f:
        read = f.readlines()
        for i in range(station_len):
            for j in range(i + 1, station_len):
                start_station_id = pass_station[i]
                end_station_id = pass_station[j]
                start_data = read[start_station_id + 1].strip('\n').split(",")
                end_data = read[end_station_id + 1].strip('\n').split(",")
                start_city_id = int(start_data[2])
                end_city_id = int(end_data[2])
                if train_id == 0:
                    print("start_station_id: " + str(start_station_id))
                    print("end_station_id: " + str(end_station_id))
                    print("start_city_id: " + str(start_city_id) + start_data[1])
                    print("end_city_id: " + str(end_city_id) + end_data[1] + "\n")
                if start_city_id != end_city_id:
                    reach_matrix[start_city_id][end_city_id] = 1


def get_reach_table():
    dim = get_line_count(city_path)
    train_num = get_line_count(train_path)
    reach_matrix = np.zeros((dim - 1, dim - 1))
    with open(train_full_info_path, "r", encoding="utf8") as train_info:
        read = train_info.readlines()
        for train_id in range(train_num):
            pass_station = []
            for row in read[1:]:
                data = row.strip("\n").split(",")
                if int(data[0]) == train_id:
                    pass_station.append(int(data[1]))
            store_info(reach_matrix, pass_station, train_id)

    reach_matrix = np.matmul(reach_matrix, reach_matrix)

    with open(city_path, "r", encoding="utf8") as city_info:
        read = city_info.readlines()
        analyze = []
        count = 0
        for row in read[1:]:
            data = row.strip("\n").split(",")
            analyze_dict = {'c_city_id': data[0], 'c_city_name': data[1], 'reach_table': reach_matrix[count]}
            analyze.append(analyze_dict)
            count += 1
    with open("after_process_city.csv", "w", encoding="utf8", newline="") as ff:
        field_name = ['c_city_id', 'c_city_name', 'reach_table']
        writer = csv.DictWriter(ff, field_name)
        writer.writeheader()

        writer.writerows(analyze)


# Press the green button in the gutter to run the script.
if __name__ == '__main__':
    get_reach_table()
# See PyCharm help at https://www.jetbrains.com/help/pycharm/
