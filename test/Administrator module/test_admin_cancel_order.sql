BEGIN;
-- 假设订单ID为1
SELECT user_cancel(1) INTO succeed;
-- 检查订单状态是否更新为“CANCELED”
SELECT o_status = 'CANCELED' FROM orders WHERE o_oid = 1;
ROLLBACK;