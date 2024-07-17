BEGIN;
-- 假设起始城市ID为1，目的城市ID为3，查询日期为 '2024-07-16'，查询时间为 '08:00'，查询换乘
SELECT * FROM get_train_bt_cities_directly(1, 3, '2024-07-16', '08:00', true);
-- 检查返回的换乘列车信息是否正确
SELECT COUNT(*) > 0 FROM get_train_bt_cities_directly(1, 3, '2024-07-16', '08:00', true);
ROLLBACK;