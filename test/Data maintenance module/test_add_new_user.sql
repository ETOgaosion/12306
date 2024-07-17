BEGIN;
-- 插入新用户
INSERT INTO users (u_user_name, u_password, u_email, u_tel_num) VALUES ('new_user', 'new_password', 'new_user@example.com', '1234567890');
-- 检查新用户是否成功添加
SELECT COUNT(*) = 1 FROM users WHERE u_user_name = 'new_user';
ROLLBACK;