<?php
namespace App\Controllers\Api;
session_start();
require_once __DIR__ . '/../../models/todo/Todolistgroup.php';
require_once __DIR__ . '/../../models/todo/Todolist.php';
require_once __DIR__ . '/../../models/todo/Task.php';

use App\Models\Todo\Todolist;
use App\Models\Todo\Todolistgroup;
use App\Models\Todo\Task;

class TodoController
{
    public function handle()
{
    header('Content-Type: application/json');

    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user_id'])) {
        $this->json(false, "Unauthorized"); return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->json(false, "Method not allowed"); return;
    }

    $action = $_POST['action'] ?? '';

    match ($action) {
        'createGroup' => $this->createGroup(),
        default => $this->json(false, "Action không hợp lệ")
    };
}

public function createGroup()
{
    $title = trim($_POST['title'] ?? '');
    $todolistId = $_SESSION['todolist'] ?? null;

    if ($title === '') {
        $this->json(false, "Tên group không được để trống"); return;
    }

    if (!$todolistId) {
        $this->json(false, "Thiếu todolist_id"); return;
    }

    $model = new Todolistgroup();
    $group = $model->createGroup($todolistId, $title);

    $this->json((bool)$group, null, ["group" => $group]);
}

public function updateGroup() {
    $title = trim($_POST['title'] ?? "");
    $id = $_POST['groupId'] ?? "";

    if ($id === "") {
        $this->json(false, "Thiếu gropu id"); return;
    }

    if ($title === '') {
        $this->json(false, "Tên group không được để trống");
    }

    $model = new Todolistgroup();
    $group = $model->update($id,['title'=> $title]);
    $this->json((bool)$group,null, ["group" => $group]);
}

public function deleteGroup() {
    $id = $_POST['groupId'] ?? "";

    if ($id === "") {
        $this->json(false, "Thiếu gropu id"); return;
    }

    

    $model = new Todolistgroup();
    $group = $model->softDelete($id);
    $this->json((bool)$group,null);
}

public function getAllTask() {
    $id = $_POST['groupId'] ?? "";

    if ($id === "") {
        $this->json(false, "Thiếu gropu id"); return;
    }

    $model = new Task();
    $task = $model->getAllTaskByGroupId($id);
    $this->json((bool)$task,null,["task" => $task]);
}

private function json($success, $error = null, $extra = [])
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array_merge([
        "success" => $success,
        "error" => $error
    ], $extra));
    exit;
}



}
