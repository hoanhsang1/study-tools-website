<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];
    
    // 1. Kiểm tra lỗi upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Lỗi upload file!";
        header("Location: /profile");
        exit();
    }
    
    // 2. Kiểm tra kích thước (max 2MB)
    if ($file['size'] > 2000000) {
        $_SESSION['error'] = "File quá lớn! Tối đa 2MB.";
        header("Location: /profile");
        exit();
    }
    
    // 3. Kiểm tra loại file bằng extension đơn giản
    $filename = $file['name'];
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (!in_array($extension, $allowedExtensions)) {
        $_SESSION['error'] = "Chỉ chấp nhận ảnh JPG, PNG, GIF!";
        header("Location: /profile");
        exit();
    }
    
    // 4. Tạo folder nếu chưa có
    $uploadDir = __DIR__ . '/assets/images/avatars/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // 5. Tạo tên file mới
    $newFileName = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
    $uploadPath = $uploadDir . $newFileName;
    
    // 6. Di chuyển file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        try {
            // 7. Lưu vào database
            require_once __DIR__ . '../../app/models/Users_avatar.php';
            $avatarModel = new App\Models\Users_avatar();
            
            // Lấy avatar cũ từ database
            $oldAvatar = $avatarModel->getAvatar($_SESSION['user_id']);
            
            // Xóa file cũ nếu có
            if ($oldAvatar && file_exists(__DIR__ . '/' . $oldAvatar)) {
                unlink(__DIR__ . '/' . $oldAvatar);
            }
            
            // Lưu vào database
            $pathForDb = 'assets/images/avatars/' . $newFileName;
            $result = $avatarModel->saveAvatar($_SESSION['user_id'], $pathForDb);
            
            if ($result) {
                $_SESSION['avatar_path'] = $pathForDb;
                $_SESSION['success'] = "Cập nhật avatar thành công!";
            } else {
                $_SESSION['error'] = "Không thể lưu vào database!";
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi database: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Không thể lưu file!";
    }
    
    header("Location: /profile");
    exit();
}

// Nếu không phải POST request
header("Location: /profile");
exit();
?>