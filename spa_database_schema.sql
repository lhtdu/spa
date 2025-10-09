-- =============================================
-- SCHEMA DATABASE CHO WEBSITE SPA CHUYÊN NGHIỆP
-- Tạo ngày: 09/10/2025
-- Mô tả: Schema đầy đủ cho hệ thống quản lý spa
-- =============================================

-- Tạo database
CREATE DATABASE spa_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE spa_management;

-- =============================================
-- 1. BẢNG QUẢN LÝ NGƯỜI DÙNG & PHÂN QUYỀN
-- =============================================

-- Bảng vai trò/quyền hạn
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    permissions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng người dùng chính
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    gender ENUM('male', 'female', 'other') DEFAULT 'other',
    date_of_birth DATE,
    avatar VARCHAR(500),
    address TEXT,
    role_id INT DEFAULT 3, -- 1: admin, 2: staff, 3: customer
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    email_verified_at TIMESTAMP NULL,
    phone_verified_at TIMESTAMP NULL,
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    two_factor_secret VARCHAR(255),
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Bảng reset password tokens
CREATE TABLE password_resets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng OTP verification
CREATE TABLE verification_codes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    code VARCHAR(10) NOT NULL,
    type ENUM('email', 'phone', '2fa') NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =============================================
-- 2. BẢNG QUẢN LÝ CHI NHÁNH & NHÂN VIÊN
-- =============================================

-- Bảng chi nhánh
CREATE TABLE branches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    working_hours JSON, -- {"monday": {"open": "08:00", "close": "22:00"}, ...}
    status ENUM('active', 'inactive') DEFAULT 'active',
    description TEXT,
    images JSON, -- Array of image URLs
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng thông tin nhân viên
CREATE TABLE staff (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    branch_id INT NOT NULL,
    employee_code VARCHAR(50) UNIQUE,
    position VARCHAR(100), -- "Massage Therapist", "Nail Artist", etc.
    specialties JSON, -- ["massage", "facial", "manicure"]
    experience_years INT DEFAULT 0,
    certification TEXT,
    salary DECIMAL(15,2),
    commission_rate DECIMAL(5,2) DEFAULT 0.00, -- % hoa hồng
    working_schedule JSON, -- Lịch làm việc trong tuần
    status ENUM('active', 'inactive', 'on_leave') DEFAULT 'active',
    hire_date DATE,
    rating DECIMAL(3,2) DEFAULT 0.00,
    total_reviews INT DEFAULT 0,
    bio TEXT,
    profile_image VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE
);

-- =============================================
-- 3. BẢNG QUẢN LÝ DỊCH VỤ & SẢN PHẨM
-- =============================================

-- Bảng danh mục dịch vụ
CREATE TABLE service_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(500),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng dịch vụ
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    short_description VARCHAR(500),
    duration INT NOT NULL, -- Thời gian (phút)
    price DECIMAL(15,2) NOT NULL,
    sale_price DECIMAL(15,2),
    images JSON, -- Array of image URLs
    benefits JSON, -- Array of benefits
    suitable_for JSON, -- ["men", "women", "all"]
    required_staff_level ENUM('junior', 'senior', 'expert') DEFAULT 'junior',
    status ENUM('active', 'inactive') DEFAULT 'active',
    booking_advance_days INT DEFAULT 7, -- Đặt trước bao nhiêu ngày
    sort_order INT DEFAULT 0,
    seo_title VARCHAR(255),
    seo_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES service_categories(id)
);

-- Bảng combo dịch vụ
CREATE TABLE service_combos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    total_duration INT NOT NULL,
    original_price DECIMAL(15,2) NOT NULL,
    combo_price DECIMAL(15,2) NOT NULL,
    discount_percentage DECIMAL(5,2),
    images JSON,
    valid_from DATE,
    valid_to DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng chi tiết combo (dịch vụ nào trong combo)
CREATE TABLE combo_services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    combo_id INT NOT NULL,
    service_id INT NOT NULL,
    quantity INT DEFAULT 1,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (combo_id) REFERENCES service_combos(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Bảng danh mục sản phẩm
CREATE TABLE product_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(500),
    parent_id INT NULL,
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES product_categories(id)
);

-- Bảng sản phẩm
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    sku VARCHAR(100) UNIQUE,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(15,2) NOT NULL,
    sale_price DECIMAL(15,2),
    stock_quantity INT DEFAULT 0,
    min_stock_level INT DEFAULT 5,
    weight DECIMAL(8,2), -- kg
    dimensions VARCHAR(100), -- "10x5x3 cm"
    images JSON,
    ingredients TEXT, -- Thành phần
    how_to_use TEXT, -- Cách sử dụng
    brand VARCHAR(100),
    origin_country VARCHAR(100),
    expiry_months INT, -- Hạn sử dụng (tháng)
    status ENUM('active', 'inactive', 'out_of_stock') DEFAULT 'active',
    is_featured BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    seo_title VARCHAR(255),
    seo_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id)
);

-- =============================================
-- 4. BẢNG ĐẶTLỊCH & LỊCH HẸN
-- =============================================

-- Bảng đặt lịch hẹn
CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    branch_id INT NOT NULL,
    staff_id INT,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration INT NOT NULL, -- Tổng thời gian (phút)
    total_amount DECIMAL(15,2) NOT NULL,
    discount_amount DECIMAL(15,2) DEFAULT 0.00,
    final_amount DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'partial', 'refunded') DEFAULT 'pending',
    payment_method VARCHAR(50), -- "cash", "card", "vnpay", etc.
    notes TEXT,
    cancellation_reason TEXT,
    reminder_sent_at TIMESTAMP NULL,
    confirmed_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (branch_id) REFERENCES branches(id),
    FOREIGN KEY (staff_id) REFERENCES staff(id)
);

-- Bảng chi tiết dịch vụ trong lịch hẹn
CREATE TABLE appointment_services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT NOT NULL,
    service_id INT,
    combo_id INT,
    staff_id INT,
    service_name VARCHAR(255) NOT NULL, -- Lưu tên tại thời điểm đặt
    duration INT NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    quantity INT DEFAULT 1,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    notes TEXT,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (combo_id) REFERENCES service_combos(id),
    FOREIGN KEY (staff_id) REFERENCES staff(id)
);

-- =============================================
-- 5. BẢNG BÁN HÀNG & THANH TOÁN
-- =============================================

-- Bảng đơn hàng
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL,
    tax_amount DECIMAL(15,2) DEFAULT 0.00,
    shipping_fee DECIMAL(15,2) DEFAULT 0.00,
    discount_amount DECIMAL(15,2) DEFAULT 0.00,
    total_amount DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'returned') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'partial', 'failed', 'refunded') DEFAULT 'pending',
    payment_method VARCHAR(50),
    shipping_address JSON, -- Địa chỉ giao hàng
    billing_address JSON, -- Địa chỉ thanh toán
    tracking_number VARCHAR(100),
    estimated_delivery DATE,
    delivered_at TIMESTAMP NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id)
);

-- Bảng chi tiết đơn hàng
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL, -- Lưu tên tại thời điểm mua
    product_sku VARCHAR(100),
    quantity INT NOT NULL,
    unit_price DECIMAL(15,2) NOT NULL,
    total_price DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Bảng thanh toán
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    payable_type ENUM('appointment', 'order') NOT NULL, -- Thanh toán cho gì
    payable_id INT NOT NULL, -- ID của appointment hoặc order
    payment_method VARCHAR(50) NOT NULL, -- "vnpay", "momo", "cash", etc.
    amount DECIMAL(15,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'VND',
    status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(255), -- ID từ gateway thanh toán
    gateway_response JSON, -- Response từ gateway
    processed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =============================================
-- 6. BẢNG TÍCH ĐIỂM & ƯU ĐÃI
-- =============================================

-- Bảng hạng thành viên
CREATE TABLE customer_tiers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    min_points INT NOT NULL,
    max_points INT,
    discount_percentage DECIMAL(5,2) DEFAULT 0.00,
    benefits JSON, -- Array of benefits
    color VARCHAR(7) DEFAULT '#000000', -- Màu đại diện
    icon VARCHAR(500),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng điểm thưởng khách hàng
CREATE TABLE customer_points (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    tier_id INT,
    total_points INT DEFAULT 0,
    available_points INT DEFAULT 0,
    total_spent DECIMAL(15,2) DEFAULT 0.00,
    lifetime_points INT DEFAULT 0, -- Tổng điểm đã kiếm được
    last_transaction_at TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tier_id) REFERENCES customer_tiers(id)
);

-- Bảng lịch sử điểm thưởng
CREATE TABLE point_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    type ENUM('earn', 'spend', 'expire', 'adjust') NOT NULL,
    points INT NOT NULL, -- Số điểm (dương: cộng, âm: trừ)
    description TEXT NOT NULL,
    reference_type VARCHAR(50), -- "appointment", "order", "review", etc.
    reference_id INT,
    expires_at TIMESTAMP NULL, -- Điểm có thể có hạn sử dụng
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng mã giảm giá/voucher
CREATE TABLE coupons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('percentage', 'fixed_amount') NOT NULL,
    value DECIMAL(15,2) NOT NULL, -- % hoặc số tiền
    min_order_amount DECIMAL(15,2) DEFAULT 0.00,
    max_discount_amount DECIMAL(15,2),
    usage_limit INT, -- Giới hạn số lần sử dụng
    used_count INT DEFAULT 0,
    user_limit INT DEFAULT 1, -- Mỗi user được dùng bao nhiêu lần
    applicable_to ENUM('all', 'services', 'products', 'appointments') DEFAULT 'all',
    applicable_ids JSON, -- IDs của services/products được áp dụng
    status ENUM('active', 'inactive', 'expired') DEFAULT 'active',
    starts_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng sử dụng coupon
CREATE TABLE coupon_uses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    coupon_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT,
    appointment_id INT,
    discount_amount DECIMAL(15,2) NOT NULL,
    used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- =============================================
-- 7. BẢNG ĐÁNH GIÁ & PHẢN HỒI
-- =============================================

-- Bảng đánh giá dịch vụ
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reviewable_type ENUM('service', 'product', 'staff', 'appointment') NOT NULL,
    reviewable_id INT NOT NULL,
    customer_id INT NOT NULL,
    appointment_id INT, -- Nếu đánh giá từ appointment
    order_id INT, -- Nếu đánh giá từ order
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255),
    comment TEXT,
    pros TEXT, -- Điểm tốt
    cons TEXT, -- Điểm chưa tốt
    images JSON, -- Ảnh đánh giá
    is_verified BOOLEAN DEFAULT FALSE, -- Đã xác thực khách hàng thật
    is_featured BOOLEAN DEFAULT FALSE, -- Đánh giá nổi bật
    helpful_count INT DEFAULT 0, -- Số người thấy hữu ích
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    admin_reply TEXT, -- Phản hồi từ admin
    replied_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id),
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Bảng đánh giá hữu ích
CREATE TABLE review_helpful (
    id INT PRIMARY KEY AUTO_INCREMENT,
    review_id INT NOT NULL,
    user_id INT NOT NULL,
    is_helpful BOOLEAN NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_review (review_id, user_id)
);

-- =============================================
-- 8. BẢNG NỘI DUNG & MARKETING
-- =============================================

-- Bảng danh mục blog
CREATE TABLE blog_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(500),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    seo_title VARCHAR(255),
    seo_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng bài viết blog
CREATE TABLE blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(500),
    images JSON,
    tags JSON, -- Array of tags
    view_count INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    seo_title VARCHAR(255),
    seo_description TEXT,
    seo_keywords TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES blog_categories(id),
    FOREIGN KEY (author_id) REFERENCES users(id)
);

-- Bảng newsletter subscribers
CREATE TABLE newsletter_subscribers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255),
    status ENUM('active', 'unsubscribed', 'bounced') DEFAULT 'active',
    interests JSON, -- ["beauty_tips", "promotions", "new_services"]
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL
);

-- Bảng campaigns email marketing
CREATE TABLE email_campaigns (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    template VARCHAR(100),
    target_audience JSON, -- Criteria để chọn audience
    status ENUM('draft', 'scheduled', 'sending', 'sent', 'cancelled') DEFAULT 'draft',
    scheduled_at TIMESTAMP NULL,
    sent_at TIMESTAMP NULL,
    total_recipients INT DEFAULT 0,
    opened_count INT DEFAULT 0,
    clicked_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =============================================
-- 9. BẢNG CÀI ĐẶT HỆ THỐNG
-- =============================================

-- Bảng cấu hình hệ thống
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    key_name VARCHAR(255) NOT NULL UNIQUE,
    value LONGTEXT,
    type ENUM('text', 'number', 'boolean', 'json', 'file') DEFAULT 'text',
    description TEXT,
    group_name VARCHAR(100) DEFAULT 'general',
    is_public BOOLEAN DEFAULT FALSE, -- Có thể hiển thị ở frontend không
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng logs hoạt động
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(255) NOT NULL, -- "created", "updated", "deleted", "login", etc.
    model VARCHAR(100), -- "User", "Appointment", "Order", etc.
    model_id INT,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    old_data JSON, -- Dữ liệu cũ (trước khi thay đổi)
    new_data JSON, -- Dữ liệu mới (sau khi thay đổi)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Bảng thông báo
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type VARCHAR(100) NOT NULL, -- "appointment_reminder", "order_update", etc.
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSON, -- Dữ liệu bổ sung
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =============================================
-- 10. BẢNG LIÊN HỆ & HỖ TRỢ
-- =============================================

-- Bảng liên hệ từ website
CREATE TABLE contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255),
    message TEXT NOT NULL,
    status ENUM('new', 'replied', 'resolved', 'closed') DEFAULT 'new',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    assigned_to INT, -- Staff được assign
    admin_notes TEXT,
    replied_at TIMESTAMP NULL,
    resolved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- =============================================
-- DỮ LIỆU MẪU CƠ BẢN
-- =============================================

-- Insert roles
INSERT INTO roles (name, description) VALUES
('admin', 'Quản trị viên hệ thống'),
('staff', 'Nhân viên spa'),
('customer', 'Khách hàng');

-- Insert customer tiers
INSERT INTO customer_tiers (name, min_points, max_points, discount_percentage, benefits, color, sort_order) VALUES
('Thành viên mới', 0, 999, 0.00, '["Tích điểm cơ bản"]', '#gray', 1),
('Thành viên bạc', 1000, 4999, 5.00, '["Giảm 5%", "Sinh nhật tặng voucher"]', '#C0C0C0', 2),
('Thành viên vàng', 5000, 9999, 10.00, '["Giảm 10%", "Ưu tiên đặt lịch", "Tư vấn miễn phí"]', '#FFD700', 3),
('Thành viên kim cương', 10000, NULL, 15.00, '["Giảm 15%", "Dịch vụ VIP", "Quà tặng đặc biệt"]', '#B9F2FF', 4);

-- Insert service categories
INSERT INTO service_categories (name, slug, description, icon) VALUES
('Massage & Thư giãn', 'massage-thu-gian', 'Các dịch vụ massage chuyên nghiệp', 'assets/img/icon/thugian.png'),
('Chăm sóc da', 'cham-soc-da', 'Điều trị và chăm sóc da mặt', 'assets/img/icon/chamsocda.png'),
('Chăm sóc cơ thể', 'cham-soc-co-the', 'Dịch vụ chăm sóc toàn thân', 'assets/img/icon/chamsoccothe.png'),
('Chăm sóc tóc', 'cham-soc-toc', 'Cắt, gội, ủ tóc chuyên nghiệp', 'assets/img/icon/chamsoctoc.png'),
('Waxing', 'waxing', 'Tẩy lông an toàn hiệu quả', 'assets/img/icon/wax.png');

-- Insert basic settings
INSERT INTO settings (key_name, value, type, description, group_name, is_public) VALUES
('site_name', 'Suối Spa', 'text', 'Tên website', 'general', TRUE),
('site_description', 'Spa chăm sóc sắc đẹp chuyên nghiệp', 'text', 'Mô tả website', 'general', TRUE),
('contact_email', 'info@suoispa.com', 'text', 'Email liên hệ', 'contact', TRUE),
('contact_phone', '(028) 1234 5678', 'text', 'Số điện thoại', 'contact', TRUE),
('contact_address', '123 Đường Nguyễn Huệ, Quận 1, TP.HCM', 'text', 'Địa chỉ', 'contact', TRUE),
('business_hours', '{"monday":{"open":"08:00","close":"22:00"},"tuesday":{"open":"08:00","close":"22:00"},"wednesday":{"open":"08:00","close":"22:00"},"thursday":{"open":"08:00","close":"22:00"},"friday":{"open":"08:00","close":"22:00"},"saturday":{"open":"08:00","close":"22:00"},"sunday":{"open":"08:00","close":"22:00"}}', 'json', 'Giờ làm việc', 'contact', TRUE),
('points_per_vnd', '1', 'number', 'Số điểm được tích cho mỗi VNĐ chi tiêu', 'loyalty', FALSE),
('vnp_enabled', '1', 'boolean', 'Bật VNPay', 'payment', FALSE),
('momo_enabled', '1', 'boolean', 'Bật MoMo', 'payment', FALSE);

-- =============================================
-- TẠO INDEXES ĐỂ TỐI ƯU HIỆU SUẤT
-- =============================================

-- User indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_phone ON users(phone);
CREATE INDEX idx_users_role ON users(role_id);
CREATE INDEX idx_users_status ON users(status);

-- Appointment indexes  
CREATE INDEX idx_appointments_date ON appointments(appointment_date);
CREATE INDEX idx_appointments_customer ON appointments(customer_id);
CREATE INDEX idx_appointments_staff ON appointments(staff_id);
CREATE INDEX idx_appointments_branch ON appointments(branch_id);
CREATE INDEX idx_appointments_status ON appointments(status);

-- Order indexes
CREATE INDEX idx_orders_customer ON orders(customer_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_date ON orders(created_at);

-- Product indexes
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_sku ON products(sku);
CREATE INDEX idx_products_status ON products(status);

-- Service indexes  
CREATE INDEX idx_services_category ON services(category_id);
CREATE INDEX idx_services_slug ON services(slug);
CREATE INDEX idx_services_status ON services(status);

-- Review indexes
CREATE INDEX idx_reviews_reviewable ON reviews(reviewable_type, reviewable_id);
CREATE INDEX idx_reviews_customer ON reviews(customer_id);
CREATE INDEX idx_reviews_rating ON reviews(rating);

-- Point transaction indexes
CREATE INDEX idx_point_transactions_customer ON point_transactions(customer_id);
CREATE INDEX idx_point_transactions_type ON point_transactions(type);
CREATE INDEX idx_point_transactions_date ON point_transactions(created_at);

-- =============================================
-- HOÀN TẤT SCHEMA
-- =============================================