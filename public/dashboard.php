<?php
// public/dashboard.php
session_start();

// ĐƠN GIẢN: Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

// Đơn giản hóa: Kiểm tra session timeout (7 ngày)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7 * 24 * 60 * 60)) {
    // Session expired
    session_destroy();
    header('Location: auth/login.php?session=expired');
    exit();
}

// Cập nhật last activity
$_SESSION['last_activity'] = time();

// Lấy thông tin user từ session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$full_name = $_SESSION['full_name'] ?? 'Người dùng';
$role = $_SESSION['role'] ?? 'student';
$subscription_type = $_SESSION['subscription_type'] ?? 'free';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - StudyHub</title>
    <style>
        /* CSS remains the same as previous */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .user-info h1 {
            color: #333;
            margin-bottom: 5px;
            font-size: 28px;
        }
        
        .user-info p {
            color: #666;
        }
        
        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .logout-btn:hover {
            background: #c82333;
        }
        
        .welcome-msg {
            background: #4a6cf7;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .tool-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .tool-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #4a6cf7 0%, #6a8cff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: white;
            font-size: 28px;
        }
        
        .tool-card h3 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .tool-card p {
            color: #666;
            font-size: 14px;
        }
        
        .coming-soon {
            opacity: 0.7;
            position: relative;
        }
        
        .coming-soon::after {
            content: 'Sắp ra mắt';
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ffc107;
            color: #333;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Welcome Message -->
        <?php if (isset($_SESSION['login_success'])): ?>
        <div class="welcome-msg">
            <i class="fas fa-check-circle"></i> 
            Đăng nhập thành công! Chào mừng <?php echo htmlspecialchars($full_name); ?> trở lại.
        </div>
        <?php unset($_SESSION['login_success']); endif; ?>
        
        <!-- Header -->
        <div class="header">
            <div class="user-info">
                <h1>Xin chào, <?php echo htmlspecialchars($full_name); ?>! 👋</h1>
                <p><?php echo htmlspecialchars($email); ?> • <?php echo ucfirst($subscription_type); ?> Plan</p>
            </div>
            <a href="auth/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
        
        <!-- Tools Grid -->
        <div class="tools-grid">
            <!-- Todo List -->
            <a href="tools/todo.php" class="tool-card">
                <div class="tool-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>📝 Todo List</h3>
                <p>Quản lý công việc học tập với hệ thống 3 cấp</p>
            </a>
            
            <!-- Calendar -->
            <div class="tool-card coming-soon">
                <div class="tool-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>📅 Lịch học</h3>
                <p>Lập kế hoạch và theo dõi lịch trình học tập</p>
            </div>
            
            <!-- Pomodoro Timer -->
            <div class="tool-card coming-soon">
                <div class="tool-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <h3>⏱️ Pomodoro Timer</h3>
                <p>Phương pháp Pomodoro để tập trung học tập</p>
            </div>
            
            <!-- Habit Tracker -->
            <div class="tool-card coming-soon">
                <div class="tool-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>💪 Theo dõi thói quen</h3>
                <p>Xây dựng và duy trì thói quen học tập</p>
            </div>
            
            <!-- Flashcards -->
            <div class="tool-card coming-soon">
                <div class="tool-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3>🧠 Flashcards</h3>
                <p>Hệ thống ghi nhớ với Spaced Repetition</p>
            </div>
        </div>
    </div>
</body>
</html>