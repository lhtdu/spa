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
            initialSlide: 0, // Start from first slide
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'slide', // Slide effect
            speed: 1000,
            slidesPerView: 1,
            spaceBetween: 0,
            on: {
                init: function() {
                    // Ensure first slide is active on init
                    this.slideTo(0, 0, false);
                    updateDotIndicator(0);
                },
                slideChange: function() {
                    // Update dot indicator when slide changes
                    updateDotIndicator(this.realIndex);
                }
            }
        });
    }
    
    // Function to update dot indicator
    function updateDotIndicator(activeIndex) {
        const dots = document.querySelectorAll('.hero-dots-indicator .dot');
        dots.forEach((dot, index) => {
            dot.classList.remove('active');
            if (index === activeIndex) {
                dot.classList.add('active');
            }
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
    
    // Initialize service description functionality
    initializeServices();
    
    // Initialize tab switching
    initializeTabSwitching();
});

// ===== EXTRACTED FROM HTML =====

// Service Description Functionality
const serviceDescriptions = {
    'trongoi': 'Trải nghiệm dịch vụ trọn gói tại Suối Spa với không gian yên bình, thỏa mãn mọi nhu cầu chăm sóc sắc đẹp và sức khỏe của bạn. Từ massage thư giãn đến các liệu trình chăm sóc chuyên sâu.',
    'massage': 'Dịch vụ massage thư giãn chuyên nghiệp giúp giải tỏa căng thẳng, thư giãn cơ bắp và tái tạo năng lượng. Với đội ngũ kỹ thuật viên giàu kinh nghiệm và phương pháp massage truyền thống.',
    'skincare': 'Chăm sóc da chuyên sâu với các liệu trình hiện đại, sử dụng sản phẩm thiên nhiên cao cấp. Giúp da trắng sáng, mịn màng và khỏe mạnh từ bên trong.',
    'bodycare': 'Dịch vụ chăm sóc cơ thể toàn diện với các liệu trình tẩy tế bào chết, dưỡng ẩm và săn chắc da. Mang lại làn da mềm mại và vóc dáng hoàn hảo.',
    'haircare': 'Chăm sóc tóc chuyên nghiệp với các liệu trình phục hồi, dưỡng ẩm và tạo kiểu. Giúp mái tóc khỏe mạnh, óng mượt và đẹp tự nhiên.',
    'waxing': 'Dịch vụ waxing an toàn và hiệu quả với công nghệ hiện đại. Loại bỏ lông không mong muốn một cách nhẹ nhàng, cho làn da mịn màng lâu dài.'
};

function changeServiceDescription(serviceType) {
    const descriptionElement = document.getElementById('serviceDescription');
    if (descriptionElement && serviceDescriptions[serviceType]) {
        descriptionElement.textContent = serviceDescriptions[serviceType];
        
        // Reset all services to default color
        const allServices = document.querySelectorAll('.service-item h5');
        allServices.forEach(service => {
            service.style.color = '#666';
        });
        
        // Change color of selected service text to brown
        const currentServiceTitle = event.currentTarget.querySelector('h5');
        if (currentServiceTitle) {
            currentServiceTitle.style.color = '#7C5E3B';
        }
    }
}

function initializeServices() {
    // Set default description on page load
    const descriptionElement = document.getElementById('serviceDescription');
    if (descriptionElement) {
        descriptionElement.textContent = serviceDescriptions['trongoi'];
        // Set first service as active by default
        const firstService = document.querySelector('.service-item h5');
        if (firstService) {
            firstService.style.color = '#7C5E3B';
        }
    }
}

// Tab Switching Functionality
function initializeTabSwitching() {
    const tabButtons = document.querySelectorAll('.service-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Update tab colors
            tabButtons.forEach(btn => {
                if (btn === this) {
                    btn.style.color = '#7C5E3B';
                } else {
                    btn.style.color = '#666';
                }
            });
            
            // Show/hide tab content
            tabContents.forEach(content => {
                if (content.id === targetTab + '-services') {
                    content.style.display = 'block';
                } else {
                    content.style.display = 'none';
                }
            });
        });
    });
}