@section('seo-meta')
@php
$seoMeta = App\Models\SeoMeta::where('page_name', 'About')->first();
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

<div class="main-about-page" style="background-color: white !important;">
	<div class="hero-section2">
		<div class="container w-100 row justify-content-center align-items-center py-4" style="min-width: 100% !important;">
			<div class="col-md-4">
				<h1 class="text-white text-md-left fw-bold mb-5" style="font-family: sans-serif !important;">About Parceldex</h1>
				<p class="text-white text-md-left">
					Parceldex is an end-to-end logistics and supply chain solutions provider for the e-commerce industry. To provide the best service possible, we’re dedicated to bringing you the most innovative logistics services, and allowing you to build up your online business in a way that is suited to you. TRAX offers the quickest deliveries and most reliable inventory management solutions with warehouses across Pakistan, along with the fastest transfer of funds and the most responsive customer experience
				</p>
				<div class="row gap-2 bg-transparent mt-4">
					<button class="col-5 col-md-4 py-2 shadow rounded-1 text-white fw-bold" style="background-color: #F27B21 !important; box-shadow: rgba(0, 0, 0, 0.15) 0px 1px 5px 0px !important;">
						<i class="fa-solid fa-bars" style="margin-right: 7px"></i>
						Discover More
					</button>
					<button class="col-4 col-md-3 py-2 bg-white shadow rounded-1 text-black fw-bold">
						<i class="fa-solid fa-phone" style="margin-right: 7px"></i>
						Contact Us
					</button>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-4">
				<img width="300px" src="/frontend/images/rider.png">
			</div>
		</div>
	</div>

	<div class="about-2nd-section container row justify-content-center gap-5 align-items-center py-5 px-2">
		<div class="col-11 col-sm-10 col-lg-3 p-4 d-flex flex-column gap-3">
			<i class="fa-solid fa-chart-line"></i>
			<strong class="fs-4">Mision</strong>
			<p>
				We believe in delivering the best logistics services throughout an exponentially growing network across the globe. Along with other innovations across the e-commerce supply chain, we are looking forward to cultivate everlasting relations with all leading online retail.
			</p>
		</div>
		<div class="col-11 col-sm-10 col-lg-3 p-4 d-flex flex-column gap-3">
			<i class="fa-regular fa-circle-check"></i>
			<strong class="fs-4">Vision</strong>
			<p>
				To be the most trusted connectivity & mobility partner for humanity
			</p>
		</div>
	</div>

	<div class="about-3nd-section container my-5">
		<h3 class="fw-bold text-center mb-2" style="font-family: sans-serif !important;">Core Values</h3>
		<p class="text-center mb-2">Parceldex operates on a set of defined principles that are applied <br> to every project and service we take under our wing.</p>
		<div class="row justify-content-center">
			<div class="col-11 col-md-6 col-lg-3 cards p-2">
				<div class=" d-flex flex-column p-3 gap-3">
					<svg width="100px" height="100px" viewBox="0 0 1024.00 1024.00" fill="#000000" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="10.24">
						<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
						<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="12.288"></g>
						<g id="SVGRepo_iconCarrier">
							<path d="M545.5395 1023.356c-12.29 0-24.57-4.678-33.922-14.022a7.988 7.988 0 0 1 0-11.304 7.992 7.992 0 0 1 11.306 0c12.49 12.474 32.79 12.458 45.25-0.016 12.454-12.468 12.446-32.752-0.032-45.218a7.988 7.988 0 0 1 0-11.304 7.988 7.988 0 0 1 11.304 0c18.706 18.69 18.72 49.114 0.032 67.828-9.354 9.35-21.652 14.036-33.938 14.036zM624.7095 944.236c-12.828 0-24.882-4.996-33.946-14.068a7.988 7.988 0 0 1 0-11.304 7.988 7.988 0 0 1 11.304 0c12.07 12.078 33.14 12.11 45.218 0.032 12.468-12.466 12.46-32.76-0.016-45.234l-62.176-62.208a7.992 7.992 0 1 1 11.304-11.304l62.176 62.208c18.714 18.706 18.714 49.146 0.016 67.844-9.038 9.046-21.074 14.034-33.88 14.034zM703.8195 865.08c-12.29 0-24.568-4.676-33.922-14.03l-62.176-62.178a7.992 7.992 0 1 1 11.304-11.304l62.176 62.178c12.476 12.474 32.76 12.474 45.234 0 12.476-12.476 12.476-32.776 0-45.252l-62.192-62.16c-3.124-3.122-3.124-8.182 0-11.304s8.184-3.124 11.304 0l62.192 62.16c18.714 18.708 18.714 49.156 0 67.862-9.35 9.352-21.63 14.028-33.92 14.028zM782.9675 785.932c-12.28 0-24.558-4.67-33.914-14.008l-62.2-62.214a7.992 7.992 0 0 1 0-11.304 7.988 7.988 0 0 1 11.304 0l62.192 62.208c12.476 12.446 32.76 12.46 45.22 0 12.474-12.476 12.474-32.774 0-45.25L596.4175 506.222a7.988 7.988 0 0 1 0-11.304 7.988 7.988 0 0 1 11.304 0l209.154 209.138c18.712 18.708 18.712 49.154 0 67.86-9.336 9.346-21.618 14.016-33.908 14.016zM195.5575 666.348a7.988 7.988 0 0 1-7.128-4.366c-34.624-67.914-46.016-134.68-46.484-137.492a7.996 7.996 0 0 1 6.566-9.196c4.396-0.656 8.47 2.224 9.204 6.566 0.11 0.672 11.5 67.234 44.954 132.864a7.988 7.988 0 0 1-7.112 11.624zM434.9495 381.682a7.964 7.964 0 0 1-3.084-0.618c-55.508-23.242-93.164-42.814-93.538-43.01a8.004 8.004 0 0 1-3.388-10.79 7.974 7.974 0 0 1 10.79-3.388c0.368 0.196 37.466 19.48 92.304 42.432a8 8 0 0 1-3.084 15.374zM814.9335 676.154a7.992 7.992 0 0 1-6.504-12.64c47.724-66.914 56.922-162.708 57.008-163.668 0.406-4.388 4.176-7.682 8.69-7.238a8 8 0 0 1 7.236 8.69c-0.376 4.09-9.704 101.104-59.91 171.508a8.002 8.002 0 0 1-6.52 3.348zM477.7015 1000.76h-0.008c-12.804 0-24.85-4.996-33.906-14.05-18.69-18.684-18.69-49.118-0.008-67.838l33.922-33.922c18.144-18.126 49.732-18.112 67.83 0.016 18.698 18.698 18.698 49.13 0 67.83l-33.9 33.898c-9.072 9.07-21.12 14.066-33.93 14.066z m33.922-113.86a31.83 31.83 0 0 0-22.626 9.362l-33.906 33.906c-12.452 12.484-12.46 32.776 0 45.236a31.756 31.756 0 0 0 22.602 9.368h0.008c8.542 0 16.576-3.332 22.624-9.382l33.9-33.898c12.466-12.468 12.466-32.754 0-45.22a31.72 31.72 0 0 0-22.602-9.372zM398.5515 921.62c-12.804 0-24.842-4.988-33.898-14.044-18.69-18.712-18.69-49.146 0-67.86l45.22-45.22c18.128-18.104 49.748-18.12 67.828 0 18.706 18.708 18.706 49.14 0 67.846l-45.218 45.218c-9.066 9.064-21.12 14.06-33.932 14.06z m45.25-125.172a31.792 31.792 0 0 0-22.624 9.352l-45.22 45.22c-12.46 12.474-12.46 32.776 0 45.25a31.746 31.746 0 0 0 22.594 9.362 31.82 31.82 0 0 0 22.626-9.376l45.218-45.218c12.468-12.468 12.468-32.768 0-45.236a31.7 31.7 0 0 0-22.594-9.354zM319.4175 842.462c-12.82 0-24.858-4.98-33.9-14.04-9.064-9.046-14.06-21.094-14.068-33.906a47.664 47.664 0 0 1 14.038-33.938l33.93-33.914c18.12-18.136 49.74-18.122 67.844 0.016 9.064 9.042 14.054 21.08 14.054 33.898 0 12.804-4.99 24.852-14.054 33.914l-33.93 33.93c-9.064 9.06-21.11 14.04-33.914 14.04z m33.916-113.858a31.756 31.756 0 0 0-22.61 9.368l-33.93 33.914a31.77 31.77 0 0 0-9.352 22.624 31.808 31.808 0 0 0 9.384 22.61c12.054 12.07 33.118 12.07 45.204 0l33.93-33.93a31.772 31.772 0 0 0 9.37-22.61c0-8.54-3.326-16.558-9.362-22.586a31.778 31.778 0 0 0-22.634-9.39zM240.2775 763.33h-0.008c-12.812 0-24.85-4.982-33.898-14.04-18.7-18.712-18.7-49.144 0-67.844l22.61-22.61c18.12-18.128 49.692-18.098 67.812 0.016 9.064 9.062 14.054 21.102 14.062 33.906s-4.982 24.844-14.046 33.906l-22.61 22.61c-9.066 9.066-21.112 14.056-33.922 14.056z m22.594-102.548a31.74 31.74 0 0 0-22.586 9.362l-22.61 22.61c-12.46 12.468-12.46 32.758 0 45.234a31.734 31.734 0 0 0 22.594 9.354h0.008c8.542 0 16.574-3.326 22.618-9.37l22.61-22.61c6.042-6.042 9.362-14.068 9.362-22.602s-3.334-16.558-9.376-22.602c-6.054-6.042-14.08-9.376-22.62-9.376zM409.8875 548.506c-12.29 0-24.578-4.684-33.93-14.038-18.698-18.698-18.698-49.114 0-67.812l73.482-73.512c68.922-68.922 224.082-91.758 230.646-92.702 4.326-0.696 8.418 2.404 9.05 6.784a7.998 7.998 0 0 1-6.786 9.048c-1.562 0.218-156.464 23.03-221.608 88.174l-73.48 73.512c-12.46 12.46-12.46 32.744 0 45.204 12.468 12.476 32.75 12.476 45.22 0.016 4.154-4.154 102.328-101.134 152.63-50.886a7.992 7.992 0 0 1 0 11.304 7.988 7.988 0 0 1-11.304 0c-32.432-32.384-104.852 25.732-130.022 50.886-9.344 9.346-21.624 14.022-33.898 14.022zM907.3315 497.26a7.96 7.96 0 0 1-5.652-2.342L686.8715 280.096a7.992 7.992 0 0 1 0-11.304 7.988 7.988 0 0 1 11.304 0l214.808 214.822a7.992 7.992 0 0 1-5.652 13.646zM986.4795 463.33a7.96 7.96 0 0 1-5.652-2.342L720.7855 200.962a7.992 7.992 0 0 1 0-11.304 7.988 7.988 0 0 1 11.304 0l260.042 260.026a7.992 7.992 0 0 1-5.652 13.646z" fill=""></path>
							<path d="M692.5235 282.438a7.992 7.992 0 0 1-5.652-13.646l33.914-33.916a7.992 7.992 0 1 1 11.304 11.304l-33.914 33.916a7.96 7.96 0 0 1-5.652 2.342zM907.3315 497.26a7.96 7.96 0 0 1-5.652-2.342 7.988 7.988 0 0 1 0-11.304l33.914-33.916a7.992 7.992 0 1 1 11.304 11.306l-33.914 33.914a7.968 7.968 0 0 1-5.652 2.342zM726.4375 203.304a7.992 7.992 0 0 1-5.652-13.646l186.672-186.67a7.992 7.992 0 1 1 11.304 11.304l-186.672 186.67a7.96 7.96 0 0 1-5.652 2.342zM986.4795 463.33a7.96 7.96 0 0 1-5.652-2.342 7.988 7.988 0 0 1 0-11.304l29.528-29.496a7.992 7.992 0 1 1 11.304 11.304l-29.528 29.496a7.966 7.966 0 0 1-5.652 2.342zM115.9175 519.838a7.992 7.992 0 0 1-5.654-13.646L325.0855 291.4c3.122-3.124 8.182-3.124 11.304 0s3.124 8.182 0 11.304L121.5695 517.496a7.978 7.978 0 0 1-5.652 2.342zM36.7835 485.94a7.966 7.966 0 0 1-5.652-2.342 7.988 7.988 0 0 1 0-11.304l260.042-260.026a7.988 7.988 0 0 1 11.304 0 7.988 7.988 0 0 1 0 11.304L42.4355 483.598a7.968 7.968 0 0 1-5.652 2.342z" fill=""></path>
							<path d="M115.9175 519.838a7.98 7.98 0 0 1-5.654-2.342l-33.914-33.898c-3.124-3.124-3.124-8.182 0-11.304s8.182-3.124 11.304 0l33.916 33.898a7.992 7.992 0 0 1-5.652 13.646zM330.7395 305.046a7.966 7.966 0 0 1-5.652-2.342l-33.916-33.914a7.988 7.988 0 0 1 0-11.304 7.988 7.988 0 0 1 11.304 0l33.916 33.914a7.988 7.988 0 0 1 0 11.304 7.968 7.968 0 0 1-5.652 2.342zM36.7835 485.94a7.966 7.966 0 0 1-5.652-2.342l-28.2-28.2a7.994 7.994 0 0 1 11.304-11.306l28.2 28.2a7.988 7.988 0 0 1 0 11.304 7.962 7.962 0 0 1-5.652 2.344zM296.8235 225.914a7.978 7.978 0 0 1-5.652-2.342L81.9555 14.354c-3.124-3.124-3.124-8.182 0-11.304s8.182-3.124 11.304 0l209.216 209.216a7.988 7.988 0 0 1 0 11.304 7.964 7.964 0 0 1-5.652 2.344zM23.9635 415.784a23.848 23.848 0 0 1-16.974-7.026c-9.33-9.376-9.314-24.584 0.016-33.914 9.026-9.056 24.882-9.064 33.922 0.008a23.778 23.778 0 0 1 7.01 16.942 23.796 23.796 0 0 1-7.018 16.964 23.78 23.78 0 0 1-16.956 7.026z m0-31.978c-2.14 0-4.146 0.828-5.644 2.334a8.024 8.024 0 0 0-0.008 11.328c2.982 2.974 8.284 2.998 11.296-0.008a7.944 7.944 0 0 0 2.342-5.66c0-2.14-0.828-4.146-2.334-5.652a7.912 7.912 0 0 0-5.652-2.342z" fill=""></path>
						</g>
					</svg>
					<strong>Partnership</strong>
					<p>
						Choosing an efficient pathway of winning and celebrating as a team.
					</p>
				</div>
			</div>
			<div class="col-11 col-md-6 col-lg-3  cards p-2">
				<div class="d-flex flex-column p-3 gap-3">
					<svg version="1.1" id="PUZZLE" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100px" height="100px" viewBox="0 0 1800.00 1800.00" enable-background="new 0 0 1800 1800" xml:space="preserve" fill="#000000" stroke="#000000" stroke-width="0.018000000000000002">
						<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
						<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="140.4"></g>
						<g id="SVGRepo_iconCarrier">
							<path fill="#333333" d="M1794.799,86.801c0-45.832-38.102-83.119-84.936-83.119H901.35h-3.8H93.755 c-45.832,0-83.119,38.102-83.119,84.94v812.311c0,1.243,0.092,2.47,0.231,3.678c-0.14,1.208-0.231,2.424-0.231,3.677v807.594 c0,45.832,38.101,83.119,84.936,83.119h808.513h3.8h803.795c45.831,0,83.119-38.102,83.119-84.94V901.747 c0-1.243-0.093-2.469-0.232-3.677c0.14-1.208,0.232-2.428,0.232-3.675V86.801z M1732.043,86.801v776.217h-243.219 c33.027-37.121,52.45-85.802,52.45-137.973c0-41.45-12.262-80.067-33.282-112.504c-36.425-59.971-102.365-100.121-177.514-100.121 c-114.426,0-207.517,93.091-207.517,207.517c0.005,41.893,12.554,81.503,34.587,114.715c6.236,10.024,13.272,19.537,21.129,28.366 H932.727V524.099c0-10.37-5.047-19.532-12.799-25.249c-5.691-5.796-13.609-9.398-22.378-9.398h-9.739 c-13.483,0-25.455,8.61-29.745,21.393c-19.808,59.008-74.951,98.65-137.22,98.65c-23.59,0-45.862-5.708-65.561-15.764 c-43.652-24.956-73.156-71.948-73.156-125.733c0-79.827,64.939-144.771,144.763-144.771c22.955,0,44.921,5.427,64.527,15.189 c30.695,17.146,54.906,45.228,66.646,80.208c2.933,8.746,9.508,15.457,17.659,18.871c5.182,3.673,11.468,5.787,18.131,5.787h7.494 c17.33,0,31.377-14.042,31.377-31.377V66.437h777.137C1722.093,66.437,1732.043,75.573,1732.043,86.801z M73.392,88.622 c0-12.23,9.136-22.185,20.364-22.185h772.417v247.737c-13.447-12.178-28.418-22.575-44.58-30.861 c-30.126-16.726-64.584-26.107-100.747-26.107c-114.426,0-207.517,93.095-207.517,207.525c0,80.024,45.557,149.56,112.072,184.172 c30.029,16.915,64.641,26.61,101.491,26.61c52.805,0,101.994-19.926,139.281-53.694v247.732h-338.39 c-17.33,0-31.377,14.048-31.377,31.38v9.735c0,7.117,2.447,13.771,6.588,19.139c3.633,7.257,10.016,13.019,18.07,15.715 c31.597,10.61,57.555,31.412,74.938,57.948c13.067,21.914,20.447,47.364,20.447,74.163c0,79.827-64.939,144.762-144.762,144.762 c-50.271,0-94.618-25.766-120.581-64.769c-13.273-21.861-20.92-47.495-20.92-74.881c-0.004-62.264,39.646-117.412,98.659-137.224 c12.782-4.29,21.392-16.258,21.392-29.74v-7.494c0-5.953-1.685-11.489-4.557-16.229c-3.83-13.005-15.842-22.505-30.086-22.505 H73.392V88.622z M73.392,1715.881V939.665h243.219c-33.028,37.12-52.45,85.797-52.45,137.968c0,41.454,12.266,80.072,33.286,112.51 c36.42,59.97,102.362,100.119,177.51,100.119c114.426,0,207.517-93.09,207.517-207.517c-0.005-41.892-12.56-81.508-34.587-114.724 c-6.238-10.024-13.272-19.532-21.125-28.356h245.946v338.919c0,10.374,5.051,19.54,12.804,25.248 c5.69,5.796,13.609,9.395,22.373,9.395h9.74c13.482,0,25.455-8.606,29.744-21.388c19.809-59.008,74.951-98.65,137.22-98.65 c23.577,0,45.836,5.699,65.53,15.741c43.67,24.951,73.187,71.956,73.187,125.756c0,79.817-64.938,144.762-144.762,144.762 c-22.96,0-44.931-5.42-64.536-15.19c-30.69-17.142-54.894-45.228-66.639-80.203c-2.938-8.746-9.512-15.461-17.671-18.876 c-5.18-3.668-11.461-5.778-18.118-5.778h-7.495c-17.33,0-31.377,14.044-31.377,31.378v345.468H95.572 C83.341,1736.245,73.392,1727.104,73.392,1715.881z M1732.043,1714.06c0,12.231-9.135,22.186-20.363,22.186H939.263v-247.737 c13.443,12.17,28.414,22.57,44.571,30.852c30.125,16.731,64.589,26.117,100.755,26.117c114.427,0,207.518-93.1,207.518-207.526 c0-80.037-45.569-149.586-112.098-184.185c-30.024-16.906-64.624-26.598-101.465-26.598c-52.805,0-101.999,19.926-139.281,53.693 V933.124h338.39c17.33,0,31.377-14.043,31.377-31.377v-9.738c0-7.113-2.447-13.767-6.593-19.133 c-3.628-7.258-10.015-13.019-18.065-15.72c-31.597-10.607-57.559-31.408-74.938-57.949c-13.066-21.909-20.446-47.364-20.446-74.163 c0-79.823,64.938-144.762,144.762-144.762c50.266,0,94.618,25.761,120.581,64.764c13.268,21.861,20.919,47.5,20.919,74.889 c0.005,62.265-39.646,117.408-98.658,137.22c-12.782,4.29-21.393,16.262-21.393,29.745v7.494c0,5.952,1.686,11.484,4.557,16.23 c3.83,13,15.838,22.499,30.087,22.499h342.201V1714.06z"></path>
						</g>
					</svg>
					<strong>Intergrity</strong>
					<p>
						Openness of culture, with the spirit of sharing knowledge & learning.
					</p>
				</div>
			</div>
			<div class="col-11 col-md-6 col-lg-3 cards p-2">
				<div class="d-flex flex-column p-3 gap-3">
					<svg fill="#000000" height="100px" width="100px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 511.999 511.999" xml:space="preserve" stroke="#000000" stroke-width="2.5599950000000002">
						<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
						<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
						<g id="SVGRepo_iconCarrier">
							<g>
								<g>
									<path d="M393.262,65.329c-7.82-7.861-17.761-12.753-28.483-14.113l-0.013-11.483c-0.013-10.628-4.163-20.615-11.689-28.12 C345.567,4.123,335.593,0,324.987,0c-0.021,0-0.04,0-0.061,0c-13.336,0.019-25.15,6.635-32.357,16.748 c-0.882-1.083-1.824-2.127-2.826-3.127c-7.525-7.506-17.493-11.673-28.15-11.615c-12.654,0.019-24.062,6.062-31.369,15.464 c-7.288-9.623-18.835-15.802-31.732-15.802c-0.019,0-0.041,0-0.061,0c-16.091,0.024-29.971,9.651-36.208,23.441 c-6.168-5.029-14.048-7.986-22.603-7.986c-0.018,0-0.037,0-0.055,0c-19.664,0.029-35.644,16.051-35.621,35.715l0.053,47.078 c0.004,3.652,0.559,7.245,1.651,10.7l0.036,69.989c0.002,4.485,3.638,8.119,8.123,8.119c0.001,0,0.002,0,0.004,0 c4.486-0.002,8.121-3.641,8.119-8.127l-0.025-49.71c5.268,3.034,11.306,4.766,17.593,4.799l0.227,0.001 c12.043-0.018,22.72-6.026,29.203-15.196c7.337,8.119,17.91,13.157,29.427,13.218l0.253,0.001 c12.644-0.02,24.047-5.969,31.382-15.338c7.286,9.525,18.74,15.605,31.525,15.674h0.252c3.551-0.005,7.065-0.476,10.445-1.397 c0.367-0.1,0.716-0.232,1.056-0.377c2.521,0.56,5.094,0.838,7.704,0.839l26.28-0.16c0.03,0,0.061,0,0.09,0 c3.889,0,7.577,1.539,10.395,4.339c2.462,2.448,4.02,5.591,4.508,9.025l-18.673,26.435c-0.136,0.193-0.263,0.392-0.382,0.596 c-14.527,24.922-16.308,55.955-4.762,83.015c1.317,3.087,4.318,4.938,7.475,4.938c1.064,0,2.143-0.21,3.183-0.654 c4.127-1.761,6.044-6.533,4.284-10.66c-9.352-21.917-7.935-48,3.681-68.16l20.117-28.476c0.967-1.371,1.488-3.009,1.488-4.688 c0-8.696-3.362-16.828-9.467-22.895c-5.885-5.849-13.636-9.062-21.845-9.062c-0.064,0-0.128,0-0.193,0l-26.28,0.16 c-2.041,0.012-4.052-0.317-5.991-0.983c-0.568-0.194-1.123-0.416-1.667-0.665c-0.056-0.026-0.108-0.059-0.165-0.085 c-1.56-0.732-3.025-1.684-4.386-2.87c-0.029-0.026-0.058-0.053-0.088-0.079c-0.618-0.549-1.211-1.157-1.803-1.84 c-0.032-0.041-0.069-0.079-0.102-0.119c-0.074-0.088-0.145-0.178-0.223-0.262c-0.026-0.029-0.05-0.061-0.077-0.09 c-0.037-0.045-0.073-0.09-0.109-0.135c-0.133-0.168-0.262-0.34-0.39-0.513c-0.108-0.165-0.222-0.325-0.341-0.481 c-0.219-0.314-0.434-0.629-0.635-0.956c-0.013-0.021-0.026-0.041-0.039-0.063c-0.159-0.261-0.302-0.534-0.447-0.805 c-0.062-0.114-0.126-0.22-0.187-0.339c-0.06-0.128-0.122-0.255-0.188-0.379c-0.093-0.19-0.179-0.386-0.266-0.581 c-0.145-0.321-0.282-0.644-0.408-0.971c-0.108-0.279-0.221-0.557-0.316-0.84l-0.066-0.195c-0.006-0.021-0.014-0.04-0.021-0.061 c-0.138-0.425-0.262-0.856-0.367-1.282c-0.022-0.106-0.046-0.212-0.071-0.317c-0.083-0.362-0.158-0.727-0.223-1.095 c-0.001-0.008-0.003-0.014-0.004-0.022c0-0.001,0-0.001,0-0.002c0-0.001,0-0.002-0.001-0.003 c-0.002-0.013-0.005-0.027-0.008-0.041v0.004l-0.002-0.012c-0.01-0.05-0.017-0.097-0.027-0.147 c-0.001-0.003-0.002-0.007-0.002-0.01l-0.005-0.026c0-0.001,0-0.001,0-0.001c-0.073-0.452-0.127-0.908-0.169-1.367 c0-0.003-0.001-0.005-0.001-0.008l-0.023-0.29c0-0.003,0-0.006,0-0.01c-0.037-0.479-0.066-0.96-0.069-1.449l-0.063-9.701 l-0.001-0.129c-0.001-5.213,1.952-10.116,5.5-13.806l0.001-0.001c0.001-0.001,0.001-0.001,0.002-0.002 c0.416-0.433,0.864-0.857,1.332-1.26h0.001c1.144-0.982,2.394-1.808,3.716-2.48c6.022-0.81,35.826-4.395,82.537-3.186 c0.535,0.11,1.089,0.17,1.656,0.17h1.942c8.69,0,16.907,3.449,23.137,9.712c6.54,6.574,10.114,15.388,10.063,24.819l-0.259,48.43 c-0.044,8.314-2.148,16.542-6.081,23.796l-59.461,109.639c-0.659,1.214-0.996,2.576-0.982,3.957l0.339,32.619l-153.668-0.822 l1.086-21.757c0.09-1.805-0.425-3.589-1.463-5.07l-38.926-55.5c-5.161-8.133-8.568-17.514-9.854-27.143 c-0.595-4.447-4.683-7.572-9.127-6.976c-4.447,0.595-7.57,4.681-6.976,9.127c1.605,12.016,5.878,23.73,12.356,33.878 c0.064,0.1,0.129,0.197,0.197,0.294l37.429,53.364l-0.983,19.695l-21.349-0.114c-10.782,0-19.599,8.772-19.657,19.553 l-0.144,26.845c-0.028,5.251,1.991,10.197,5.683,13.931c3.308,3.344,7.605,5.341,12.239,5.744l-8.813,99.911 c-0.583,6.61,1.645,13.215,6.114,18.12c4.468,4.906,10.836,7.74,17.472,7.776l204.594,1.095c0.015,0,0.121,0.002,0.131,0 c6.497,0,12.79-2.703,17.264-7.418c4.474-4.716,6.845-11.144,6.504-17.636l-5.323-101.38c8.021-2.447,13.906-9.901,13.952-18.7 l0.143-26.844c0.028-5.251-1.99-10.199-5.683-13.932c-3.693-3.732-8.619-5.803-13.87-5.831l-23.078-0.123l-0.32-30.769 l58.456-107.789c5.205-9.597,7.988-20.475,8.047-31.454l0.259-48.43C408.127,87.906,402.873,74.992,393.262,65.329z M159.235,99.904c-0.015,10.756-8.778,19.52-19.5,19.537l-0.172-0.001c-7.748-0.041-14.82-4.806-17.873-11.928 c-0.076-0.34-0.166-0.677-0.286-1.006c-0.768-2.115-1.159-4.338-1.161-6.608L120.19,52.82c-0.012-10.709,8.691-19.435,19.4-19.45 c0.01,0,0.019,0,0.028,0c9.343,0,17.469,6.837,19.109,15.979l0.543,49.834C159.248,99.42,159.235,99.661,159.235,99.904z M198.617,117.463l-0.199-0.001c-10.945-0.057-20.438-7.729-22.902-18.342l-0.551-50.632l-0.008-7.015 c-0.015-12.972,10.526-23.541,23.499-23.56c0.012,0,0.024,0,0.036,0c12.383,0,22.658,9.63,23.47,21.961l0.024,57.083 C220.417,108.652,210.427,117.445,198.617,117.463z M268.855,54.196c-0.065,0.01-0.127,0.028-0.192,0.039 c-0.157,0.028-0.313,0.06-0.47,0.097c-0.128,0.03-0.253,0.066-0.379,0.103c-0.123,0.037-0.247,0.074-0.37,0.116 c-0.154,0.053-0.304,0.112-0.454,0.173c-0.06,0.025-0.119,0.042-0.179,0.068c-0.048,0.021-0.093,0.047-0.141,0.068 c-0.094,0.043-0.187,0.089-0.279,0.135c-2.959,1.354-5.724,3.109-8.214,5.246c-0.002,0.001-0.003,0.003-0.005,0.005 c-0.859,0.738-1.686,1.521-2.461,2.326c0,0,0,0-0.001,0c-0.001,0.001-0.002,0.002-0.003,0.003 c-0.002,0.002-0.003,0.004-0.005,0.005c-6.469,6.731-10.03,15.632-10.027,25.064c0,0.017,0,0.035,0,0.051l0.001,0.178l0.063,9.699 c0.005,0.798,0.042,1.618,0.115,2.58c0.001,0.012,0.003,0.023,0.004,0.035c0.001,0.013,0,0.025,0.001,0.038l0.04,0.487 c0,0.002,0.001,0.004,0.001,0.007c0.08,0.869,0.181,1.677,0.309,2.468c0,0.001,0,0.001,0,0.001c0,0.001,0,0.002,0,0.003 l0.069,0.387c0.001,0.004,0.002,0.009,0.003,0.013c0.002,0.015,0.007,0.035,0.01,0.05c0.001,0.008,0.001,0.014,0.002,0.022 c0.009,0.048,0.021,0.094,0.029,0.142c0.01,0.052,0.019,0.096,0.03,0.148c0.106,0.582,0.22,1.16,0.353,1.731 c0.002,0.008,0.004,0.015,0.005,0.023c0.003,0.014,0.008,0.033,0.012,0.048c0,0,0,0,0,0.001c0.004,0.019,0.009,0.037,0.01,0.041 c0,0.001,0.001,0.003,0.001,0.004c0.002,0.009,0.003,0.017,0.005,0.026l0.084,0.36c0.005,0.021,0.011,0.041,0.015,0.062 c0.182,0.726,0.395,1.47,0.634,2.215c0.01,0.031,0.021,0.063,0.03,0.093l0.001,0.002l0.062,0.187 c0.009,0.028,0.018,0.057,0.028,0.086l0.005,0.016c0,0.001,0.001,0.002,0.001,0.003l0.038,0.109l0.012,0.036 c0.005,0.017,0.012,0.035,0.017,0.052c0.109,0.321,0.222,0.639,0.34,0.956c0.001,0.001,0.001,0.003,0.001,0.004 c0.118,0.32,0.255,0.634,0.382,0.95c0.079,0.195,0.153,0.391,0.235,0.585c0.3,0.708,0.615,1.408,0.957,2.089l0.001,0.002 c0.001,0.001,0.001,0.002,0.001,0.004c0.005,0.01,0.012,0.019,0.017,0.029c0.008,0.014,0.013,0.028,0.019,0.042 c0.001,0.003,0.002,0.005,0.003,0.009l0.066,0.129l0.073,0.144c0.002,0.004,0.004,0.008,0.006,0.011l0.007,0.012 c0.001,0.001,0.002,0.003,0.003,0.004c0.139,0.266,0.275,0.516,0.412,0.766c-6.414-3.581-11.017-10.056-11.959-17.787 l-0.024-57.072c0.949-12.149,11.194-21.656,23.411-21.675c6.253-0.011,12.202,2.43,16.653,6.871 c4.451,4.441,6.908,10.349,6.914,16.637l0.013,10.63C275.024,53.265,269.397,54.113,268.855,54.196z M301.465,51.275 l-0.013-11.469c-0.014-12.972,10.528-23.542,23.498-23.56c0.013,0,0.024,0,0.036,0c6.274,0,12.175,2.439,16.619,6.871 c4.451,4.441,6.908,10.349,6.915,16.636l0.012,10.763C329.732,50.196,313.918,50.616,301.465,51.275z M357.181,493.397 c-1.442,1.518-3.387,2.355-5.479,2.355c-0.001,0-0.001,0-0.001,0l-0.001,8.123l-0.042-8.123l-204.594-1.095 c-2.138-0.012-4.109-0.888-5.549-2.469c-1.439-1.581-2.129-3.624-1.941-5.753l8.924-101.172l205.421,1.099l5.327,101.435 C359.355,489.889,358.622,491.879,357.181,493.397z M364.602,336.503c1.229,0.008,2.035,0.638,2.407,1.013 c0.371,0.376,0.993,1.188,0.986,2.417l-0.143,26.845c-0.01,1.872-1.54,3.394-3.41,3.393v0.495 c-0.007-0.002-0.013-0.004-0.019-0.007l-0.001-0.488l-2.747-0.015c0,0-0.001,0-0.002,0l-87.738-0.47l-140.013-0.75 c-1.229-0.006-2.035-0.637-2.407-1.012c-0.371-0.376-0.993-1.188-0.987-2.417l0.144-26.845c0.01-1.872,1.54-3.393,3.43-3.393 l29.008,0.155c0.001,0,0.002,0,0.003,0l72.974,0.391L364.602,336.503z"></path>
								</g>
							</g>
						</g>
					</svg>
					<strong>Passion</strong>
					<p>
						Aimed at taking quick initiatives and showing full ownership of the results.
					</p>
				</div>
			</div>
			<div class="col-11 col-md-6 col-lg-3 cards p-2">
				<div class="d-flex flex-column p-3 gap-3">
					<svg fill="#000000" width="100px" height="100px" viewBox="0 0 256 256" id="Layer_1" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" stroke="#000000" stroke-width="1.536">
						<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
						<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
						<g id="SVGRepo_iconCarrier">
							<g>
								<path d="M174,175.3c0.8,0.3,2.1,0.7,3.6,0.6c2.4-0.1,4.6-1.1,6.2-2.7c1.8-1.8,2.7-4.2,2.7-6.7c-0.2-15.1,9.4-28.3,23.7-32.8 c1-0.3,2-0.8,2.9-1.5c2-1.5,3.3-3.7,3.6-6c0.4-2.5-0.2-5-1.7-7.1c-9-12.1-9.1-28.4-0.1-40.5l1.1-1.4l0.5-1.5c0.8-2.4,0.5-5-0.6-7 c-1.1-2.3-3.1-4-5.5-4.7c-14.3-4.6-23.9-17.7-23.7-32.7c0-1.2-0.2-2.2-0.5-3.2c-0.8-2.3-2.5-4.2-4.8-5.4c-2.2-1-4.7-1.2-7-0.4 c-3.5,1.2-7.2,1.8-10.9,1.8c-11,0-21.4-5.3-27.7-14.3c-0.7-0.9-1.5-1.7-2.2-2.2c-4.2-3-10-2-13,2.2c-6.4,9-16.7,14.3-27.7,14.3 c-3.8,0-7.4-0.6-10.8-1.8c-3.2-1.1-7.3-0.3-9.7,2.2c-1.8,1.8-2.7,4.2-2.7,6.6c0.2,15.1-9.4,28.3-23.7,32.7c-1.1,0.3-2,0.8-3,1.5 c-4.1,3.1-4.9,8.9-1.9,13.1c9,12.1,9,28.4,0,40.6c-0.6,0.8-1.1,1.8-1.4,2.7c-0.7,2.4-0.5,4.8,0.6,7.2c1.2,2.2,3.2,3.8,5.4,4.5 c14.4,4.6,23.9,17.8,23.7,32.6c-0.1,1.2,0.1,2.3,0.5,3.4c0.8,2.3,2.5,4.2,4.8,5.4c2.3,1,4.7,1.2,7,0.4c3.6-1.2,7.4-1.8,11.1-1.8 c11.1,0,21.1,5.2,27.6,14.3c0.6,0.8,1.4,1.6,2.2,2.2c1.6,1.1,3.5,1.7,5.4,1.7c0.5,0,1.1,0,1.6-0.1c2.5-0.4,4.6-1.8,6-3.8 C144.3,175.6,159.7,170.5,174,175.3z M127.9,181.7c-8.2-11.3-21.3-18-35.2-18c-4.6,0-9.3,0.7-13.6,2.2c0-19-12.1-35.7-30-41.4 c11.2-15.4,11.2-35.9,0.1-51.3c18-5.9,30-22.4,30.1-41.4c4.3,1.4,8.8,2.1,13.4,2.1c13.9,0,27.1-6.7,35.3-17.9 c8.2,11.2,21.4,17.9,35.4,17.9c4.7,0,9.3-0.7,13.4-2.2c0,18.8,11.9,35.3,29.9,41.4c-11.1,15.4-11.1,35.8,0,51.3 c-18,5.9-29.9,22.4-30,41.4C158.6,160,139.2,166.3,127.9,181.7z"></path>
								<path d="M168.4,92.1c-15.3-6.4-27.3-18.3-33.8-33.6c-1.1-2.7-3.8-4.5-6.7-4.5c-2.9,0-5.6,1.8-6.7,4.4 c-6.5,15.2-18.4,27.1-33.6,33.6c-2.7,1.1-4.5,3.7-4.5,6.6c0,2.9,1.7,5.5,4.4,6.7c14.7,6.4,26.9,18.8,33.6,34 c1.2,2.7,3.8,4.4,6.6,4.4c0,0,0,0,0,0c2.9,0,5.5-1.7,6.6-4.4c6.4-14.8,18.8-27.1,34.1-33.9c2.6-1.2,4.3-3.8,4.3-6.7 C172.8,95.8,171.1,93.2,168.4,92.1z M127.8,130.6c-7.2-13.6-18.3-24.8-31.5-31.9c13.6-7,24.6-17.9,31.6-31.5 c7,13.7,18.1,24.7,31.8,31.6C146,106.1,134.8,117.3,127.8,130.6z"></path>
								<path d="M98.6,188.8c-2.3-1.5-5.3-0.8-6.8,1.4l-26.2,40.1l-13.1-26.9l-27.3-0.1l27.4-42c1.5-2.3,0.8-5.3-1.4-6.8 c-2.3-1.5-5.3-0.8-6.8,1.4L7.1,213.1l39.3,0.1l18,36.9l35.6-54.5C101.5,193.3,100.9,190.2,98.6,188.8z"></path>
								<path d="M211.5,155.9c-1.5-2.3-4.5-2.9-6.8-1.4c-2.3,1.5-2.9,4.5-1.4,6.8l27.5,42l-27.4,0.1l-13.1,26.9l-26.2-40.1 c-1.5-2.3-4.5-2.9-6.8-1.4c-2.3,1.5-2.9,4.5-1.4,6.8l35.7,54.5l18-36.9l39.3-0.1L211.5,155.9z"></path>
							</g>
						</g>
					</svg>
					<strong>Excellence</strong>
					<p>
						Striving towards superior performance, delivering lasting results.
					</p>
				</div>
			</div>
		</div>
	</div>


	<div class="container py-5">
		<div class="row align-items-center">
			<div class="col-md-6 mb-4 mb-md-0">
				<span class="badge badge-success">
					Testimonials
				</span>
				<h2 class="display-4 font-weight-bold text-dark mt-3">
					Let's hear it from our customers!
				</h2>
				<p class="text-muted mt-2">
					Here's what our customers are saying about us.
				</p>
			</div>
			<div class="col-md-6">

				<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
					<div class="carousel-indicators">
						<button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="0" class="active"></button>
						<button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="1"></button>
						<button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="2"></button>
					</div>
					<div class="carousel-inner">
						<div class="carousel-item active">
							<div class="d-block w-100">
								<div class="row justify-content-between">
									<div class="col-6 position-relative px-2">
										<img alt="Customer testimonial video thumbnail d-block w-100" class="img-fluid rounded shadow" height="144" src="https://placehold.co/600x400" />
										<div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0;">
											<button class=" bg-transparent" onclick="openModal('video1')">
												<i class="fas fa-play text-black fs-3 bg-transparent"></i>
											</button>
										</div>
									</div>
									<div class="col-6 position-relative px-2">
										<img alt="Customer testimonial video thumbnail d-block w-100" class="img-fluid rounded shadow" height="144" src="https://placehold.co/600x400" />
										<div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0;">
											<button class=" bg-transparent" onclick="openModal('video1')">
												<i class="fas fa-play text-black fs-3 bg-transparent"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="carousel-item">
							<div class="d-block w-100">
								<div class="row justify-content-between">
									<div class="col-6 position-relative px-2">
										<img alt="Customer testimonial video thumbnail d-block w-100" class="img-fluid rounded shadow" height="144" src="https://placehold.co/600x400" />
										<div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0;">
											<button class=" bg-transparent" onclick="openModal('video1')">
												<i class="fas fa-play text-black fs-3 bg-transparent"></i>
											</button>
										</div>
									</div>
									<div class="col-6 position-relative px-2">
										<img alt="Customer testimonial video thumbnail d-block w-100" class="img-fluid rounded shadow" height="144" src="https://placehold.co/600x400" />
										<div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0;">
											<button class=" bg-transparent" onclick="openModal('video1')">
												<i class="fas fa-play text-black fs-3 bg-transparent"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="carousel-item">
							<div class="d-block w-100">
								<div class="row justify-content-between">
									<div class="col-6 position-relative px-2">
										<img alt="Customer testimonial video thumbnail d-block w-100" class="img-fluid rounded shadow" height="144" src="https://placehold.co/600x400" />
										<div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0;">
											<button class=" bg-transparent" onclick="openModal('video1')">
												<i class="fas fa-play text-black fs-3 bg-transparent"></i>
											</button>
										</div>
									</div>
									<div class="col-6 position-relative px-2">
										<img alt="Customer testimonial video thumbnail d-block w-100" class="img-fluid rounded shadow" height="144" src="https://placehold.co/600x400" />
										<div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0;">
											<button class=" bg-transparent" onclick="openModal('video1')">
												<i class="fas fa-play text-black fs-3 bg-transparent"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Previous</span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Next</span>
					</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal for Video 1 -->
	<div class="modal" id="video1">
		<div class="modal-content">
			<span class="close my-2" onclick="closeModal('video1')">
				<i class="fa-solid fa-xmark fs-5"></i>
			</span>
			<iframe allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen="" frameborder="0" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560">
			</iframe>
		</div>
	</div>
	<!-- Modal for Video 2 -->
	<!-- <div class="modal" id="video2">
		<div class="modal-content">
			<span class="close" onclick="closeModal('video2')">
				×
			</span>
			<iframe allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen="" frameborder="0" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560">
			</iframe>
		</div>
	</div> -->
	<!-- Modal for Video 3 -->
	<!-- <div class="modal" id="video3">
		<div class="modal-content">
			<span class="close" onclick="closeModal('video3')">
				×
			</span>
			<iframe allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen="" frameborder="0" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560">
			</iframe>
		</div>
	</div> -->
	<!-- Modal for Video 4 -->
	<!-- <div class="modal" id="video4">
		<div class="modal-content">
			<span class="close" onclick="closeModal('video4')">
				×
			</span>
			<iframe allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen="" frameborder="0" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560">
			</iframe>
		</div>
	</div> -->
	<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js">
	</script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js">
	</script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js">
	</script> -->
	<script>
		function openModal(videoId) {
			document.getElementById(videoId).style.display = "block";
		}

		function closeModal(videoId) {
			document.getElementById(videoId).style.display = "none";
		}
	</script>
</div>

@endsection