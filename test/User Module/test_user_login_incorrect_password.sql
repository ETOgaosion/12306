BEGIN;
-- 尝试使用错误的密码登录
SELECT query_uid_from_uname_password__u__('new_user', 'wrong_password') INTO uid, err;
-- 检查用户ID是否为0
SELECT uid = 0 AS uid_is_zero;
-- 检查错误信息是否为密码错误
SELECT err = 'ERROR_NOT_CORRECT_PASSWORD' AS err_is_wrong_password;
ROLLBACK;