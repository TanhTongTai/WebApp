<?php
session_start();
require_once 'config.php';

// Nếu đã đăng nhập rồi thì vào thẳng Home, không cần đăng nhập lại
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT * FROM account WHERE username = :u");
        $stmt->execute(['u' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Cập nhật trạng thái Online
            try {
                $pdo->prepare("UPDATE users SET status = 'online' WHERE id = ?")->execute([$user['id']]);
            } catch (Exception $e) {}

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];

            header("Location: home.php");
            exit;
        } else {
            $error = "Sai tên đăng nhập hoặc mật khẩu!";
        }
    } catch (PDOException $e) {
        $error = "Lỗi hệ thống, vui lòng thử lại sau.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 350px;
            text-align: center;
        }
        h2 { margin-bottom: 25px; color: #333; }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover { background: #0056b3; }
        .error { color: #d93025; background: #f8d7da; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; }

        /* CSS cho phần liên kết bên dưới */
        .footer-links { margin-top: 20px; font-size: 14px; color: #666; }
        .footer-links a { text-decoration: none; color: #007bff; }
        .footer-links a:hover { text-decoration: underline; }
        .divider { margin: 0 8px; color: #ddd; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Đăng nhập</h2>

        <?php if($error) echo "<div class='error'>$error</div>"; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Vào hệ thống</button>
        </form>

        <div class="footer-links">
            <a href="forgot_password.php">Quên mật khẩu?</a>
            <span class="divider">|</span>
            <a href="../admin/newuser.php">Đăng ký ngay</a>
        </div>
    </div>
</body>
</html>
