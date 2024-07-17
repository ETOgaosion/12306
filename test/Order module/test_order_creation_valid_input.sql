BEGIN;
-- 假设输入参数已验证有效
SELECT pre_order_train(1, '2024-07-16', 101, 102, 'YZ', 1, '2024-07-16') INTO succeed, _, order_id;
-- 检查是否返回成功标志
SELECT succeed = true AS succeed_is_true;
-- 检查订单是否存在于数据库中
SELECT COUNT(*) > 0 FROM orders WHERE o_oid = order_id;
-- 检查订单状态是否为“PRE_ORDERED”
SELECT o_status = 'PRE_ORDERED' FROM orders WHERE o_oid = order_id;
ROLLBACK;