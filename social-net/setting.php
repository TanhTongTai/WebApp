<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$msg = "";
$msg_type = "";
$tab = $_GET['tab'] ?? 'menu';

// 1. XỬ LÝ CẬP NHẬT THÔNG TIN & UPLOAD ẢNH
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    $fullname = trim($_POST['fullname']);
    $description = trim($_POST['description']);
    $error_upload = false;

    // Xử lý upload ảnh nếu có file được chọn
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $upload_dir = 'images/';
        $file_ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed)) {
            // Xóa toàn bộ file cũ có tên trùng username nhưng khác đuôi
            foreach ($allowed as $ext) {
                if (file_exists($upload_dir . $username . "." . $ext)) {
                    unlink($upload_dir . $username . "." . $ext);
                }
            }
            // Lưu file mới
            move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_dir . $username . "." . $file_ext);
        } else {
            $msg = "Định dạng ảnh không hợp lệ!";
            $msg_type = "error";
            $error_upload = true;
        }
    }

    if (!$error_upload) {
        $stmt = $pdo->prepare("UPDATE account SET fullname = ?, description = ? WHERE id = ?");
        if ($stmt->execute([$fullname, $description, $user_id])) {
            $_SESSION['fullname'] = $fullname;
            $msg = "Cập nhật thông tin thành công!";
            $msg_type = "success";
        }
    }
    $tab = 'info';
}

// 2. XỬ LÝ ĐỔI MẬT KHẨU
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];

    $stmt = $pdo->prepare("SELECT password FROM account WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_db = $stmt->fetch();

    if ($user_db && password_verify($old_pass, $user_db['password'])) {
        if (strlen($new_pass) > 10) {
            $msg = "Mật khẩu mới tối đa 10 ký tự!";
            $msg_type = "error";
        } else {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE account SET password = ? WHERE id = ?")->execute([$hashed, $user_id]);
            $msg = "Đổi mật khẩu thành công!";
            $msg_type = "success";
        }
    } else {
        $msg = "Mật khẩu cũ không chính xác!";
        $msg_type = "error";
    }
    $tab = 'password';
}

// Lấy dữ liệu mới nhất
$stmt = $pdo->prepare("SELECT * FROM account WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cài đặt tài khoản</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; }
        .container { display: flex; flex-direction: column; align-items: center; padding: 40px 20px; min-height: 80vh; }
        .box { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); padding: 35px; border-radius: 20px; width: 100%; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center; border: 1px solid rgba(255,255,255,0.3); }
        .menu-btn { display: block; width: 100%; padding: 15px; margin-bottom: 12px; background: white; border: 1px solid #ddd; border-radius: 12px; cursor: pointer; text-align: left; font-weight: 600; text-decoration: none; color: #333; transition: 0.2s; box-sizing: border-box; }
        .menu-btn:hover { background: #f0f7ff; border-color: #007bff; color: #007bff; transform: translateX(5px); }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #007bff; font-weight: bold; font-size: 14px; }
        label { display: block; text-align: left; margin-top: 15px; font-weight: 600; color: #555; font-size: 14px; }
        input, textarea { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 15px; }
        .btn-submit { width: 100%; padding: 13px; border: none; border-radius: 10px; cursor: pointer; font-weight: bold; margin-top: 25px; color: white; font-size: 16px; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 20px; width: 100%; max-width: 400px; text-align: center; font-weight: 500; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <?php if($msg): ?>
            <div class="alert <?= $msg_type ?>"><?= $msg ?></div>
        <?php endif; ?>

        <!-- TRANG CHỦ SETTING -->
        <?php if($tab == 'menu'): ?>
            <div class="box">
                <h2 style="margin-bottom: 25px;">Cài đặt tài khoản</h2>
                <a href="?tab=info" class="menu-btn">👤 &nbsp; Chỉnh sửa thông tin cá nhân</a>
                <a href="?tab=password" class="menu-btn">🔑 &nbsp; Đổi mật khẩu</a>
		<a href="profile.php" class="menu-btn" style="margin-top: 15px; border-top: 2px solid #eee;">⬅ &nbsp; Quay lại hồ sơ</a>
                <a href="logout.php" class="menu-btn" style="color: #e74c3c; border-color: #fab1a0;">
                  🚪 &nbsp; Đăng xuất tài khoản
               </a>

            </div>

        <!-- FORM CHỈNH SỬA THÔNG TIN -->
        <?php elseif($tab == 'info'): ?>
            <div class="box">
                <a href="?tab=menu" class="back-link">← Quay lại Menu</a>
                <h2>Hồ sơ cá nhân</h2>
                <form method="POST" enctype="multipart/form-data">
                    <label>Ảnh đại diện mới:</label>
                    <input type="file" name="avatar" accept="image/*" style="border:none; padding-left:0;">
                    
                    <label>Họ và tên:</label>
                    <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
                    
                    <label>Mô tả bản thân:</label>
                    <textarea name="description" rows="4"><?= htmlspecialchars($user['description'] ?? '') ?></textarea>
                    
                    <button type="submit" name="update_info" class="btn-submit" style="background:#007bff;">Lưu thay đổi</button>
                </form>
            </div>

        <!-- FORM ĐỔI MẬT KHẨU -->
        <?php elseif($tab == 'password'): ?>
            <div class="box">
                <a href="?tab=menu" class="back-link">← Quay lại Menu</a>
                <h2>Đổi mật khẩu</h2>
                <form method="POST">
                    <label>Mật khẩu hiện tại:</label>
                    <input type="password" name="old_password" required>
                    
                    <label>Mật khẩu mới (Max 10):</label>
                    <input type="password" name="new_password" maxlength="10" required>
                    
                    <button type="submit" name="change_password" class="btn-submit" style="background:#28a745;">Cập nhật mật khẩu</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

