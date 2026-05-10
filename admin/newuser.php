<?php
// Nhúng kết nối database
require_once '../social-net/config.php';

$message = "";

// Kiểm tra phương thức request là POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $username = $_POST['username'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        // Mã hóa mật khẩu (bảo mật hơn lưu text thuần)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Chuẩn bị câu lệnh chèn dữ liệu
        $sql = "INSERT INTO account (username, fullname, password) VALUES (:u, :f, :p)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'u' => $username,
            'f' => $fullname,
            'p' => $hashed_password
        ]);

        $message = "<div class='alert success'>Đã lưu tài khoản <b>$username</b> vào Database!</div>";
    } catch(PDOException $e) {
        // Kiểm tra lỗi trùng username (vì ta để UNIQUE trong MariaDB)
        if ($e->getCode() == 23000) {
            $message = "<div class='alert error'>Lỗi: Tên đăng nhập này đã tồn tại!</div>";
        } else {
            $message = "<div class='alert error'>Lỗi: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý thông tin người dùng</title>
    <style>
        body { font-family: 'Arial', sans-serif; display: flex; justify-content: center; padding-top: 50px; background-color: #f0f2f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 350px; }
        h2 { text-align: center; color: #1c1e21; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #4b4f56; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #1877f2; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background-color: #166fe5; }
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="container">
    <h2>Thông tin người dùng</h2>

    <?php echo $message; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="username">Tên đăng nhập (Max 20):</label>
            <input type="text" id="username" name="username" maxlength="20" required>
        </div>

        <div class="form-group">
            <label for="fullname">Họ và tên (Max 200):</label>
            <input type="text" id="fullname" name="fullname" maxlength="200" required>
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu (Max 10):</label>
            <input type="password" id="password" name="password" maxlength="10" required>
        </div>

        <button type="submit">Gửi thông tin</button>
    </form>
</div>

</body>
</html>
