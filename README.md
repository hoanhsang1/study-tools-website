<div align="center">

# 🎓 StudyHub - Study Tools Platform

**Nền tảng web hỗ trợ học tập và quản lý thời gian toàn diện dành cho học sinh, sinh viên.**

[![PHP](https://img.shields.io/badge/PHP-8.x%20%7C%207.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/)
[![Architecture](https://img.shields.io/badge/Architecture-Custom%20MVC-brightgreen?style=for-the-badge)](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
[![License](https://img.shields.io/badge/License-MIT-blue.style=for-the-badge)](LICENSE)

<br/>

[Tính năng](#-tính-năng-chính) • [Công nghệ](#-công-nghệ-sử-dụng) • [Cài đặt](#-hướng-dẫn-cài-đặt) • [Cấu trúc](#-cấu-trúc-thư-mục) • [Đóng góp](#-đóng-góp)

</div>

---

## 📖 Giới thiệu

**StudyHub** là giải pháp web tất-cả-trong-một (All-in-One) được thiết kế nhằm nâng cao hiệu suất học tập, rèn luyện tính kỷ luật và tối ưu hóa thời gian cho người học. Thay vì phải sử dụng nhiều ứng dụng rời rạc, StudyHub quy tụ các phương pháp học tập khoa học nhất (Pomodoro, Spaced Repetition, Habit Loop) vào một giao diện trực quan, hiện đại và dễ tiếp cận.

Dự án được xây dựng theo mô hình kiến trúc **MVC (Model - View - Controller)** hướng đối tượng (OOP) bằng **PHP thuần**, kết hợp hệ thống API nội bộ xử lý dữ liệu động bằng **Vanilla JavaScript** (Fetch API).

---

## ✨ Tính năng chính

### 1. 📊 Dashboard tổng quan & Phân tích
- Thống kê thời gian tập trung tích lũy (Study Time).
- Tỷ lệ hoàn thành công việc và theo dõi chuỗi ngày học tập (Study Streak).
- Bảng tổng hợp công việc cần làm trong ngày và lịch học sắp tới.

### 2. ✅ Quản lý công việc (To-Do List)
- Phân loại công việc theo từng nhóm/dự án học tập (Group Tasks).
- Thêm mới, chỉnh sửa, xóa và đánh dấu hoàn thành nhanh chóng.
- Thiết lập mức độ ưu tiên, hạn chót (Deadline) và xem thông tin chi tiết từng tác vụ.

### 3. 📅 Lịch học tập & Sự kiện (Calendar)
- Giao diện lịch biểu trực quan theo tháng/tuần.
- Lên lịch các buổi học, bài thi, nộp đồ án và sự kiện quan trọng.
- Hỗ trợ thiết lập thông báo và nhắc hẹn (Event Reminders).

### 4. ⏱️ Đồng hồ tập trung (Pomodoro Timer)
- Ứng dụng kỹ thuật Pomodoro kinh điển: 25 phút tập trung / 5 phút nghỉ ngắn / nghỉ dài.
- Tùy chỉnh linh hoạt thời gian theo nhu cầu học tập cá nhân.
- Tự động ghi nhận lịch sử các phiên học và thống kê tổng thời gian tập trung.

### 5. 🔁 Theo dõi thói quen (Habit Tracker)
- Xây dựng và duy trì thói quen tích cực (đọc sách, ôn từ vựng, dậy sớm...).
- Check-in hàng ngày với tính năng tính chuỗi ngày duy trì liên tục (Current / Best Streak).
- Báo cáo trực quan mức độ duy trì thói quen theo thời gian.

### 6. 📇 Thẻ ghi nhớ thông minh (Flashcards)
- Quản lý thẻ học theo các bộ chủ đề (Flashcard Sets) riêng biệt.
- Cơ chế lật thẻ ôn tập linh hoạt giúp tăng cường khả năng ghi nhớ dài hạn.
- Đánh dấu mức độ ghi nhớ và thống kê tiến độ học từng chủ đề.

### 7. 👤 Cá nhân hóa & Bảo mật tài khoản
- Hệ thống đăng ký, đăng nhập với cơ chế mã hóa mật khẩu an toàn (`password_hash`).
- Hỗ trợ đăng nhập nhanh qua Google OAuth 2.0.
- Quản lý trang cá nhân (Profile), cập nhật thông tin và tải lên ảnh đại diện (Avatar upload).

### 8. 🛡️ Bảng điều khiển Quản trị viên (Admin Panel)
- Quản lý danh sách người dùng và phân quyền hệ thống.
- Báo cáo tổng thể dữ liệu và hoạt động trên toàn hệ thống.

---

## 🛠 Công nghệ sử dụng

| Lớp (Layer) | Công nghệ / Kỹ thuật |
| :--- | :--- |
| **Backend** | PHP 7.4+ / PHP 8.x (OOP, PDO, Custom MVC Router, RESTful APIs) |
| **Frontend** | HTML5, Modern CSS3 (Flexbox/Grid, Responsive), Vanilla JavaScript (ES6+, Fetch API) |
| **Database** | MySQL / MariaDB (Bộ mã hóa `utf8mb4`) |
| **Server & Hosting** | Apache (hỗ trợ `mod_rewrite` qua `.htaccess`), tương thích hoàn toàn với XAMPP, Laragon, WampServer |
| **Xác thực** | Session-based Auth, Google OAuth 2.0 Client |

---

## 📁 Cấu trúc thư mục

```text
study-tools-website/
├── app/
│   ├── config/          # Cấu hình kết nối cơ sở dữ liệu và biến môi trường
│   ├── Core/            # Framework Core (App, Controller, Model, Router)
│   ├── controllers/     # Bộ điều khiển xử lý logic
│   │   ├── Api/         # RESTful API Controllers cho Frontend Fetch
│   │   └── Web/         # Web View Controllers
│   ├── Models/          # Các Model tương tác Database (User, Todo, Pomodoro...)
│   ├── views/           # Giao diện người dùng (Admin, Auth, Dashboard, Modules...)
│   └── routes.php       # Định tuyến URL (Web routes & API endpoints)
├── public/              # Document Root công khai (Apache DocumentRoot)
│   ├── assets/          # Tài nguyên tĩnh: CSS, JavaScript, icons, ảnh
│   ├── uploads/         # Thư mục lưu trữ tệp tin tải lên (User avatars)
│   ├── .htaccess        # Rewrite URL đưa toàn bộ request vào index.php
│   └── index.php        # Tệp khởi chạy chính của ứng dụng
├── .env.example         # Tệp mẫu thiết lập biến môi trường
├── .gitignore           # Danh sách các tệp tin loại trừ khỏi Git
└── README.md            # Tài liệu giới thiệu và hướng dẫn dự án
```

---

## 🚀 Hướng dẫn cài đặt & Chạy dự án

### Yêu cầu hệ thống
- Môi trường chạy PHP & MySQL: [XAMPP](https://www.apachefriends.org/), [Laragon](https://laragon.org/), hoặc tương đương.
- PHP: Phiên bản >= 7.4 (Khuyến nghị PHP 8.0+).
- MySQL: Phiên bản >= 5.7 hoặc MariaDB >= 10.3.
- Tiện ích mở rộng PHP đã bật: `pdo_mysql`, `curl`, `mbstring`, `fileinfo`.

---

### Các bước triển khai

#### 1. Clone mã nguồn về máy
Sao chép thư mục dự án vào thư mục gốc của Web Server (ví dụ: `d:/xampp/htdocs/` hoặc `C:/laragon/www/`):

```bash
git clone https://github.com/hoanhsang1/study-tools-website.git
cd study-tools-website
```

#### 2. Cấu hình biến môi trường (`.env`)
Tạo bản sao từ file `.env.example` và đổi tên thành `.env`:

```bash
cp .env.example .env
```

Mở tệp `.env` vừa tạo và cập nhật các thông số phù hợp với môi trường của bạn:

```env
# Database Configuration
DB_HOST=localhost
DB_PORT=3306
DB_NAME=StudyHub
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4

# App Configuration
APP_NAME=StudyHub
APP_URL=http://localhost/study-tools-website/public
DEBUG=true

# Google OAuth Configuration (Tùy chọn)
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost/study-tools-website/public/google-callback
```

#### 3. Khởi tạo Cơ sở dữ liệu
1. Mở **phpMyAdmin** (`http://localhost/phpmyadmin`) hoặc công cụ quản trị MySQL (Navicat, DBeaver).
2. Tạo mới một database với tên tương ứng trong cấu hình (mặc định: `StudyHub`, định dạng bảng mã `utf8mb4_unicode_ci`).
3. Import cấu trúc bảng và dữ liệu mẫu từ tệp cơ sở dữ liệu `db.sql`.

#### 4. Phân quyền thư mục tải lên
Đảm bảo thư mục lưu trữ ảnh có quyền ghi:
- Thư mục: `public/uploads/` (quyền `775` hoặc `777` trên Linux/macOS).

#### 5. Khởi chạy và trải nghiệm
- Khởi động Apache và MySQL trên trình điều khiển (XAMPP / Laragon).
- Truy cập vào trình duyệt web theo đường dẫn:
  ```
  http://localhost/study-tools-website/public
  ```
  *(Hoặc tên miền ảo VirtualHost nếu bạn cấu hình riêng, ví dụ: `http://studyhub.local`)*

---

## 📡 Tổng quan API nội bộ (REST Endpoints)

Dự án cung cấp hệ thống API nội bộ phục vụ tải và xử lý dữ liệu bất đồng bộ (AJAX/Fetch):

| Endpoint | Method | Mô tả |
| :--- | :---: | :--- |
| `/todo/api/task` | `GET` | Lấy danh sách nhiệm vụ cần làm |
| `/todo/api/createTask` | `POST` | Tạo mới một công việc |
| `/todo/api/toggleStatus`| `POST` | Cập nhật trạng thái hoàn thành task |
| `/pomodoro/api/start` | `POST` | Bắt đầu một phiên tập trung mới |
| `/pomodoro/api/stats` | `GET` | Lấy số liệu thống kê thời gian học |
| `/habit/api/get` | `GET` | Lấy danh sách thói quen và chuỗi ngày |
| `/habit/api/toggle` | `POST` | Check-in hoàn thành thói quen trong ngày |
| `/flashcards/api` | `GET / POST` | Quản lý bộ thẻ và theo dõi tiến độ ôn tập |
| `/calendar/api` | `GET / POST` | Truy xuất và thêm sự kiện vào lịch |

---

## 🤝 Đóng góp (Contributing)

Mọi ý kiến đóng góp, báo cáo lỗi (Issues) và đề xuất tính năng (Pull Requests) đều được chào đón!

1. **Fork** dự án về tài khoản của bạn.
2. Tạo một nhánh tính năng mới: `git checkout -b feature/AmazingFeature`
3. Commit các thay đổi: `git commit -m 'feat: Add some AmazingFeature'`
4. Push lên nhánh của bạn: `git push origin feature/AmazingFeature`
5. Mở một **Pull Request** trên GitHub.

---

## 📝 Giấy phép (License)

Dự án được phát hành theo giấy phép [MIT License](LICENSE). Bạn hoàn toàn có thể tự do tham khảo, học tập và phát triển thêm.

---

<div align="center">
  <sub>Xây dựng với ❤️ bởi <a href="https://github.com/hoanhsang1">Sang</a> và cộng đồng người học.</sub>
</div>
