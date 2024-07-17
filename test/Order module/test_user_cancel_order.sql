BEGIN;
-- 假设订单ID是已知的，并且订单状态为ORDERED
SELECT user_cancel(1) INTO succeed;
-- 检查是否返回成功标志
SELECT succeed = true AS succeed_is_true;
-- 检查订单状态是否更新为“CANCELED”
SELECT o_status = 'CANCELED' FROM orders WHERE o_oid = 1;
ROLLBACK;