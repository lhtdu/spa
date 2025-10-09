<?php
session_start();
require_once 'auth_functions.php';

// Xử lý đăng nhập nếu form được submit
$login_error = '';
if ($_POST) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($email) || empty($password)) {
        $login_error = 'Vui lòng nhập đầy đủ email và mật khẩu';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_error = 'Email không hợp lệ';
    } else {
        // Đăng nhập qua database
        $result = loginUser($email, $password);
        
        if ($result['success']) {
            // Chuyển hướng về trang chủ sau khi đăng nhập thành công
            header('Location: index.php');
            exit();
        } else {
            $login_error = $result['message'];
        }
    }
}

// Include header
include 'header.php';
?>

<!-- Include Navigation -->
<?php include 'nav.php'; ?>

<!-- Login Section -->
<section class="py-5" style="min-height: 80vh; background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="col-md-10 col-lg-8">
                <div class="row g-0 shadow-lg" style="border-radius: 20px; overflow: hidden; background: white;">
                    <!-- Left side - Welcome & Benefits -->
                    <div class="col-md-6 d-none d-md-block position-relative" 
                         style="background: linear-gradient(135deg, #7C5E3B 0%, #5D4428 100%);">
                        <div class="d-flex flex-column justify-content-center h-100 p-5 text-white">
                            <div class="mb-4">
                                <img src="assets/img/waterfall.png" alt="Suối Spa" width="80" class="mb-3"
                                     style="filter: brightness(0) saturate(100%) invert(100%);">
                                <h3 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Chào mừng trở lại!</h3>
                                <p class="opacity-90 mb-4">Đăng nhập để trải nghiệm những dịch vụ spa tuyệt vời và nhận thêm nhiều ưu đãi hấp dẫn.</p>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3">
                                    <i class="fa fa-gift me-2"></i>
                                    Ưu đãi dành cho thành viên
                                </h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="bg-white bg-opacity-20 rounded-circle p-2" style="width: 40px; height: 40px;">
                                                    <i class="fa fa-percent d-flex align-items-center justify-content-center h-100" style="font-size: 0.9rem;"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Giảm giá đặc biệt</div>
                                                <small class="opacity-80">Lên đến 20% cho thành viên VIP</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="bg-white bg-opacity-20 rounded-circle p-2" style="width: 40px; height: 40px;">
                                                    <i class="fa fa-star d-flex align-items-center justify-content-center h-100" style="font-size: 0.9rem;"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Tích điểm thưởng</div>
                                                <small class="opacity-80">Đổi điểm lấy voucher miễn phí</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="bg-white bg-opacity-20 rounded-circle p-2" style="width: 40px; height: 40px;">
                                                    <i class="fa fa-calendar d-flex align-items-center justify-content-center h-100" style="font-size: 0.9rem;"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Ưu tiên đặt lịch</div>
                                                <small class="opacity-80">Đặt lịch nhanh chóng, linh hoạt</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Decorative elements -->
                            <div class="position-absolute" style="top: 20px; right: 20px; opacity: 0.1;">
                                <i class="fa fa-leaf" style="font-size: 3rem;"></i>
                            </div>
                            <div class="position-absolute" style="bottom: 20px; left: 20px; opacity: 0.1;">
                                <i class="fa fa-spa" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right side - Login Form -->
                    <div class="col-md-6">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <div class="d-md-none mb-3">
                                    <img src="assets/img/waterfall.png" alt="Suối Spa" width="60"
                                         style="filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%);">
                                </div>
                                <h2 style="color: #7C5E3B; font-family: 'Playfair Display', serif; font-weight: 700; margin-bottom: 8px;">Đăng nhập</h2>
                                <p class="text-muted mb-0">Vui lòng đăng nhập để tiếp tục</p>
                            </div>
                            
                            <?php if ($login_error): ?>
                                <div class="alert alert-danger" role="alert" style="border-radius: 12px; border: none; background: rgba(220, 53, 69, 0.1); color: #721c24;">
                                    <i class="fa fa-exclamation-circle me-2"></i>
                                    <?php echo htmlspecialchars($login_error); ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="login.php" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="email" class="form-label" style="color: #7C5E3B; font-weight: 600;">
                                        <i class="fa fa-envelope me-1"></i>
                                        Email
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                           required placeholder="example@gmail.com"
                                           style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 12px 16px; transition: all 0.3s ease;"
                                           onfocus="this.style.borderColor='#7C5E3B'; this.style.boxShadow='0 0 0 0.2rem rgba(124, 94, 59, 0.25)'" 
                                           onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
                                    <div class="invalid-feedback">
                                        Vui lòng nhập email hợp lệ.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label" style="color: #7C5E3B; font-weight: 600;">
                                        <i class="fa fa-lock me-1"></i>
                                        Mật khẩu
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" id="password" name="password" required
                                               placeholder="Nhập mật khẩu của bạn"
                                               style="border-radius: 12px; border: 2px solid #e0e0e0; padding: 12px 16px; padding-right: 45px; transition: all 0.3s ease;"
                                               onfocus="this.style.borderColor='#7C5E3B'; this.style.boxShadow='0 0 0 0.2rem rgba(124, 94, 59, 0.25)'" 
                                               onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
                                        <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0" 
                                                onclick="togglePassword()" style="background: none; border: none; color: #999; width: 30px;">
                                            <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                        </button>
                                        <div class="invalid-feedback">
                                            Vui lòng nhập mật khẩu.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label text-muted" for="remember">
                                            Ghi nhớ đăng nhập
                                        </label>
                                    </div>
                                    <a href="#" class="text-decoration-none" style="color: #7C5E3B; font-size: 0.9rem;">
                                        Quên mật khẩu?
                                    </a>
                                </div>
                                
                                <button type="submit" class="btn w-100 py-3 mb-4"
                                        style="background: linear-gradient(135deg, #7C5E3B 0%, #5D4428 100%); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease;"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(124, 94, 59, 0.4)'" 
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(124, 94, 59, 0.3)'">
                                    <i class="fa fa-sign-in me-2"></i>
                                    Đăng nhập
                                </button>
                            </form>
                            
                            <div class="text-center">
                                <div class="position-relative mb-4">
                                    <hr style="border-color: #e0e0e0;">
                                    <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white text-muted" style="font-size: 0.9rem;">
                                        Hoặc
                                    </span>
                                </div>
                                
                                <p class="mb-3" style="color: #666;">
                                    Chưa có tài khoản? 
                                    <a href="register.php" class="text-decoration-none fw-semibold" 
                                       style="color: #7C5E3B; transition: color 0.3s ease;"
                                       onmouseover="this.style.color='#5D4428'" onmouseout="this.style.color='#7C5E3B'">
                                        Đăng ký ngay
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Include Footer -->
<?php include 'footer.php'; ?>

<!-- Bootstrap 5 JavaScript Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- Custom JavaScript -->
<script src="assets/js/main.js"></script>

<!-- Login Page JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.needs-validation');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    // Real-time email validation
    emailInput.addEventListener('input', function() {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(this.value) && this.value.length > 0) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (this.value.length > 0) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
        }
    });
    
    // Real-time password validation
    passwordInput.addEventListener('input', function() {
        if (this.value.length < 6 && this.value.length > 0) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (this.value.length >= 6) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
        }
    });
    
    // Form submit validation
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
    
    // Auto-focus first empty field
    if (emailInput.value === '') {
        emailInput.focus();
    } else {
        passwordInput.focus();
    }
    
    // Loading state on submit
    form.addEventListener('submit', function() {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Đang đăng nhập...';
        
        // Reset button after 3 seconds if form doesn't submit
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }, 3000);
    });
});

// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Animate benefits on scroll (for mobile)
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
        }
    });
});

document.querySelectorAll('.d-flex.align-items-center').forEach(el => {
    observer.observe(el);
});

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .form-control.is-valid {
        border-color: #28a745;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.79-.79L4.25 7.2l2.26-2.26.79.79L4.25 8.67z'/%3e%3c/svg%3e");
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='M5.8 5.8l.4.4.4-.4M5.8 6.2l.4-.4.4.4'/%3e%3c/svg%3e");
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .form-check-input:checked {
        background-color: #7C5E3B;
        border-color: #7C5E3B;
    }
    
    @media (max-width: 768px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
    }
`;
document.head.appendChild(style);
</script>

</body>
</html>