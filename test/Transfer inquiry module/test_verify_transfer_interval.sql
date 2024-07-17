BEGIN;
-- 假设上一趟列车的到达时间为 '09:00'，下一趟列车的出发时间为 '10:00'
SELECT get_actual_interval_bt_time('09:00', '10:00', 0) INTO transfer_interval;
-- 检查换乘时间间隔是否正确
SELECT transfer_interval = '1:00:00' AS interval_is_correct;
ROLLBACK;