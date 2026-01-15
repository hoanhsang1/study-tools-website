<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../../app/models/todo/Todolist.php';
require_once __DIR__ . '../../app/models/todo/Todolistgroup.php';
use App\Models\Todo\Todolist;
use App\Models\Todo\Todolistgroup;
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login');
    exit();
}

// Set page variables for layout
$page_title = 'Todo List';
$show_breadcrumb = true;

// Add page-specific CSS/JS
$page_css = []; // Thêm CSS riêng nếu cần
$page_js = []; // Thêm JS riêng nếu cần

$modelTodolist = new Todolist();
$modelGroup = new Todolistgroup();

$allGroups = $modelGroup->getAllGroupById($_SESSION['todolist']);

// Page content
ob_start();
?>

<style>
    .badge {
  padding: 4px 12px;
  font-size: 12px;
  font-weight: 600;
  border-radius: 999px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  color: #4a5568;
}

.badge-soft {
  background: #f8fafc;
  color: #4a5568;
}

.badge-medium {
  background: rgba(74,108,247,0.08);
  color: #4a6cf7;
  border-color: rgba(74,108,247,0.2);
}

.badge-strong {
  background: #4a6cf7;
  color: white;
  border-color: #4a6cf7;
}

.btn-none {
        background-color: white;
    border: none;
    cursor: pointer;
}

</style>
<!-- Todo Header -->
<div class="card mb-6">
    <div class="card-header">
        <h2 class="card-title">📝 Todo List</h2>
        <button class="btn btn-primary" id="addTodoBtn" onclick="openTodoModal()">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="mr-2">
                <path d="M8 3V13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M3 8H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Add Todo
        </button>
    </div>
    <div class="card-list">
        <ul id="groupList" class="card-nav">
            <?php foreach ($allGroups as $group): ?>
            <li
                class="card-list-item"
                data-id="<?= htmlspecialchars($group['group_id']) ?>"
                data-editable
            >
                <input
                class="edit-input"
                type="text"
                value="<?= htmlspecialchars($group['title']) ?>"
                readonly
                />
                <span class="input-sizer"></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>


<!-- Todo List -->
<div style="height: 417px;" class="card">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-border">
                    <th class="text-left p-4 text-text-secondary text-sm font-medium">Task</th>
                    <th class="text-left p-4 text-text-secondary text-sm font-medium">Priority</th>
                    <th class="text-left p-4 text-text-secondary text-sm font-medium">Due Date</th>
                    <th class="text-left p-4 text-text-secondary text-sm font-medium">Status</th>
                    <th class="text-left p-4 text-text-secondary text-sm font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>

<div id="addTodoModal" class="modal hidden">
  <div class="modal-overlay" onclick="closeTodoModal()"></div>

  <div class="modal-card">
    <h3 class="modal-title">Add new group</h3>

    <input 
      id="todoInput"
      type="text"
      placeholder="Enter group name..."
      class="modal-input"
    />

    <div class="modal-actions">
      <button onclick="closeTodoModal()" class="btn-ghost">Cancel</button>
      <button onclick="createTodo()" class="btn-primary">Create</button>
    </div>
  </div>
</div>



<?php

$content = ob_get_clean();

// Include layout
require_once __DIR__ . '/includes/layout.php';