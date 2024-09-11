@extends('layouts.frontend.app')


@section('content')
    <div class="container-fluid" style="margin-top: 100px">
        <div class="row">
            <div class="col-lg-7 col-md-7">
                <img class="auth-image" src="{{ asset('image/register.png') }}" style="max-width: 100%;">
            </div>

            <div class="col-md-5 col-md-5">
                <div class="contact-form" style="margin-top: 0; padding-top: 0;">
                    <div class="col-sm-12 text-center" style="margin-top: 0">
                        <h3 style="color: #00509D; font-weight: bold; margin-bottom: 20px;" class="login-box-msg">
                            Become A Merchant
                        </h3>
                        <p style="color: #00509D; font-weight: bold; margin-bottom: 20px;" class="login-box-msg">
                            Join as a Merchant and begin delivering today!
                        </p>
                    </div>
                    <form name="contact-form" id="merchantRegistrationForm"
                        action="{{ route('frontend.confirmMerchantRegistration') }}" method="POST">
                        <div class="form-group row">
                            <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 com-sm-10">
                                <strong style="color: #00509D" for="company_name" class="col-form-label">
                                    Company Name : <span style="font-weight: bold; color: red;">*</span>
                                </strong>
                                <input type="text" class="form-control" id="company_name" name="company_name"
                                    placeholder="Business Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 com-sm-10">
                                <strong style="color: #00509D" for="name" class="col-form-label">
                                    Owner's Name : <span style="font-weight: bold; color: red;">*</span>
                                </strong>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Business Owner's Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 com-sm-10">
                                <strong style="color: #00509D" for="address" class="col-form-label">
                                    Owner's Address : <span style="font-weight: bold; color: red;">*</span>
                                </strong>
                                <textarea class="form-control" name="address" id="address" cols="30" rows="1"
                                    placeholder="Business Owner's Address"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 com-sm-10">
                                <strong style="color: #00509D" for="business_address" class="col-form-label">
                                    Business Address : <span style="font-weight: bold; color: red;"></span>
                                </strong>
                                <textarea class="form-control" name="business_address" id="business_address" cols="30" rows="1"
                                    placeholder="Parcel Pickup Address. (e.g House-18, Road-4, Banani)"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 com-sm-10" style="padding-right: 0px;">
                                <strong style="color: #00509D" for="district_id" class="col-form-label">
                                    District : <span style="font-weight: bold; color: red;">*</span>
                                </strong>
                                <select name="district_id" id="district_id" class="form-control select2"
                                    style="width: 100%">
                                    <option value="0">Select District</option>
                                    @if ($districts->count() > 0)
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 com-sm-10" style="padding-right: 0px;">
                                <strong style="color: #00509D" for="district_id" class="col-form-label">
                                    Area : <span style="font-weight: bold; color: red;">*</span>
                                </strong>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%">
                                    <option value="0">Select Area</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 com-sm-10">
                                <strong style="color: #00509D" for="contact_number" class="col-form-label">
                                    Mobile Number : <span style="font-weight: bold; color: red;">*</span>
                                </strong>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="padding: 0rem .75rem">+88</div>
                                    </div>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number"
                                        placeholder="Mobile Number. (e.g 01711057407)">
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                        <strong style="color: #00509D" for="fb_url" class="col-form-label">
                            Facebook Business Page:  
                        </strong>
                        <div class="col-sm-9">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">http://</div>
                                </div>
                                <input type="text" class="form-control" id="fb_url" name="fb_url" placeholder=" Facebook Business Page Url" >
                            </div>
                        </div>
                    </div> --}}
                        {{-- <div class="form-group row">
                        <strong style="color: #00509D" for="web_url" class="col-form-label">
                            Website : <span style="font-weight: bold; color: red;"></span>
                        </strong>
                        <div class="col-sm-9">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">http://</div>
                                </div>
                                <input type="text" class="form-control" id="web_url" name="web_url" placeholder="Merchant Website Url" >
                            </div>
                        </div>
                    </div> --}}

                        <div class="form-group row">
                            <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 com-sm-10">
                                <strong style="color: #00509D" for="email" class="col-form-label">
                                    Email Address: <span style="font-weight: bold; color: red;">*</span>
                                </strong>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Email Address (e.g you@example.com)">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 com-sm-10">
                                <strong style="color: #00509D" for="confirm_password" class="col-form-label">
                                    Password:<span style="font-weight: bold; color: red;">*</span>
                                </strong>
                                <div class="input-group mb-1">
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Type your password" required>
                                    <div class="input-group-append" id="togglePassword">
                                        <div class="input-group-text">
                                            <span style="color: #00509D" id="icon" class="fas fa-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 com-sm-10">
                                <strong style="color: #00509D" for="confirm_password" class="col-form-label">
                                    Confirm Password:<span style="font-weight: bold; color: red;">*</span>
                                </strong>
                                <div class="input-group mb-1">
                                    <input type="password" id="confirm_password" name="confirm_password"
                                        class="form-control" placeholder="Type your password again" required>
                                    <div class="input-group-append" id="togglePassword2">
                                        <div class="input-group-text">
                                            <span style="color: #00509D" id="icon2" class="fas fa-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 com-sm-10">
                                <button type="submit" class="btn btn-block"
                                    style="background-color: #00509D; color: #fff;">
                                    </i> Sign Up
                                </button>
                            </div>

                        </div>
                        <p class="mt-3" style="text-align: center;">Have an account? <a href="/login"
                                style="color: #00509D">Sign In</a></p>

                        {{-- <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary submit" type="submit" name="submit"
                                    id="registrationBtn">
                                    Submit
                                </button>
                            </div>
                        </div> --}}
                    </form>

                    <div style="text-align: center; margin-top: 30px;">
                        <strong style="color: #00509D" style="color: #00509D">Download Our App</strong> &nbsp; &nbsp; <a
                            href="https://play.google.com/store/apps/details?id=com.parceldexltd.merchant&pcampaignid=web_share"
                            target="_blank"><img height="30" width="100"
                                src="{{ asset('image/playstore.jpg') }}"></a>

                        <a href="https://play.google.com/store/apps/details?id=com.parceldexltd.merchant&pcampaignid=web_share"
                            target="_blank"><img height="30" width="100"
                                src="{{ asset('image/appstore.jpg') }}"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style_css')
    <style>
        .form-group {
            margin-bottom: 0.25rem;
        }

        #contactForm {
            font-size: 15px;
        }

        .select2-container--default .select2-selection--single {
            width: 97%;
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 1px;
            right: 15px !important;
            width: 20px;
        }



        .contact-form {
            margin-top: 10px;
            padding: 16px 5px 16px 10px;
        }

        .contact-form input,
        .contact-form textarea {
            margin-bottom: 0px;
        }

        .form-control {
            padding: 8px 8px;
            font-size: 0.79rem;
            line-height: 1;
            border: 1px solid #c1c2c4;
        }

        .select2-results__option {
            padding: 1px;
        }

        .select2-results__options {
            font-size: 14px;
        }

        .btn-primary.submit:hover {
            background-color: #61B334;
            color: #fffdfd;
        }

        .btn-primary.submit {
            padding: 6px 16px;
        }

        @media (min-width:1200px) {
            .registrationContainer {
                max-width: 1300px !important;
            }
        }

        @media only screen and (max-width: 600px) {
            .auth-image {
                height: 200px !important;
                min-width: 100%;
            }
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 12px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    {{-- Sweet Alert --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.css" rel="stylesheet"
        type="text/css">
@endpush

@push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.js"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {

            $("#togglePassword").on("click", function() {
                $(this).toggleClass("active");
                if ($(this).hasClass("active")) {
                    $('#icon').removeClass('fa-eye')
                    $('#icon').addClass('fa-eye-slash')
                    $(this).prev("input").attr("type", "text");
                } else {
                    $(this).prev("input").attr("type", "password");
                    $('#icon').addClass('fa-eye')
                    $('#icon').removeClass('fa-eye-slash')
                }
            });

            $("#togglePassword2").on("click", function() {
                $(this).toggleClass("active");
                if ($(this).hasClass("active")) {
                    $('#icon2').removeClass('fa-eye')
                    $('#icon2').addClass('fa-eye-slash')
                    $(this).prev("input").attr("type", "text");
                } else {
                    $(this).prev("input").attr("type", "password");
                    $('#icon2').addClass('fa-eye')
                    $('#icon2').removeClass('fa-eye-slash')
                }
            });


            if ($(".select2").length > 0) $('.select2').select2();

            $('#district_id').on('change', function() {
                var district_id = $("#district_id option:selected").val();
                $("#area_id").val(0).change().attr('disabled', true);
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        district_id: district_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    // url       : "{{ route('upazila.districtOption') }}",
                    url: "{{ route('area.districtWiseAreaOption') }}",
                    success: function(response) {
                        // $("#upazila_id").html(response.option).attr('disabled', false);
                        $("#area_id").html(response.option).attr('disabled', false);
                    }
                })
            });


            $('#merchantRegistrationForm').on('submit', function(e) {
                e.preventDefault();

                var district_id = $("#district_id option:selected").val();
                var upazila_id = $("#upazila_id option:selected").val();
                var area_id = $("#area_id option:selected").val();
                var password = $("#password").val();
                var confirm_password = $("#confirm_password").val();
                var fb_url = $("#fb_url").val();
                var company_name = $("#company_name").val();
                var name = $("#name").val();
                var address = $("#address").val();
                var address = $("#address").val();
                var email = $("#email").val();
                var contact_number = $("#contact_number").val();

                if (company_name == '') {
                    toastMessage('Please Enter Company Name', 'Error', 'error');
                    return false;
                }
                if (name == '') {
                    toastMessage('Please Enter Merchant Name', 'Error', 'error');
                    return false;
                }
                if (address == '') {
                    toastMessage('Please Enter Merchant Address', 'Error', 'error');
                    return false;
                }
                if (district_id == '0') {
                    toastMessage('Please Select District', 'Error', 'error');
                    return false;
                }
                if (upazila_id == '0') {
                    toastMessage('Please Select Upazila', 'Error', 'error');
                    return false;
                }

                // console.log(contact_number.length);
                if (contact_number.length != 11) {
                    toastMessage('Please Enter Merchant Contact Number', 'Error', 'error');
                    return false;
                }

                // if(fb_url == ''){
                //     toastMessage('Please Enter Facebook Url', 'Error', 'error');
                //     return false;
                // }
                if (email == '') {
                    toastMessage('Please Enter Merchant Email', 'Error', 'error');
                    return false;
                }
                if (password.length < 5) {
                    toastMessage('Password Length Must be 5 Digit', 'Error', 'error');
                    return false;
                }
                if (password != confirm_password) {
                    toastMessage("Password and Confirm Password Does Not Match ", 'Error', 'error');
                    return false;
                }





                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    error: function(xhr) {
                        console.log(xhr);
                    },
                    url: this.action,
                    success: function(response) {
                        if (response.success) {

                            ///  window.location = "{{ route('frontend.otp_merchant_registration_login') }}";
                            $("#merchantRegistrationForm")[0].reset();
                            //    toastMessage(response.success, 'Success', 'success');
                            Swal.fire({
                                type: response.type,
                                title: response.title,
                                text: response.message,
                                showConfirmButton: true,
                                timer: 4000
                            });


                            setTimeout(function() {
                                window.location =
                                    "{{ route('frontend.otp_merchant_registration_login') }}";
                            }, 4000);

                            {{-- setTimeout(function(){ --}}
                            {{-- window.location = "{{ route('frontend.otp_merchant_registration_check') }}"; --}}
                            {{-- },5000); --}}
                        } else {
                            var getError = response.error;
                            var message = "";
                            if (getError.company_name) {
                                message = getError.company_name[0];
                            }
                            if (getError.name) {
                                message = getError.name[0];
                            }
                            if (getError.address) {
                                message = getError.address[0];
                            }
                            if (getError.district_id) {
                                message = getError.district_id[0];
                            }
                            if (getError.area_id) {
                                message = getError.area_id[0];
                            }
                            if (getError.contact_number) {
                                message = getError.contact_number[0];
                            }
                            if (getError.email) {
                                message = getError.email[0];
                            }
                            if (getError.password) {
                                message = getError.password[0];
                            }
                            if (getError.confirm_password) {
                                message = getError.confirm_password[0];
                            }
                            if (getError.fb_url) {
                                message = getError.fb_url[0];
                            }
                            toastMessage(message, 'Error', 'error');
                        }
                    }
                });
            });



        });
    </script>
@endpush
