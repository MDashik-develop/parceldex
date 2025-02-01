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
				<div class="carousel slide" data-ride="carousel" id="testimonialCarousel">
					<div class="carousel-inner">
						<div class="carousel-item active">
							<div class="d-flex">
								<div class="position-relative mr-3">
									<img alt="Customer testimonial video thumbnail" class="img-fluid rounded shadow"
										height="144"
										src="https://storage.googleapis.com/a1aa/image/ZPcPKHTModNmsWV0bYGcrlUZfCHaA96b5poQqUD9wRU.jpg"
										width="256" />
									<div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
										style="top: 0; left: 0;">
										<button class="btn btn-light rounded-circle p-3 shadow"
											onclick="openModal('video1')">
											<i class="fas fa-play text-success">
											</i>
										</button>
									</div>
								</div>
								<div class="position-relative">
									<img alt="Customer testimonial video thumbnail" class="img-fluid rounded shadow"
										height="144"
										src="https://storage.googleapis.com/a1aa/image/ZPcPKHTModNmsWV0bYGcrlUZfCHaA96b5poQqUD9wRU.jpg"
										width="256" />
									<div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
										style="top: 0; left: 0;">
										<button class="btn btn-light rounded-circle p-3 shadow"
											onclick="openModal('video2')">
											<i class="fas fa-play text-success">
											</i>
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="carousel-item">
							<div class="d-flex">
								<div class="position-relative mr-3">
									<img alt="Customer testimonial video thumbnail" class="img-fluid rounded shadow"
										height="144"
										src="https://storage.googleapis.com/a1aa/image/ZPcPKHTModNmsWV0bYGcrlUZfCHaA96b5poQqUD9wRU.jpg"
										width="256" />
									<div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
										style="top: 0; left: 0;">
										<button class="btn btn-light rounded-circle p-3 shadow"
											onclick="openModal('video3')">
											<i class="fas fa-play text-success">
											</i>
										</button>
									</div>
								</div>
								<div class="position-relative">
									<img alt="Customer testimonial video thumbnail" class="img-fluid rounded shadow"
										height="144"
										src="https://storage.googleapis.com/a1aa/image/ZPcPKHTModNmsWV0bYGcrlUZfCHaA96b5poQqUD9wRU.jpg"
										width="256" />
									<div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
										style="top: 0; left: 0;">
										<button class="btn btn-light rounded-circle p-3 shadow"
											onclick="openModal('video4')">
											<i class="fas fa-play text-success">
											</i>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<a class="carousel-control-prev" data-slide="prev" href="#testimonialCarousel" role="button">
						<span aria-hidden="true" class="carousel-control-prev-icon">
						</span>
						<span class="sr-only">
							Previous
						</span>
					</a>
					<a class="carousel-control-next" data-slide="next" href="#testimonialCarousel" role="button">
						<span aria-hidden="true" class="carousel-control-next-icon">
						</span>
						<span class="sr-only">
							Next
						</span>
					</a>
				</div>
			</div>
		</div>
		<div class="d-flex justify-content-center mt-4">
			<div class="d-flex">
				<span class="badge badge-secondary rounded-circle mr-1" style="width: 10px; height: 10px;">
				</span>
				<span class="badge badge-secondary rounded-circle mr-1" style="width: 10px; height: 10px;">
				</span>
				<span class="badge badge-secondary rounded-circle mr-1" style="width: 10px; height: 10px;">
				</span>
				<span class="badge badge-secondary rounded-circle mr-1" style="width: 10px; height: 10px;">
				</span>
				<span class="badge badge-secondary rounded-circle mr-1" style="width: 10px; height: 10px;">
				</span>
				<span class="badge badge-secondary rounded-circle mr-1" style="width: 10px; height: 10px;">
				</span>
				<span class="badge badge-secondary rounded-circle mr-1" style="width: 10px; height: 10px;">
				</span>
				<span class="badge badge-secondary rounded-circle mr-1" style="width: 10px; height: 10px;">
				</span>
				<span class="badge badge-secondary rounded-circle mr-1" style="width: 10px; height: 10px;">
				</span>
				<span class="badge badge-secondary rounded-circle mr-1" style="width: 10px; height: 10px;">
				</span>
			</div>
		</div>
	</div>
	<!-- Modal for Video 1 -->
	<div class="modal" id="video1">
		<div class="modal-content">
			<span class="close" onclick="closeModal('video1')">
				×
			</span>
			<iframe allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen="" frameborder="0" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560">
			</iframe>
		</div>
	</div>
	<!-- Modal for Video 2 -->
	<div class="modal" id="video2">
		<div class="modal-content">
			<span class="close" onclick="closeModal('video2')">
				×
			</span>
			<iframe allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen="" frameborder="0" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560">
			</iframe>
		</div>
	</div>
	<!-- Modal for Video 3 -->
	<div class="modal" id="video3">
		<div class="modal-content">
			<span class="close" onclick="closeModal('video3')">
				×
			</span>
			<iframe allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen="" frameborder="0" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560">
			</iframe>
		</div>
	</div>
	<!-- Modal for Video 4 -->
	<div class="modal" id="video4">
		<div class="modal-content">
			<span class="close" onclick="closeModal('video4')">
				×
			</span>
			<iframe allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen="" frameborder="0" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560">
			</iframe>
		</div>
	</div>
	   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js">
	   </script>
	   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js">
	   </script>
	   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js">
	   </script>
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
