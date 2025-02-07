@extends('layouts.frontend.app')

@section('content')

    <div class="prices-main-page">
        <div class="faq-1st-section text-center">
            <h3 class="mb-4">
                Our Service Prices
            </h3>
            <p class="mb-4 fs-4" style="color: #6C757D;">
                We are always on the lookout for dynamic, growth-oriented <br> individuals willing to step out of their comfort zone and perform <br> beyond the ordinary.
            </p>
            <div class="row justify-content-center gap-2 bg-transparent mt-4">
                <button class="col-6 col-sm-3 col-lg-2 py-2 bg-white  shadow rounded-1 text-black fw-bold">
                    <i class="fa-solid fa-bars" style="margin-right: 7px"></i>
                    Discover More
                </button>
                <button class="col-5 col-sm-3 col-lg-2 py-2 shadow rounded-1 text-white fw-bold" style="background-color: #F27B21 !important; box-shadow: rgba(0, 0, 0, 0.15) 0px 1px 5px 0px !important;">
                    <i class="fa-solid fa-phone" style="margin-right: 7px"></i>
                    Contact Us
                </button>
            </div>
        </div>
        <div class="prices-2nd-section">
            <div class="container">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Home</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Profile</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact"
                            type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">...</div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
                </div>
            </div>
        </div>

    </div>

@endsection