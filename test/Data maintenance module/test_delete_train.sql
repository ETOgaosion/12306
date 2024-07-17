BEGIN;
-- 假设要删除的列车名为 'New Train'
DELETE FROM train WHERE t_train_name = 'New Train';
-- 检查列车是否被删除
SELECT COUNT(*) = 0 FROM train WHERE t_train_name = 'New Train';
ROLLBACK;