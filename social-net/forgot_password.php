<?php
session_start();
require_once 'config.php';

$message = "";
$step = 1; // Bước 1: Xác minh thông tin

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // XỬ LÝ BƯỚC 1: XÁC MINH
    if (isset($_POST['verify'])) {
        $username = trim($_POST['username'] ?? '');
        $answer = trim($_POST['security_answer'] ?? '');

        $stmt = $pdo->prepare("SELECT * FROM account WHERE username = ? AND security_answer = ?");
        $stmt->execute([$username, $answer]);
        $user = $stmt->fetch();

        if ($user) {
            $step = 2;
            $_SESSION['reset_user_id'] = $user['id']; // Lưu ID vào session tạm
        } else {
            $message = "<div class='alert error'>Thông tin xác minh không chính xác!</div>";
        }
    }

    // XỬ LÝ BƯỚC 2: ĐẶT LẠI MẬT KHẨU
    if (isset($_POST['reset'])) {
        $new_pass = $_POST['new_password'];
        $user_id = $_SESSION['reset_user_id'] ?? null;

        if ($user_id) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE account SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $user_id]);

            // Xóa session tạm
            unset($_SESSION['reset_user_id']);

            $message = "<div class='alert success'>✔️ Thành công! Đang quay lại trang đăng nhập...</div>";
            $step = 3; // Ẩn form

            // Tự động quay lại trang login sau 3 giây
            header("refresh:3; url=login.php");
        } else {
            header("Location: forgot_password.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khôi phục mật khẩu</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 35px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        h2 { margin-bottom: 20px; color: #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 16px; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        button:hover { background: #0056b3; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .back-link { display: block; margin-top: 20px; text-decoration: none; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Khôi phục</h2>
        <?php echo $message; ?>

        <?php if($step == 1): ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Tên đăng nhập" required>
                <input type="text" name="security_answer" placeholder="Câu trả lời bí mật" required>
                <button type="submit" name="verify">Xác minh tài khoản</button>
            </form>
        <?php elseif($step == 2): ?>
            <form method="POST">
                <p style="color: #666; font-size: 14px;">Xác minh đúng! Nhập mật khẩu mới:</p>
                <input type="password" name="new_password" placeholder="Mật khẩu mới (Max 10)" maxlength="10" required>
                <button type="submit" name="reset">Cập nhật mật khẩu</button>
            </form>
        <?php endif; ?>

        <a href="login.php" class="back-link">← Quay lại đăng nhập</a>
    </div>
</body>
</html>
