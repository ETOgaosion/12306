BEGIN;
-- 假设 user_id 是一个已存在的用户ID
-- 执行用户注销操作（具体实现根据业务逻辑确定，这里假设是删除用户）
DELETE FROM users WHERE u_uid = user_id;
-- 检查用户信息是否被删除
SELECT COUNT(*) FROM users WHERE u_uid = user_id;
ROLLBACK;