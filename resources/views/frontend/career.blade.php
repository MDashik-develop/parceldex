@extends('layouts.frontend.app')

@section('content')

    <div class="main-career-page">
        <div class="common-hero-section text-center">
            <h3 class="mb-4">
                Join our team
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
    </div>

    <div class="career-2nd-section my-5">
        <div class="container">
            <div class="row p-3 gap-3 justify-content-center">
                <div class="col-lg d-flex justify-content-center align-content-center">
                    <div class="d-flex justify-content-center align-content-center h-100 w-100" >
                        <img src="/frontend/images/deleveryai.png" alt="" class="rounded" style="width: 100%; height: 100%;">
                    </div>
                </div>
                <div class="col-lg">
                    <div class="form-div container p-3 p-md-5 border rounded">
                        <form>
                            <div class="row form-row gap-2">
                                <div class="form-group col-md mb-3">
                                    <label for="inputEmail4">First Name</label>
                                    <input type="text" class="form-control" id="inputEmail4" placeholder="Enter your First Name">
                                </div>
                                <div class="form-group col-md mb-3">
                                    <label for="inputPassword4">Last Name</label>
                                    <input type="text" class="form-control" id="inputPassword4" placeholder="Enter your Last Name">
                                </div>
                            </div>
                            <div class="row form-row gap-2">
                                <div class="form-group col-md mb-3">
                                    <label for="inputEmail4">Email</label>
                                    <input type="email" class="form-control" id="inputEmail4" placeholder="Enter your Email address">
                                </div>
                                <div class="form-group col-md mb-3">
                                    <label for="inputPassword4">Phone</label>
                                    <input type="password" class="form-control" id="inputPassword4" placeholder="Enter your phone number">
                                </div>
                            </div>
                            <div class="form-group col-md mb-3">
                                <label for="inputAddress">Present Address</label>
                                <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                            </div>
                            <div class="form-group col-md mb-3">
                                <label for="inputAddress2">Parmanent Address</label>
                                <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
                            </div>
                            <div class="form-row row gap-2">
                                <div class="form-group col-md-6 mb-3">
                                    <label for="inputCity">City</label>
                                    <input type="text" class="form-control" id="inputCity" placeholder="Enter your City">
                                </div>
                                <div class="form-group col-md-4 mb-3">
                                    <label for="inputState">State</label>
                                    <select id="inputState" class="form-control">
                                        <option selected>Choose State<option>
                                        <option value="Dhaka">Dhaka</option>
                                        <option value="Chattogram">Chattogram</option>
                                        <option value="Rajshahi">Rajshahi</option>
                                        <option value="Khulna">Khulna</option>
                                        <option value="Barishal">Barishla</option>
                                        <option value="Sylhet">Sylhet</option>
                                        <option value="Rangpur">Rangpur</option>
                                        <option value="Mymensingh">Mymensingh</option>
                                    </select>
                                </div>
                                <div class="form-group col-md mb-3">
                                    <label for="inputZip">Zip</label>
                                    <input type="text" class="form-control" id="inputZip" placeholder="Postal Code...">
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="inputGroupFile">
                                <label class="input-group-text" for="inputGroupFile">Upload your Resume</label>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Your Message</label>
                                <textarea class="form-control" id="message" rows="2" placeholder="Type your message..."></textarea>
                            </div>                          
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck">
                                    <label class="form-check-label" for="gridCheck">
                                        Are you agree all terms and conditions
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn w-100">Submit <i class="fa-solid fa-paper-plane"></i> </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection