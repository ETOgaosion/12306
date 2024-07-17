BEGIN;
-- 假设要删除的用户名为 'new_user'
DELETE FROM users WHERE u_user_name = 'new_user';
-- 检查用户是否被删除
SELECT COUNT(*) = 0 FROM users WHERE u_user_name = 'new_user';
ROLLBACK;