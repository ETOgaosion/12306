BEGIN;
-- 假设 user_id 是一个已存在的用户ID
UPDATE users SET u_password = 'new_password', u_tel_num = 'new_phone_num', u_email = 'new_email' WHERE u_uid = user_id;
-- 验证用户信息是否更新
SELECT * FROM users WHERE u_uid = user_id;
ROLLBACK;