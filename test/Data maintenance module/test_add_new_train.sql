BEGIN;
-- 插入新列车
INSERT INTO train (t_train_type, t_train_name) VALUES ('G', 'New Train');
-- 检查新列车是否成功添加
SELECT COUNT(*) = 1 FROM train WHERE t_train_name = 'New Train';
ROLLBACK;