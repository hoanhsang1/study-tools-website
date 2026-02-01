<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Todo\Task;
use App\Models\Todo\Todolist;
use App\Models\Todo\Todolistgroup;
use App\Models\Habit\Habit;
use App\Models\Flashcard\Flashcardprogress;
use App\Models\Flashcard\Flashcard;
use App\Models\Calendar\Event;
use App\Models\Pomodoro\PomodoroHistory;

class DashboardController extends Controller
{
    protected $taskModel;
    protected $todolistModel;
    protected $todolistgroupModel;
    protected $habitModel;
    protected $flashcardProgressModel;
    protected $flashcardModel;
    protected $eventModel;
    protected $pomodoroHistoryModel;

    public function __construct()
    {
        
        // Khởi tạo models
        $this->taskModel = new Task();
        $this->todolistModel = new Todolist();
        $this->todolistgroupModel = new Todolistgroup();
        $this->habitModel = new Habit();
        $this->flashcardProgressModel = new Flashcardprogress();
        $this->flashcardModel = new Flashcard();
        $this->eventModel = new Event();
        $this->pomodoroHistoryModel = new PomodoroHistory();
        
    }

    public function index()
    {

        $userId = $_SESSION['user_id'];
        
        // Lấy dữ liệu thống kê
        $stats = $this->getDashboardStats($userId);
        
        // Lấy hoạt động gần đây
        $activities = $this->getRecentActivities($userId);
        
        // Lấy thông tin người dùng
        $userInfo = [
            'fullname' => $_SESSION['fullname'] ?? $_SESSION['username'] ?? 'User',
            'role' => $_SESSION['role'] ?? 'free',
            'username' => $_SESSION['username'] ?? 'User'
        ];
        $page_css = ['/assets/css/modules/dashboard.css'];
        $page_js = ['/assets/js/modules/dashboard.js'];
        $data = [
            'page_title' => 'Dashboard',
            'show_breadcrumb' => true,
            'user_info' => $userInfo,
            'stats' => $stats,
            'activities' => $activities,
            'page_css' => $page_css,
            'page_js' => $page_js
        ];

        $this->view('dashboard/index', $data);
    }

    private function getDashboardStats($userId)
    {
        return [
            'pending_todos' => $this->getPendingTodosCount($userId),
            'upcoming_events' => $this->getUpcomingEventsCount($userId),
            'active_habits' => $this->getActiveHabitsCount($userId),
            'due_flashcards' => $this->getDueFlashcardsCount($userId),
            'study_time' => $this->getTodayStudyTime($userId),
            'completion_rate' => $this->getCompletionRate($userId),
            'streak_days' => $this->getCurrentStreak($userId)
        ];
    }

    private function getPendingTodosCount($userId)
    {
        try {
            // Sửa: Gọi phương thức đúng từ Todolist model
            $todolist = $this->todolistModel->findTodolistByUser($userId);
            
            if (!$todolist) {
                return 0;
            }
            
            $groups = $this->todolistgroupModel->getAllGroupById($todolist['todolist_id']);
            
            $totalPending = 0;
            
            foreach ($groups as $group) {
                $tasks = $this->taskModel->getAllTaskByGroupId($group['group_id']);
                
                foreach ($tasks as $task) {
                    if ($task['status'] === 'pending' || $task['status'] === 'overdue') {
                        $totalPending++;
                    }
                }
            }
            
            return $totalPending;
            
        } catch (\Exception $e) {
            error_log('Pending todos count error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getActiveHabitsCount($userId)
    {
        try {
            $habits = $this->habitModel->getHabitsWithHistory($userId);
            return count($habits);
        } catch (\Exception $e) {
            error_log('Active habits count error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getDueFlashcardsCount($userId)
    {
        try {
            // Sửa: Lấy flashcards cần review
            $reviewCards = $this->flashcardProgressModel->getReviewCards($userId, 1000);
            return count($reviewCards);
        } catch (\Exception $e) {
            error_log('Due flashcards count error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getTodayStudyTime($userId)
    {
        try {
            $stats = $this->pomodoroHistoryModel->getTodayStats($userId);
            return $stats['today_minutes'] ?? 0;
        } catch (\Exception $e) {
            error_log('Today study time error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getCompletionRate($userId)
    {
        try {
            $todolist = $this->todolistModel->findTodolistByUser($userId);
            
            if (!$todolist) {
                return 0;
            }
            
            $groups = $this->todolistgroupModel->getAllGroupById($todolist['todolist_id']);
            
            $totalTasks = 0;
            $completedTasks = 0;
            
            foreach ($groups as $group) {
                $tasks = $this->taskModel->getAllTaskByGroupId($group['group_id']);
                $totalTasks += count($tasks);
                
                foreach ($tasks as $task) {
                    if ($task['status'] === 'completed') {
                        $completedTasks++;
                    }
                }
            }
            
            if ($totalTasks > 0) {
                return round(($completedTasks / $totalTasks) * 100);
            }
            
            return 0;
            
        } catch (\Exception $e) {
            error_log('Completion rate error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getCurrentStreak($userId)
    {
        try {
            $habits = $this->habitModel->getHabitsWithHistory($userId);
            if (empty($habits)) {
                return 0;
            }
            
            // Tính streak đơn giản: check xem hôm nay có hoàn thành habit không
            $today = date('Y-m-d');
            $currentDay = date('j');
            $streak = 0;
            
            foreach ($habits as $habit) {
                if (in_array($currentDay, $habit['completed_days'])) {
                    $streak = 1;
                    break;
                }
            }
            
            return $streak;
            
        } catch (\Exception $e) {
            error_log('Streak calculation error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getUpcomingEventsCount($userId)
    {
        try {
            $upcomingEvents = $this->eventModel->getUpcomingEvents($userId, 1000);
            return count($upcomingEvents);
        } catch (\Exception $e) {
            error_log('Upcoming events count error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getRecentActivities($userId)
    {
        $activities = [];
        
        try {
            // 1. Hoạt động từ Pomodoro
            $pomodoroActivities = $this->pomodoroHistoryModel->getUserHistory($userId, 3);
            
            foreach ($pomodoroActivities as $activity) {
                $startTime = new \DateTime($activity['start_time']);
                $duration = $activity['duration_minutes'] ?? 0;
                $title = $activity['pomodoro_title'] ?? 'Study Session';
                
                $activities[] = [
                    'type' => 'pomodoro',
                    'icon' => '⏱️',
                    'title' => $title,
                    'description' => "Studied for {$duration} minutes",
                    'time_ago' => $this->getTimeAgo($startTime)
                ];
            }
            
            // 2. Hoạt động từ todos (completed)
            $todolist = $this->todolistModel->findTodolistByUser($userId);
            
            if ($todolist) {
                $groups = $this->todolistgroupModel->getAllGroupById($todolist['todolist_id']);
                $completedTasks = [];
                
                foreach ($groups as $group) {
                    $tasks = $this->taskModel->getAllTaskByGroupId($group['group_id']);
                    
                    foreach ($tasks as $task) {
                        if ($task['status'] === 'completed' && !empty($task['updated_at'])) {
                            $task['group_name'] = $group['title'] ?? 'Untitled Group';
                            $completedTasks[] = $task;
                        }
                    }
                }
                
                // Sắp xếp và lấy 2-3 task gần nhất
                usort($completedTasks, function($a, $b) {
                    return strtotime($b['updated_at']) - strtotime($a['updated_at']);
                });
                
                $recentCompleted = array_slice($completedTasks, 0, 2);
                
                foreach ($recentCompleted as $task) {
                    $updatedAt = new \DateTime($task['updated_at']);
                    $activities[] = [
                        'type' => 'todo',
                        'icon' => '✅',
                        'title' => 'Task Completed',
                        'description' => "Completed '{$task['title']}'",
                        'time_ago' => $this->getTimeAgo($updatedAt)
                    ];
                }
            }
            
            // 3. Hoạt động từ habits (hôm nay)
            $habits = $this->habitModel->getHabitsWithHistory($userId);
            $today = date('j');
            
            foreach ($habits as $habit) {
                if (in_array($today, $habit['completed_days'])) {
                    $activities[] = [
                        'type' => 'habit',
                        'icon' => '💪',
                        'title' => 'Habit Completed',
                        'description' => "Completed '{$habit['name']}' habit",
                        'time_ago' => 'Today'
                    ];
                }
            }
            
            // Sắp xếp theo thời gian mới nhất
            usort($activities, function($a, $b) {
                if ($a['time_ago'] === 'Today') return -1;
                if ($b['time_ago'] === 'Today') return 1;
                
                // Convert time_ago to sortable value (x days ago, x hours ago, etc.)
                return $this->getSortableTimeValue($b['time_ago']) - $this->getSortableTimeValue($a['time_ago']);
            });
            
            // Giới hạn 5 hoạt động
            $activities = array_slice($activities, 0, 5);
            
        } catch (\Exception $e) {
            error_log('Recent activities error: ' . $e->getMessage());
            
            // Dữ liệu mẫu nếu có lỗi
            $activities = [
                [
                    'type' => 'todo',
                    'icon' => '✅',
                    'title' => 'Task Completed',
                    'description' => 'Completed "Math homework"',
                    'time_ago' => '10 minutes ago'
                ],
                [
                    'type' => 'pomodoro',
                    'icon' => '⏱️',
                    'title' => 'Study Session',
                    'description' => 'Studied for 25 minutes',
                    'time_ago' => '1 hour ago'
                ],
                [
                    'type' => 'habit',
                    'icon' => '💪',
                    'title' => 'Habit Completed',
                    'description' => 'Completed "Morning exercise" habit',
                    'time_ago' => 'Today'
                ]
            ];
        }
        
        return $activities;
    }

    private function getTimeAgo($datetime)
    {
        $now = new \DateTime();
        $interval = $now->diff($datetime);
        
        if ($interval->y > 0) {
            return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
        } elseif ($interval->m > 0) {
            return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
        } elseif ($interval->d > 0) {
            if ($interval->d == 1) return 'Yesterday';
            return $interval->d . ' days ago';
        } elseif ($interval->h > 0) {
            return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
        } elseif ($interval->i > 0) {
            return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
        } else {
            return 'Just now';
        }
    }

    private function getSortableTimeValue($timeAgo)
    {
        if ($timeAgo === 'Just now') return 0;
        if ($timeAgo === 'Today') return -1;
        if ($timeAgo === 'Yesterday') return -2;
        
        $timeAgo = strtolower($timeAgo);
        $matches = [];
        
        if (preg_match('/(\d+)\s+(year|month|day|hour|minute)s?\s+ago/', $timeAgo, $matches)) {
            $value = intval($matches[1]);
            $unit = $matches[2];
            
            // Convert to minutes for sorting
            switch ($unit) {
                case 'year': return $value * 525600; // 365*24*60
                case 'month': return $value * 43800; // 30.4*24*60
                case 'day': return $value * 1440; // 24*60
                case 'hour': return $value * 60;
                case 'minute': return $value;
                default: return 0;
            }
        }
        
        return 0;
    }
}