BEGIN;
-- 更新用户密码
UPDATE users SET u_password = 'new_password' WHERE u_uid = user_id;
-- 尝试使用新密码登录
SELECT query_uid_from_uname_password__u__('user_name', 'new_password') INTO uid, err;
-- 检查用户ID是否有效
SELECT uid = user_id AS uid_is_valid;
ROLLBACK;