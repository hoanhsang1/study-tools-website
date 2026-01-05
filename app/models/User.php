<?php
namespace App\Models;

use App\Core\Model\Model;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'user_id'; // Vì database của bạn dùng user_id
    
    /**
     * Find user by email - DÙNG query() method từ Base Model
     */
    public function findByEmail($email)
    {
        $result = $this->query(
            "SELECT * FROM {$this->table} WHERE email = ?", 
            [$email]
        );
        return $result->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Find user by username
     */
    public function findByUsername($username)
    {
        $result = $this->query(
            "SELECT * FROM {$this->table} WHERE username = ?", 
            [$username]
        );
        return $result->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Check if email exists
     */
    public function emailExists($email)
    {
        $user = $this->findByEmail($email);
        return $user !== false;
    }
    
    /**
     * Create user with hashed password - DÙNG create() method từ Base Model
     */
    public function createUser($data)
    {
        // Hash password nếu có
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Thêm timestamps nếu chưa có
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->create($data);
    }

    
    /**
     * Update user password - DÙNG update() method từ Base Model
     */
    public function updatePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, [
            'password' => $hashedPassword,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get all users with pagination
     */
    public function getAllPaginated($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        
        $result = $this->query(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
        
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Search users by name or email
     */
    public function search($keyword)
    {
        $keyword = "%{$keyword}%";
        
        $result = $this->query(
            "SELECT * FROM {$this->table} WHERE username LIKE ? OR email LIKE ? OR fullname LIKE ?",
            [$keyword, $keyword, $keyword]
        );
        
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Count total users
     */
    public function countAll()
    {
        $result = $this->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $result->fetch(\PDO::FETCH_ASSOC)['total'];
    }
    
    /**
     * Get users by role
     */
    public function getByRole($role)
    {
        $result = $this->query(
            "SELECT * FROM {$this->table} WHERE role = ? ORDER BY created_at DESC",
            [$role]
        );
        
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Soft delete user (update is_deleted flag) - DÙNG update() method
     */
    public function softDelete($userId)
    {
        return $this->update($userId, [
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Restore soft deleted user
     */
    public function restore($userId)
    {
        return $this->update($userId, [
            'is_deleted' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get active users only (not deleted)
     */
    public function getActiveUsers()
    {
        $result = $this->query(
            "SELECT * FROM {$this->table} WHERE is_deleted = 0 ORDER BY created_at DESC"
        );
        
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>