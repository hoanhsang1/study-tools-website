<!-- ==================== -->
<!--     PAGE CONTENT     -->
<!-- ==================== -->
<!-- Flash Messages -->
<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success mb-6">
    <?php 
    echo htmlspecialchars($_SESSION['success']);
    unset($_SESSION['success']);
    ?>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-error mb-6">
    <?php 
    echo htmlspecialchars($_SESSION['error']);
    unset($_SESSION['error']);
    ?>
</div>
<?php endif; ?>

<style>
.profil {
    display: flex !important;
    flex-direction: row !important;
    justify-content: center;
    align-items: center !important;
}

.profile_avatar {
    border-radius: 50% !important;
    height: 150px;
    width: 150px;
    text-align: center;
    line-height: 150px;
    margin-right: 60px;
}

.upload_form {
    top: 0;
    left: 0;
    right: 0;
    z-index: 99;
    position: absolute;
    border-radius: 50% !important;
    height: 150px;
    width: 150px;
}

.hidden {
    display: none !important;
}

.avatar-container {
    position: relative;
    display: inline-block;
}

.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
    color: white;
    font-size: 14px;
}

.avatar-container:hover .avatar-overlay {
    opacity: 1;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6b7280;
    padding: 0;
    line-height: 1;
}

.modal-close:hover {
    color: #111827;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}
</style>

<div class="space-y-6">
    <!-- Profile Header -->
    <div class="card">
        <div class="p-6">
            <div class="profil flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- Avatar -->
                <div class="avatar-container">
                    <div class="profile_avatar w-20 h-20 rounded-full bg-gradient-primary flex items-center justify-center text-white text-2xl font-bold shadow-md cursor-pointer relative overflow-hidden">
                        
                        <!-- Nếu có ảnh avatar thì hiển thị ảnh, không thì hiển thị chữ cái đầu -->
                        <?php if($avatarPath && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $avatarPath)): ?>
                            <img src="<?php echo htmlspecialchars($avatarPath); ?>" 
                                alt="Avatar" 
                                class="w-full h-full rounded-full object-cover"
                                id="avatarImage">
                        <?php else: ?>
                            <span id="avatarInitial"><?php echo strtoupper(substr($userData['fullname'] ?? $userData['username'] ?? 'U', 0, 1)); ?></span>
                        <?php endif; ?>
                        
                        <!-- Form upload -->
                        <form id="avatarForm" action="/profile/upload" method="POST" enctype="multipart/form-data" class="upload_form">
                            <input type="file" 
                                name="avatar" 
                                id="avatarInput" 
                                accept="image/*"
                                class="hidden">
                        </form>
                        
                        <!-- Hover overlay -->
                        <div class="avatar-overlay">
                            <span>Change Avatar</span>
                        </div>
                    </div>
                    
                    <!-- Online status -->
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-white border-2 border-white shadow-sm">
                        <div class="w-full h-full rounded-full bg-green-500 flex items-center justify-center">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M10 3L4.5 8.5L2 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                        <div class="min-w-0">
                            <h1 class="text-2xl font-bold text-text truncate"><?php echo htmlspecialchars($userData['fullname'] ?? $userData['username']); ?></h1>
                            <div class="flex items-center gap-2 text-text-secondary text-sm mt-1">
                                <span class="truncate">@<?php echo htmlspecialchars($userData['username']); ?></span>
                                <span>•</span>
                                <span class="truncate"><?php echo htmlspecialchars($userData['email'] ?? ''); ?></span>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 shrink-0">
                            <span class="badge badge-primary px-3 py-1"><?php echo htmlspecialchars(ucfirst($userData['role'] ?? 'free')); ?> Plan</span>
                            <button onclick="openEditModal()" class="btn btn-secondary px-4 py-2 text-sm">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Profile
                            </button>
                        </div>
                    </div>
                    
                    <!-- Profile Meta -->
                    <div class="pt-4 border-t border-border">
                        <div class="flex flex-wrap gap-4 text-sm">
                            <div class="flex items-center gap-2 text-text-secondary">
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
                                    <path d="M8 14C11.3137 14 14 11.3137 14 8C14 4.68629 11.3137 2 8 2C4.68629 2 2 4.68629 2 8C2 11.3137 4.68629 14 8 14Z" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M8 4V8L10 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                <span>Member since:</span>
                                <span class="font-medium text-text"><?php echo date('F j, Y', strtotime($userData['created_at'] ?? 'now')); ?></span>
                            </div>
                            <div class="flex items-center gap-2 text-text-secondary">
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
                                    <path d="M14 8C14 8 11.625 10.375 8 10.375C4.375 10.375 2 8 2 8C2 8 4.375 5.625 8 5.625C11.625 5.625 14 8 14 8Z" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M8 10C9.10457 10 10 9.10457 10 8C10 6.89543 9.10457 6 8 6C6.89543 6 6 6.89543 6 8C6 9.10457 6.89543 10 8 10Z" stroke="currentColor" stroke-width="1.5"/>
                                </svg>
                                <span>Last active:</span>
                                <span class="font-medium text-text">2 hours ago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Personal Information -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Personal Information</h2>
                </div>
                <div class="space-y-6 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <div class="form-control bg-bg-input border-border px-4 py-3 rounded-md">
                                <?php echo htmlspecialchars($userData['fullname'] ?? 'Not set'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <div class="form-control bg-bg-input border-border px-4 py-3 rounded-md">
                                @<?php echo htmlspecialchars($userData['username']); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="form-control bg-bg-input border-border px-4 py-3 rounded-md">
                            <?php echo htmlspecialchars($userData['email'] ?? 'Not set'); ?>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Account Type</label>
                            <div class="form-control bg-bg-input border-border px-4 py-3 rounded-md">
                                <?php echo htmlspecialchars(ucfirst($userData['role'] ?? 'free')); ?> Account
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Member Since</label>
                            <div class="form-control bg-bg-input border-border px-4 py-3 rounded-md">
                                <?php echo date('F j, Y', strtotime($userData['created_at'] ?? 'now')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="modal flex_full justify-center modal-overlay">
    <div class="modal-content modal-card" style="overflow: hidden;">
        <div class="modal-header">
            <h3>Edit Profile</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editProfileForm" action="/profile/update" method="POST" class="scroll" style="overflow-y: auto;
    height: 73vh;">
            <div class="modal-body">
                <div class="space-y-4">
                    <div class="form-group">
                        <label class="form-label" for="fullname">Full Name</label>
                        <input type="text" 
                               id="fullname" 
                               name="fullname" 
                               value="<?php echo htmlspecialchars($userData['fullname'] ?? ''); ?>"
                               class="modal-input form-input w-full"
                               placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>"
                               class="modal-input form-input w-full"
                               placeholder="Enter your email address">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               value="<?php echo htmlspecialchars($userData['username'] ?? ''); ?>"
                               class="modal-input form-input w-full"
                               placeholder="Enter username">
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Change Password</h4>
                        <div class="space-y-3">
                            <div class="form-group">
                                <label class="form-label" for="current_password">Current Password</label>
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password"
                                       class="modal-input form-input w-full"
                                       placeholder="Enter current password">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="new_password">New Password</label>
                                <input type="password" 
                                       id="new_password" 
                                       name="new_password"
                                       class="modal-input form-input w-full"
                                       placeholder="Enter new password">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="confirm_password">Confirm New Password</label>
                                <input type="password" 
                                       id="confirm_password" 
                                       name="confirm_password"
                                       class="modal-input form-input w-full"
                                       placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarContainer = document.querySelector('.avatar-container');
    const avatarInput = document.getElementById('avatarInput');
    const avatarForm = document.getElementById('avatarForm');
    
    // Click vào avatar để chọn file
    if (avatarContainer) {
        avatarContainer.addEventListener('click', function() {
            avatarInput.click();
        });
    }
    
    // Khi chọn file avatar
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                // Hiển thị preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatarImage = document.getElementById('avatarImage');
                    const avatarInitial = document.getElementById('avatarInitial');
                    
                    if (avatarImage) {
                        avatarImage.src = e.target.result;
                    } else {
                        // Tạo img nếu chưa có
                        if (avatarInitial) avatarInitial.remove();
                        avatarContainer.querySelector('.profile_avatar').innerHTML = 
                            `<img src="${e.target.result}" alt="Preview" class="w-full h-full rounded-full object-cover" id="avatarImage">`;
                    }
                    
                    // Hiển thị loading text
                    const overlay = avatarContainer.querySelector('.avatar-overlay');
                    if (overlay) {
                        overlay.innerHTML = '<span><i class="fas fa-spinner fa-spin mr-2"></i>Uploading...</span>';
                    }
                };
                reader.readAsDataURL(this.files[0]);
                
                // Submit form
                avatarForm.submit();
            }
        });
    }
    editProfileForm.addEventListener('scroll',(e)=>{
        e.preventDefault();
    })
    // Form validation for edit profile
    const editProfileForm = document.getElementById('editProfileForm');
    if (editProfileForm) {
        editProfileForm.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            // Check if passwords match
            if (newPassword && newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match!');
                return false;
            }
            
            // Check password length
            if (newPassword && newPassword.length < 6) {
                e.preventDefault();
                alert('New password must be at least 6 characters long!');
                return false;
            }
            
            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                submitBtn.disabled = true;
            }
        });
    }
});

// Modal functions
function openEditModal() {
    const modal = document.getElementById('editProfileModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeEditModal() {
    const modal = document.getElementById('editProfileModal');
    if (modal) {
        modal.style.display = 'none';
        // Reset form loading state
        const submitBtn = document.querySelector('#editProfileForm button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = 'Save Changes';
            submitBtn.disabled = false;
        }
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editProfileModal');
    if (event.target === modal) {
        closeEditModal();
    }
}
</script>