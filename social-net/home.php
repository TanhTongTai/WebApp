<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. Logic tìm ảnh đại diện
$username = $_SESSION['username'] ?? 'default';
$user_img = "images/" . $username . ".jpg";
$display_img = file_exists($user_img) ? $user_img . "?t=" . time() : "images/default.jpg";

// THÊM: Lấy danh sách những người dùng khác (Yêu cầu đề bài)
$stmt = $pdo->prepare("SELECT id, fullname, username FROM account WHERE id != ?");
$stmt->execute([$_SESSION['user_id']]);
$other_users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Hệ thống</title>
    <style>
        /* GIỮ NGUYÊN TOÀN BỘ CSS CŨ CỦA BẠN */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; color: #333; }
        .navbar { position: sticky; top: 0; z-index: 9999 !important; }
        .main-content { display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 70px); position: relative; z-index: 1; }
        .welcome-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); text-align: center; width: 100%; max-width: 380px; transition: transform 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.3); }
        .welcome-card:hover { transform: translateY(-5px); }
        .home-avatar { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 5px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .welcome-card h1 { margin: 0 0 10px 0; color: #1c1e21; font-size: 26px; }
        .user-greeting { font-size: 18px; color: #4b4f56; margin-bottom: 5px; }
        .user-name { color: #007bff; font-weight: bold; }
        .btn-group { margin-top: 30px; display: flex; gap: 15px; justify-content: center; }
        .btn { padding: 12px 25px; border-radius: 10px; text-decoration: none; display: inline-block; font-weight: bold; transition: all 0.3s; font-size: 15px; cursor: pointer; z-index: 10; }
        .btn-profile { background: #007bff; color: white !important; }
        .btn-setting { background: #f0f2f5; color: #4b4f56 !important; border: 1px solid #ddd; }
        .btn:hover { opacity: 0.9; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transform: scale(1.05); }
        hr { border: 0; border-top: 1px solid #eee; margin: 25px 0; }

        /* THÊM CSS CHO DANH SÁCH USER (Vẫn giữ style kính mờ) */
        .user-list { text-align: left; max-height: 150px; overflow-y: auto; padding-right: 5px; }
        .user-item { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
        .user-item:last-child { border: none; }
        .user-link { color: #007bff; text-decoration: none; font-size: 13px; font-weight: bold; }
        .user-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="main-content">
        <div class="welcome-card">
            <img src="<?php echo $display_img; ?>" alt="Avatar" class="home-avatar">

            <h1>Chào mừng!</h1>

            <p class="user-greeting">
                Xin chào, <span class="user-name"><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'Thành viên'); ?></span>
            </p>

            <p style="color: #888; font-size: 14px; margin: 0;">
                @<?php echo htmlspecialchars($_SESSION['username'] ?? 'user'); ?>
            </p>

            <hr>

            <!-- PHẦN DANH SÁCH NGƯỜI DÙNG KHÁC (YÊU CẦU ĐỀ BÀI) -->
            <p style="text-align: left; font-size: 14px; font-weight: bold; color: #333; margin-bottom: 10px;">Thành viên khác:</p>
            <div class="user-list">
                <?php foreach ($other_users as $u): ?>
                <div class="user-item">
                    <span style="font-size: 14px; color: #555;"><?= htmlspecialchars($u['fullname']) ?></span>
                    <a href="profile.php?owner=<?= $u['id'] ?>" class="user-link">Xem hồ sơ</a>
                </div>
                <?php endforeach; ?>
                <?php if(empty($other_users)) echo "<p style='font-size: 12px; color: #999;'>Chưa có người dùng khác.</p>"; ?>
            </div>

            <div class="btn-group">
                <a href="profile.php" class="btn btn-profile">Hồ sơ của tôi</a>
                <a href="setting.php" class="btn btn-setting">Cài đặt</a>
            </div>
        </div>
    </div>

</body>
</html>

