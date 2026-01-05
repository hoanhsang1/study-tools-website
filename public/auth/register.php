<?php
// public/auth/register.php
session_start();

// Khởi tạo biến
$error = '';
$success = '';
$username = $email = $fullname = '';

// Xử lý POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $agreeTerms = isset($_POST['agree_terms']) ? true : false;
    
    // Basic validation (sẽ cải thiện sau)
    if (empty($username)) {
        $error = 'Vui lòng nhập tên đăng nhập';
    } elseif (empty($email)) {
        $error = 'Vui lòng nhập email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
    } elseif (empty($password)) {
        $error = 'Vui lòng nhập mật khẩu';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự';
    } elseif ($password !== $confirmPassword) {
        $error = 'Mật khẩu xác nhận không khớp';
    } elseif (!$agreeTerms) {
        $error = 'Bạn cần đồng ý với điều khoản sử dụng';
    } else {
        // TODO: Xử lý đăng ký với database
        $success = 'Đăng ký thành công! Vui lòng kiểm tra email.';
        
        // Reset form
        $username = $email = $fullname = '';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyHub | Đăng ký</title>
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Logo/Header -->
            <div class="auth-header">
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span>StudyHub</span>
                </div>
                <h1 class="auth-title">Đăng ký tài khoản</h1>
                <p class="auth-subtitle">Bắt đầu hành trình học tập của bạn</p>
            </div>

            <!-- Messages -->
            <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" action="" class="auth-form" id="registerForm">
                <div class="form-group">
                    <label for="username">TÊN ĐĂNG NHẬP</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" 
                               value="<?php echo htmlspecialchars($username); ?>"
                               placeholder="Nhập tên đăng nhập" required>
                    </div>
                    <div class="error-message" id="usernameError"></div>
                </div>

                <div class="form-group">
                    <label for="fullname">HỌ VÀ TÊN</label>
                    <div class="input-with-icon">
                        <i class="fas fa-id-card"></i>
                        <input type="text" id="fullname" name="fullname" 
                               value="<?php echo htmlspecialchars($fullname); ?>"
                               placeholder="Nhập họ và tên đầy đủ">
                    </div>
                    <div class="error-message" id="fullnameError"></div>
                </div>

                <div class="form-group">
                    <label for="email">EMAIL</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" 
                               value="<?php echo htmlspecialchars($email); ?>"
                               placeholder="your.email@example.com" required>
                    </div>
                    <div class="error-message" id="emailError"></div>
                </div>

                <div class="form-group">
                    <label for="password">MẬT KHẨU</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" 
                               placeholder="Ít nhất 6 ký tự" required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye eye"></i>
                        </button>
                    </div>
                    <div class="error-message" id="passwordError"></div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">XÁC NHẬN MẬT KHẨU</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" 
                               placeholder="Nhập lại mật khẩu" required>
                        <button type="button" class="toggle-password" id="toggleConfirmPassword">
                            <i class="fas fa-eye eye"></i>
                        </button>
                    </div>
                    <div class="error-message" id="confirm_passwordError"></div>
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required>
                    <label for="agree_terms">
                        Tôi đồng ý với <a href="#" class="link">Điều khoản sử dụng</a> và <a href="#" class="link">Chính sách bảo mật</a>
                    </label>
                    <div class="error-message" id="termsError"></div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
                    <span class="btn-text">Đăng ký</span>
                    <div class="spinner hidden" id="loadingSpinner">
                        <div class="spinner-dot"></div>
                        <div class="spinner-dot"></div>
                        <div class="spinner-dot"></div>
                    </div>
                </button>
            </form>


            <!-- Footer Links -->
            <div class="auth-footer">
                <p>
                    Đã có tài khoản? 
                    <a href="login.php" class="link">Đăng nhập ngay</a>
                </p>
                <p class="copyright">
                    &copy; 2024 StudyHub. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script src="js/auth.js"></script>
</body>
</html>