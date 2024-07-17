BEGIN;
SELECT admin_query_orders() INTO total_order_num, total_price;
-- 检查返回的总订单数和总票价是否正确
SELECT total_order_num > 0 AND total_price > 0;
ROLLBACK;