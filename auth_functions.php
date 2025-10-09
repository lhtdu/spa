<?php
// File: auth_functions.php
// Các function xử lý authentication và user management

require_once 'config.php';

// Function đăng ký user mới
function registerUser($data) {
    $pdo = getDBConnection();
    if (!$pdo) {
        return ['success' => false, 'message' => 'Lỗi kết nối database'];
    }
    
    try {
        // Bắt đầu transaction
        $pdo->beginTransaction();
        
        // Kiểm tra email đã tồn tại
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            $pdo->rollback();
            return ['success' => false, 'message' => 'Email này đã được sử dụng'];
        }
        
        // Kiểm tra phone đã tồn tại  
        if (!empty($data['phone'])) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE phone = ?");
            $stmt->execute([$data['phone']]);
            if ($stmt->fetch()) {
                $pdo->rollback();
                return ['success' => false, 'message' => 'Số điện thoại này đã được sử dụng'];
            }
        }
        
        // Insert user mới
        $stmt = $pdo->prepare("
            INSERT INTO users (email, password, fullname, phone, gender, role_id, status, created_at) 
            VALUES (?, ?, ?, ?, ?, 3, 'active', NOW())
        ");
        
        $hashedPassword = hashPassword($data['password']);
        $stmt->execute([
            $data['email'],
            $hashedPassword,
            $data['fullname'],
            $data['phone'],
            $data['gender'] ?? 'other'
        ]);
        
        $userId = $pdo->lastInsertId();
        
        // Kiểm tra xem có bảng customer_points và customer_tiers không
        $stmt = $pdo->prepare("SHOW TABLES LIKE 'customer_points'");
        $stmt->execute();
        $hasCustomerPoints = $stmt->fetch();
        
        $stmt = $pdo->prepare("SHOW TABLES LIKE 'customer_tiers'");
        $stmt->execute();
        $hasCustomerTiers = $stmt->fetch();
        
        if ($hasCustomerPoints && $hasCustomerTiers) {
            // Lấy ID của tier đầu tiên (thành viên mới)
            $stmt = $pdo->prepare("SELECT id FROM customer_tiers ORDER BY sort_order ASC, id ASC LIMIT 1");
            $stmt->execute();
            $firstTier = $stmt->fetch();
            $tierId = $firstTier ? $firstTier['id'] : 1;
            
            // Tạo record điểm thưởng cho user mới
            $stmt = $pdo->prepare("
                INSERT INTO customer_points (customer_id, tier_id, total_points, available_points, total_spent, lifetime_points) 
                VALUES (?, ?, 100, 100, 0, 100)
            ");
            $stmt->execute([$userId, $tierId]);
            
            // Kiểm tra bảng point_transactions có tồn tại không
            $stmt = $pdo->prepare("SHOW TABLES LIKE 'point_transactions'");
            $stmt->execute();
            $hasPointTransactions = $stmt->fetch();
            
            if ($hasPointTransactions) {
                // Tạo điểm thưởng chào mừng (100 điểm)
                $stmt = $pdo->prepare("
                    INSERT INTO point_transactions (customer_id, type, points, description, created_at) 
                    VALUES (?, 'earn', 100, 'Chào mừng thành viên mới', NOW())
                ");
                $stmt->execute([$userId]);
            }
        }
        
        // Commit transaction
        $pdo->commit();
        
        // Lấy thông tin user vừa tạo để trả về cho auto login
        $stmt = $pdo->prepare("
            SELECT u.*, r.name as role_name, cp.total_points, cp.available_points, ct.name as tier_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            LEFT JOIN customer_points cp ON u.id = cp.customer_id
            LEFT JOIN customer_tiers ct ON cp.tier_id = ct.id
            WHERE u.id = ?
        ");
        $stmt->execute([$userId]);
        $userInfo = $stmt->fetch();
        
        return [
            'success' => true, 
            'message' => 'Đăng ký thành công! Chào mừng bạn đến với Suối Spa.', 
            'user_id' => $userId,
            'user_data' => $userInfo
        ];
        
    } catch (PDOException $e) {
        // Rollback transaction nếu có lỗi
        if ($pdo->inTransaction()) {
            $pdo->rollback();
        }
        
        // Log chi tiết lỗi để debug
        error_log("Registration error: " . $e->getMessage());
        error_log("Error Code: " . $e->getCode());
        error_log("SQL State: " . ($e->errorInfo[0] ?? 'Unknown'));
        
        // Kiểm tra lỗi cụ thể
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            return ['success' => false, 'message' => 'Email hoặc số điện thoại đã được sử dụng'];
        } elseif (strpos($e->getMessage(), "Table") !== false && strpos($e->getMessage(), "doesn't exist") !== false) {
            return ['success' => false, 'message' => 'Hệ thống chưa được thiết lập đầy đủ. Vui lòng liên hệ quản trị viên.'];
        } else {
            return ['success' => false, 'message' => 'Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại sau.'];
        }
    }
}

// Function đăng nhập
function loginUser($email, $password) {
    $pdo = getDBConnection();
    if (!$pdo) {
        return ['success' => false, 'message' => 'Lỗi kết nối database'];
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT u.*, r.name as role_name, cp.total_points, cp.available_points, ct.name as tier_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            LEFT JOIN customer_points cp ON u.id = cp.customer_id
            LEFT JOIN customer_tiers ct ON cp.tier_id = ct.id
            WHERE u.email = ? AND u.status = 'active'
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'message' => 'Email không tồn tại hoặc tài khoản đã bị khóa'];
        }
        
        if (!verifyPassword($password, $user['password'])) {
            return ['success' => false, 'message' => 'Mật khẩu không đúng'];
        }
        
        // Cập nhật last login
        $stmt = $pdo->prepare("
            UPDATE users 
            SET last_login_at = NOW(), last_login_ip = ? 
            WHERE id = ?
        ");
        $stmt->execute([$_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', $user['id']]);
        
        // Tạo session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role_name'];
        $_SESSION['logged_in'] = true;
        
        return ['success' => true, 'user' => $user];
        
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'];
    }
}

// Function lấy thông tin user hiện tại
function getCurrentUser() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    $pdo = getDBConnection();
    if (!$pdo) {
        return null;
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT u.*, r.name as role_name, cp.total_points, cp.available_points, 
                   cp.total_spent, cp.lifetime_points, ct.name as tier_name, ct.color as tier_color,
                   (SELECT COUNT(*) FROM appointments WHERE customer_id = u.id) as total_appointments,
                   (SELECT COUNT(*) FROM orders WHERE customer_id = u.id) as total_orders
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            LEFT JOIN customer_points cp ON u.id = cp.customer_id
            LEFT JOIN customer_tiers ct ON cp.tier_id = ct.id
            WHERE u.id = ? AND u.status = 'active'
        ");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
        
    } catch (PDOException $e) {
        error_log("Get current user error: " . $e->getMessage());
        return null;
    }
}

// Function kiểm tra đăng nhập
function requireLogin() {
    if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
        header('Location: login.php');
        exit();
    }
}

// Function đăng xuất
function logoutUser() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

// Function cập nhật thông tin user
function updateUserProfile($userId, $data) {
    $pdo = getDBConnection();
    if (!$pdo) {
        return ['success' => false, 'message' => 'Lỗi kết nối database'];
    }
    
    try {
        // Kiểm tra email/phone đã tồn tại (nếu thay đổi)
        $currentUser = getCurrentUser();
        
        if ($data['email'] !== $currentUser['email']) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$data['email'], $userId]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Email này đã được sử dụng'];
            }
        }
        
        if (!empty($data['phone']) && $data['phone'] !== $currentUser['phone']) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE phone = ? AND id != ?");
            $stmt->execute([$data['phone'], $userId]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Số điện thoại này đã được sử dụng'];
            }
        }
        
        // Update user
        $stmt = $pdo->prepare("
            UPDATE users 
            SET fullname = ?, email = ?, phone = ?, gender = ?, date_of_birth = ?, address = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        $stmt->execute([
            $data['fullname'],
            $data['email'],
            $data['phone'],
            $data['gender'],
            $data['date_of_birth'] ?? null,
            $data['address'] ?? null,
            $userId
        ]);
        
        // Cập nhật session
        $_SESSION['email'] = $data['email'];
        $_SESSION['fullname'] = $data['fullname'];
        
        return ['success' => true, 'message' => 'Cập nhật thông tin thành công'];
        
    } catch (PDOException $e) {
        error_log("Update profile error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'];
    }
}

// Function đổi mật khẩu
function changePassword($userId, $currentPassword, $newPassword) {
    $pdo = getDBConnection();
    if (!$pdo) {
        return ['success' => false, 'message' => 'Lỗi kết nối database'];
    }
    
    try {
        // Kiểm tra mật khẩu hiện tại
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user || !verifyPassword($currentPassword, $user['password'])) {
            return ['success' => false, 'message' => 'Mật khẩu hiện tại không đúng'];
        }
        
        // Cập nhật mật khẩu mới
        $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([hashPassword($newPassword), $userId]);
        
        return ['success' => true, 'message' => 'Đổi mật khẩu thành công'];
        
    } catch (PDOException $e) {
        error_log("Change password error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại'];
    }
}

// Function lấy lịch sử điểm thưởng
function getPointHistory($userId, $limit = 10) {
    $pdo = getDBConnection();
    if (!$pdo) {
        return [];
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM point_transactions 
            WHERE customer_id = ? 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Get point history error: " . $e->getMessage());
        return [];
    }
}

// Function lấy lịch hẹn của user
function getUserAppointments($userId, $limit = 5) {
    $pdo = getDBConnection();
    if (!$pdo) {
        return [];
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT a.*, b.name as branch_name, s.fullname as staff_name
            FROM appointments a
            LEFT JOIN branches b ON a.branch_id = b.id
            LEFT JOIN staff st ON a.staff_id = st.id
            LEFT JOIN users s ON st.user_id = s.id
            WHERE a.customer_id = ? 
            ORDER BY a.appointment_date DESC, a.appointment_time DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Get user appointments error: " . $e->getMessage());
        return [];
    }
}

// Function lấy đơn hàng của user
function getUserOrders($userId, $limit = 5) {
    $pdo = getDBConnection();
    if (!$pdo) {
        return [];
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM orders 
            WHERE customer_id = ? 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Get user orders error: " . $e->getMessage());
        return [];
    }
}
?>