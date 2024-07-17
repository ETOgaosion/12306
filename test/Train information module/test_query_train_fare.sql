BEGIN;
-- 假设列车ID为1
SELECT * FROM train_full_info WHERE tfi_train_id = 1;
-- 检查返回的票价信息是否正确
SELECT tfi_price FROM train_full_info WHERE tfi_train_id = 1;
ROLLBACK;