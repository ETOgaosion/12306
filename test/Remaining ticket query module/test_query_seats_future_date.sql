BEGIN;
-- 假设列车ID为1，未来日期为 '2025-07-16'
SELECT stt_num FROM station_tickets WHERE stt_train_id = 1 AND stt_date = '2025-07-16';
-- 检查是否返回空结果集
SELECT COUNT(*) = 0 FROM station_tickets WHERE stt_train_id = 1 AND stt_date = '2025-07-16';
ROLLBACK;