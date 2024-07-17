BEGIN;
SELECT t_train_name FROM train LEFT JOIN top_10_train_ids ON train.t_train_id = top_10_train_ids.train_id;
-- 检查返回的热点车次列表是否正确
SELECT COUNT(*) > 0 FROM top_10_train_ids;
ROLLBACK;