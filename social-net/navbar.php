<?php
// 1. Xác định trang hiện tại để làm sáng menu (Active Link)
$current_page = basename($_SERVER['PHP_SELF']);

// 2. Logic lấy Avatar thông minh (Kiểm tra cả jpg và png)
$username = $_SESSION['username'] ?? 'default';
$nav_img_jpg = "imagess/" . $username . ".jpg";
$nav_img_png = "imagess/" . $username . ".png";

if (file_exists($nav_img_jpg)) {
    $display_nav_img = $nav_img_jpg . "?t=" . time();
} elseif (file_exists($nav_img_png)) {
    $display_nav_img = $nav_img_png . "?t=" . time();
} else {
    // Ưu tiên file mặc định png như trong thư mục của bạn
    $display_nav_img = file_exists("images/default.png") ? "images/default.png" : "images/default.jpg";
}

// 3. Đường dẫn ảnh nền
$bg_image = "images/background.jpg";
?>

<style>
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        font-family: 'Segoe UI', Arial, sans-serif;
        /* Thiết lập hình nền */
        background-image: url('<?= $bg_image ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }

    /* QUAN TRỌNG: Sửa lỗi không bấm được bằng cách ép Navbar lên trên cùng */
    .navbar {
        background-color: rgba(44, 62, 80, 0.95) !important; /* Xanh đen hơi trong suốt */
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 50px;
        height: 65px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        position: sticky;
        top: 0;
        z-index: 99999 !important; /* Số cực lớn để không bị card nào đè lên */
    }

    /* Hiệu ứng kính mờ cho các khung nội dung (Home, Profile, About, Setting, Friends) */
    .welcome-card, .card, .form-box, .profile-card, .about-card, .section-box {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
        position: relative;
        z-index: 1;
    }

    .nav-brand { color: white; font-weight: bold; text-decoration: none; font-size: 20px; }
    .nav-links { display: flex; align-items: center; }

    .navbar a.nav-item {
        color: #ecf0f1;
        padding: 8px 15px;
        text-decoration: none;
        transition: 0.3s;
        margin-left: 5px;
        font-weight: 500;
    }

    .navbar a.nav-item:hover { color: #3498db; }

    .navbar a.active {
        background: #3498db;
        border-radius: 5px;
        color: white !important;
    }

    .nav-user {
        display: flex;
        align-items: center;
        margin-left: 15px;
        padding-left: 15px;
        border-left: 1px solid rgba(255,255,255,0.2);
    }

    .nav-avatar-mini {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #3498db;
    }

    .logout {
        color: #e74c3c !important;
        margin-left: 15px;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
    }
    .logout:hover { text-decoration: underline; }
</style>

<nav class="navbar">
    <a href="home.php" class="nav-brand">MY APP</a>
    <div class="nav-links">
        <a href="home.php" class="nav-item <?= ($current_page == 'home.php') ? 'active' : '' ?>">Trang chủ</a>
        <a href="about.php" class="nav-item <?= ($current_page == 'about.php') ? 'active' : '' ?>">Giới thiệu</a>
        <a href="friends.php" class="nav-item <?= ($current_page == 'friends.php') ? 'active' : '' ?>">Bạn bè</a>
        <a href="profile.php" class="nav-item <?= ($current_page == 'profile.php') ? 'active' : '' ?>">Hồ sơ</a>
        <a href="setting.php" class="nav-item <?= ($current_page == 'setting_page.php') ? 'active' : '' ?>">Cài đặt</a>

        <div class="nav-user">
            <img src="<?= $display_nav_img ?>" class="nav-avatar-mini" alt="Avatar">
            <a href="logout.php" class="logout">Thoát</a>
        </div>
    </div>
</nav>
