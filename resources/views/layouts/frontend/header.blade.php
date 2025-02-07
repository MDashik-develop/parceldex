<!-- Header -->
<div class="navbar-wrapper d-flex align-items-center">
    <div class="container-fluid">
        <nav class="navbar">
            <div class="d-flex gap-3 align-items-center">
                <div class="mobile-nav-toggler">
                    <img src="/frontend/images/fa_bar.svg" alt="">
                </div>
                <div class="brand-logo">
                    <!-- <a href="index.html"><img src="/frontend/images/logo/logo.png" alt="" class="web_logo"></a> -->
                    <a href="/"><img src="/frontend/images/logo/logo.png" alt="" class="web_logo"></a>
                </div>
            </div>
            <div class="navbar-menu">
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="/prices">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="/services">Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="/coverage-map">Coverage</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="/about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="contact.html">Contact</a>
                    </li>
                </ul>
            </div>
            <div class="navbar-action d-flex align-items-center">
                <a href="tracking.html" class=" d-none d-sm-inline-block txt-primary me-4"> <img
                        src="/frontend/images/parcel_tarck_icon.svg" alt="" class="parcel_tarck_icon">
                    Track Parcel </a>
                <div class="d-flex align-items-center gap-2 gap-sm-3">

                    <a href="register.html">
                        <button class="header-button">Sign Up</button>
                    </a>
                    <a href="{{ route('frontend.login') }}">
                        
                        <button class="header-button">Login</button>
                    </a>
                </div>
                <!-- <button class="btn-outline btn-md d-none d-sm-inline-block">EN</button> -->

            </div>
        </nav>
    </div>
</div>
<!-- Header -->

<!-- Mobile Menu  -->
<div class="mobile-menu">
    <div class="menu-backdrop"></div>
    <nav class="menu-box">
        <div class="menubtn d-flex align-items-center justify-content-between">
            <div class="logo">
                <a href="index.html"> <img src="/frontend/images/logo/logo.png" class="img-fluid" /></a>
            </div>
            <div class="close-btn ">
                <img src="/frontend/images/close.png" alt="" class="img-fluid">
            </div>
        </div>
        <div class="divider"></div>
        <div class="menu-outer">
            <a href="tracking.html" class="tracking_tab"><img src="/frontend/images/parcel_tarck_icon.svg"
                    alt="" class="w-auto me-2">
                Track
                Parcel
            </a>
            <div class="nav_link d-flex flex-column gap-2 mt-2">

            </div>
        </div>
    </nav>
</div>
<!-- Mobile Menu  -->
