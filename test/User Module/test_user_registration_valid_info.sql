BEGIN;
-- 插入新用户并捕获返回的用户ID和错误信息
SELECT insert_all_info_into__u__('new_user', 'valid_password', '1234567890', 'new_user@example.com') INTO uid, err;
-- 检查用户ID是否为序列的当前值（假设没有其他并发插入）
SELECT uid = currval(pg_get_serial_sequence('users', 'u_uid')) AS uid_is_valid;
-- 检查错误信息是否为预期的无错误值
SELECT err = 'NO_ERROR' AS err_is_no_error;
-- 检查用户信息是否正确存储
SELECT * FROM users WHERE u_user_name = 'new_user';
ROLLBACK;