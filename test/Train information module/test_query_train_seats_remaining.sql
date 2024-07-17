BEGIN;
-- 假设列车ID为1，日期为 '2024-07-16'
SELECT * FROM station_tickets WHERE stt_train_id = 1 AND stt_date = '2024-07-16';
-- 检查返回的余票信息是否正确
SELECT stt_num FROM station_tickets WHERE stt_train_id = 1 AND stt_date = '2024-07-16';
ROLLBACK;