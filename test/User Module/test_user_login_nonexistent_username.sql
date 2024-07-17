BEGIN;
-- 尝试使用不存在的用户名登录
SELECT query_uid_from_uname_password__u__('nonexistent_user', 'any_password') INTO uid, err;
-- 检查用户ID是否为0
SELECT uid = 0 AS uid_is_zero;
-- 检查错误信息是否为用户名未找到
SELECT err = 'ERROR_NOT_FOUND_UNAME' AS err_is_not_found_uname;
ROLLBACK;