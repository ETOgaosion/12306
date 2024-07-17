BEGIN;
-- 假设用户ID为1
SELECT * FROM orders WHERE o_uid = 1;
-- 检查返回的订单列表是否包含用户的所有订单
SELECT COUNT(*) > 0 FROM orders WHERE o_uid = 1;
ROLLBACK;