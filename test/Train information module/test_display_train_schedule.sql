BEGIN;
-- 假设列车ID为1
SELECT * FROM train_full_info WHERE tfi_train_id = 1;
-- 检查返回的时刻表是否包含到达和离开时间
SELECT tfi_arrive_time, tfi_leave_time FROM train_full_info WHERE tfi_train_id = 1;
ROLLBACK;