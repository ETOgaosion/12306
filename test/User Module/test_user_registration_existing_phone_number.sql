BEGIN;
-- 尝试插入已存在的电话号码
SELECT insert_all_info_into__u__('another_user', 'valid_password', '1234567890', 'another_user@example.com') INTO uid, err;
-- 检查用户ID是否为0
SELECT uid = 0 AS uid_is_zero;
-- 检查错误信息是否为电话号码重复错误
SELECT err = 'ERROR_DUPLICATE_U_TEL_NUM' AS err_is_duplicate_tel_num;
ROLLBACK;