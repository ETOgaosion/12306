BEGIN;
-- 假设有预订单超过30分钟未确认
SELECT remove_outdated_order();
-- 检查超过时间限制的预订单是否被取消
SELECT COUNT(*) = 0 FROM orders WHERE o_status = 'PRE_ORDERED' AND o_effect_time < NOW() - INTERVAL '30 minutes';
ROLLBACK;