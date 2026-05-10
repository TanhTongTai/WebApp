<?php
session_start();
require_once 'config.php'; // Nhúng để đảm bảo kết nối DB nếu cần

// 1. Kiểm tra bảo mật: Nếu chưa đăng nhập thì bắt quay về trang login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. Logic tìm ảnh đại diện dựa trên username (Không dùng SQL)
$username = $_SESSION['username'] ?? 'default';
$user_img = "images/" . $username . ".jpg";
// Thêm ?t=time() để tránh trình duyệt lưu ảnh cũ trong bộ nhớ đệm
$display_img = file_exists($user_img) ? $user_img . "?t=" . time() : "images/default.jpg";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Hệ thống</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            /* Nền sẽ được lấy từ file navbar.php nếu bạn đã chèn ảnh nền vào đó */
        }

        /* KHẮC PHỤC LỖI KHÔNG BẤM ĐƯỢC: Navbar phải luôn nằm trên cùng */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 9999 !important;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 70px);
            position: relative;
            z-index: 1; /* Nằm dưới navbar */
        }

        /* Card chào mừng với hiệu ứng kính mờ (Glassmorphism) */
        .welcome-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 380px;
            transition: transform 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .welcome-card:hover {
            transform: translateY(-5px);
        }

        /* Style cho ảnh đại diện trên trang Home */
        .home-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .welcome-card h1 {
            margin: 0 0 10px 0;
            color: #1c1e21;
            font-size: 26px;
        }

        .user-greeting {
            font-size: 18px;
            color: #4b4f56;
            margin-bottom: 5px;
        }

        .user-name {
            color: #007bff;
            font-weight: bold;
        }

        .btn-group {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        /* Nút bấm chuẩn, đảm bảo có thuộc tính cursor:pointer để biết là bấm được */
        .btn {
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
            transition: all 0.3s;
            font-size: 15px;
            cursor: pointer;
            z-index: 10;
        }

        .btn-profile {
            background: #007bff;
            color: white !important;
        }

        .btn-setting {
            background: #f0f2f5;
            color: #4b4f56 !important;
            border: 1px solid #ddd;
        }

        .btn:hover {
            opacity: 0.9;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: scale(1.05);
        }

        hr {
            border: 0;
            border-top: 1px solid #eee;
            margin: 25px 0;
        }
    </style>
</head>
<body>

    <!-- Nhúng Navbar dùng chung -->
    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <div class="welcome-card">
            <!-- Hiển thị ảnh đại diện của người dùng -->
            <img src="<?php echo $display_img; ?>" alt="Avatar" class="home-avatar">

            <h1>Chào mừng!</h1>

            <p class="user-greeting">
                Xin chào, <span class="user-name"><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'Thành viên'); ?></span>
            </p>

            <p style="color: #888; font-size: 14px; margin: 0;">
                @<?php echo htmlspecialchars($_SESSION['username'] ?? 'user'); ?>
            </p>

            <hr>

            <p style="line-height: 1.6; color: #606770; font-size: 15px;">
                Bạn đã đăng nhập thành công. Hãy khám phá các tính năng quản lý hồ sơ ngay bên dưới.
            </p>

            <div class="btn-group">
                <a href="profile.php" class="btn btn-profile">Hồ sơ của tôi</a>
                <a href="setting_page.php" class="btn btn-setting">Cài đặt</a>
            </div>
        </div>
    </div>

</body>
</html>
