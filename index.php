<?php include 'header.php'; ?>

<body>
    <?php include 'nav.php'; ?>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="swiper hero-swiper" id="heroSlider">
            <div class="swiper-wrapper">
                
                <!-- Slide 1: Image -->
                <div class="swiper-slide hero-slide">
                    <img src="assets/img/banner1.jpg" alt="Slide 1">
                </div>
                
                <!-- Slide 2: Image -->
                <div class="swiper-slide hero-slide">
                    <img src="assets/img/banner2.jpg" alt="Slide 2">
                </div>
                
                <!-- Slide 3: Image -->
                <div class="swiper-slide hero-slide">
                    <img src="assets/img/banner3.jpg" alt="Slide 3">
                </div>
            </div>
            
            <!-- Pagination dots as individual dots -->
            <div class="hero-dots-indicator">
                <span class="dot dot-1 active">•</span>
                <span class="dot dot-2">•</span>
                <span class="dot dot-3">•</span>
            </div>
        </div>
    </section>
 
    <!-- Dịch Vụ Của Suối Section -->
    <section style="padding: 80px 0; background: #fafafa; position: relative;">
        <div class="container"> 
            <!-- Title with decorative lines on both sides -->
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <div style="height: 1px; width: 80px; background: linear-gradient(90deg, transparent, #7C5E3B);"></div>
                <div style="margin: 0 20px; display: flex; align-items: center;">
                    <img src="assets/img/waterfall.png" alt="Divider" style="width: 20px; height: 20px; filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%); margin-right: 15px;">
                    <h2 style="font-family: 'Playfair Display', serif; font-size: 42px; font-weight: 700; color: #7C5E3B; margin: 0; line-height: 1.2; white-space: nowrap;">
                        Dịch vụ của Suối
                    </h2>
                    <img src="assets/img/waterfall.png" alt="Divider" style="width: 20px; height: 20px; filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%); margin-left: 15px;">
                </div>
                <div style="height: 1px; width: 80px; background: linear-gradient(90deg, #7C5E3B, transparent);"></div>
            </div>
        </div>
        
        <!-- Services Grid -->
        <div class="row justify-content-center" style="margin-bottom: 50px;">
            <!-- Service 1: Trọn Gói -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="service-item" onclick="changeServiceDescription('trongoi')" style="text-align: center; padding: 20px; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="margin-bottom: 20px;">
                        <!-- Trọn Gói Icon -->
                        <div style="width: 80px; height: 80px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            <img src="assets/img/icon/trongoi.png" alt="Trọn Gói" style="width: 70px; height: 70px; filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%);">
                        </div>
                    </div>
                    <h5 id="service-trongoi" style="color: #7C5E3B; font-weight: 600; margin-bottom: 10px; font-size: 16px;">TRỌN GÓI</h5>
                </div>
            </div>
            
            <!-- Service 2: Massage Thư Giãn -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="service-item" onclick="changeServiceDescription('massage')" style="text-align: center; padding: 20px; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="margin-bottom: 20px;">
                        <!-- Massage Thư Giãn Icon -->
                        <div style="width: 80px; height: 80px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            <img src="assets/img/icon/thugian.png" alt="Massage Thư Giãn" style="width: 70px; height: 70px; filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%);">
                        </div>
                    </div>
                    <h5 id="service-massage" style="color: #666; font-weight: 600; margin-bottom: 10px; font-size: 16px;">MASSAGE THƯ GIÃN</h5>
                </div>
            </div>
            
            <!-- Service 3: Chăm Sóc Da -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="service-item" onclick="changeServiceDescription('skincare')" style="text-align: center; padding: 20px; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="margin-bottom: 20px;">
                        <!-- Chăm Sóc Da Icon -->
                        <div style="width: 80px; height: 80px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            <img src="assets/img/icon/chamsocda.png" alt="Chăm Sóc Da" style="width: 70px; height: 70px; filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%);">
                        </div>
                    </div>
                    <h5 id="service-skincare" style="color: #666; font-weight: 600; margin-bottom: 10px; font-size: 16px;">CHĂM SÓC DA</h5>
                </div>
            </div>
            
            <!-- Service 4: Chăm Sóc Cơ Thể -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="service-item" onclick="changeServiceDescription('bodycare')" style="text-align: center; padding: 20px; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="margin-bottom: 20px;">
                        <!-- Chăm Sóc Cơ Thể Icon -->
                        <div style="width: 80px; height: 80px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            <img src="assets/img/icon/chamsoccothe.png" alt="Chăm Sóc Cơ Thể" style="width: 70px; height: 70px; filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%);">
                        </div>
                    </div>
                    <h5 id="service-bodycare" style="color: #666; font-weight: 600; margin-bottom: 10px; font-size: 16px;">CHĂM SÓC CƠ THỂ</h5>
                </div>
            </div>
            
            <!-- Service 5: Chăm Sóc Tóc -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="service-item" onclick="changeServiceDescription('haircare')" style="text-align: center; padding: 20px; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="margin-bottom: 20px;">
                        <!-- Chăm Sóc Tóc Icon -->
                        <div style="width: 80px; height: 80px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            <img src="assets/img/icon/chamsoctoc.png" alt="Chăm Sóc Tóc" style="width: 70px; height: 70px; filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%);">
                        </div>
                    </div>
                    <h5 id="service-haircare" style="color: #666; font-weight: 600; margin-bottom: 10px; font-size: 16px;">CHĂM SÓC TÓC</h5>
                </div>
            </div>
            
            <!-- Service 6: Waxing -->
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="service-item" onclick="changeServiceDescription('waxing')" style="text-align: center; padding: 20px; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="margin-bottom: 20px;">
                        <!-- Waxing Icon -->
                        <div style="width: 80px; height: 80px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            <img src="assets/img/icon/wax.png" alt="Waxing" style="width: 70px; height: 70px; filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%);">
                        </div>
                    </div>
                    <h5 id="service-waxing" style="color: #666; font-weight: 600; margin-bottom: 10px; font-size: 16px;">WAXING</h5>
                </div>
            </div>
        </div>
        
        <!-- Description -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div style="text-align: center; margin-bottom: 40px;">
                    <p id="serviceDescription" style="color: #666; font-size: 16px; line-height: 1.8; margin-bottom: 30px; max-width: 800px; margin-left: auto; margin-right: auto; transition: opacity 0.3s ease;">
                        Dịch vụ trọn gói nhà Suối bao gồm từ 2 đến 3 dịch vụ lẻ kết hợp với nhau để đem lại trải nghiệm và ưu đãi tốt nhất. Tất cả các dịch vụ Trọn gói đều được miễn phí xông hơi khô hoặc xông hơi ướt thảo dược. Bạn có thể nâng cấp các dịch vụ trong các gói để phù hợp với sở thích và nhu cầu, sẽ có phí dịch vụ chênh lệch.
                    </p>
                    
                    <!-- CTA Button -->
                    <button style="padding: 12px 30px; background: #C8A882; color: #FAFAFA; border: none; border-radius: 4px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; font-size: 13px; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#B8966F'" onmouseout="this.style.backgroundColor='#C8A882'">
                        XEM CHI TIẾT
                    </button>
                </div>
            </div>
        </div>
    </div>
    </section>
    
    <!-- Suối Spa Introduction Section -->
    <section class="spa-intro-section">
        
        <div class="container">
            <div class="row align-items-center">
                <!-- Left Content - Images -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div style="position: relative;">
                        <!-- Main Image Rounded Rectangle -->
                        <div class="spa-intro-image">
                            <div class="spa-intro-image-content">
                                <img src="assets/img/suoi3.png" alt="Spa Experience">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Text -->
                <div class="col-lg-6">
                    <div class="spa-intro-content">
                        <!-- Small Title -->
                        <p class="spa-intro-subtitle">
                            HÀNH TRÌNH TÌM VỀ CHÍNH MÌNH
                        </p>
                        
                        <!-- Main Title -->
                        <h2 class="spa-intro-title">
                            SUỐI SPA - THẢ HỒN VÀO SUỐI, TÌM LẠI CHÍNH MÌNH
                        </h2>

                        <!-- Description -->
                        <p class="spa-intro-text">
                            Giữa dòng chảy hối hả của cuộc sống, Suối Spa là một nơi dừng chân để bạn thả lỏng tâm hồn, như dòng suối trong vắt cuốn trôi mọi mệt mỏi. Tại đây, không chỉ là nơi chăm sóc cơ thể, mà còn là hành trình đưa bạn trở về với chính mình - nơi tâm hồn được tĩnh lặng và tinh thần được tái sinh.
                        </p>
                        
                        <!-- Philosophy Section -->
                        <p class="spa-philosophy">
                            Triết lý của chúng tôi:
                        </p>
                        <p class="spa-intro-text">
                            Như dòng suối luôn tìm đường về biển cả, tâm hồn con người cũng cần tìm về với sự bình yên nội tại.
                            <br>
                            Sức khỏe chân thực bắt đầu từ việc lắng nghe và yêu thương bản thân một cách sâu sắc nhất.
                        </p>
                        
                        <!-- Experience Description -->
                        <p class="spa-intro-text">
                            Tại Suối Spa, mỗi liệu trình massage không chỉ là kỹ thuật chạm vào cơ thể, mà là nghệ thuật chạm đến tâm hồn. Với đôi bàn tay điều luyện và tâm huyết, các kỹ thuật viên của chúng tôi sẽ dẫn dắt bạn qua từng khoảnh khắc thư giãn, giúp bạn "thả hồn vào suối" - buông bỏ mọi căng thẳng và tìm lại sự cân bằng trong tâm hồn.
                        </p>
                        
                        <!-- CTA Button -->
                        <a href="#" class="spa-cta-btn">
                            XEM THÊM
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Section -->
    <section id="booking" style="padding: 80px 0; background: #fafafa;">
        <div class="container">
            <!-- Section Header -->
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <div style="height: 1px; width: 80px; background: linear-gradient(90deg, transparent, #7C5E3B);"></div>
                    <div style="margin: 0 20px; display: flex; align-items: center;">
                        <img src="assets/img/waterfall.png" alt="Divider" style="width: 20px; height: 20px; filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%); margin-right: 15px;">
                        <h2 style="font-family: 'Playfair Display', serif; font-size: 42px; font-weight: 700; color: #7C5E3B; margin: 0; line-height: 1.2; white-space: nowrap;">
                        Menu Dịch Vụ
                        </h2>
                        <img src="assets/img/waterfall.png" alt="Divider" style="width: 20px; height: 20px; filter: brightness(0) saturate(100%) invert(34%) sepia(29%) saturate(1037%) hue-rotate(350deg) brightness(95%) contrast(87%); margin-left: 15px;">
                    </div>
                    <div style="height: 1px; width: 80px; background: linear-gradient(90deg, #7C5E3B, transparent);"></div>
                </div>
            
            <!-- Tabs Navigation -->
            <div style="display: flex; justify-content: center; margin-bottom: 40px; gap: 30px;">
                <span class="service-tab active" data-tab="single" style="font-weight: 600; font-size: 16px; cursor: pointer; text-transform: uppercase; color: #7C5E3B; transition: color 0.3s ease;">
                    DỊCH VỤ LẺ
                </span>
                <span class="service-tab" data-tab="combo" style="font-weight: 600; font-size: 16px; cursor: pointer; text-transform: uppercase; color: #666; transition: color 0.3s ease;">
                    COMBO
                </span>
            </div>

            <!-- Single Services Tab Content -->
            <div id="single-services" class="tab-content" style="display: block; min-height: 400px;">
                <div class="row">
                    <!-- Massage Services Column -->
                    <div class="col-md-6 mb-4">
                        <h4 style="color: #7C5E3B; font-weight: 600; margin-bottom: 20px; font-size: 18px; border-bottom: 2px solid #7C5E3B; padding-bottom: 10px;">
                            MASSAGE & THƯ GIÃN
                        </h4>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div style="flex: 1;">
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px; font-size: 16px;">Massage Thư Giãn</h5>
                                <p style="color: #666; font-size: 13px; margin: 0;">Massage toàn thân giúp thư giãn cơ bắp (60 phút)</p>
                            </div>
                            <div style="color: #7C5E3B; font-weight: 700; font-size: 16px;">350,000đ</div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div style="flex: 1;">
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px; font-size: 16px;">Massage Đá Nóng</h5>
                                <p style="color: #666; font-size: 13px; margin: 0;">Massage với đá nóng tự nhiên (75 phút)</p>
                            </div>
                            <div style="color: #7C5E3B; font-weight: 700; font-size: 16px;">450,000đ</div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div style="flex: 1;">
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px; font-size: 16px;">Massage Thảo Dược</h5>
                                <p style="color: #666; font-size: 13px; margin: 0;">Massage với tinh dầu thảo dược (90 phút)</p>
                            </div>
                            <div style="color: #7C5E3B; font-weight: 700; font-size: 16px;">550,000đ</div>
                        </div>
                    </div>

                    <!-- Facial & Body Services Column -->
                    <div class="col-md-6 mb-4">
                        <h4 style="color: #7C5E3B; font-weight: 600; margin-bottom: 20px; font-size: 18px; border-bottom: 2px solid #7C5E3B; padding-bottom: 10px;">
                            CHĂM SÓC DA & CƠ THỂ
                        </h4>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div style="flex: 1;">
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px; font-size: 16px;">Chăm Sóc Da Mặt Cơ Bản</h5>
                                <p style="color: #666; font-size: 13px; margin: 0;">Làm sạch, tẩy tế bào chết và dưỡng ẩm (60 phút)</p>
                            </div>
                            <div style="color: #7C5E3B; font-weight: 700; font-size: 16px;">300,000đ</div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div style="flex: 1;">
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px; font-size: 16px;">Điều Trị Mụn Chuyên Sâu</h5>
                                <p style="color: #666; font-size: 13px; margin: 0;">Liệu trình điều trị mụn và se khít lỗ chân lông (90 phút)</p>
                            </div>
                            <div style="color: #7C5E3B; font-weight: 700; font-size: 16px;">500,000đ</div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div style="flex: 1;">
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px; font-size: 16px;">Tẩy Tế Bào Chết Toàn Thân</h5>
                                <p style="color: #666; font-size: 13px; margin: 0;">Tẩy tế bào chết và dưỡng ẩm toàn thân (75 phút)</p>
                            </div>
                            <div style="color: #7C5E3B; font-weight: 700; font-size: 16px;">400,000đ</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Combo Services Tab Content -->
            <div id="combo-services" class="tab-content" style="display: none; min-height: 400px;">
                <div class="row">
                    <!-- Premium Combos Column -->
                    <div class="col-md-6 mb-4">
                        <h4 style="color: #7C5E3B; font-weight: 600; margin-bottom: 20px; font-size: 18px; border-bottom: 2px solid #7C5E3B; padding-bottom: 10px;">
                            COMBO CAO CẤP
                        </h4>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div style="flex: 1;">
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px; font-size: 16px;">Combo Thư Giãn Hoàn Hảo</h5>
                                <p style="color: #666; font-size: 13px; margin: 0;">Massage thư giãn + Chăm sóc da mặt + Xông hơi (150 phút)</p>
                            </div>
                            <div style="text-align: right;">
                                <div style="color: #999; font-size: 13px; text-decoration: line-through;">750,000đ</div>
                                <div style="color: #7C5E3B; font-weight: 700; font-size: 16px;">599,000đ</div>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div style="flex: 1;">
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px; font-size: 16px;">Combo Làm Đẹp Toàn Diện</h5>
                                <p style="color: #666; font-size: 13px; margin: 0;">Điều trị da mặt + Tẩy tế bào chết + Massage đá nóng (180 phút)</p>
                            </div>
                            <div style="text-align: right;">
                                <div style="color: #999; font-size: 13px; text-decoration: line-through;">950,000đ</div>
                                <div style="color: #7C5E3B; font-weight: 700; font-size: 16px;">799,000đ</div>
                            </div>
                        </div>
                    </div>

                    <!-- Couple Combos Column -->
                    <div class="col-md-6 mb-4">
                        <h4 style="color: #7C5E3B; font-weight: 600; margin-bottom: 20px; font-size: 18px; border-bottom: 2px solid #7C5E3B; padding-bottom: 10px;">
                            COMBO ĐÔI
                        </h4>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div style="flex: 1;">
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px; font-size: 16px;">Combo Couple Romance</h5>
                                <p style="color: #666; font-size: 13px; margin: 0;">Massage đôi + Chăm sóc da mặt đôi (120 phút)</p>
                            </div>
                            <div style="text-align: right;">
                                <div style="color: #999; font-size: 13px; text-decoration: line-through;">1,200,000đ</div>
                                <div style="color: #7C5E3B; font-weight: 700; font-size: 16px;">999,000đ</div>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div style="flex: 1;">
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 5px; font-size: 16px;">Combo Family Relax</h5>
                                <p style="color: #666; font-size: 13px; margin: 0;">Package gia đình 4 người, massage + xông hơi (240 phút)</p>
                            </div>
                            <div style="text-align: right;">
                                <div style="color: #999; font-size: 13px; text-decoration: line-through;">2,000,000đ</div>
                                <div style="color: #7C5E3B; font-weight: 700; font-size: 16px;">1,599,000đ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Appointment Button -->
            <div style="text-align: center; margin-top: 30px;">
                <button style="padding: 15px 40px; background: #7C5E3B; color: white; border: none; font-weight: 600; text-transform: uppercase; font-size: 14px; cursor: pointer; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#6B4F32'" onmouseout="this.style.backgroundColor='#7C5E3B'">
                    ĐẶT LỊCH HẸN NGAY
                </button>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- jQuery và Bootstrap 3 JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/main.js"></script>
    
    <!-- Service Description Change JavaScript -->
    <script>
        // Service descriptions content
        const serviceDescriptions = {
            'trongoi': 'Trải nghiệm dịch vụ trọn gói tại Suối Spa với không gian yên bình, thỏa mãn mọi nhu cầu chăm sóc sắc đẹp và sức khỏe của bạn. Từ massage thư giãn đến các liệu trình chăm sóc chuyên sâu.',
            'massage': 'Dịch vụ massage thư giãn chuyên nghiệp giúp giải tỏa căng thẳng, thư giãn cơ bắp và tái tạo năng lượng. Với đội ngũ kỹ thuật viên giàu kinh nghiệm và phương pháp massage truyền thống.',
            'skincare': 'Chăm sóc da chuyên sâu với các liệu trình hiện đại, sử dụng sản phẩm thiên nhiên cao cấp. Giúp da trắng sáng, mịn màng và khỏe mạnh từ bên trong.',
            'bodycare': 'Dịch vụ chăm sóc cơ thể toàn diện với các liệu trình tẩy tế bào chết, dưỡng ẩm và săn chắc da. Mang lại làn da mềm mại và vóc dáng hoàn hảo.',
            'haircare': 'Chăm sóc tóc chuyên nghiệp với các liệu trình phục hồi, dưỡng ẩm và tạo kiểu. Giúp mái tóc khỏe mạnh, óng mượt và đẹp tự nhiên.',
            'waxing': 'Dịch vụ waxing an toàn và hiệu quả với công nghệ hiện đại. Loại bỏ lông không mong muốn một cách nhẹ nhàng, cho làn da mịn màng lâu dài.'
        };
        
        // Function to change service description
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
        
        // Set default description on page load
        document.addEventListener('DOMContentLoaded', function() {
            const descriptionElement = document.getElementById('serviceDescription');
            if (descriptionElement) {
                descriptionElement.textContent = serviceDescriptions['trongoi'];
                // Set first service as active by default
                const firstService = document.querySelector('.service-item h5');
                if (firstService) {
                    firstService.style.color = '#7C5E3B';
                }
            }
        });
    </script>
</body>
</html>