<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];

// --- 1. XỬ LÝ GỬI LỜI MỜI ---
if (isset($_GET['add'])) {
    $friend_id = $_GET['add'];
    // Kiểm tra xem đã có lời mời nào chưa trước khi insert để tránh trùng lặp
    $check = $pdo->prepare("SELECT id FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)");
    $check->execute([$user_id, $friend_id, $friend_id, $user_id]);
    if (!$check->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'pending')");
        $stmt->execute([$user_id, $friend_id]);
    }
    header("Location: friends.php"); exit;
}

// --- 2. XỬ LÝ CHẤP NHẬN LỜI MỜI ---
if (isset($_GET['accept'])) {
    $request_id = $_GET['accept'];
    $stmt = $pdo->prepare("UPDATE friends SET status = 'accepted' WHERE id = ? AND friend_id = ?");
    $stmt->execute([$request_id, $user_id]);
    header("Location: friends.php"); exit;
}

// --- 3. XỬ LÝ XÓA BẠN / TỪ CHỐI ---
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    $stmt = $pdo->prepare("DELETE FROM friends WHERE id = ? AND (user_id = ? OR friend_id = ?)");
    $stmt->execute([$id, $user_id, $user_id]);
    header("Location: friends.php"); exit;
}

// --- 4. LẤY DANH SÁCH LỜI MỜI ĐẾN ---
$stmt_req = $pdo->prepare("SELECT f.id, u.fullname, u.username FROM friends f JOIN account u ON f.user_id = u.id WHERE f.friend_id = ? AND f.status = 'pending'");
$stmt_req->execute([$user_id]);
$requests = $stmt_req->fetchAll();

// --- 5. LẤY DANH SÁCH BẠN BÈ ---
$stmt_f = $pdo->prepare("
    SELECT f.id, u.fullname, u.username, u.status as online_status FROM account u
    JOIN friends f ON (u.id = f.friend_id OR u.id = f.user_id)
    WHERE ((f.user_id = ? OR f.friend_id = ?) AND u.id != ?) AND f.status = 'accepted'
");
$stmt_f->execute([$user_id, $user_id, $user_id]);
$my_friends = $stmt_f->fetchAll();

// --- 6. TÌM NGƯỜI ĐỂ KẾT BẠN ---
$search = $_POST['search'] ?? '';
$stmt_find = $pdo->prepare("SELECT id, username, fullname FROM account WHERE id != ? AND (username LIKE ? OR fullname LIKE ?)
    AND id NOT IN (SELECT friend_id FROM friends WHERE user_id = ?) 
    AND id NOT IN (SELECT user_id FROM friends WHERE friend_id = ?)");
$stmt_find->execute([$user_id, "%$search%", "%$search%", $user_id, $user_id]);
$strangers = $stmt_find->fetchAll();

// Hàm lấy ảnh nhanh cho danh sách
function get_avatar($uname) {
    $path = "images/$uname.jpg";
    return file_exists($path) ? $path : "images/default.jpg";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bạn bè - Hệ thống</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f0f2f5; }
        /* Căn chỉnh container không bị lệch */
        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
            padding: 40px 20px; 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 30px; 
            min-height: calc(100vh - 65px);
        }
        .section-box { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(10px); 
            padding: 25px; 
            border-radius: 20px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
        }
        .user-item { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            padding: 12px; 
            border-bottom: 1px solid rgba(0,0,0,0.05); 
        }
        .mini-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #007bff; }
        .user-info { flex: 1; }
        .user-info b { display: block; font-size: 15px; }
        .status-tag { font-size: 12px; color: #28a745; }
        .btn { padding: 6px 15px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: bold; transition: 0.2s; }
        .btn-add { background: #007bff; color: white; }
        .btn-accept { background: #28a745; color: white; }
        .btn-remove { background: #f8d7da; color: #721c24; }
        .btn:hover { opacity: 0.8; transform: scale(1.05); }
        h2 { font-size: 18px; margin-bottom: 20px; color: #333; border-bottom: 2px solid #007bff; display: inline-block; padding-bottom: 5px; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div>
            <!-- LỜI MỜI -->
            <div class="section-box">
                <h2>Lời mời kết bạn (<?= count($requests) ?>)</h2>
                <?php foreach ($requests as $r): ?>
                    <div class="user-item">
                        <img src="<?= get_avatar($r['username']) ?>" class="mini-avatar">
                        <div class="user-info">
                            <b><?= htmlspecialchars($r['fullname']) ?></b>
                            <span style="font-size: 12px; color: #888;">@<?= $r['username'] ?></span>
                        </div>
                        <div style="display:flex; gap:5px;">
                            <a href="?accept=<?= $r['id'] ?>" class="btn btn-accept">Đồng ý</a>
                            <a href="?remove=<?= $r['id'] ?>" class="btn btn-remove">Xóa</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- DANH SÁCH BẠN BÈ -->
            <div class="section-box" style="margin-top: 20px;">
                <h2>Bạn bè của tôi (<?= count($my_friends) ?>)</h2>
                <?php foreach ($my_friends as $f): ?>
                    <div class="user-item">
                        <img src="<?= get_avatar($f['username']) ?>" class="mini-avatar">
                        <div class="user-info">
                            <b><?= htmlspecialchars($f['fullname']) ?></b>
                            <span class="status-tag"><?= ($f['online_status'] == 'online') ? '● Đang online' : '' ?></span>
                        </div>
                        <a href="?remove=<?= $f['id'] ?>" class="btn btn-remove" onclick="return confirm('Hủy kết bạn?')">Hủy</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- TÌM KIẾM -->
        <div class="section-box">
            <h2>Tìm kiếm người dùng</h2>
            <form method="POST" style="margin-bottom: 20px; display: flex; gap: 10px;">
                <input type="text" name="search" placeholder="Nhập tên hoặc username..." value="<?= htmlspecialchars($search) ?>" style="flex:1; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                <button type="submit" class="btn btn-add" style="border:none; cursor:pointer;">Tìm kiếm</button>
            </form>
            <div style="max-height: 500px; overflow-y: auto;">
                <?php foreach ($strangers as $s): ?>
                    <div class="user-item">
                        <img src="<?= get_avatar($s['username']) ?>" class="mini-avatar">
                        <div class="user-info">
                            <b><?= htmlspecialchars($s['fullname']) ?></b>
                            <span style="font-size: 12px; color: #888;">@<?= $s['username'] ?></span>
                        </div>
                        <a href="?add=<?= $s['id'] ?>" class="btn btn-add">Kết bạn</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>

