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
    header('Location: /Projects/study-tools-website/public/auth/login.php');
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
            <?php
                foreach ($allGroups as $group) {
                    $title = htmlspecialchars($group['title']);
                    $id = htmlspecialchars($group['group_id']);

                    echo '
                        <li data-id='.$id.' class="card-list-item">
                            <span class="label">' . $title . '</span>

                            <input class="edit-input" type="text" value="' . $title . '" hidden />

                            <div class="actions">
                                <button class="icon-btn" onclick="enableEdit(this)">✏️</button>
                                <button class="icon-btn" onclick="deleteItem(this)">🗑</button>
                            </div>
                        </li>';
                }
            ?>
        </ul>
    </div>
</div>


<!-- Todo List -->
<div class="card">
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
                <?php
                $todos = [
                    ['task' => 'Complete math assignment', 'priority' => 'high', 'due_date' => 'Today', 'status' => 'pending'],
                    ['task' => 'Read chapter 5 of textbook', 'priority' => 'medium', 'due_date' => 'Tomorrow', 'status' => 'in_progress'],
                    ['task' => 'Prepare for group meeting', 'priority' => 'high', 'due_date' => 'Dec 20', 'status' => 'pending'],
                    ['task' => 'Submit project proposal', 'priority' => 'medium', 'due_date' => 'Dec 22', 'status' => 'completed'],
                    ['task' => 'Review lecture notes', 'priority' => 'low', 'due_date' => 'Dec 25', 'status' => 'pending'],
                ];
                
                foreach ($todos as $todo):
                    $priority_class = [
                        'high' => 'badge badge-strong',
                        'medium' => 'badge badge-medium',
                        'low' => 'badge badge-soft'
                        ][$todo['priority']] ?? 'badge';

                        $status_class = [
                        'pending' => 'badge badge-soft',
                        'in_progress' => 'badge badge-medium',
                        'completed' => 'badge badge-strong'
                        ][$todo['status']] ?? 'badge';
                ?>
                <tr class="border-b border-border hover:bg-bg-input">
                    <td class="p-4">
                        <div class="flex items-center">
                            <input type="checkbox" class="mr-3 h-5 w-5 rounded border-border" <?php echo $todo['status'] === 'completed' ? 'checked' : ''; ?>>
                            <span class="<?php echo $todo['status'] === 'completed' ? 'line-through text-text-secondary' : ''; ?>">
                                <?php echo htmlspecialchars($todo['task']); ?>
                            </span>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $priority_class; ?>">
                            <?php echo ucfirst($todo['priority']); ?>
                        </span>
                    </td>
                    <td class="p-4 text-text"><?php echo $todo['due_date']; ?></td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $status_class; ?>">
                            <?php echo str_replace('_', ' ', ucfirst($todo['status'])); ?>
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex space-x-2">
                            <button class="btn-none p-1 text-text-secondary hover:text-primary">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M11.3333 2.00001C11.5084 1.82492 11.7163 1.68601 11.9452 1.59075C12.1741 1.4955 12.4197 1.44568 12.668 1.44401C12.9162 1.44234 13.1625 1.48884 13.3928 1.58091C13.6232 1.67298 13.8331 1.80886 14.0107 1.98093C14.1882 2.153 14.3299 2.35777 14.4275 2.58352C14.5251 2.80926 14.5765 3.05152 14.5787 3.29668C14.5809 3.54185 14.5339 3.78501 14.4403 4.01237C14.3467 4.23973 14.2083 4.44684 14.0333 4.62223L6.59999 12L2.66666 13.3333L3.99999 9.40001L11.3333 2.00001Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button class="btn-none p-1 text-text-secondary hover:text-red-600">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M2 4H3.33333H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5.33331 4V2.66667C5.33331 2.31305 5.47379 1.97391 5.72384 1.72386C5.97389 1.47381 6.31303 1.33334 6.66665 1.33334H9.33331C9.68693 1.33334 10.0261 1.47381 10.2761 1.72386C10.5262 1.97391 10.6666 2.31305 10.6666 2.66667V4M12.6666 4V13.3333C12.6666 13.687 12.5262 14.0261 12.2761 14.2761C12.0261 14.5262 11.6869 14.6667 11.3333 14.6667H4.66665C4.31303 14.6667 3.97389 14.5262 3.72384 14.2761C3.47379 14.0261 3.33331 13.687 3.33331 13.3333V4H12.6666Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
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