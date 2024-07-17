BEGIN;
-- 更新列车信息
UPDATE train SET t_train_type = 'D', t_train_name = 'Updated Train' WHERE t_train_name = 'New Train';
-- 检查列车信息是否被正确更新
SELECT t_train_type = 'D' AND t_train_name = 'Updated Train' FROM train WHERE t_train_name = 'New Train';
ROLLBACK;