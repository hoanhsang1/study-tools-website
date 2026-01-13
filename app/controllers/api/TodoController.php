<?php
namespace App\Controllers\Api;
use App\Models\Todo\Todolist;
use App\Models\Todo\Todolistgroup;

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

private function createGroup()
{
    $title = trim($_POST['title'] ?? '');
    $todolistId = $_SESSION['todolist_id'] ?? null;

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

private function json($success, $error = null, $extra = [])
{
    echo json_encode(array_merge([
        "success" => $success,
        "error" => $error
    ], $extra));
}



}
