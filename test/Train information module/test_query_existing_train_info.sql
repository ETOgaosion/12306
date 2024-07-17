BEGIN;
-- 假设列车名称为 'Train123'
SELECT query_train_id_from_name__t__('Train123') INTO train_id;
-- 查询列车详细信息
SELECT * FROM train WHERE t_train_id = train_id;
-- 检查返回的列车信息是否正确
SELECT t_train_type, t_train_name FROM train WHERE t_train_id = train_id;
ROLLBACK;