
@section('seo-meta')
    @php
        $seoMeta = App\Models\SeoMeta::where('page_name', 'NewsDetails')->first();
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

<div class="main-newsroom-page" style="font-family: sans-serif !important">
    <div class="container my-5 px-3 px-md-0">
        <div>
            <div class="mb-3">
                <h1 class="fw-semibold" style="font-size: 28px; line-height: 40px;">
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Provident iste quod autem distinctio, laborum velit.
                </h1>
            </div>
            <div class="w-100 mb-3">
                <img class="w-100" src="https://placehold.co/600x300" alt="Card image cap">
            </div>
            <div class="mb-3">
                <p class="text-black fs-5" style="line-height: 30px;">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo asperiores nam illo, sit voluptate qui magnam corrupti fugiat in soluta omnis, aliquam veritatis harum nemo illum distinctio assumenda quaerat. Dolorem quaerat incidunt maxime fugit, nesciunt repudiandae dolor impedit officiis corporis, sunt nemo corrupti odio asperiores nisi architecto voluptatibus natus laborum.
                </p>
            </div>
        </div>
    </div>
    <div class="container mt-5 px-3 px-md-0">
        <div class="row justify-content-center justify-content-md-around">
            <div class="col-12 d-flex justify-content-between">
                <h5>Related Post</h5>
                <a href="/newsroom" class="border px-3 py-2 rounded">View All</a>
            </div>
            <div class="col-12 col-md-5 col-lg-3 my-2">
                <a href="/news-details" class="p-2 w-100">
                    <div class="row justify-content-center gap-3 felx-wrap mb-2">
                        <div class="card border my-2" style="width: 18rem;">
                            <img class="card-img-top" src="https://placehold.co/400x400" alt="Card image cap">
                            <div class="card-body d-flex flex-column align-items-center gap-2">
                                <h5 class="card-title">News Title</h5>
                                <p class="card-text custom-ellipsis" style="">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum
                                    maxime rem assumenda asperiores ut? Voluptate molestias quidem praesentium voluptatibus
                                    quo ad soluta est voluptatum laborum exercitationem reprehenderit vel, sequi
                                    facilis!
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-5 col-lg-3 my-2">
                <a href="/news-details" class="p-2 w-100">
                    <div class="row justify-content-center gap-3 felx-wrap mb-2">
                        <div class="card border my-2" style="width: 18rem;">
                            <img class="card-img-top" src="https://placehold.co/400x400" alt="Card image cap">
                            <div class="card-body d-flex flex-column align-items-center gap-2">
                                <h5 class="card-title">News Title</h5>
                                <p class="card-text custom-ellipsis" style="">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum
                                    maxime rem assumenda asperiores ut? Voluptate molestias quidem praesentium voluptatibus
                                    quo ad soluta est voluptatum laborum exercitationem reprehenderit vel, sequi
                                    facilis!
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-5 col-lg-3 my-2">
                <a href="/news-details" class="p-2 w-100">
                    <div class="row justify-content-center gap-3 felx-wrap mb-2">
                        <div class="card border my-2" style="width: 18rem;">
                            <img class="card-img-top" src="https://placehold.co/400x400" alt="Card image cap">
                            <div class="card-body d-flex flex-column align-items-center gap-2">
                                <h5 class="card-title">News Title</h5>
                                <p class="card-text custom-ellipsis" style="">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum
                                    maxime rem assumenda asperiores ut? Voluptate molestias quidem praesentium voluptatibus
                                    quo ad soluta est voluptatum laborum exercitationem reprehenderit vel, sequi
                                    facilis!
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-5 col-lg-3 my-2">
                <a href="/news-details" class="p-2 w-100">
                    <div class="row justify-content-center gap-3 felx-wrap mb-2">
                        <div class="card border my-2" style="width: 18rem;">
                            <img class="card-img-top" src="https://placehold.co/400x400" alt="Card image cap">
                            <div class="card-body d-flex flex-column align-items-center gap-2">
                                <h5 class="card-title">News Title</h5>
                                <p class="card-text custom-ellipsis" style="">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum
                                    maxime rem assumenda asperiores ut? Voluptate molestias quidem praesentium voluptatibus
                                    quo ad soluta est voluptatum laborum exercitationem reprehenderit vel, sequi
                                    facilis!
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection