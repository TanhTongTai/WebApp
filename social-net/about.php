<?php
session_start();
// Trang này không nhất thiết phải đăng nhập mới xem được,
// nhưng nếu bạn muốn chỉ thành viên mới xem được thì hãy giữ đoạn code bên dưới:
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu - My App</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            color: #333;
        }

        .container {
            display: flex;
            justify-content: center;
            padding: 50px 20px;
        }

        .about-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            max-width: 600px;
            width: 100%;
            line-height: 1.6;
        }

        .about-card h1 {
            color: #007bff;
            margin-top: 0;
            border-bottom: 2px solid #f0f2f5;
            padding-bottom: 10px;
        }

        .about-card h2 {
            font-size: 1.2rem;
            color: #1c1e21;
            margin-top: 25px;
        }

        .tech-stack {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .tech-item {
            background: #e7f3ff;
            color: #007bff;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .footer-text {
            margin-top: 30px;
            font-size: 14px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Nhúng Navbar chung -->
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="about-card">
            <h1>Về Dự Án Này</h1>
            <p>Chào mừng bạn đến với <strong>My App</strong>. Đây là một hệ thống quản lý người dùng cơ bản được xây dựng để thực hành các kỹ năng lập trình web backend.</p>

            <h2>Chức năng chính:</h2>
            <ul>
                <li>Đăng ký và Đăng nhập bảo mật (mã hóa mật khẩu).</li>
                <li>Quản lý hồ sơ cá nhân (Profile).</li>
                <li>Chỉnh sửa thông tin và Đổi mật khẩu (Settings).</li>
                <li>Hệ thống Session duy trì đăng nhập.</li>
            </ul>

            <h2>Công nghệ sử dụng:</h2>
            <div class="tech-stack">
                <span class="tech-item">PHP 8.x</span>
                <span class="tech-item">MariaDB / MySQL</span>
                <span class="tech-item">PDO</span>
                <span class="tech-item">HTML5 & CSS3</span>
            </div>

            <div class="footer-text">
                Dự án được thực hiện bởi <strong>Tuấn Anh</strong> <br>
                &copy; <?php echo date("Y"); ?> My App Project.
            </div>
        </div>
    </div>

</body>
</html>
