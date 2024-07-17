BEGIN;
-- 假设列车ID为1，日期为 '2024-07-16'
SELECT stt_num FROM station_tickets WHERE stt_train_id = 1 AND stt_date = '2024-07-16';
-- 检查每种座位类型的余票数是否大于0
SELECT stt_num > 0 FROM station_tickets WHERE stt_train_id = 1 AND stt_date = '2024-07-16';
ROLLBACK;