BEGIN;
-- 假设列车名称为 'NonExistentTrain'
SELECT query_train_id_from_name__t__('NonExistentTrain') INTO train_id;
-- 查询列车详细信息
SELECT * FROM train WHERE t_train_id = train_id;
-- 检查是否返回空结果集
SELECT COUNT(*) = 0 FROM train WHERE t_train_id = train_id;
ROLLBACK;