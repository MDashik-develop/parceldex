@php
    $socialLinks = App\Models\SocialLink::where('status', 1)->get();
    $partners = App\Models\Partner::where('status', 1)->get();
@endphp


@section('seo-meta')
    @php
        $seoMeta = App\Models\SeoMeta::where('page_name', 'Home')->first();
    @endphp

    @if ($seoMeta)
        <title>{{ $seoMeta->og_title }}</title>

        <meta name="description" content="{{ $seoMeta->og_description }}">
        <meta name="keywords" content="{{ $seoMeta->meta_keywords }}">

        <meta property="og:title" content="{{ $seoMeta->og_title }}">
        <meta property="og:description" content="{{ $seoMeta->og_description }}">
        <meta property="og:image" content="{{ asset('storage/images/' . $seoMeta->og_image) }}">
        <meta property="og:url" content="{{ url()->current() }}">

        <meta name="twitter:title" content="{{ $seoMeta->og_title }}">
        <meta name="twitter:description" content="{{ $seoMeta->og_description }}">
        <meta name="twitter:image" content="{{ asset('storage/images/' . $seoMeta->og_image) }}">

        <link rel="canonical" href="{{ url()->current() }}">

        <!-- JSON-LD Schema Markup for Services Page -->
        <script type="application/ld+json">
     {
       "@context": "https://schema.org",
       "@type": "Service",
       "name": "Courier Services",
       "url": "{{url()->current()}}",
       "provider": {
         "@type": "CourierService",
         "name": "Parceldex Courier",
         "url": "https://parceldex.com"
       },
       "serviceType": "Express Delivery, Same-Day Delivery, Next-Day Delivery, Cash on Delivery, eCommerce Logistics",
       "areaServed": {
         "@type": "Country",
         "name": "Bangladesh"
       },
       "availableChannel": {
         "@type": "ServiceChannel",
         "serviceUrl": "{{url()->current()}}",
         "availableLanguage": ["English", "Bengali"]
       },
       "offers": {
         "@type": "Offer",
         "priceCurrency": "BDT",
         "price": "Varies",
         "url": "{{url()->current()}}"
       },
       "contactPoint": {
         "@type": "ContactPoint",
         "telephone": "+8809642727727",
         "contactType": "customer service"
       }
     }
     </script>
    @endif
@endsection


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
                    <div class="home-hero-section-buttons row justify-content-start px-0 gap-2 gap-lg-3">
                        <a class="col col-md-auto col-lg-auto text-left" href="/register"><button class="">Become a
                                Merchant</button></a>
                        <a href="/get-a-call" class="col col-md-auto col-lg-auto"><button class=""
                                style=" background-color: #F27B21;">Get A Call</button></a>

                    </div>
                </div>
                <div class="intro-img center text-center">
                    <img class=" animate__animated animate__fadeIn" src="/frontend/images/rider.png" alt="">
                </div>

            </div>
        </div>
    </div>


    <div class="px-sm-2 px-md-4">
        <div
            class="patner-wrapper patner-wrapper1 d-flex flex-column flex-md-row justify-content-center align-items-center gap-5 animate__animated animate__fadeInUp">
            <p class="brands-logos-p p-1 rounded">Fashion</p>
            <div class="container-fluid mx-0 brand_logos">
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
        <div
            class="patner-wrapper patner-wrapper2 d-flex flex-column flex-md-row justify-content-center align-items-center gap-5 animate__animated animate__fadeInUp ">
            <p class="brands-logos-p p-1 rounded">Corporate</p>
            <div class="container-fluid mx-0 brand_logos">
                <div
                    class="patner d-flex flex-nowrap justify-content-between align-items-center brand_logo_items brand_logo_items2">
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

        <div
            class="patner-wrapper patner-wrapper2 d-flex flex-column flex-md-row justify-content-center align-items-center gap-5 animate__animated animate__fadeInUp">
            <p class="brands-logos-p py-1 px-3 rounded">Marketplace</p>
            <div class="container-fluid mx-0 brand_logos">
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
        <div
            class="patner-wrapper patner-wrapper2 d-flex flex-column flex-md-row justify-content-center align-items-center gap-5 animate__animated animate__fadeInUp ">
            <p class="brands-logos-p p-1 rounded">Banking</p>
            <div class="container-fluid mx-0 brand_logos">
                <div
                    class="patner d-flex flex-nowrap justify-content-between align-items-center brand_logo_items brand_logo_items2">
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

    <div class="px-3 px-md-0 container blog-section">
        <div class="w-100 mb-3 d-flex justify-content-between">
            <h5>Related Post</h5>
            <a href="/blogs" class="border px-3 py-2 rounded">View All</a>
        </div>
        <div class="row gap-5 gap-md-2 flex-column flex-md-row justify-content-start align-items-center">
            <div class="card col" style="width: 18rem;">
                <img class="card-img-top" src="https://placehold.co/600x400" alt="Card image cap">
                <div class="card-body p-2">
                    <span class="card-title fw-bold fs-6">Card title</span>
                    <p class="card-text custom-ellipsis">Some quick example text to build on the card title and make up the
                        bulk of the card's content.</p>
                </div>
            </div>
            <div class="card col" style="width: 18rem;">
                <img class="card-img-top" src="https://placehold.co/600x400" alt="Card image cap">
                <div class="card-body p-2">
                    <span class="card-title fw-bold fs-6">Card title</span>
                    <p class="card-text custom-ellipsis">Some quick example text to build on the card title and make up the
                        bulk of the card's content.</p>
                </div>
            </div>
            <div class="card col" style="width: 18rem;">
                <img class="card-img-top" src="https://placehold.co/600x400" alt="Card image cap">
                <div class="card-body p-2">
                    <span class="card-title fw-bold fs-6">Card title</span>
                    <p class="card-text custom-ellipsis">Some quick example text to build on the card title and make up the
                        bulk of the card's content.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-3 container text-box-seo pt-5 pb-2">
        <p style="">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde ipsum praesentium omnis at veniam? Excepturi
            rerum saepe quas, provident ipsum sequi cum reiciendis eligendi quam optio quos tempora facilis? Assumenda
            temporibus perferendis laboriosam ut nobis itaque ex earum, fuga a nisi unde placeat repellat autem rerum rem?
            Unde, repellat rerum. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Fugiat porro dignissimos ducimus
            necessitatibus vero dolorum quas saepe tenetur. Ea totam delectus quo quia voluptatibus aperiam autem quaerat!
            Sint assumenda ab dolorum, placeat dolor voluptates perspiciatis quaerat illo ipsum eveniet laborum consequatur
            explicabo, dignissimos, minus minima magni pariatur! Fuga, esse ea?
        </p>
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
