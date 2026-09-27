<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\User;
use App\Models\Users_avatar;

class ProfileController extends Controller
{
    public function index()
    {
        $avatarModel = new Users_avatar();
        $userModel = new User();
        
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header("Location: /login");
            exit;
        }
        
        $userData = $userModel->findById($userId);
        if (!$userData) {
            $_SESSION['error'] = "User not found";
            header("Location: /login");
            exit;
        }
        
        $avatarPath = $avatarModel->getAvatar($userId) ?? null;

        $this->view('profile/index', [
            'page_title' => 'Profile',
            'avatarPath' => $avatarPath,
            'userData' => $userData
        ]);
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['avatar'])) {
            header("Location: /profile");
            exit;
        }

        $file = $_FILES['avatar'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "Lỗi upload file!";
            header("Location: /profile");
            exit;
        }

        if ($file['size'] > 2000000) {
            $_SESSION['error'] = "File quá lớn!";
            header("Location: /profile");
            exit;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','gif'])) {
            $_SESSION['error'] = "Sai định dạng!";
            header("Location: /profile");
            exit;
        }

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/avatars/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $newName = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $fullPath = $uploadDir . $newName;

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            $_SESSION['error'] = "Không thể lưu file";
            header("Location: /profile");
            exit;
        }

        $pathForDb = 'assets/images/avatars/' . $newName;

        $model = new Users_avatar();
        $old = $model->getAvatar($_SESSION['user_id']);

        if ($old && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $old)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $old);
        }

        $model->saveAvatar($_SESSION['user_id'], $pathForDb);
        $_SESSION['avatar_path'] = $pathForDb;
        $_SESSION['success'] = "Cập nhật avatar thành công";

        header("Location: /profile");
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /profile");
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $_SESSION['error'] = "User not authenticated";
            header("Location: /login");
            exit;
        }

        $userModel = new User();
        $currentUser = $userModel->findById($userId);
        
        if (!$currentUser) {
            $_SESSION['error'] = "User not found";
            header("Location: /profile");
            exit;
        }

        // Get form data
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate required fields
        if (empty($fullname)) {
            $_SESSION['error'] = "Full name is required";
            header("Location: /profile");
            exit;
        }

        if (empty($email)) {
            $_SESSION['error'] = "Email is required";
            header("Location: /profile");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format";
            header("Location: /profile");
            exit;
        }

        if (empty($username)) {
            $_SESSION['error'] = "Username is required";
            header("Location: /profile");
            exit;
        }

        // Check if email is already taken by another user
        if ($email !== $currentUser['email']) {
            $existingUser = $userModel->findByEmail($email);
            if ($existingUser && $existingUser['user_id'] !== $userId) {
                $_SESSION['error'] = "Email is already registered";
                header("Location: /profile");
                exit;
            }
        }

        // Check if username is already taken by another user
        if ($username !== $currentUser['username']) {
            $existingUser = $userModel->findByUsername($username);
            if ($existingUser && $existingUser['user_id'] !== $userId) {
                $_SESSION['error'] = "Username is already taken";
                header("Location: /profile");
                exit;
            }
        }

        // Prepare update data
        $updateData = [
            'fullname' => $fullname,
            'email' => $email,
            'username' => $username,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle password change if provided
        if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
            if (empty($currentPassword)) {
                $_SESSION['error'] = "Current password is required to change password";
                header("Location: /profile");
                exit;
            }

            if (empty($newPassword)) {
                $_SESSION['error'] = "New password is required";
                header("Location: /profile");
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "New passwords do not match";
                header("Location: /profile");
                exit;
            }

            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = "New password must be at least 6 characters long";
                header("Location: /profile");
                exit;
            }

            // Verify current password
            if (!password_verify($currentPassword, $currentUser['password'])) {
                $_SESSION['error'] = "Current password is incorrect";
                header("Location: /profile");
                exit;
            }

            // Update password
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Update user in database
        $success = $userModel->update($userId, $updateData);
        
        if ($success) {
            // Update session data
            $_SESSION['fullname'] = $fullname;
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            
            $_SESSION['success'] = "Profile updated successfully";
        } else {
            $_SESSION['error'] = "Failed to update profile";
        }

        header("Location: /profile");
        exit;
    }
}
