// dashboard.js
document.addEventListener('DOMContentLoaded', function() {
    // Nếu bạn muốn dùng JSON data từ PHP
    if (typeof window.dashboardStats !== 'undefined') {
        // Update stats from JSON data
        document.getElementById('stat-study-time').textContent = formatStudyTime(window.dashboardStats.study_time);
        document.getElementById('stat-completion').textContent = window.dashboardStats.completion_rate + '%';
        document.getElementById('stat-streak').textContent = window.dashboardStats.streak_days + ' Days';
        document.getElementById('stat-pending-todos').textContent = window.dashboardStats.pending_todos;
        document.getElementById('stat-upcoming-events').textContent = window.dashboardStats.upcoming_events;
        document.getElementById('stat-active-habits').textContent = window.dashboardStats.active_habits;
        document.getElementById('stat-flashcards-due').textContent = window.dashboardStats.due_flashcards;
        
        // Render activities timeline
        if (typeof window.dashboardActivities !== 'undefined') {
            renderActivities(window.dashboardActivities);
        }
    }
    
    // User info
    document.getElementById('user-welcome-name').textContent = window.userName || 'User';
    document.getElementById('user-plan-status').textContent = window.userPlan || 'Free';
});

function formatStudyTime(minutes) {
    if (minutes >= 60) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return hours + 'h ' + mins + 'm';
    }
    return minutes + 'm';
}

function renderActivities(activities) {
    const container = document.getElementById('recent-activity-timeline');
    if (!container) return;
    
    if (activities.length === 0) {
        container.innerHTML = '<div class="text-center py-8 text-text-secondary italic">No recent activities</div>';
        return;
    }
    
    let html = '';
    activities.forEach((activity, index) => {
        html += `
            <div class="relative flex items-start">
                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-white border-2 border-border flex items-center justify-center z-10">
                    ${activity.icon}
                </div>
                <div class="ml-4 flex-1 pb-8 ${index === activities.length - 1 ? '' : 'border-b border-border'}">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-sm font-medium text-text">${activity.title}</h4>
                            <p class="text-sm text-text-secondary mt-1">${activity.description}</p>
                        </div>
                        <span class="text-xs text-text-secondary">${activity.time_ago}</span>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}