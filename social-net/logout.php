<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    try {
        // Cập nhật trạng thái về offline trước khi thoát
        $stmt = $pdo->prepare("UPDATE account SET status = 'offline' WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
    } catch (PDOException $e) {}
}

// Xóa sạch session và cookie
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Chống quay lại trang cũ bằng nút Back của trình duyệt
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Location: login.php");
exit();

