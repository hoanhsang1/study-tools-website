// public/auth/js/auth.js

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const btnText = submitBtn.querySelector('.btn-text');
    
    // Password toggle
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    // Validation patterns
    const patterns = {
        username: /^[a-zA-Z0-9_]{3,20}$/,
        email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        password: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{6,}$/
    };
    // Error messages
    const errorMessages = {
        username: 'Tên đăng nhập phải có 3-20 ký tự (chữ, số, gạch dưới)',
        fullname: 'Vui lòng nhập họ tên hợp lệ',
        email: 'Vui lòng nhập email hợp lệ',
        password: 'Mật khẩu phải có ít nhất 6 ký tự, bao gồm chữ và số',
        confirmPassword: 'Mật khẩu xác nhận không khớp',
        terms: 'Bạn cần đồng ý với điều khoản sử dụng'
    };
    
    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Real-time validation
    const inputs = ['username', 'fullname', 'email', 'password', 'confirm_password'];
    
    inputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        const errorElement = document.getElementById(inputId + 'Error');
        
        if (input) {
            input.addEventListener('input', function() {
                validateField(this);
            });
            
            input.addEventListener('blur', function() {
                validateField(this);
            });
        }
    });
    
    // Field validation function
    function validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        const errorElement = document.getElementById(field.name + 'Error');
        
        // Clear previous error
        if (errorElement) {
            errorElement.classList.remove('show');
            errorElement.textContent = '';
        }
        
        let isValid = true;
        let errorMessage = '';
        
        switch (fieldName) {
            case 'username':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Vui lòng nhập tên đăng nhập';
                } else if (!patterns.username.test(value)) {
                    isValid = false;
                    errorMessage = errorMessages.username;
                }
                break;
                
            case 'fullname':
                if (value && value.length < 2) {
                    isValid = false;
                    errorMessage = errorMessages.fullname;
                }
                break;
                
            case 'email':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Vui lòng nhập email';
                } else if (!patterns.email.test(value)) {
                    isValid = false;
                    errorMessage = errorMessages.email;
                }
                break;
            
            case 'password':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Vui lòng nhập mật khẩu';
                } else if (!patterns.password.test(value)) {
                    isValid = false;
                    errorMessage = errorMessages.password;
                }
                break;
                
            case 'confirm_password':
                const password = document.getElementById('password').value;
                if (!value) {
                    isValid = false;
                    errorMessage = 'Vui lòng xác nhận mật khẩu';
                } else if (value !== password) {
                    isValid = false;
                    errorMessage = errorMessages.confirmPassword;
                }
                break;
        }
        
        // Update UI
        if (!isValid && errorElement) {
            errorElement.textContent = errorMessage;
            errorElement.classList.add('show');
            field.style.borderColor = '#ef4444';
            field.style.boxShadow = '0 0 0 4px rgba(239, 68, 68, 0.1)';
        } else if (errorElement) {
            field.style.borderColor = '#e5e7eb';
            field.style.boxShadow = 'none';
        }
        
        return isValid;
    }
    
    // Terms checkbox validation
    const termsCheckbox = document.getElementById('agree_terms');
    const termsError = document.getElementById('termsError');
    
    termsCheckbox.addEventListener('change', function() {
        if (termsError) {
            termsError.classList.remove('show');
        }
    });
    
    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validate all fields
        let isValid = true;
        
        inputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input && !validateField(input)) {
                isValid = false;
            }
        });
        
        // Validate terms
        if (!termsCheckbox.checked) {
            isValid = false;
            if (termsError) {
                termsError.textContent = errorMessages.terms;
                termsError.classList.add('show');
                termsCheckbox.parentElement.classList.add('error-shake');
                setTimeout(() => {
                    termsCheckbox.parentElement.classList.remove('error-shake');
                }, 500);
            }
        }
        
        if (!isValid) {
            // Shake form on error
            form.classList.add('error-shake');
            setTimeout(() => {
                form.classList.remove('error-shake');
            }, 500);
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Đang xử lý...';
        loadingSpinner.classList.remove('hidden');
        
        // Simulate API call (replace with actual fetch)
        try {
            // In a real app, you would use fetch() to send data to server
            // const response = await fetch('process_register.php', {
            //     method: 'POST',
            //     body: new FormData(form)
            // });
            
            // For now, just simulate delay
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            // Show success message
            showSuccessMessage();
            
        } catch (error) {
            console.error('Registration error:', error);
            showErrorMessage('Có lỗi xảy ra. Vui lòng thử lại.');
        } finally {
            // Reset button state
            submitBtn.disabled = false;
            btnText.textContent = 'Đăng ký';
            loadingSpinner.classList.add('hidden');
        }
    });
    
    // Success message
    function showSuccessMessage() {
        // Create success alert
        const successAlert = document.createElement('div');
        successAlert.className = 'alert alert-success';
        successAlert.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>Đăng ký thành công! Kiểm tra email để xác nhận tài khoản.</span>
        `;
        
        // Insert after form
        form.parentNode.insertBefore(successAlert, form.nextSibling);
        
        // Reset form
        form.reset();
        
        // Scroll to alert
        successAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Remove alert after 5 seconds
        setTimeout(() => {
            successAlert.remove();
        }, 5000);
    }
    
    // Error message
    function showErrorMessage(message) {
        const errorAlert = document.createElement('div');
        errorAlert.className = 'alert alert-error';
        errorAlert.innerHTML = `
            <i class="fas fa-exclamation-circle"></i>
            <span>${message}</span>
        `;
        
        // Insert after form
        form.parentNode.insertBefore(errorAlert, form.nextSibling);
        
        // Scroll to alert
        errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Remove after 5 seconds
        setTimeout(() => {
            errorAlert.remove();
        }, 5000);
    }
    
    // Add shake animation to CSS
    const style = document.createElement('style');
    style.textContent = `
        .error-shake {
            animation: shake 0.5s ease;
        }
    `;
    document.head.appendChild(style);
});