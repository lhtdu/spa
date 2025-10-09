<?php
session_start();
require_once 'auth_functions.php';

// Initialize variables
$register_error = '';
$register_success = '';

// Check if already registered successfully  
if (isset($_SESSION['registration_success']) && $_SESSION['registration_success'] === true) {
    $register_success = $_SESSION['registration_message'] ?? 'Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.';
    unset($_SESSION['registration_success']);
    unset($_SESSION['registration_message']);
}

// Process form submission
if ($_POST && !isset($_SESSION['registration_success'])) {
    // Get form data
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $agree_terms = isset($_POST['agree_terms']);
    
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $register_error = 'Phiên làm việc không hợp lệ. Vui lòng làm mới trang và thử lại.';
        // Generate new token
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } else {
        // Validate input
        if (empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
            $register_error = 'Vui lòng điền đầy đủ tất cả thông tin bắt buộc';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $register_error = 'Email không hợp lệ';
        } elseif (strlen($password) < 6) {
            $register_error = 'Mật khẩu phải có ít nhất 6 ký tự';
        } elseif ($password !== $confirm_password) {
            $register_error = 'Xác nhận mật khẩu không khớp';
        } elseif (!preg_match('/^[0-9]{10,11}$/', $phone)) {
            $register_error = 'Số điện thoại không hợp lệ (10-11 số)';
        } elseif (!$agree_terms) {
            $register_error = 'Vui lòng đồng ý với điều khoản sử dụng';
        } else {
            // Test database connection
            $testConnection = getDBConnection();
            if (!$testConnection) {
                $register_error = 'Không thể kết nối database. Vui lòng kiểm tra cấu hình hoặc liên hệ quản trị viên.';
            } else {
                // Register user
                $result = registerUser([
                    'fullname' => $fullname,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => $password,
                    'gender' => $gender
                ]);
                
                if ($result['success']) {
                    // Auto login
                    if (isset($result['user_data']) && $result['user_data']) {
                        $user = $result['user_data'];
                        
                        // Create session
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['fullname'] = $user['fullname'];
                        $_SESSION['role'] = $user['role_name'];
                        $_SESSION['logged_in'] = true;
                        
                        // Update last login
                        if ($testConnection) {
                            $updateStmt = $testConnection->prepare("UPDATE users SET last_login_at = NOW(), last_login_ip = ? WHERE id = ?");
                            $updateStmt->execute([$_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', $user['id']]);
                        }
                    }
                    
                    // Set welcome message
                    $_SESSION['welcome_message'] = 'Đăng ký thành công! Chào mừng bạn đến với Suối Spa. Bạn nhận được 100 điểm thưởng chào mừng!';
                    
                    // Redirect to homepage
                    header('Location: index.php');
                    exit();
                } else {
                    $register_error = $result['message'];
                }
            }
        }
    }
}

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Include header
include 'header.php';
?>

<!-- Include Navigation -->
<?php include 'nav.php'; ?>

<!-- Register Section -->
<section class="py-5" style="min-height: 80vh; background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg" style="border: none; border-radius: 15px; overflow: hidden;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="assets/img/waterfall.png" alt="Suối Spa" width="60" class="mb-3"
                                 style="filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%);">
                            <h2 class="card-title" style="color: #7C5E3B; font-family: 'Playfair Display', serif; font-weight: 700;">Đăng ký tài khoản</h2>
                            <p class="text-muted">Tham gia cộng đồng Suối Spa để nhận ưu đãi đặc biệt</p>
                        </div>
                        
                        <?php if ($register_error): ?>
                            <div class="alert alert-danger" role="alert" style="border-radius: 10px; border: none;">
                                <i class="fa fa-exclamation-circle me-2"></i>
                                <?php echo htmlspecialchars($register_error); ?>
                                
                                <?php if (strpos($register_error, 'Phiên làm việc') !== false): ?>
                                    <hr>
                                    <small>
                                        <strong>Gợi ý khắc phục:</strong><br>
                                        • Làm mới trang và thử lại<br>
                                        • Đảm bảo JavaScript đã được bật<br>
                                        • Xóa cookies và thử lại<br>
                                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="location.reload()">
                                            <i class="fa fa-refresh me-1"></i>Làm mới trang
                                        </button>
                                    </small>
                                <?php elseif (strpos($register_error, 'database') !== false || strpos($register_error, 'kết nối') !== false): ?>
                                    <hr>
                                    <small>
                                        <strong>Gợi ý khắc phục:</strong><br>
                                        • Kiểm tra XAMPP đã bật MySQL chưa<br>
                                        • Đảm bảo database 'spa_management' đã được tạo<br>
                                        • <a href="test_db.php" target="_blank" style="color: #dc3545;">Kiểm tra database →</a>
                                    </small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($register_success): ?>
                            <div class="alert alert-success" role="alert" style="border-radius: 10px; border: none;">
                                <i class="fa fa-check-circle me-2"></i>
                                <?php echo htmlspecialchars($register_success); ?>
                                <div class="mt-3">
                                    <a href="login.php" class="btn btn-success btn-sm">
                                        <i class="fa fa-sign-in me-1"></i>
                                        Đăng nhập ngay
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="register.php">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fullname" class="form-label" style="color: #7C5E3B; font-weight: 600;">
                                        <i class="fa fa-user me-1"></i>
                                        Họ và tên *
                                    </label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" 
                                           value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>" 
                                           required style="border-radius: 10px; border: 2px solid #e0e0e0; padding: 12px 15px;"
                                           onfocus="this.style.borderColor='#7C5E3B'" 
                                           onblur="this.style.borderColor='#e0e0e0'">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label" style="color: #7C5E3B; font-weight: 600;">
                                        <i class="fa fa-phone me-1"></i>
                                        Số điện thoại *
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" 
                                           required placeholder="0987654321"
                                           style="border-radius: 10px; border: 2px solid #e0e0e0; padding: 12px 15px;"
                                           onfocus="this.style.borderColor='#7C5E3B'" 
                                           onblur="this.style.borderColor='#e0e0e0'">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label" style="color: #7C5E3B; font-weight: 600;">
                                    <i class="fa fa-envelope me-1"></i>
                                    Email *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                       required placeholder="example@gmail.com"
                                       style="border-radius: 10px; border: 2px solid #e0e0e0; padding: 12px 15px;"
                                       onfocus="this.style.borderColor='#7C5E3B'" 
                                       onblur="this.style.borderColor='#e0e0e0'">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label" style="color: #7C5E3B; font-weight: 600;">
                                        <i class="fa fa-lock me-1"></i>
                                        Mật khẩu *
                                    </label>
                                    <input type="password" class="form-control" id="password" name="password" required
                                           style="border-radius: 10px; border: 2px solid #e0e0e0; padding: 12px 15px;"
                                           onfocus="this.style.borderColor='#7C5E3B'" 
                                           onblur="this.style.borderColor='#e0e0e0'">
                                    <small class="text-muted">Tối thiểu 6 ký tự</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label" style="color: #7C5E3B; font-weight: 600;">
                                        <i class="fa fa-lock me-1"></i>
                                        Xác nhận mật khẩu *
                                    </label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                                           style="border-radius: 10px; border: 2px solid #e0e0e0; padding: 12px 15px;"
                                           onfocus="this.style.borderColor='#7C5E3B'" 
                                           onblur="this.style.borderColor='#e0e0e0'">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" style="color: #7C5E3B; font-weight: 600;">
                                    <i class="fa fa-venus-mars me-1"></i>
                                    Giới tính
                                </label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="male"
                                               <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'male') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="male">Nam</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="female"
                                               <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'female') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="female">Nữ</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="other" value="other"
                                               <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'other') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="other">Khác</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms" required>
                                <label class="form-check-label" for="agree_terms">
                                    Tôi đồng ý với 
                                    <a href="#" style="color: #7C5E3B; text-decoration: none;">Điều khoản sử dụng</a> 
                                    và 
                                    <a href="#" style="color: #7C5E3B; text-decoration: none;">Chính sách bảo mật</a> *
                                </label>
                            </div>
                            
                            <button type="submit" 
                                    style="width: 100%; padding: 15px; background: linear-gradient(135deg, #7C5E3B 0%, #5D4428 100%); color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(124, 94, 59, 0.3)'" 
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(124, 94, 59, 0.2)'">
                                <i class="fa fa-user-plus me-2"></i>
                                Đăng ký tài khoản
                            </button>
                        </form>
                        
                        <hr class="my-4" style="border: none; height: 1px; background: linear-gradient(90deg, transparent, #C8A882, transparent);">
                        
                        <div class="text-center">
                            <p class="mb-0" style="color: #666;">
                                Đã có tài khoản? 
                                <a href="login.php" style="color: #7C5E3B; text-decoration: none; font-weight: 600; transition: color 0.3s ease;"
                                   onmouseover="this.style.color='#5D4428'" onmouseout="this.style.color='#7C5E3B'">
                                    Đăng nhập ngay
                                </a>
                            </p>
                        </div>
                        
                        <!-- Benefits section -->
                        <div class="mt-4 p-4 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <h6 style="color: #7C5E3B; font-weight: 600; margin-bottom: 15px;">
                                <i class="fa fa-gift me-2"></i>
                                Ưu đãi thành viên
                            </h6>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <small style="color: #666;">
                                        <i class="fa fa-check text-success me-1"></i>
                                        Giảm 10% lần đầu
                                    </small>
                                </div>
                                <div class="col-6 mb-2">
                                    <small style="color: #666;">
                                        <i class="fa fa-check text-success me-1"></i>
                                        Tích điểm thưởng
                                    </small>
                                </div>
                                <div class="col-6 mb-2">
                                    <small style="color: #666;">
                                        <i class="fa fa-check text-success me-1"></i>
                                        Ưu tiên đặt lịch
                                    </small>
                                </div>
                                <div class="col-6 mb-2">
                                    <small style="color: #666;">
                                        <i class="fa fa-check text-success me-1"></i>
                                        Nhận khuyến mại
                                    </small>
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

<!-- Form validation JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    // Real-time password match validation
    confirmPassword.addEventListener('input', function() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Mật khẩu không khớp');
            confirmPassword.style.borderColor = '#dc3545';
        } else {
            confirmPassword.setCustomValidity('');
            confirmPassword.style.borderColor = '#28a745';
        }
    });
    
    // Phone number validation
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function() {
        const phonePattern = /^[0-9]{10,11}$/;
        if (!phonePattern.test(this.value) && this.value.length > 0) {
            this.setCustomValidity('Số điện thoại phải có 10-11 số');
            this.style.borderColor = '#dc3545';
        } else {
            this.setCustomValidity('');
            this.style.borderColor = '#28a745';
        }
    });
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Đang xử lý...';
        
        // Re-enable after timeout (in case of issues)
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }, 10000);
    });
});
</script>

</body>
</html>