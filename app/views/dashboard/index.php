<div class="min-h-screen bg-gray-50">
    

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Card -->
        <div class="card mb-8 overflow-hidden relative border-none bg-gradient-to-r from-blue-600 to-indigo-700 text-white"
            style="background: linear-gradient(135deg, #4a6cf7 0%, #3a5bd9 100%);">
            <div class="relative z-10 p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Welcome back, <?php echo htmlspecialchars($user_info['fullname']); ?>! 👋</h2>
                        <p class="text-blue-100 opacity-90 max-w-lg">Track your progress, manage your studies, and reach your goals. You've got this!</p>
                        <div class="mt-4 inline-flex items-center px-3 py-1 bg-white/20 rounded-full text-xs font-medium backdrop-blur-sm">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            <?php echo htmlspecialchars(ucfirst($user_info['role'])); ?> Plan Active
                        </div>
                    </div>
                    <div class="flex justify-between grid grid-cols-2 md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="text-blue-100 text-xs uppercase tracking-wider font-semibold mb-1 opacity-80">Study Time</div>
                            <div class="text-2xl font-bold" id="stat-study-time">
                                <?php 
                                $studyMinutes = $stats['study_time'] ?? 0;
                                if ($studyMinutes >= 60) {
                                    $hours = floor($studyMinutes / 60);
                                    $minutes = $studyMinutes % 60;
                                    echo $hours . 'h ' . $minutes . 'm';
                                } else {
                                    echo $studyMinutes . 'm';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-blue-100 text-xs uppercase tracking-wider font-semibold mb-1 opacity-80">Completion</div>
                            <div class="text-2xl font-bold" id="stat-completion"><?php echo $stats['completion_rate'] ?? 0; ?>%</div>
                        </div>
                        <div class="text-center hidden md:block">
                            <div class="text-blue-100 text-xs uppercase tracking-wider font-semibold mb-1 opacity-80">Streak</div>
                            <div class="text-2xl font-bold" id="stat-streak"><?php echo $stats['streak_days'] ?? 0; ?> Days</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Decorative patterns -->
            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-64 h-64 bg-white opacity-5 rounded-full"></div>
            <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-48 h-48 bg-white opacity-5 rounded-full"></div>
        </div>

        <!-- Quick Stats -->
        <div class="flex flex_full justify-center grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pending Todos -->
            <div class="w-25 card hover:shadow-lg transition-all duration-300 border-l-4 border-blue-500 group">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <div class="text-text-secondary text-sm font-medium">Pending Todos</div>
                        <div class="text-3xl font-bold text-text group-hover:text-primary transition-colors">
                            <?php echo $stats['pending_todos'] ?? 0; ?>
                        </div>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-tasks text-blue-500 text-xl group-hover:text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="w-25 card hover:shadow-lg transition-all duration-300 border-l-4 border-purple-500 group">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <div class="text-text-secondary text-sm font-medium">Upcoming Events</div>
                        <div class="text-3xl font-bold text-text group-hover:text-purple-600 transition-colors">
                            <?php echo $stats['upcoming_events'] ?? 0; ?>
                        </div>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                        <i class="far fa-calendar text-purple-600 text-xl group-hover:text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Active Habits -->
            <div class="w-25 card hover:shadow-lg transition-all duration-300 border-l-4 border-green-500 group">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <div class="text-text-secondary text-sm font-medium">Active Habits</div>
                        <div class="text-3xl font-bold text-text group-hover:text-green-600 transition-colors">
                            <?php echo $stats['active_habits'] ?? 0; ?>
                        </div>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-chart-line text-green-600 text-xl group-hover:text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Flashcards Due -->
            <div class="w-25 card hover:shadow-lg transition-all duration-300 border-l-4 border-orange-500 group">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <div class="text-text-secondary text-sm font-medium">Flashcards Due</div>
                        <div class="text-3xl font-bold text-text group-hover:text-orange-600 transition-colors">
                            <?php echo $stats['due_flashcards'] ?? 0; ?>
                        </div>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-id-card text-orange-600 text-xl group-hover:text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Recent Activity -->
            <div class="lg:col-span-2">
                <div class="card h-full">
                    <div class="card-header flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
                        <h2 class="card-title text-xl font-semibold text-gray-800">Recent Activity</h2>
                        <a href="/activity" class="btn btn-ghost btn-sm text-blue-600 hover:text-blue-800">View More</a>
                    </div>
                    <div id="recent-activity-timeline" class="relative space-y-6">
                        <?php if (!empty($activities)): ?>
                            <?php foreach ($activities as $activity): ?>
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-lg">
                                    <?php echo htmlspecialchars($activity['icon']); ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($activity['title']); ?></p>
                                    <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($activity['description']); ?></p>
                                    <p class="text-xs text-gray-400 mt-2"><?php echo htmlspecialchars($activity['time_ago']); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-8 text-gray-500 italic">
                                No recent activities. Start studying to see your progress here!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


        </div>

        <!-- Motivation Section -->
        <div class="card mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 border-indigo-100">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        <i class="fas fa-star mr-2 text-yellow-500"></i>Stay Motivated!
                    </h3>
                    <p class="text-gray-600">
                        <?php
                        $totalPoints = ($stats['pending_todos'] ?? 0) + 
                                      ($stats['active_habits'] ?? 0) + 
                                      ($stats['streak_days'] ?? 0) * 10;
                        
                        if ($totalPoints > 50) {
                            echo "You're doing amazing! Keep up the great work and maintain your momentum.";
                        } elseif ($totalPoints > 20) {
                            echo "Good progress! Every small step counts toward your goals.";
                        } else {
                            echo "Start your journey today! Create your first todo or habit to begin.";
                        }
                        ?>
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-white font-bold"><?php echo min(100, $stats['completion_rate'] ?? 0); ?></span>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Your Score</div>
                        <div class="font-bold text-gray-800"><?php echo $totalPoints; ?> pts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
