@extends('layouts.frontend.app')

@section('content')

<div class="main-about-page" style="background-color: white !important;">
	<div class="hero-section2">
		<div class="container w-100 row justify-content-center align-items-center py-4" style="min-width: 100% !important;">
			<div class="col-4">
				<h1 class="text-white text-md-left fw-bold mb-5" style="font-family: sans-serif !important;">About Parceldex</h1>
				<p class="text-white text-md-left">
					Parceldex is an end-to-end logistics and supply chain solutions provider for the e-commerce industry. To provide the best service possible, we’re dedicated to bringing you the most innovative logistics services, and allowing you to build up your online business in a way that is suited to you. TRAX offers the quickest deliveries and most reliable inventory management solutions with warehouses across Pakistan, along with the fastest transfer of funds and the most responsive customer experience
				</p>
				<div class="row gap-2 bg-transparent mt-4">
					<button class="col-4 py-2 shadow rounded-1 text-white fw-bold" style="background-color: #F27B21 !important; box-shadow: rgba(0, 0, 0, 0.15) 0px 1px 5px 0px !important;">
						<i class="fa-solid fa-bars" style="margin-right: 7px"></i>
						Discover More
					</button>
					<button class="col-3 py-2 bg-white shadow rounded-1 text-black fw-bold">
						<i class="fa-solid fa-phone" style="margin-right: 7px"></i>
						Contact Us
					</button>
				</div>
			</div>
			<div class="col-4">
				<img width="300px" src="/frontend/images/rider.png">
			</div>
		</div>
	</div>
	
	<div class="about-2nd-section container row justify-content-center gap-5 align-items-center py-5">
		<div class="col-3 p-4 d-flex flex-column gap-3">
			<i class="fa-solid fa-chart-line"></i>
			<strong class="fs-4">Mision</strong>
			<p>
				We believe in delivering the best logistics services throughout an exponentially growing network across the globe. Along with other innovations across the e-commerce supply chain, we are looking forward to cultivate everlasting relations with all leading online retail.
			</p>
		</div>
		<div class="col-3 p-4 d-flex flex-column gap-3">
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
			<div class="col-3 cards p-2">
				<div class=" d-flex flex-column p-3 gap-3">
					<i class="fa-regular fa-handshake"></i>
					<strong>Partnership</strong>
					<p>
						Choosing an efficient pathway of winning and celebrating as a team.
					</p>
				</div>
			</div>
			<div class="col-3 cards p-2">
				<div class=" d-flex flex-column p-3 gap-3">
					<i class="fa-solid fa-puzzle-piece"></i>
					<strong>Intergrity</strong>
					<p>
						Openness of culture, with the spirit of sharing knowledge & learning.
					</p>
				</div>
			</div>
			<div class="col-3 cards p-2">
				<div class=" d-flex flex-column p-3 gap-3">
					<i class="fa-solid fa-hand-fist"></i>
					<strong>Passion</strong>
					<p>
						Aimed at taking quick initiatives and showing full ownership of the results.
					</p>
				</div>
			</div>
			<div class="col-3 cards p-2">
				<div class=" d-flex flex-column p-3 gap-3">
					<i class="fa-solid fa-award"></i>
					<strong>Excellence</strong>
					<p>
						Striving towards superior performance, delivering lasting results.
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
