<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login');
    exit();
}

// Set page variables for layout
$page_title = 'Calendar';
$show_breadcrumb = true;

// Page content
ob_start();
?>
<!-- Calendar Header -->
<div class="card mb-6">
    <div class="card-header">
        <h2 class="card-title">📅 Calendar</h2>
        <div class="flex items-center space-x-2">
            <button class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="mr-2">
                    <path d="M10 4L6 8L10 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Previous
            </button>
            <h3 class="text-lg font-bold">December 2023</h3>
            <button class="btn btn-secondary">
                Next
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="ml-2">
                    <path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button class="btn btn-primary ml-4">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="mr-2">
                    <path d="M8 3V13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M3 8H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Add Event
            </button>
        </div>
    </div>
</div>

<!-- Calendar View -->
<div class="card">
    <div class="overflow-x-auto">
        <!-- Weekday Headers -->
        <div class="grid grid-cols-7 border-b border-border">
            <?php
            $weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            foreach ($weekdays as $day):
            ?>
            <div class="p-4 text-center font-medium text-text-secondary">
                <?php echo $day; ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Calendar Grid -->
        <div class="grid grid-cols-7">
            <?php
            // Generate calendar days (example for December 2023)
            $days = [];
            // Add empty days for the start of the month
            for ($i = 0; $i < 5; $i++) {
                $days[] = ['day' => '', 'events' => []];
            }
            // Add actual days
            for ($day = 1; $day <= 31; $day++) {
                $events = [];
                if ($day == 15) {
                    $events[] = ['title' => 'Math Exam', 'color' => 'bg-red-100 border-red-200'];
                }
                if ($day == 20) {
                    $events[] = ['title' => 'Group Meeting', 'color' => 'bg-blue-100 border-blue-200'];
                }
                if ($day == 25) {
                    $events[] = ['title' => 'Christmas', 'color' => 'bg-green-100 border-green-200'];
                }
                $days[] = ['day' => $day, 'events' => $events];
            }
            
            foreach ($days as $index => $day_data):
                $isToday = $day_data['day'] == date('j');
            ?>
            <div class="min-h-32 border-r border-b border-border p-2 <?php echo $index % 7 == 6 ? 'border-r-0' : ''; ?>">
                <?php if ($day_data['day']): ?>
                <div class="text-right">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full <?php echo $isToday ? 'bg-primary text-white' : 'text-text'; ?>">
                        <?php echo $day_data['day']; ?>
                    </span>
                </div>
                
                <!-- Events -->
                <div class="mt-2 space-y-1">
                    <?php foreach ($day_data['events'] as $event): ?>
                    <div class="text-xs p-2 rounded border <?php echo $event['color']; ?> truncate" title="<?php echo htmlspecialchars($event['title']); ?>">
                        <?php echo htmlspecialchars($event['title']); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Upcoming Events -->
<div class="card mt-6">
    <div class="card-header">
        <h2 class="card-title">📅 Upcoming Events</h2>
    </div>
    <div class="space-y-4 p-6">
        <?php
        $events = [
            ['title' => 'Math Final Exam', 'date' => 'Dec 15, 2023', 'time' => '9:00 AM', 'color' => 'bg-red-100 text-red-800'],
            ['title' => 'Group Project Meeting', 'date' => 'Dec 20, 2023', 'time' => '2:00 PM', 'color' => 'bg-blue-100 text-blue-800'],
            ['title' => 'Physics Assignment Due', 'date' => 'Dec 22, 2023', 'time' => '11:59 PM', 'color' => 'bg-orange-100 text-orange-800'],
            ['title' => 'Winter Break Starts', 'date' => 'Dec 25, 2023', 'time' => 'All day', 'color' => 'bg-green-100 text-green-800'],
        ];
        
        foreach ($events as $event):
        ?>
        <div class="flex items-center justify-between p-3 rounded-lg border border-border hover:bg-bg-input">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg <?php echo $event['color']; ?> flex items-center justify-center mr-4">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M6 2V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 2V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 8H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 4H16C16.5523 4 17 4.44772 17 5V15C17 15.5523 16.5523 16 16 16H4C3.44772 16 3 15.5523 3 15V5C3 4.44772 3.44772 4 4 4Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </div>
                <div>
                    <div class="font-medium"><?php echo htmlspecialchars($event['title']); ?></div>
                    <div class="text-sm text-text-secondary">
                        <?php echo $event['date']; ?> • <?php echo $event['time']; ?>
                    </div>
                </div>
            </div>
            <button class="btn btn-icon btn-secondary">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M8 13.3333C11.3137 13.3333 14 10.647 14 7.33333C14 4.01962 11.3137 1.33333 8 1.33333C4.68629 1.33333 2 4.01962 2 7.33333C2 10.647 4.68629 13.3333 8 13.3333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 4V8L10.6667 9.33333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php

$content = ob_get_clean();

// Include layout
require_once __DIR__ . '/includes/layout.php';