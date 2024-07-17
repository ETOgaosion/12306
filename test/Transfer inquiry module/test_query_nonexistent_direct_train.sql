BEGIN;
-- 假设起始城市ID为1，目的城市ID为999（不存在直达列车）
SELECT * FROM get_train_bt_cities_directly(1, 999, '2024-07-16', '08:00');
-- 检查是否返回空结果集
SELECT COUNT(*) = 0 FROM get_train_bt_cities_directly(1, 999, '2024-07-16', '08:00');
ROLLBACK;