
@section('seo-meta')
    @php
        $seoMeta = App\Models\SeoMeta::where('page_name', 'Blogs')->first();
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
    <div class="main-blogs-page">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-2/3 md:pr-8">
                    <!-- First Blog Post with Big Image -->
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                        <img alt="A detailed description of the first blog post image" class="w-full h-auto rounded-lg mb-4" src="https://placehold.co/800x400" />
                        <h2 class="text-2xl font-bold mb-2">
                            First Blog Post
                        </h2>
                        <p class="text-gray-600 mb-4">
                            January 1, 2023 by
                            <a class="text-blue-500" href="#">
                                Author
                            </a>
                        </p>
                        <p class="text-gray-700 mb-4">
                            This is the content of the first blog post. It contains some interesting information about a particular topic.
                        </p>
                        <a class="text-blue-500 hover:underline" href="#">
                            Read more
                        </a>
                    </div>
                    <!-- Smaller Blog Posts -->
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6 flex">
                        <img alt="" style="height: 200px !important;" class=" rounded-lg mr-4" src="https://placehold.co/100x100" />
                        <div>
                            <h2 class="text-xl font-bold mb-2">
                                Second Blog Post
                            </h2>
                            <p class="text-gray-600 mb-2">
                                February 1, 2023 by
                                <a class="text-blue-500" href="#">
                                    Author
                                </a>
                            </p>
                            <p class="text-gray-700 line-clamp-2">
                                This is the content of the second blog post. It contains some more interesting information about another topic.
                            </p>
                            <a class="text-blue-500 hover:underline" href="#">
                                Read more
                            </a>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6 flex">
                        <img alt="" style="height: 200px !important;" class="w-1/3 h-auto rounded-lg mr-4" src="https://placehold.co/200x200" />
                        <div>
                            <h2 class="text-xl font-bold mb-2">
                                Third Blog Post
                            </h2>
                            <p class="text-gray-600 mb-2">
                                March 1, 2023 by
                                <a class="text-blue-500" href="#">
                                    Author
                                </a>
                            </p>
                            <p class="text-gray-700 line-clamp-2">
                                This is the content of the third blog post. It contains even more interesting information about yet another topic.
                            </p>
                            <a class="text-blue-500 hover:underline" href="#">
                                Read more
                            </a>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6 flex">
                        <img alt="" style="height: 200px !important;" class="w-1/3 h-auto rounded-lg mr-4" src="https://placehold.co/200x200" />
                        <div>
                            <h2 class="text-xl font-bold mb-2">
                                Third Blog Post
                            </h2>
                            <p class="text-gray-600 mb-2">
                                March 1, 2023 by
                                <a class="text-blue-500" href="#">
                                    Author
                                </a>
                            </p>
                            <p class="text-gray-700 line-clamp-2">
                                This is the content of the third blog post. It contains even more interesting information about yet another topic.
                            </p>
                            <a class="text-blue-500 hover:underline" href="#">
                                Read more
                            </a>
                        </div>
                    </div>
                </div>
                <!-- <div class="md:w-1/3">
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                        <h4 class="text-xl font-bold mb-4">
                            About
                        </h4>
                        <p class="text-gray-700">
                            This is a blog about various interesting topics. Stay tuned for more updates!
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                        <h4 class="text-xl font-bold mb-4">
                            Archives
                        </h4>
                        <ul class="list-disc list-inside text-gray-700">
                            <li>
                                <a class="text-blue-500" href="#">
                                    January 2023
                                </a>
                            </li>
                            <li>
                                <a class="text-blue-500" href="#">
                                    February 2023
                                </a>
                            </li>
                            <li>
                                <a class="text-blue-500" href="#">
                                    March 2023
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                        <h4 class="text-xl font-bold mb-4">
                            Elsewhere
                        </h4>
                        <ul class="list-disc list-inside text-gray-700">
                            <li>
                                <a class="text-blue-500" href="#">
                                    GitHub
                                </a>
                            </li>
                            <li>
                                <a class="text-blue-500" href="#">
                                    Twitter
                                </a>
                            </li>
                            <li>
                                <a class="text-blue-500" href="#">
                                    Facebook
                                </a>
                            </li>
                        </ul>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="container d-flex justify-content-center justify-content-md-end mt-5">
            <nav aria-label="...">
                <ul class="pagination" style="color: black">
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

@push('style_css')
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<style type="text/css">
    .my-actives span {
        background-color: #dc3545 !important;
        color: white !important;
        border-color: #dc3545 !important;
    }

    .pager li {
        font-size: 18px !important;
    }

    .btn-primary {
        background-color: #ff0000;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 600px;
        padding: 14px 28px;
        border: 1px solid#ff0000;
        text-transform: uppercase;
        -webkit-transition: none;
        -moz-transition: none;
        transition: none;
        -webkit-transition: all 0.5s ease;
        -ms-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        transition: all 0.5s ease;
    }
</style>
@endpush