@php

    $socialLinks = App\Models\SocialLink::where('status', 1)->get();
    $partners = App\Models\Partner::where('status', 1)->get();

@endphp

@extends('layouts.frontend.app')

@section('content')
    <div class="intro-wrapper">
        <div class="container-fluid">
            <div class="intro d-flex justify-content-between">
                <div class="intro-content d-flex flex-column animate__animated animate__fadeInLeftBig">
                    <h2>We <mark>Deliver</mark> <br> Parcel on Time with
                        no Hassle</h2>
                    <p>Different times of the year can determine the cost of moving your belongings. For example,
                        moving during
                        a hurricane or tornado season can demand a higher cost due to the circumstances surrounding
                        the process.
                    </p>
                    <a href="register.html"><button class="btn btn-lg">Become a Merchant</button></a>
                </div>
                <div class="intro-img center text-center">
                    <img class=" animate__animated animate__fadeIn" src="/frontend/images/rider.png" alt="">
                </div>

            </div>
        </div>
    </div>

    <div class="patner-wrapper patner-wrapper1 animate__animated animate__fadeInUp overflow-hidden">
        <div class="container-fluid brand_logos">
            <div class="patner d-flex flex-nowrap justify-content-between align-items-center brand_logo_items">
                    <img src="/frontend/images/partner_img/sm_ghor.png" alt="">
                    <img src="/frontend/images/partner_img/sm_halal_food.png" alt="">
                    <img src="/frontend/images/partner_img/rokomari.png" alt="">
                    <img src="/frontend/images/partner_img/naturo-01.png" alt="">
                    <img src="/frontend/images/partner_img/sm_online.png" alt="">
                    <img src="/frontend/images/partner_img/sm_ghor.png" alt="">
                    <img src="/frontend/images/partner_img/sm_halal_food.png" alt="">
                    <img src="/frontend/images/partner_img/rokomari.png" alt="">
                    <img src="/frontend/images/partner_img/naturo-01.png" alt="">
                    <img src="/frontend/images/partner_img/sm_online.png" alt="">
                    <img src="/frontend/images/partner_img/sm_ghor.png" alt="">
                    <img src="/frontend/images/partner_img/sm_halal_food.png" alt="">
                    <img src="/frontend/images/partner_img/rokomari.png" alt="">
                    <img src="/frontend/images/partner_img/naturo-01.png" alt="">
                    <img src="/frontend/images/partner_img/sm_online.png" alt="">

            </div>
        </div>


    </div>

    <div class="patner-wrapper patner-wrapper2 animate__animated animate__fadeInUp overflow-hidden">

        <div class="container-fluid brand_logos">
            <div class="patner d-flex flex-nowrap justify-content-between align-items-center brand_logo_items brand_logo_items2">
                    <img src="/frontend/images/partner_img/othoba.png" alt="">
                    <img src="/frontend/images/partner_img/lotto.png" alt="">
                    <img src="/frontend/images/partner_img/sailor.png" alt="">
                    <img src="/frontend/images/partner_img/fashion.png" alt="">
                    <img src="/frontend/images/partner_img/htbazar.png" alt="">
                    <img src="/frontend/images/partner_img/othoba.png" alt="">
                    <img src="/frontend/images/partner_img/lotto.png" alt="">
                    <img src="/frontend/images/partner_img/sailor.png" alt="">
                    <img src="/frontend/images/partner_img/fashion.png" alt="">
                    <img src="/frontend/images/partner_img/htbazar.png" alt="">
                    <img src="/frontend/images/partner_img/othoba.png" alt="">
                    <img src="/frontend/images/partner_img/lotto.png" alt="">
                    <img src="/frontend/images/partner_img/sailor.png" alt="">
                    <img src="/frontend/images/partner_img/fashion.png" alt="">
                    <img src="/frontend/images/partner_img/htbazar.png" alt="">


            </div>
        </div>
    </div>

    <div class="chairman-wrapper animate__animated animate__fadeInUp">
        <div class="container-fluid">
            <div class="about-ceo ">
                <div class="row">
                    <div class="col-5">
                        <div class="ceo-img position-relative">
                            <img src="/frontend/images/mans.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-7">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-4">
                                Find Solutions to Your Shipping Challenges
                            </h2>
                            <p class="text-gray-600 mb-6">
                                We keep moving forward, opening new doors, and doing new things, because we're
                                curious and curiosity
                                keeps leading us down new paths.
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <i class="fas fa-smile text-green-500 text-2xl mr-4">
                                    </i>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">
                                            Happy Customer
                                        </h3>
                                        <p class="text-gray-600">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-box text-green-500 text-2xl mr-4">
                                    </i>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">
                                            500+ Routes
                                        </h3>
                                        <p class="text-gray-600">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-cube text-green-500 text-2xl mr-4">
                                    </i>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">
                                            Care Packaging
                                        </h3>
                                        <p class="text-gray-600">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-truck text-green-500 text-2xl mr-4">
                                    </i>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">
                                            Quick Response
                                        </h3>
                                        <p class="text-gray-600">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="service-wrapper animate__animated animate__fadeInUp">
        <div class="container-fluid">
            <div class="service">
                <div class="section_header d-flex flex-column justify-content-center align-items-center">
                    <h4>Our Service</h4>
                </div>
                <div class="row service-list d-flex">
                    <div class="col-md-3 d-flex flex-column justify-content-center align-items-center gap-4">
                        <img src="/frontend/images/ecom_delivry.svg" alt="">
                        <span>Ecommerce Delivery</span>
                    </div>
                    <div class="col-md-3 d-flex flex-column justify-content-center align-items-center gap-4">
                        <img src="/frontend/images/pick_n_drop.svg" alt="">
                        <span>Pick and Drop</span>

                    </div>
                    <div class="col-md-3 d-flex flex-column justify-content-center align-items-center gap-4">
                        <img src="/frontend/images/packeging.svg" alt="">
                        <span>Packging</span>

                    </div>
                    <div class="col-md-3 d-flex flex-column justify-content-center align-items-center gap-4">
                        <img src="/frontend/images/warehouse.svg" alt="">
                        <span>Warehousing</span>

                    </div>
                </div>

            </div>
        </div>
        <div class="ellipseFade1">

        </div>
    </div>

    <div class="why-wrapper animate__animated animate__fadeInUp">
        <div class="container-fluid">
            <div class="why-choose">
                <div class="section_header d-flex flex-column justify-content-center align-items-center">
                    <h4 class="text-center">Why you should choose <br>
                        Parceldex?</h4>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="key-feature d-flex flex-column gap-3">
                            <div>
                                <img src="/frontend/images/daily_pickup.svg" alt="" class="w-auto h-auto">
                            </div>
                            <h5>Daily pickup, no limits</h5>
                            <p>Parceldex Courier gives you the opportunity of unlimited pickup. </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="key-feature d-flex flex-column gap-3">
                            <div>
                                <img src="/frontend/images/cod.svg" alt="" class="w-auto h-auto">
                            </div>
                            <h5>Cash on Delivery</h5>
                            <p>At Parceldex Courier we will collect the cash on behalf of you. </p>
                        </div>

                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="key-feature d-flex flex-column gap-3">
                            <div>
                                <img src="/frontend/images/payment.svg" alt="" class="w-auto h-auto">
                            </div>
                            <h5>Faster Payment Service</h5>
                            <p>We provides multiple payment methods such as cash, Bank or Mobile Banking </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="key-feature d-flex flex-column gap-3">
                            <div>
                                <img src="/frontend/images/management.svg" alt="" class="w-auto h-auto">
                            </div>
                            <h5>Online Management</h5>
                            <p>You can get all the information you need in your own user dashboard. </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="key-feature d-flex flex-column gap-3">
                            <div>
                                <img src="/frontend/images/realtime_track.svg" alt="" class="w-auto h-auto">
                            </div>
                            <h5>Real-Time Tracking</h5>
                            <p>Parceldex Courier provides an unique tracking code for your every consignments. </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="key-feature d-flex flex-column gap-3">
                            <div>
                                <img src="/frontend/images/24_4_support.svg" alt="" class="w-auto h-auto">
                            </div>
                            <h5>24/7 Customer Service</h5>
                            <p>Our Call Center Executives are always ready 24/7 to help you with your problems. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ellipseFade2"></div>
    </div>

    <div class="banner-wrapper animate__animated animate__fadeInUp">
        <div class="banner d-flex flex-column justify-content-center align-items-center gap-2">
            <h4>Grow Your Business with Parceldex</h4>
            <p>Start Your first step with Parceldex</p>
            <a href="register.html"> Become a Marchant</a>
        </div>

        <div class="ellipseFade2"></div>
    </div>
    <script>
        const events = document.querySelectorAll('.animate__animated');

            events.forEach((event, index) => {
            event.style.animationDelay = `${index * 1}s`;
        });

    </script>
@endsection

@push('script_js')
@endpush

@push('style_css')
@endpush
