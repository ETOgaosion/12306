BEGIN;
-- 假设列车ID为1，起始站ID为101，终点站ID为102，座位类型为 'YZ'，购票数量为1，日期为 '2024-07-16'
-- 模拟购票操作
SELECT try_occupy_seats(1, '2024-07-16', 101, 102, 'YZ', 1) INTO succeed, _;
-- 查询购票后的余票信息
SELECT stt_num FROM station_tickets WHERE stt_train_id = 1 AND stt_date = '2024-07-16';
-- 检查余票数是否正确减少
SELECT stt_num[1] = 4 FROM station_tickets WHERE stt_train_id = 1 AND stt_date = '2024-07-16';
ROLLBACK;