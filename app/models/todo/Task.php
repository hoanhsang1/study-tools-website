<?php
namespace App\Models\Todo;
require_once __DIR__ . '/../../core/Model.php';
require_once __DIR__ . '/Todolistgroup.php';
use App\Models\Todo\Todolistgroup;
use FFI\Exception;
use PDO;
class Task extends Todolistgroup {
    protected $table = 'task';
    protected $primaryKey = 'task_id';

    public function getAllTaskByGroupId($id) {
        $sql = "SELECT * FROM {$this->table} WHERE group_id = ? && is_deleted = 0 ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createGroup($todolistID, $title) {
        $groupId = $this->generateUUID();

        $sql = "INSERT INTO {$this->table} (todolist_id, title, group_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([$todolistID, $title, $groupId]);

        if (!$ok) return false;

        return [
            "group_id" => $groupId,
            "title" => $title,
            "todolist_id" => $todolistID
        ];
    }

}