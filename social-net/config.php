<?php
// 1. Khai báo thông tin cấu hình
$host   = "127.0.0.1";
$dbname = "socialnet";
$user   = "ADMIN";      // Tài khoản mặc định của MariaDB/XAMPP
$pass   = "123456";          // Mật khẩu bạn dùng để vào Terminal (nếu không có thì để trống "")

try {
    // 2. Tạo kết nối PDO với charset utf8mb4 để tránh lỗi font tiếng Việt
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);

    // 3. Thiết lập chế độ báo lỗi để dễ dàng sửa lỗi nếu có
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Nếu kết nối thành công, PHP sẽ chạy tiếp các file khác mà không hiện gì ra màn hình
} catch (PDOException $e) {
    // Nếu lỗi (sai mật khẩu, sai tên DB...), nó sẽ dừng và hiện thông báo lỗi
    die("Lỗi kết nối Database: " . $e->getMessage());
}
?>
