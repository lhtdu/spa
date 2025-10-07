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
    
    // Blog Slider functionality - 3 Articles with Single Article Movement
    let currentIndex = 0;
    const totalArticles = 6; // Total 6 articles
    const articlesPerView = 3; // Show 3 articles at a time
    const maxIndex = totalArticles - articlesPerView; // Maximum index (3)
    
    const blogSlider = document.getElementById('blogSlider');
    const blogPrevBtn = document.getElementById('blogPrevBtn');
    const blogNextBtn = document.getElementById('blogNextBtn');
    
    function updateBlogSlider() {
        if (blogSlider) {
            // Each article takes up (33.333% + margin), so we need to calculate the actual pixel movement
            // For viewport width, each article is approximately 33.333% + margin
            // We move by one article width at a time
            const movePercent = (100 / 3); // One article = 33.333% of viewport
            const translateX = currentIndex * -movePercent;
            blogSlider.style.transform = `translateX(${translateX}%)`;
        }
    }
    
    function nextBlogSlide() {
        if (currentIndex < maxIndex) {
            currentIndex++;
        } else {
            currentIndex = 0; // Loop back to start
        }
        updateBlogSlider();
    }
    
    function prevBlogSlide() {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = maxIndex; // Loop to end
        }
        updateBlogSlider();
    }
    
    // Add event listeners for blog navigation buttons
    if (blogNextBtn) {
        blogNextBtn.addEventListener('click', nextBlogSlide);
    }
    
    if (blogPrevBtn) {
        blogPrevBtn.addEventListener('click', prevBlogSlide);
    }
    
    // Initialize slider position
    updateBlogSlider();
    
    // Auto-slide for blog (optional) - disabled for manual control
    // setInterval(nextBlogSlide, 8000); // Auto slide every 8 seconds
});