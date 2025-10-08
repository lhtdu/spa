    <!-- Navigation -->
    <header class="navbar-sticky sticky-top container-fluid z-fixed px-4" data-sticky-element="">
      <div class="navbar navbar-expand-lg flex-nowrap rounded-pill shadow ps-0 mx-3">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark rounded-pill z-0 d-none d-block-dark"></div>

        <!-- Mobile offcanvas menu toggler (Hamburger) -->
        <button type="button" class="navbar-toggler ms-3" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar brand (Logo) -->
        <a class="navbar-brand position-relative z-1 ms-4 ms-sm-5 ms-lg-4 me-2 me-sm-0 me-lg-3" href="/">
          <img src="/assets/img/waterfall.png" class="d-flex d-none d-md-inline-flex justify-content-center align-items-center flex-shrink-0 me-1">
          Suối Spa
        </a>

        <!-- Main navigation that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
        <nav class="offcanvas offcanvas-start" id="navbarNav" tabindex="-1" aria-labelledby="navbarNavLabel">
            <div class="offcanvas-header py-3">
                <h5 class="offcanvas-title" id="navbarNavLabel">Menu điều hướng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body pt-3 pb-4 py-lg-0 mx-lg-auto">
                <ul class="navbar-nav position-relative">
                    <li class="nav-item me-lg-n2 me-xl-0">
                        <a class="nav-link fs-sm active" href="#home">
                            Trang chủ
                        </a>
                    </li>
                    <li class="nav-item me-lg-n2 me-xl-0">
                        <a class="nav-link fs-sm" href="#services">
                            Dịch vụ
                        </a>
                    </li>
                    <li class="nav-item me-lg-n2 me-xl-0">
                        <a class="nav-link fs-sm" href="#booking">
                            Đặt lịch
                        </a>
                    </li>
                    <li class="nav-item me-lg-n2 me-xl-0">
                        <a class="nav-link fs-sm" href="#blog">
                            Blog
                        </a>
                    </li>
                    <li class="nav-item me-lg-n2 me-xl-0">
                        <a class="nav-link fs-sm" href="#contact">
                            Liên hệ
                        </a>
                    </li>
                    <li class="nav-item me-lg-n2 me-xl-0">
                        <a class="nav-link fs-sm" href="#products">
                            Sản phẩm
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Button group -->
        <div class="d-flex gap-2 position-relative z-1 align-items-center">
          <!-- User -->
          <div class="d-flex gap-1">
            <a class="btn btn-sm d-flex align-items-center justify-content-center login-btn" href="#login">Đăng nhập</a>
          </div>
          
          <!-- Cart -->
          <div class="dropdown position-relative">
            <a class="btn btn-icon fs-lg btn-outline-secondary border-0 rounded-circle animate-scale cart-icon position-relative d-flex align-items-center justify-content-center" href="#cart" id="cart-btn">
              <i class="fa fa-shopping-cart animate-target"></i>
              <span class="cart-count badge bg-danger position-absolute" id="cartCount">0</span>
            </a>
            <div class="dropdown-menu cart-dropdown">
              <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h6 class="mb-0">Giỏ hàng</h6>
                  <span class="text-muted small" id="cartDropdownCount">0 sản phẩm</span>
                </div>
                
                <div id="cartDropdownItems">
                  <div class="text-center text-muted py-4">
                    <i class="fa fa-shopping-cart fs-2 mb-2 d-block"></i>
                    <small>Giỏ hàng trống</small>
                  </div>
                </div>
                
                <div id="cartDropdownFooter" style="display: none;">
                  <hr class="my-3">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-medium">Tổng cộng:</span>
                    <span class="fw-bold" id="cartDropdownTotal">$0.00</span>
                  </div>
                  <div class="d-grid gap-2">
                    <a href="#cart" class="btn btn-dark btn-sm">Xem giỏ hàng</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>