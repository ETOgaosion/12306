BEGIN;
-- 查询用户信息
SELECT * FROM users WHERE u_user_name = 'new_user';
-- 检查返回的用户信息是否正确
SELECT COUNT(*) > 0 FROM users WHERE u_user_name = 'new_user';
ROLLBACK;