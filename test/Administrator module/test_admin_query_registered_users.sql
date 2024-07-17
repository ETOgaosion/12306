BEGIN;
SELECT p_pid, u_user_name FROM passengers LEFT JOIN users u ON passengers.p_pid = u.u_uid;
-- 检查返回的用户列表是否正确
SELECT COUNT(*) > 0 FROM passengers;
ROLLBACK;