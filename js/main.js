// Cart functionality
let cart = [];
let cartCount = 0;

function updateCartBadge() {
    const cartBadge = document.getElementById('cart-badge');
    if (cartBadge) {
        cartBadge.textContent = cartCount;
    }
}

function addToCart(item) {
    cart.push(item);
    cartCount++;
    updateCartBadge();
    
    // Show notification
    alert('Đã thêm vào giỏ hàng: ' + item.name);
}

function showCartModal() {
    let cartContent = '<h4>Giỏ hàng của bạn</h4>';
    
    if (cart.length === 0) {
        cartContent += '<p>Giỏ hàng trống</p>';
    } else {
        cartContent += '<ul>';
        cart.forEach(function(item, index) {
            cartContent += '<li>' + item.name + ' - ' + item.price + '</li>';
        });
        cartContent += '</ul>';
    }
    
    // Simple modal implementation
    if (confirm(cartContent + '\n\nBạn có muốn xem chi tiết giỏ hàng không?')) {
        // Redirect to cart page or show detailed modal
        console.log('Show detailed cart');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart
    updateCartBadge();
    
    // Cart button click handler
    const cartBtn = document.getElementById('cart-btn');
    if (cartBtn) {
        cartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showCartModal();
        });
    }
    
    // Cart dropdown functionality
    if (cartBtn) {
        cartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const dropdown = this.nextElementSibling;
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        });
    }
    
    // Close cart dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const cartDropdown = document.querySelector('.cart-dropdown');
        const cartBtn = document.getElementById('cart-btn');
        if (cartDropdown && cartBtn && !cartBtn.contains(e.target) && !cartDropdown.contains(e.target)) {
            cartDropdown.classList.remove('show');
        }
    });
    
    // Initialize Swiper
    if (typeof Swiper !== 'undefined') {
        const heroSwiper = new Swiper('#heroSlider', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'slide',
            speed: 1000,
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }
});