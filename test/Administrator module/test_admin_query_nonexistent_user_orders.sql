BEGIN;
-- 假设用户ID为999（不存在）
SELECT * FROM orders WHERE o_uid = 999;
-- 检查是否返回空结果集
SELECT COUNT(*) = 0 FROM orders WHERE o_uid = 999;
ROLLBACK;