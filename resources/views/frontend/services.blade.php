@extends('layouts.frontend.app')

@section('content')

<div class="faq-1st-section text-center">
	<h3 class="mb-4">
		Our Services
	</h3>
	<p class="mb-4 fs-4" style="color: #6C757D;">
		Some of our value-added services for you to manage your  <br> online store just the way you want.
	</p>
	<div class="row justify-content-center gap-2 bg-transparent mt-4">
		<button class="col-1 py-2 bg-white  shadow rounded-1 text-black fw-bold">
			<i class="fa-solid fa-bars" style="margin-right: 7px"></i>
			Discover More
		</button>
		<button class="col-1 py-2 shadow rounded-1 text-white fw-bold" style="background-color: #F27B21 !important; box-shadow: rgba(0, 0, 0, 0.15) 0px 1px 5px 0px !important;">
			<i class="fa-solid fa-phone" style="margin-right: 7px"></i>
			Contact Us
		</button>
	</div>
</div>

    <!-- Breadcroumb Area -->
	{{-- <div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>Service</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / Service</h6>
					</div>
				</div>
			</div>
		</div>
    </div> --}}


	<!-- services start -->
	<div id="service-detail" class="services-main-block-3 pb-3">
		<div class="container">
			<div class="section text-center my-3 pb-2">
				<h1 class="section-heading">Courier Services</h1>
			</div>
			{{-- <div class="row">
                <div class="col-md-12">
                    <p>
                        @if (!empty($servicePage))
                            {!! $servicePage->long_details !!}
                        @endif
                    </p>
                </div>
			</div> --}}
            @if ($services->count() > 0)
			<div class="row gap-3 felx-wrap mb-5">
                @foreach ($services as $service)

				<div class="card shadow my-2" style="width: 18rem;">
					<img class="card-img-top" src="{{ asset('uploads/service/' . $service->image) }}" alt="Card image cap">
					<div class="card-body d-flex flex-column align-items-center gap-2">
					  <h5 class="card-title">{{ $service->name }}</h5>
					  <p class="card-text">{{ $service->short_details }}</p>
					  <a href="{{ route('frontend.serviceDetails', $service->slug) }}" class="btn btn-primary">Go somewhere</a>
					</div>
				  </div>
                @endforeach
            </div>
			<div class="row">
				<div class="col-md-12">
                    {{-- {{ $services->links() }} --}}
				</div>
            </div>
            @endif
		</div>
	</div>
    <!-- services end -->


	<!-- facts start-->
	<!--<div id="facts" class="facts-main-block" style="background-image: url({{ asset('image/frontend_images/bg/facts-bg.html') }});">-->
	<!--	<div class="container">-->
	<!--		<div class="row no-gutters text-white">-->
	<!--			<div class="col-lg-3 col-md-3 col-sm-6">-->
	<!--				<div class="facts-block text-center mrg-btm-30">-->
	<!--					<h1 class="facts-heading text-white counter">2150</h1><span>+</span>-->
	<!--					<div class="facts-heading-sign text-white"></div>-->
	<!--					<div class="facts-dtl">Satisfied Clients</div>-->
	<!--				</div>-->
	<!--			</div>-->
	<!--			<div class="col-lg-3 col-md-3 col-sm-6">-->
	<!--				<div class="facts-block text-center mrg-btm-30">-->
	<!--					<h1 class="facts-heading text-white counter">100</h1>-->
	<!--					<div class="facts-heading-sign text-white"></div>-->
	<!--					<div class="facts-dtl">Offices Worldwide</div>-->
	<!--				</div>-->
	<!--			</div>-->

	<!--			<div class="col-lg-3 col-md-3 col-sm-6">-->
	<!--				<div class="facts-block text-center mrg-btm-30">-->
	<!--					<h1 class="facts-heading text-white counter">55</h1>-->
	<!--					<div class="facts-dtl">Countries Covered</div>-->
	<!--				</div>-->
	<!--			</div>-->
	<!--			<div class="col-lg-3 col-md-3 col-sm-6">-->
	<!--				<div class="facts-block text-center mrg-btm-30">-->
	<!--					<h1 class="facts-heading text-white counter">4.6</h1>-->
	<!--					<div class="facts-heading-sign text-white"></div>-->
	<!--					<div class="facts-dtl">Reviews</div>-->
	<!--				</div>-->
	<!--			</div>-->
	<!--		</div>-->
	<!--	</div>-->
	<!--</div>-->
    <!-- facts end-->

    @if ($deliveryServices->count() > 0)
    <!-- services start -->
	<div id="services" class="services-main-block-2 py-4" style="background-image: url({{ asset('image/frontend_images/bg/facts-bg.jpg') }});">
		<div class="container">
			<div class="section text-center">
				<h1 class="section-heading my-4 pt-2">Delivery Services</h1>
			</div>
			<div class="row justify-content-center gap-3 felx-wrap mb-5">
                @foreach ($deliveryServices as $deliveryService)

				<div class="card shadow my-2" style="width: 18rem;">
					<img class="card-img-top" src="{{ asset('uploads/deliveryService/' . $deliveryService->image) }}" alt="Card image cap">
					<div class="card-body d-flex flex-column align-items-center gap-2">
					  <h5 class="card-title">{{ $deliveryService->name }}</h5>
					  <p class="card-text">{{ $deliveryService->short_details }}</p>
					</div>
				  </div>
                @endforeach

			</div>
		</div>
	</div>
    <!-- services end-->
    @endif

   <!-- clients start-->
    <div class="container px-2">
		<h4 class="section-heading my-4 py-2 text-center">Our Trusted Partners</h4>
		<div class=" item-clients d-flex overflow-hidden" style="width: 100%; position: relative;">
			<div class="clients-slider d-flex" style="transition: transform 0.5s linear; width: max-content;">
				@foreach ($partners as $partner)
					<div class="item-clients-img" style="width: 204px; margin-right: 30px;">
						<img src="{{ asset('uploads/partner/' . $partner->image) }}" class="img-fluid" alt="clients-1">
					</div>
				@endforeach
				<!-- Duplicate images for smooth transition -->
				@foreach ($partners as $partner)
					<div class="item-clients-img" style="width: 204px; margin-right: 30px;">
						<img src="{{ asset('uploads/partner/' . $partner->image) }}" class="img-fluid" alt="clients-1">
					</div>
				@endforeach
			</div>
		</div>
	</div>
   <!-- clients end-->

   <script>
    document.addEventListener("DOMContentLoaded", function() {
        let slider = document.querySelector(".clients-slider");
        let position = 0;
        let imgWidth = 234; // Image width + margin
        let totalImages = slider.children.length / 2; // Count unique images

        function slide() {
            position -= imgWidth;
            slider.style.transform = `translate3d(${position}px, 0, 0)`;

            if (position <= -(imgWidth * totalImages)) {
                setTimeout(() => {
                    slider.style.transition = "none"; // Disable transition for instant jump
                    position = 0;
                    slider.style.transform = `translate3d(${position}px, 0, 0)`;
                    setTimeout(() => {
                        slider.style.transition = "transform 0.5s linear"; // Re-enable transition
                    });
                }, 500);
            }
        }

        setInterval(slide, 2000); // Slide every 2 seconds
    });
</script>
@endsection
