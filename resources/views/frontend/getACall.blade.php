@extends('layouts.frontend.app')

@section('content')
    <div class="main-getACall-page">
        <div class="getACall-1st-section">
            <div class="common-hero-section text-center">
                <h3 class="mb-4">
                    Get A Call
                </h3>
                <p class="mb-4" style="color: #6C757D;">
                    Send your parcel quickly and securely! <br> For any inquiries, call us – we provide 24/7 support. 📞 Contact us now!
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
        </div>
        <div class="container">
            <div class="p-2 p-md-5">
                <form>
                    <div class="form-row">
                        <div class="row gap-3">
                            <div class="form-group col mb-3">
                                <label for="inputEmail4">Email</label>
                                <input type="text" class="form-control text-start" id="inputEmail4" placeholder="Enter Your Name">
                            </div>
                            <div class="form-group col mb-3">
                                <label for="inputPassword4">Phone</label>
                                <input type="tel" class="form-control text-start" id="inputPassword4" placeholder="Enter Your Phone Number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                      <label for="inputAddress">Facebook Or Website</label>
                      <input type="link" class="form-control text-start" id="inputAddress" placeholder="Enter Link" style="text-align: center !important">
                    </div>

                    <div class="form-control border-0 pb-4">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection