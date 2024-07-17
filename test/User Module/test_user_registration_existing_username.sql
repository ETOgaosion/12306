BEGIN;
-- 尝试插入已存在的用户名
SELECT insert_all_info_into__u__('existing_user', 'valid_password', '0987654321', 'existing_user@example.com') INTO uid, err;
-- 检查用户ID是否为0
SELECT uid = 0 AS uid_is_zero;
-- 检查错误信息是否为用户名重复错误
SELECT err = 'ERROR_DUPLICATE_UNAME' AS err_is_duplicate_uname;
ROLLBACK;