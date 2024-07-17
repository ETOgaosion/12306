BEGIN;
-- 查询列车信息
SELECT * FROM train WHERE t_train_name = 'New Train';
-- 检查返回的列车信息是否正确
SELECT COUNT(*) > 0 FROM train WHERE t_train_name = 'New Train';
ROLLBACK;