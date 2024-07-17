BEGIN;
-- 假设输入参数已验证有效，但座位数量超过余票
SELECT pre_order_train(1, '2024-07-16', 101, 102, 'YZ', 11, '2024-07-16') INTO succeed, _, order_id;
-- 检查是否返回失败标志
SELECT succeed = false AS succeed_is_false;
-- 检查订单是否未创建
SELECT COUNT(*) = 0 FROM orders WHERE o_train_id = 1 AND o_date = '2024-07-16';
ROLLBACK;