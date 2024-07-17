BEGIN;
-- 更新用户信息
UPDATE users SET u_password = 'updated_password', u_email = 'updated_user@example.com' WHERE u_user_name = 'new_user';
-- 检查用户信息是否被正确更新
SELECT u_password = 'updated_password' AND u_email = 'updated_user@example.com' FROM users WHERE u_user_name = 'new_user';
ROLLBACK;