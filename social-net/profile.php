<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php"); 
    exit;
}

$owner_id = $_GET['owner'] ?? $_SESSION['user_id'];
// Biến kiểm tra xem có phải đang xem hồ sơ của chính mình không
$is_mine = ($owner_id == $_SESSION['user_id']);

try {
    $stmt = $pdo->prepare("SELECT * FROM account WHERE id = :id");
    $stmt->execute(['id' => $owner_id]);
    $user = $stmt->fetch();

    if (!$user) {
        die("<div style='text-align:center; padding:50px;'><h2>Người dùng không tồn tại!</h2><a href='home.php'>Quay lại</a></div>");
    }

    $u_name = $user['username'];
    $avatar_url = "images/default.jpg";
    $extensions = ['jpg', 'jpeg', 'png', 'gif'];
    foreach ($extensions as $ext) {
        if (file_exists("images/$u_name.$ext")) {
            $avatar_url = "images/$u_name.$ext?t=" . time();
            break;
        }
    }

} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu.");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_mine ? "Hồ sơ của tôi" : "Hồ sơ của " . htmlspecialchars($user['username']) ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 0; }
        .container { display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 65px); padding: 20px; box-sizing: border-box; }
        .card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); width: 100%; max-width: 400px; text-align: center; border: 1px solid rgba(255, 255, 255, 0.3); }
        .avatar-wrapper { position: relative; display: inline-block; margin-bottom: 20px; }
        .profile-avatar { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 5px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .status-dot { position: absolute; bottom: 10px; right: 10px; width: 20px; height: 20px; border-radius: 50%; border: 3px solid #fff; }
        .info-box { text-align: left; margin-top: 25px; }
        .info-label { font-size: 11px; color: #888; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        .info-value { font-size: 15px; color: #333; padding: 12px; background: rgba(249, 249, 249, 0.8); border-radius: 8px; margin-bottom: 15px; border: 1px solid #eee; line-height: 1.5; }
        .btn-edit { display: block; width: 100%; padding: 14px; background: #007bff; color: white !important; text-decoration: none; border-radius: 10px; font-weight: bold; transition: 0.3s; margin-top: 20px; box-sizing: border-box; }
        .btn-edit:hover { background: #0056b3; transform: translateY(-3px); }
        
        /* Style cho nhãn phân biệt hồ sơ */
        .profile-tag {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .tag-mine { background: #e1f0ff; color: #007bff; }
        .tag-others { background: #f0f2f5; color: #65676b; }
    </style>
</head>
<body>

    <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>

    <div class="container">
        <div class="card">
            <div class="avatar-wrapper">
                <img src="<?= $avatar_url ?>" alt="Avatar" class="profile-avatar">
                <span class="status-dot" style="background-color: <?= (($user['status'] ?? 'offline') == 'online') ? '#28a745' : '#888' ?>;"></span>
            </div>

            <!-- Nhãn hiển thị phân biệt -->
            <div>
                <span class="profile-tag <?= $is_mine ? 'tag-mine' : 'tag-others' ?>">
                    <?= $is_mine ? "Hồ sơ của tôi" : "Hồ sơ thành viên" ?>
                </span>
            </div>

            <h2 style="margin: 0; color: #1c1e21;"><?= htmlspecialchars($user['fullname']) ?></h2>
            <p style="color: #007bff; margin: 5px 0; font-weight: 500;">@<?= htmlspecialchars($user['username']) ?></p>

            <p style="font-size: 14px; font-weight: 600; color: <?= (($user['status'] ?? 'offline') == 'online') ? '#28a745' : '#888' ?>; margin-top: 10px;">
                ● <?= (($user['status'] ?? 'offline') == 'online') ? 'Đang hoạt động' : 'Ngoại tuyến' ?>
            </p>

            <div class="info-box">
                <div class="info-label">Mô tả cá nhân (Description)</div>
                <div class="info-value">
                    <?= !empty($user['description']) ? nl2br(htmlspecialchars($user['description'])) : "<i>Chưa có mô tả.</i>" ?>
                </div>
            </div>

            <?php if ($is_mine): ?>
                <a href="setting.php?tab=info" class="btn-edit">Chỉnh sửa hồ sơ</a>
            <?php else: ?>
                <a href="home.php" class="btn-edit" style="background: #6c757d;">Quay lại trang chủ</a>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>

