<?php
// Include auth check và yêu cầu đăng nhập
include 'auth_check.php';
requireLogin(); // Redirect về login.php nếu chưa đăng nhập

// Lấy thông tin user
$user = getCurrentUser();
if (!$user) {
    header('Location: login.php');
    exit();
}

// Lấy lịch sử điểm thưởng
$pointHistory = getPointHistory($user['id'], 5);

// Lấy lịch hẹn gần đây
$recentAppointments = getUserAppointments($user['id'], 3);

// Lấy đơn hàng gần đây  
$recentOrders = getUserOrders($user['id'], 3);

// Xử lý cập nhật profile
$update_message = '';
$update_success = false;

if ($_POST && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_profile') {
        $result = updateUserProfile($user['id'], [
            'fullname' => trim($_POST['fullname'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'gender' => $_POST['gender'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'address' => trim($_POST['address'] ?? '')
        ]);
        
        $update_message = $result['message'];
        $update_success = $result['success'];
        
        if ($result['success']) {
            // Refresh user data
            $user = getCurrentUser();
        }
    } elseif ($_POST['action'] === 'change_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $update_message = 'Vui lòng điền đầy đủ thông tin';
            $update_success = false;
        } elseif (strlen($newPassword) < 6) {
            $update_message = 'Mật khẩu mới phải có ít nhất 6 ký tự';
            $update_success = false;
        } elseif ($newPassword !== $confirmPassword) {
            $update_message = 'Xác nhận mật khẩu mới không khớp';
            $update_success = false;
        } else {
            $result = changePassword($user['id'], $currentPassword, $newPassword);
            $update_message = $result['message'];
            $update_success = $result['success'];
        }
    }
}

// Include header
include 'header.php';
?>

<!-- Include Navigation -->
<?php include 'nav.php'; ?>

<!-- Profile Section -->
<section class="py-5" style="min-height: 80vh; background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);">
    <div class="container">
        <?php if ($update_message): ?>
            <div class="row mb-4">
                <div class="col-md-10 mx-auto">
                    <div class="alert <?php echo $update_success ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                        <i class="fa <?php echo $update_success ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> me-2"></i>
                        <?php echo htmlspecialchars($update_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Profile Overview -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100" style="border: none; border-radius: 15px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <img src="<?php echo $user['avatar'] ? htmlspecialchars($user['avatar']) : 'https://via.placeholder.com/120/7C5E3B/ffffff?text=' . strtoupper(substr($user['fullname'], 0, 1)); ?>" 
                                 alt="Avatar" class="rounded-circle shadow" width="120" height="120"
                                 style="border: 4px solid #7C5E3B;">
                        </div>
                        
                        <h5 style="color: #7C5E3B; font-weight: 600;"><?php echo htmlspecialchars($user['fullname']); ?></h5>
                        <p class="text-muted mb-2"><?php echo htmlspecialchars($user['email']); ?></p>
                        
                        <?php if ($user['tier_name']): ?>
                            <span class="badge px-3 py-2" style="background-color: <?php echo $user['tier_color'] ?? '#7C5E3B'; ?>; color: white; border-radius: 20px;">
                                <i class="fa fa-crown me-1"></i><?php echo htmlspecialchars($user['tier_name']); ?>
                            </span>
                        <?php endif; ?>
                        
                        <hr class="my-3">
                        
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="h5 mb-1" style="color: #7C5E3B;"><?php echo number_format($user['available_points'] ?? 0); ?></div>
                                <small class="text-muted">Điểm có sẵn</small>
                            </div>
                            <div class="col-4">
                                <div class="h5 mb-1" style="color: #7C5E3B;"><?php echo number_format($user['total_appointments'] ?? 0); ?></div>
                                <small class="text-muted">Lịch hẹn</small>
                            </div>
                            <div class="col-4">
                                <div class="h5 mb-1" style="color: #7C5E3B;"><?php echo number_format($user['total_spent'] ?? 0); ?>đ</div>
                                <small class="text-muted">Đã chi tiêu</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Profile Details & Actions -->
            <div class="col-md-8">
                <!-- Navigation Tabs -->
                <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="pill" data-bs-target="#info" type="button" style="color: #7C5E3B;">
                            <i class="fa fa-info-circle me-1"></i>Thông tin cá nhân
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="appointments-tab" data-bs-toggle="pill" data-bs-target="#appointments" type="button" style="color: #7C5E3B;">
                            <i class="fa fa-calendar me-1"></i>Lịch hẹn
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="points-tab" data-bs-toggle="pill" data-bs-target="#points" type="button" style="color: #7C5E3B;">
                            <i class="fa fa-star me-1"></i>Điểm thưởng
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="settings-tab" data-bs-toggle="pill" data-bs-target="#settings" type="button" style="color: #7C5E3B;">
                            <i class="fa fa-cog me-1"></i>Cài đặt
                        </button>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <div class="card shadow-sm" style="border: none; border-radius: 15px;">
                            <div class="card-body p-4">
                                <h6 class="mb-4" style="color: #7C5E3B; font-weight: 600;">
                                    <i class="fa fa-user me-2"></i>Thông tin tài khoản
                                </h6>
                                
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="update_profile">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: #7C5E3B; font-weight: 600;">Họ và tên</label>
                                            <input type="text" class="form-control" name="fullname" 
                                                   value="<?php echo htmlspecialchars($user['fullname']); ?>" required
                                                   style="border-radius: 10px; border: 2px solid #e0e0e0;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: #7C5E3B; font-weight: 600;">Email</label>
                                            <input type="email" class="form-control" name="email" 
                                                   value="<?php echo htmlspecialchars($user['email']); ?>" required
                                                   style="border-radius: 10px; border: 2px solid #e0e0e0;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: #7C5E3B; font-weight: 600;">Số điện thoại</label>
                                            <input type="tel" class="form-control" name="phone" 
                                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                                   style="border-radius: 10px; border: 2px solid #e0e0e0;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: #7C5E3B; font-weight: 600;">Ngày sinh</label>
                                            <input type="date" class="form-control" name="date_of_birth" 
                                                   value="<?php echo htmlspecialchars($user['date_of_birth'] ?? ''); ?>"
                                                   style="border-radius: 10px; border: 2px solid #e0e0e0;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: #7C5E3B; font-weight: 600;">Giới tính</label>
                                            <select class="form-select" name="gender" style="border-radius: 10px; border: 2px solid #e0e0e0;">
                                                <option value="male" <?php echo ($user['gender'] === 'male') ? 'selected' : ''; ?>>Nam</option>
                                                <option value="female" <?php echo ($user['gender'] === 'female') ? 'selected' : ''; ?>>Nữ</option>
                                                <option value="other" <?php echo ($user['gender'] === 'other') ? 'selected' : ''; ?>>Khác</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label" style="color: #7C5E3B; font-weight: 600;">Địa chỉ</label>
                                            <textarea class="form-control" name="address" rows="2"
                                                      style="border-radius: 10px; border: 2px solid #e0e0e0;"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn px-4 py-2" 
                                            style="background: linear-gradient(135deg, #7C5E3B 0%, #5D4428 100%); color: white; border: none; border-radius: 10px; font-weight: 600;">
                                        <i class="fa fa-save me-2"></i>Cập nhật thông tin
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Appointments Tab -->
                    <div class="tab-pane fade" id="appointments" role="tabpanel">
                        <div class="card shadow-sm" style="border: none; border-radius: 15px;">
                            <div class="card-body p-4">
                                <h6 class="mb-4" style="color: #7C5E3B; font-weight: 600;">
                                    <i class="fa fa-calendar me-2"></i>Lịch hẹn gần đây
                                </h6>
                                
                                <?php if (empty($recentAppointments)): ?>
                                    <div class="text-center py-4">
                                        <i class="fa fa-calendar-o text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3">Bạn chưa có lịch hẹn nào</p>
                                        <a href="index.php#booking" class="btn btn-primary">Đặt lịch ngay</a>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($recentAppointments as $appointment): ?>
                                        <div class="border-bottom py-3">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($appointment['branch_name']); ?></h6>
                                                    <p class="text-muted mb-1">
                                                        <i class="fa fa-calendar me-1"></i>
                                                        <?php echo date('d/m/Y', strtotime($appointment['appointment_date'])); ?> - 
                                                        <?php echo date('H:i', strtotime($appointment['appointment_time'])); ?>
                                                    </p>
                                                    <?php if ($appointment['staff_name']): ?>
                                                        <p class="text-muted mb-0">
                                                            <i class="fa fa-user me-1"></i>
                                                            <?php echo htmlspecialchars($appointment['staff_name']); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <span class="badge bg-<?php 
                                                        switch($appointment['status']) {
                                                            case 'completed': echo 'success'; break;
                                                            case 'confirmed': echo 'primary'; break;
                                                            case 'pending': echo 'warning'; break;
                                                            case 'cancelled': echo 'danger'; break;
                                                            default: echo 'secondary';
                                                        }
                                                    ?>"><?php echo ucfirst($appointment['status']); ?></span>
                                                    <div class="mt-1">
                                                        <strong><?php echo number_format($appointment['final_amount']); ?>đ</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Points Tab -->
                    <div class="tab-pane fade" id="points" role="tabpanel">
                        <div class="card shadow-sm" style="border: none; border-radius: 15px;">
                            <div class="card-body p-4">
                                <h6 class="mb-4" style="color: #7C5E3B; font-weight: 600;">
                                    <i class="fa fa-star me-2"></i>Lịch sử điểm thưởng
                                </h6>
                                
                                <div class="row mb-4">
                                    <div class="col-md-4 text-center">
                                        <div class="p-3 rounded" style="background: linear-gradient(135deg, #7C5E3B 0%, #5D4428 100%); color: white;">
                                            <div class="h4 mb-1"><?php echo number_format($user['available_points'] ?? 0); ?></div>
                                            <small>Điểm có sẵn</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="p-3 rounded bg-light">
                                            <div class="h4 mb-1"><?php echo number_format($user['lifetime_points'] ?? 0); ?></div>
                                            <small>Tổng điểm đã kiếm</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="p-3 rounded bg-light">
                                            <div class="h4 mb-1"><?php echo number_format($user['total_spent'] ?? 0); ?>đ</div>
                                            <small>Tổng chi tiêu</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if (empty($pointHistory)): ?>
                                    <div class="text-center py-4">
                                        <i class="fa fa-star-o text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-3">Chưa có giao dịch điểm thưởng</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($pointHistory as $point): ?>
                                        <div class="border-bottom py-3">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($point['description']); ?></h6>
                                                    <small class="text-muted">
                                                        <?php echo date('d/m/Y H:i', strtotime($point['created_at'])); ?>
                                                    </small>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <span class="h6 <?php echo ($point['points'] > 0) ? 'text-success' : 'text-danger'; ?>">
                                                        <?php echo ($point['points'] > 0) ? '+' : ''; ?><?php echo number_format($point['points']); ?> điểm
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Settings Tab -->
                    <div class="tab-pane fade" id="settings" role="tabpanel">
                        <div class="card shadow-sm" style="border: none; border-radius: 15px;">
                            <div class="card-body p-4">
                                <h6 class="mb-4" style="color: #7C5E3B; font-weight: 600;">
                                    <i class="fa fa-lock me-2"></i>Đổi mật khẩu
                                </h6>
                                
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="change_password">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label" style="color: #7C5E3B; font-weight: 600;">Mật khẩu hiện tại</label>
                                            <input type="password" class="form-control" name="current_password" required
                                                   style="border-radius: 10px; border: 2px solid #e0e0e0;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: #7C5E3B; font-weight: 600;">Mật khẩu mới</label>
                                            <input type="password" class="form-control" name="new_password" required
                                                   style="border-radius: 10px; border: 2px solid #e0e0e0;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: #7C5E3B; font-weight: 600;">Xác nhận mật khẩu mới</label>
                                            <input type="password" class="form-control" name="confirm_password" required
                                                   style="border-radius: 10px; border: 2px solid #e0e0e0;">
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn px-4 py-2" 
                                            style="background: linear-gradient(135deg, #7C5E3B 0%, #5D4428 100%); color: white; border: none; border-radius: 10px; font-weight: 600;">
                                        <i class="fa fa-key me-2"></i>Đổi mật khẩu
                                    </button>
                                </form>
                                
                                <hr class="my-4">
                                
                                <div class="d-grid">
                                    <a href="logout.php" class="btn btn-outline-danger btn-lg" 
                                       style="border-radius: 10px; font-weight: 600;">
                                        <i class="fa fa-sign-out me-2"></i>Đăng xuất
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

<!-- Profile JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab styling
    const navPills = document.querySelectorAll('#profileTabs .nav-link');
    navPills.forEach(pill => {
        pill.addEventListener('shown.bs.tab', function() {
            // Reset all pills
            navPills.forEach(p => {
                p.style.background = 'transparent';
                p.style.color = '#7C5E3B';
            });
            // Activate current pill
            this.style.background = 'linear-gradient(135deg, #7C5E3B 0%, #5D4428 100%)';
            this.style.color = 'white';
        });
    });
    
    // Activate first pill
    if (navPills.length > 0) {
        navPills[0].style.background = 'linear-gradient(135deg, #7C5E3B 0%, #5D4428 100%)';
        navPills[0].style.color = 'white';
    }
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const passwords = form.querySelectorAll('input[type="password"]');
            if (passwords.length >= 2) {
                const newPass = form.querySelector('input[name="new_password"]');
                const confirmPass = form.querySelector('input[name="confirm_password"]');
                
                if (newPass && confirmPass && newPass.value !== confirmPass.value) {
                    e.preventDefault();
                    alert('Xác nhận mật khẩu không khớp');
                    return false;
                }
            }
        });
    });
    
    // Input focus effects
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#7C5E3B';
            this.style.boxShadow = '0 0 0 0.2rem rgba(124, 94, 59, 0.25)';
        });
        
        input.addEventListener('blur', function() {
            this.style.borderColor = '#e0e0e0';
            this.style.boxShadow = 'none';
        });
    });
});
</script>

</body>
</html>