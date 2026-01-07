<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// Page content
ob_start();
?>
<!-- Todo Header -->
<div class="card mb-6">
    <div class="card-header">
        <h2 class="card-title">📝 Todo List</h2>
        <button class="btn btn-primary" id="addTodoBtn">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="mr-2">
                <path d="M8 3V13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M3 8H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Add Todo
        </button>
    </div>
</div>

<!-- Todo Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-text-secondary text-sm">Total</div>
                <div class="text-2xl font-bold">24</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-primary">
                    <path d="M5 3H15C16.1046 3 17 3.89543 17 5V15C17 16.1046 16.1046 17 15 17H5C3.89543 17 3 16.1046 3 15V5C3 3.89543 3.89543 3 5 3Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M7 10L9 12L13 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-text-secondary text-sm">Completed</div>
                <div class="text-2xl font-bold">12</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-green-600">
                    <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-text-secondary text-sm">Pending</div>
                <div class="text-2xl font-bold">8</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-orange-600">
                    <path d="M10 5V10L13.3333 11.6667" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="2"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-text-secondary text-sm">Overdue</div>
                <div class="text-2xl font-bold">4</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-red-600">
                    <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M10 6V11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M10 14H10.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
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
                        'high' => 'bg-red-100 text-red-800',
                        'medium' => 'bg-orange-100 text-orange-800',
                        'low' => 'bg-blue-100 text-blue-800'
                    ][$todo['priority']] ?? 'bg-gray-100 text-gray-800';
                    
                    $status_class = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'in_progress' => 'bg-blue-100 text-blue-800',
                        'completed' => 'bg-green-100 text-green-800'
                    ][$todo['status']] ?? 'bg-gray-100 text-gray-800';
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
                            <button class="p-1 text-text-secondary hover:text-primary">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M11.3333 2.00001C11.5084 1.82492 11.7163 1.68601 11.9452 1.59075C12.1741 1.4955 12.4197 1.44568 12.668 1.44401C12.9162 1.44234 13.1625 1.48884 13.3928 1.58091C13.6232 1.67298 13.8331 1.80886 14.0107 1.98093C14.1882 2.153 14.3299 2.35777 14.4275 2.58352C14.5251 2.80926 14.5765 3.05152 14.5787 3.29668C14.5809 3.54185 14.5339 3.78501 14.4403 4.01237C14.3467 4.23973 14.2083 4.44684 14.0333 4.62223L6.59999 12L2.66666 13.3333L3.99999 9.40001L11.3333 2.00001Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button class="p-1 text-text-secondary hover:text-red-600">
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

<!-- Add Todo Modal (Placeholder) -->
<div class="hidden" id="addTodoModal">
    <!-- Modal content will go here -->
</div>

<script>
// Inline JavaScript for this page
document.addEventListener('DOMContentLoaded', function() {
    // Add todo button click handler
    document.getElementById('addTodoBtn').addEventListener('click', function() {
        alert('Add todo functionality will be implemented here!');
        // You can implement a modal or form here
    });
    
    // Todo checkbox toggle
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const taskText = this.closest('tr').querySelector('span');
            if (this.checked) {
                taskText.classList.add('line-through', 'text-text-secondary');
            } else {
                taskText.classList.remove('line-through', 'text-text-secondary');
            }
        });
    });
});
</script>
<?php

$content = ob_get_clean();

// Include layout
require_once __DIR__ . '/includes/layout.php';