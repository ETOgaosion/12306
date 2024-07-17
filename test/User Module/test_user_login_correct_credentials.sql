BEGIN;
-- 尝试使用正确的凭据登录
SELECT query_uid_from_uname_password__u__('new_user', 'valid_password') INTO uid, err;
-- 检查返回的用户ID是否有效
SELECT uid > 0 AS uid_is_valid;
-- 检查错误信息是否为无错误
SELECT err = 'NO_ERROR' AS err_is_no_error;
ROLLBACK;