
@section('seo-meta')
    @php
        $seoMeta = App\Models\SeoMeta::where('page_name', 'PrivacyPolicyPage')->first();
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

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>Privacy Policy</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / Privacy Policy</h6>
					</div>
				</div>
			</div>
		</div>
    </div>

   <!-- Contact Area -->
	<div class="contact-section section-padding">
		<div class="container registrationContainer">
			<div class="row">
				<div class="col-md-12  col-sm-12" style="margin-top: 10px;">
                    @if ($privacyPolicyPage)
                        {!! $privacyPolicyPage->long_details !!}
                    @endif
				</div> 
			</div>
		</div>
	</div>


@endsection

@push('style_css')
    <style>
        #contactForm{
            font-size: 15px;
        }
        .contact-form{
            background-color: rgb(236, 236, 236);
            margin-top: 10px;
            padding: 16px 5px 16px 10px;
        }

        .contact-form input, .contact-form textarea{
            margin-bottom: 0px;
        }
        .form-control{
            padding: 8px 8px;
            font-size: 0.79rem;
            line-height: 1;
            border: 1px solid #c1c2c4;
        }
        .select2-results__option{
            padding: 1px;
        }
        .select2-results__options{
            font-size: 14px;
        }
        .btn-primary.submit:hover{
            background-color: #61B334;
            color: #fffdfd;
        }
        .btn-primary.submit{
            padding : 6px 16px;
        }

        @media (min-width:1200px) {
            .registrationContainer {
                max-width: 1300px !important;
            }
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered{
            font-size : 12px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    {{--Sweet Alert--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.css" rel="stylesheet" type="text/css">

@endpush

 @push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.js"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function(){
     

        });
    </script>
 @endpush
