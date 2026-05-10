<?php
session_start();
// Kiểm tra đăng nhập để bảo vệ trang (tùy chọn theo đề bài)
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
    <title>Giới thiệu - About Us</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; margin: 0; padding: 0; }

        /* Container căn giữa card thông tin */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 65px);
            padding: 20px;
            box-sizing: border-box;
        }

        /* Card thông tin sinh viên phong cách kính mờ */
        .about-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease;
        }

        .about-card:hover {
            transform: translateY(-5px);
        }

        h2 {
            color: #1c1e21;
            margin-bottom: 30px;
            font-size: 24px;
            border-bottom: 2px solid #007bff;
            display: inline-block;
            padding-bottom: 5px;
        }

        .student-info {
            text-align: left;
            margin-top: 20px;
        }

        .info-item {
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .value {
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }

        .project-tag {
            margin-top: 30px;
            font-size: 13px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>

    <!-- Nhúng Navbar dùng chung -->
    <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>

    <div class="container">
        <div class="about-card">
            <h2>Thông tin sinh viên</h2>

            <div class="student-info">
                <div class="info-item">
                    <span class="label">Họ và tên</span>
                    <span class="value">Hoàng Phúc Tuấn Anh</span>
                </div>

                <div class="info-item">
                    <span class="label">Mã số sinh viên</span>
                    <span class="value">1695092</span>
                </div>
            </div>

            <p class="project-tag">Web Application Mock Project - SocialNet</p>
            
            <hr style="border: 0; border-top: 1px solid rgba(0,0,0,0.1); margin: 25px 0;">
            
            <a href="home.php" style="color: #007bff; text-decoration: none; font-weight: bold; font-size: 14px;">
                ← Quay lại Trang chủ
            </a>
        </div>
    </div>

</body>
</html>

