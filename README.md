# SocialNet Project - Web Application Mock Project

## 1. Thông tin sinh viên (Student Information)
- **Họ và tên (Full Name):** Hoàng Phúc Tuấn Anh
- **Mã số sinh viên (Student Number):** 20239509

## 2. Công nghệ sử dụng (Techstack)
Dự án được xây dựng trên mô hình LEMP Stack:
- **Operating System:** Linux (Kali Linux)
- **Web Server:** Nginx
- **Database:** MySQL/MariaDB (Database name: `socialnet`)
- **Backend:** PHP 8.4
- **Testing account:** Giúp thầy đỡ tạo nhiều tài khoản mà vẫn muốn check các tính năng như Kết bạn, Xóa bạn,...
    - tuananh2605
    - tuananh260505
    - tuananh26052005
    - Same password: 123456

## 3. Cấu trúc thư mục (Project Structure)
```text
WebApp/
├── admin/
│   └── newuser.php        # Trang tạo tài khoản mới (Admin Page)
├── socialnet/             # Thư mục chính của ứng dụng
│   ├── signin.php         # Trang đăng nhập (SignIn Page)
│   ├── home.php           # Trang chủ (Home Page)
│   ├── profile.php        # Trang hồ sơ người dùng (Profile Page)
│   ├── setting.php        # Trang cài đặt (Setting Page)
│   ├── about.php          # Trang giới thiệu (About Page)
│   ├── signout.php        # Trang đăng xuất (SignOut Page)
│   ├── navbar.php         # Thanh Menu chung (MenuBar)
│   ├── config.php         # Cấu hình kết nối Database
│   └── images/            # Thư mục chứa ảnh đại diện và ảnh nền
├── db.sql                 # File khởi tạo cơ sở dữ liệu
└── README.md              # File hướng dẫn này
```

## 4. Hướng dẫn cài đặt (Installation)

### Bước 1: Khởi tạo Database
Sử dụng file `db.sql` đi kèm để tạo cấu trúc bảng:
```bash
sudo mariadb -u root < db.sql
```

### Bước 2: Cấu hình kết nối
Chỉnh sửa file `socialnet/config.php` để khớp với thông tin MySQL của bạn (username, password).

### Bước 3: Cấp quyền cho thư mục ảnh
Để tính năng upload ảnh đại diện hoạt động, bạn cần cấp quyền ghi cho thư mục `images`:
```bash
chmod 777 socialnet/images
```

## 5. Các tính năng mở rộng (Extended Features)
Ngoài các yêu cầu cơ bản trong đề bài, dự án đã được phát triển thêm các tính năng sau:
- **Avatar System:** Cho phép người dùng tải lên và thay đổi ảnh đại diện cá nhân.
- **Online/Offline Status:** Hiển thị trạng thái hoạt động của người dùng trong thời gian thực.
- **Glassmorphism UI:** Giao diện hiện đại với hiệu ứng kính mờ (blur), tương thích tốt trên nhiều thiết bị.
- **Friend System:** Tính năng tìm kiếm người dùng và gửi lời mời kết bạn (Bảng `friends`).
- **Security Question:** Tính năng đặt câu hỏi bí mật để khôi phục mật khẩu khi bị quên.

## 6. Lưu ý (Notes)
- Đảm bảo Nginx đã được cấu hình để đọc các file PHP trong thư mục dự án.
- Tài khoản quản trị mặc định sau khi chạy file SQL:
  - **Username:** `ADMIN`
  - **Password:** `123456`
- Default sercurity question: my_secret

## 7. Cấu trúc dữ liệu thực tế (Database Preview)
Dưới đây là minh họa dữ liệu thực tế được trích xuất từ hệ thống quản trị MariaDB, cho thấy các mối quan hệ bạn bè và trạng thái của người dùng:

- **Bảng `account`**: Lưu trữ thông tin định danh, trạng thái `online/offline` và nội dung mô tả cá nhân (description).
- **Bảng `friends`**: Lưu trữ các mối quan hệ kết bạn với các trạng thái `pending` (đang chờ) hoặc `accepted` (đã đồng ý).
*(Ghi chú: Hình ảnh trích xuất từ lệnh SELECT thực tế trong Terminal)*

<p align="center">
  <img width="384" height="215" alt="image" src="https://github.com/user-attachments/assets/2df27557-42f8-470f-a550-c0eeff70ef97" />
</p>
