
@section('seo-meta')
    @php
        $seoMeta = App\Models\SeoMeta::where('page_name', 'NewsRoom')->first();
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

<div class="main-newsroom-page">
    <div class="container mt-4 mb-2">
        <h4 class=" fw-bold f">Our Newsroom</h4>
    </div>
    <div class="container">
        <div class="row">
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
    <div class="container d-flex justify-content-center justify-content-md-end mt-5">
        <nav aria-label="...">
            <ul class="pagination">
              <li class="page-item disabled">
                <a class="page-link">Previous</a>
              </li>
              <li class="page-item active"><a class="page-link" href="/news-details">1</a></li>
              <li class="page-item" aria-current="page">
                <a class="page-link" href="/news-details">2</a>
              </li>
              <li class="page-item"><a class="page-link" href="/news-details">3</a></li>
              <li class="page-item">
                <a class="page-link" href="/news-details">Next</a>
              </li>
            </ul>
        </nav>
    </div>
</div>

@endsection