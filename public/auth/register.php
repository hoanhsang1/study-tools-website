<?php
// public/auth/register.php - FINAL FIXED VERSION
ob_start();
session_start();

// Bật debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Nếu đã login
if (isset($_SESSION['user_id'])) {
    ob_end_clean();
    header('Location: dashboard');
    exit();
}

require_once __DIR__ . '/../../app/models/User.php';
use App\Models\User; 

// Khởi tạo biến
$username = $email = $fullname = '';
$errors = [];

// Xử lý POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("=== REGISTRATION START ===");
    
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $agreeTerms = isset($_POST['agree_terms']);
    
    // VALIDATION
    if (empty($username)) $errors['username'] = 'Vui lòng nhập tên đăng nhập';
    if (empty($fullname)) $errors['fullname'] = 'Vui lòng nhập họ và tên';
    if (empty($email)) $errors['email'] = 'Vui lòng nhập email';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email không hợp lệ';
    if (empty($password)) $errors['password'] = 'Vui lòng nhập mật khẩu';
    if (strlen($password) < 6) $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
    if ($password !== $confirmPassword) $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp';
    if (!$agreeTerms) $errors['terms'] = 'Bạn cần đồng ý với điều khoản sử dụng';
    
    if (empty($errors)) {
        try {
            $userModel = new User();
            
            // Kiểm tra trùng
            if ($userModel->findByUsername($username)) {
                $errors['username'] = 'Tên đăng nhập đã được sử dụng';
            }
            
            if ($userModel->findByEmail($email)) {
                $errors['email'] = 'Email đã được đăng ký';
            }
            
            if (empty($errors)) {
                error_log("Creating user: $username, $email");
                
                $userData = [
                    'username' => $username,
                    'email' => $email,
                    'fullname' => $fullname,
                    'password' => $password,
                    'role' => 'free',
                    'is_deleted' => 0
                ];
                
                $createdUser = $userModel->createUser($userData);
                
                if ($createdUser && is_array($createdUser)) {
                    error_log("✅ User created successfully");
                    
                    // Tạo session
                    $_SESSION['user_id'] = $createdUser['user_id'];
                    $_SESSION['username'] = $createdUser['username'];
                    $_SESSION['email'] = $createdUser['email'];
                    $_SESSION['full_name'] = $createdUser['fullname'];
                    $_SESSION['role'] = $createdUser['role'];
                    $_SESSION['last_activity'] = time();
                    
                    error_log("Session created - user_id: " . $_SESSION['user_id']);
                    
                    // REDIRECT đến dashboard
                    ob_end_clean();
                    header('Location: dashboard');
                    exit();
                    
                } else {
                    error_log("❌ createUser returned false");
                    $errors['general'] = 'Không thể tạo tài khoản. Vui lòng thử lại.';
                }
            }
            
        } catch (Exception $e) {
            error_log('Registration error: ' . $e->getMessage());
            $errors['general'] = 'Lỗi hệ thống: ' . $e->getMessage();
        }
    }
}

ob_end_flush();
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
    <style>
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }
        .input-error {
            border-color: #dc3545 !important;
        }
        .terms-checkbox {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }
        .terms-checkbox input {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span>StudyHub</span>
                </div>
                <h1 class="auth-title">Đăng ký tài khoản</h1>
                <p class="auth-subtitle">Bắt đầu hành trình học tập của bạn</p>
            </div>

            <?php if (isset($errors['general'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($errors['general']); ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form" id="registerForm">
                <!-- Username -->
                <div class="form-group">
                    <label for="username">TÊN ĐĂNG NHẬP</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" 
                               value="<?php echo htmlspecialchars($username); ?>"
                               class="<?php echo isset($errors['username']) ? 'input-error' : ''; ?>"
                               placeholder="Nhập tên đăng nhập" required>
                    </div>
                    <?php if (isset($errors['username'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['username']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Full Name -->
                <div class="form-group">
                    <label for="fullname">HỌ VÀ TÊN</label>
                    <div class="input-with-icon">
                        <i class="fas fa-id-card"></i>
                        <input type="text" id="fullname" name="fullname" 
                               value="<?php echo htmlspecialchars($fullname); ?>"
                               class="<?php echo isset($errors['fullname']) ? 'input-error' : ''; ?>"
                               placeholder="Nhập họ và tên">
                    </div>
                    <?php if (isset($errors['fullname'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['fullname']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">EMAIL</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" 
                               value="<?php echo htmlspecialchars($email); ?>"
                               class="<?php echo isset($errors['email']) ? 'input-error' : ''; ?>"
                               placeholder="your.email@example.com" required>
                    </div>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['email']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">MẬT KHẨU</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" 
                               class="<?php echo isset($errors['password']) ? 'input-error' : ''; ?>"
                               placeholder="Ít nhất 6 ký tự" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php if (isset($errors['password'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['password']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="confirm_password">XÁC NHẬN MẬT KHẨU</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" 
                               class="<?php echo isset($errors['confirm_password']) ? 'input-error' : ''; ?>"
                               placeholder="Nhập lại mật khẩu" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['confirm_password']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Terms -->
                <div class="terms-checkbox">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required>
                    <label for="agree_terms">
                        Tôi đồng ý với <a href="#" style="color: #4a6cf7;">Điều khoản sử dụng</a>
                    </label>
                </div>
                <?php if (isset($errors['terms'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['terms']); ?></div>
                <?php endif; ?>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary btn-block">Đăng ký tài khoản</button>
            </form>

            <div class="auth-footer">
                <p>Đã có tài khoản? <a href="/auth/login" class="link">Đăng nhập ngay</a></p>
            </div>
        </div>
    </div>

    <script>
        // SIMPLE JAVASCRIPT - NO AJAX
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
        
        // Simple client-side validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const terms = document.getElementById('agree_terms').checked;
            
            let isValid = true;
            
            // Check password match
            if (password !== confirmPassword) {
                alert('Mật khẩu xác nhận không khớp!');
                isValid = false;
            }
            
            // Check terms
            if (!terms) {
                alert('Bạn cần đồng ý với điều khoản sử dụng!');
                isValid = false;
            }
            
            // Check password length
            if (password.length < 6) {
                alert('Mật khẩu phải có ít nhất 6 ký tự!');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
            // Nếu valid, form sẽ submit bình thường
        });
    </script>
</body>
</html>