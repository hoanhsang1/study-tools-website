<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-text mb-2">
            <span class="text-gradient">Habit Tracker</span>
        </h1>
        <p class="text-text-secondary text-lg">Build consistency with daily habits</p>
    </div>

    <!-- Stats Overview -->
    <div id="habit-stats" class="flex flex_full grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Stats will be loaded by JavaScript -->
    </div>

    <!-- Main Card Container -->
    <div class="card shadow-lg mb-8">
        <!-- Card Header -->
        <div class="card-header border-b border-border">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Month & Week Navigation -->
                <div class="flex items-center space-x-4">
                    <button id="prev-month" class="btn btn-icon btn-secondary" title="Previous month">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h2 id="current-month" class="text-xl font-bold text-text"></h2>
                    <button id="next-month" class="btn btn-icon btn-secondary" title="Next month">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <button id="today-btn" class="btn btn-primary ml-2">Today</button>
                    
                    <!-- Week Navigation -->
                    <div id="week-nav" class="flex items-center space-x-2 ml-4">
                        <button id="prev-week" class="btn btn-icon btn-secondary" title="Previous week">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <span id="current-week" class="text-text font-medium text-sm"></span>
                        <button id="next-week" class="btn btn-icon btn-secondary" title="Next week">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Add Habit Form -->
                <div class="flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="input-group">
                            <input type="text" 
                                   id="new-habit-input" 
                                   class="form-control" 
                                   placeholder="Add a new habit..."
                                   maxlength="50">
                        </div>
                    </div>
                    
                    <!-- Color Picker -->
                    <div class="flex items-center gap-2 mr-3">
                        <div class="flex gap-1">
                            <div class="color-option active" data-color="#4a6cf7" style="background-color: #4a6cf7;" title="Primary Blue"></div>
                            <div class="color-option" data-color="#10B981" style="background-color: #10B981;" title="Green"></div>
                            <div class="color-option" data-color="#F59E0B" style="background-color: #F59E0B;" title="Amber"></div>
                            <div class="color-option" data-color="#EF4444" style="background-color: #EF4444;" title="Red"></div>
                        </div>
                    </div>
                    
                    <button id="add-habit-btn" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Add
                    </button>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <!-- Weekly Header -->
            <div id="week-header" class="mb-4">
                <!-- Will be populated by JavaScript -->
            </div>

            <!-- Habits List -->
            <div id="habits-container">
                <!-- Habits will be loaded by JavaScript -->
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 text-text-secondary">

                        <i style="font-size: 60px;" class="fa-regular fa-clipboard"></i>
                    </div>
                    <h3 class="text-lg font-medium text-text mb-2">No habits yet</h3>
                    <p class="text-text-secondary">Start by adding your first habit above</p>
                </div>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="card-footer border-t border-border px-6 py-4 bg-bg-input">
            <div class="flex items-center justify-between text-sm">
                <div class="text-text-secondary">
                    <span id="total-habits">0</span> habits • 
                    <span id="total-completions">0</span> completions this week
                </div>
                <div class="text-text-secondary">
                    Legend: 
                    <span class="inline-flex items-center ml-2">
                        <span class="w-3 h-3 rounded-full bg-primary mr-1"></span> Completed
                        <span class="w-3 h-3 rounded-full bg-border ml-4 mr-1"></span> Pending
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Legend -->
    <div class="calendar-legend mt-4 pt-4 border-t border-border">
        <div class="legend-item">
            <div class="legend-color" style="background-color: #4a6cf7;"></div>
            <span>Completed</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="border: 2px solid #4a6cf7; background-color: rgba(74, 108, 247, 0.1);"></div>
            <span>Today</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="border: 2px solid #e2e8f0;"></div>
            <span>Pending</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: rgba(0, 0, 0, 0.02);"></div>
            <span>Weekend</span>
        </div>
    </div>
</div>
<!-- Edit Habit Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 items-center justify-center p-4" style="display: none;">
    <div class="modal-overlay modal-backdrop absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="flex flex_full justify-center modal-container relative z-10 w-full max-w-md">
        <div class="modal-content bg-white rounded-xl shadow-2xl">
            <div class="modal-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-text">Edit Habit</h3>
                    <button id="close-edit-modal" class="modal-close text-text-secondary hover:text-text p-1">
                        ✕
                    </button>
                </div>
                
                <form id="edit-habit-form">
                    <input type="hidden" id="edit-habit-id">
                    
                    <!-- Habit Name -->
                    <div class="mb-4">
                        <label for="edit-habit-name" class="block text-sm font-medium text-text-secondary mb-2">
                            Habit Name
                        </label>
                        <input type="text" 
                               id="edit-habit-name" 
                               class="form-control w-full"
                               placeholder="Enter habit name..."
                               maxlength="50"
                               required>
                        <div class="text-xs text-text-secondary mt-1">Max 50 characters</div>
                    </div>
                    
                    <!-- Color Picker -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-text-secondary mb-2">
                            Color
                        </label>
                        <div class="flex gap-3">
                            <div class="edit-color-option active" data-color="#4a6cf7" style="background-color: #4a6cf7;" title="Primary Blue"></div>
                            <div class="edit-color-option" data-color="#10B981" style="background-color: #10B981;" title="Green"></div>
                            <div class="edit-color-option" data-color="#F59E0B" style="background-color: #F59E0B;" title="Amber"></div>
                            <div class="edit-color-option" data-color="#EF4444" style="background-color: #EF4444;" title="Red"></div>
                            <div class="edit-color-option" data-color="#8B5CF6" style="background-color: #8B5CF6;" title="Purple"></div>
                            <div class="edit-color-option" data-color="#3B82F6" style="background-color: #3B82F6;" title="Blue"></div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="gap-2 flex justify-end space-x-3 pt-4 border-t border-border">
                        <button type="button" id="cancel-edit" class="btn btn-secondary px-6">Cancel</button>
                        <button type="submit" id="save-habit" class="btn btn-primary px-6">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 z-50 items-center justify-center p-4" style="display: none;">
    <div class="modal-backdrop absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="modal-container relative z-10 w-full max-w-md">
        <div class="modal-content bg-white rounded-xl shadow-2xl">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-text">Delete Habit</h3>
                </div>
                <p class="text-text-secondary mb-6" id="confirm-message">
                    Are you sure you want to delete this habit? This action cannot be undone.
                </p>
                <div class="flex justify-end space-x-3">
                    <button id="cancel-delete" class="btn btn-secondary px-6">Cancel</button>
                    <button id="confirm-delete" class="btn btn-error px-6">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="modal-backdrop absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="modal-container relative z-10 w-full max-w-md">
        <div class="modal-content bg-white rounded-xl shadow-2xl">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-text">Delete Habit</h3>
                </div>
                <p class="text-text-secondary mb-6" id="confirm-message">
                    Are you sure you want to delete this habit? This action cannot be undone.
                </p>
                <div class="flex justify-end space-x-3">
                    <button id="cancel-delete" class="btn btn-secondary px-6">Cancel</button>
                    <button id="confirm-delete" class="btn btn-error px-6">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
