
@section('seo-meta')
    @php
        $seoMeta = App\Models\SeoMeta::where('page_name', 'Prices')->first();
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

    <div class="prices-main-page bg-white">
        <div class="common-hero-section text-center">
            <h3 class="mb-4">
                Our Service Prices
            </h3>
            <p class="mb-4" style="color: #6C757D;">
                We are always on the lookout for dynamic, growth-oriented <br> individuals willing to step out of their comfort zone and perform <br> beyond the ordinary.
            </p>
            <div class="row justify-content-center gap-2 bg-transparent mt-4">
                <a class="col-6 col-sm-3 col-lg-auto py-2 bg-white px-lg-4 shadow rounded-1 text-black fw-semibold">
                    <i class="fa-solid fa-bars" style="margin-right: 7px"></i>
                    Discover More
                </a>
                <a class="col-5 col-sm-3 col-lg-1 py-2 shadow rounded-1 text-white fw-semibold" style="background-color: #F27B21 !important; box-shadow: rgba(0, 0, 0, 0.15) 0px 1px 5px 0px !important;">
                    <i class="fa-solid fa-phone" style="margin-right: 7px"></i>
                    Contact Us
                </a>
            </div>
        </div>
        <div class="prices-2nd-section my-4">
            <div class="container">
                <div class="px-3">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item m-1" role="presentation">
                            <button class="nav-link active border" id="pills-1-tab" data-bs-toggle="pill" data-bs-target="#pills-1"
                                type="button" role="tab" aria-controls="pills-1" aria-selected="true">Same City Delivery</button>
                        </li>
                        <li class="nav-item m-1" role="presentation">
                            <button class="nav-link border py-2 px-3" id="pills-2-tab" data-bs-toggle="pill" data-bs-target="#pills-2"
                                type="button" role="tab" aria-controls="pills-2" aria-selected="false">Suburbs Delivery</button>
                        </li>
                        <li class="nav-item m-1" role="presentation">
                            <button class="nav-link border p-2 px-3" id="pills-3-tab" data-bs-toggle="pill" data-bs-target="#pills-3"
                                type="button" role="tab" aria-controls="pills-3" aria-selected="false">Inter City Dhaka</button>
                        </li>
                        <li class="nav-item m-1" role="presentation">
                            <button class="nav-link border p-2 px-3" id="pills-4-tab" data-bs-toggle="pill" data-bs-target="#pills-4"
                                type="button" role="tab" aria-controls="pills-4" aria-selected="false">Intercity outside dhaka</button>
                        </li>
                    </ul>
                    <div class="tab-content py-5" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-1" role="tabpanel" aria-labelledby="pills-1-tab">
                            
                            
                            <div class="bg-white">
                                <h2 class="h5 mb-4">Pickup & Delivery Same District/City</h2>
                                <table class="table table-bordered overflow-x-auto">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th scope="col">Delivery Time</th>
                                            <th scope="col">Upto 500 gm</th>
                                            <th scope="col">500 gm to 1 Kilo</th>
                                            <th scope="col">1 Kilo to 2 Kilo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr class="bg-white">
                                            <td class="text-center">24 Hours</td>
                                            <td class="text-center">BDT 60</td>
                                            <td class="text-center">BDT 70</td>
                                            <td class="text-center">BDT 90</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <ul class="mt-3 text-muted">
                                    <li>1% COD charge will be applicable</li>
                                    <li>This price/plan is exclusive of any VAT/TAX.</li>
                                    <li>For weight more than 2KG, additional 15TK/Per KG (SAME CITY) will be applicable.</li>
                                </ul>
                            </div>
    
                        </div>
                        <div class="tab-pane fade" id="pills-2" role="tabpanel" aria-labelledby="pills-2-tab">
                            
                            <div class="bg-white">
                                <h2 class="h5 mb-4">Pickup from Dhaka Metropolitan City & Delivery- Narayanganj, Gazipur, Keranignaj, Savar</h2>
                                <table class="table table-bordered">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th scope="col">Delivery Time</th>
                                            <th scope="col">Upto 500 gm</th>
                                            <th scope="col">500 gm to 1 Kilo</th>
                                            <th scope="col">1 Kilo to 2 Kilo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr class="bg-white">
                                            <td class="text-center">72 Hours</td>
                                            <td class="text-center">BDT 80</td>
                                            <td class="text-center">BDT 100</td>
                                            <td class="text-center">BDT 130</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <ul class="mt-3 text-muted">
                                    <li>1% COD charge will be applicable</li>
                                    <li>This price/plan is exclusive of any VAT/TAX.</li>
                                    <li>For weight more than 2KG, additional 25TK/Per KG will be applicable.</li>
                                </ul>
                            </div>
    
                        </div>
                        <div class="tab-pane fade" id="pills-3" role="tabpanel" aria-labelledby="pills-3-tab">
                            
                            <div class="bg-white">
                                <h2 class="h5 mb-4">Inside Dhaka & Suburbs to Outside Dhaka Delivery & Vice Versa</h2>
                                <table class="table table-bordered">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th scope="col">Delivery Time</th>
                                            <th scope="col">Upto 500 gm</th>
                                            <th scope="col">500 gm to 1 Kilo</th>
                                            <th scope="col">1 Kilo to 2 Kilo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr class="bg-white">
                                            <td class="text-center">72 Hours</td>
                                            <td class="text-center">BDT 110</td>
                                            <td class="text-center">BDT 130</td>
                                            <td class="text-center">BDT 170</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <ul class="mt-3 text-muted">
                                    <li>1% COD charge on the price of the product.</li>
                                    <li>This price/plan is exclusive of any VAT/TAX.</li>
                                    <li>For weight more than 2KG, additional 25TK/Per KG (INTER CITY) will be applicable.</li>
                                </ul>
                            </div>
    
                        </div>
                        <div class="tab-pane fade" id="pills-4" role="tabpanel" aria-labelledby="pills-4-tab">
                            
                            <div class="bg-white">
                                <h2 class="h5 mb-4">Outside Dhaka to Outside Dhaka Delivery (Pickup & Delivery Different City)</h2>
                                <table class="table table-bordered">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th scope="col">Delivery Time</th>
                                            <th scope="col">Upto 500 gm</th>
                                            <th scope="col">500 gm to 1 Kilo</th>
                                            <th scope="col">1 Kilo to 2 Kilo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <tr class="bg-white">
                                            <td class="text-center">72 Hours</td>
                                            <td class="text-center">BDT 120</td>
                                            <td class="text-center">BDT 145</td>
                                            <td class="text-center">BDT 180</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <ul class="mt-3 text-muted">
                                    <li>1% COD charge will be applicable</li>
                                    <li>For weight more than 2KG, additional 25TK/Per KG will be applicable</li>
                                    <li>This price/plan is exclusive of any VAT/TAX.</li>
                                </ul>
                            </div>
    
                        </div>
                    </div>                
                </div>
            </div>
        </div>
        <div class="price-3rd-section my-3">
            <div class=" container px-3 px-md-0 blog-section">
                <div class="w-100 mb-3
                 d-flex justify-content-between">
                    <h5>Related Post</h5>
                    <a href="/newsroom" class="border px-3 py-2 rounded">View All</a>
                </div>
                <div class="row gap-5 gap-md-2 flex-column flex-md-row justify-content-start align-items-center">
                    <div class="card col" style="width: 18rem;">
                        <img class="card-img-top" src="https://placehold.co/600x400" alt="Card image cap">
                        <div class="card-body p-2">
                            <span class="card-title fw-bold fs-6">Card title</span>
                            <p class="card-text custom-ellipsis">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        </div>
                    </div>
                    <div class="card col" style="width: 18rem;">
                        <img class="card-img-top" src="https://placehold.co/600x400" alt="Card image cap">
                        <div class="card-body p-2">
                            <span class="card-title fw-bold fs-6">Card title</span>
                            <p class="card-text custom-ellipsis">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        </div>
                    </div>
                    <div class="card col" style="width: 18rem;">
                        <img class="card-img-top" src="https://placehold.co/600x400" alt="Card image cap">
                        <div class="card-body p-2">
                            <span class="card-title fw-bold fs-6">Card title</span>
                            <p class="card-text custom-ellipsis">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="container text-box-seo pt-5 pb-2 px-3">
                <p class="fs-5" style=" line-height: 27px; color: #000000c2; word-spacing: 2px; letter-spacing: -1px; font-weight: 500;">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde ipsum praesentium omnis at veniam? Excepturi rerum saepe quas, provident ipsum sequi cum reiciendis eligendi quam optio quos tempora facilis? Assumenda temporibus perferendis laboriosam ut nobis itaque ex earum, fuga a nisi unde placeat repellat autem rerum rem? Unde, repellat rerum. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Fugiat porro dignissimos ducimus necessitatibus vero dolorum quas saepe tenetur. Ea totam delectus quo quia voluptatibus aperiam autem quaerat! Sint assumenda ab dolorum, placeat dolor voluptates perspiciatis quaerat illo ipsum eveniet laborum consequatur explicabo, dignissimos, minus minima magni pariatur! Fuga, esse ea?
                </p>
            </div>
        </div>

    </div>

@endsection