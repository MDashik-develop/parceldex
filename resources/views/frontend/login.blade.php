@extends('layouts.frontend.app')

@section('content')
    <div class="" style="margin-top: 10px">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-sm-12">
                <img class="auth-image" src="{{ asset('image/login.png') }}" style="max-width: 100%;">
            </div>

            <div class="col-md-5 col-md-5 col-sm-12">
                <div class="hold-transition login-page">
                    <div class="login-box">
                        <div class="login-logo" style="margin-bottom: 10px;">
                        </div>
                        <div class="text-center">
                            <div class="login-card-body">
                                <h3 style="color: #00509D; font-weight: bold; margin-bottom: 20px;" class="login-box-msg">
                                    Welcome back!
                                </h3>
                                <p style="color: #00509D; font-weight: bold; margin-bottom: 20px;" class="login-box-msg">
                                    We're excited to continue delivering excellence to you.
                                </p>
                                @include('layouts.merchant_layout.merchant_session_alert')

                                @if (Session::has('success'))
                                    <div class="alert alert-success" role="alert">
                                        <strong>Thank you for signup!</strong> <br> One of our Sales Representatives will
                                        contact you
                                        shortly to
                                        review your information. Once the review is complete, you’ll gain access to the
                                        Parcelede x
                                        Merchant Panel, enabling you to seamlessly manage and deliver your products.
                                    </div>
                                @endif

                                @if (Session::has('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ Session::get('error') }}
                                    </div>
                                @endif

                                <form action="{{ route('frontend.login') }}" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <strong for="email" class="col-form-label" style="float: left; color: #00509D">
                                            Email:<span style="font-weight: bold; color: red;">*</span>
                                        </strong>

                                        <input type="text" id="email" name="email" class="form-control"
                                            placeholder="Enter your register email" required>
                                    </div>

                                    <strong for="confirm_password" class="col-form-label"
                                        style="float: left; color: #00509D">
                                        Password:<span style="font-weight: bold; color: red;">*</span>
                                    </strong>
                                    <div class="input-group mb-1">


                                        <input type="password" name="password" class="form-control"
                                            placeholder="Enter your Password" required>
                                        <div class="input-group-append" id="togglePassword">
                                            <div class="input-group-text h-100" style="border-bottom-left-radius: 0 !important; border-top-left-radius: 0 !important;">
                                                <span style="color: #00509D" id="icon" class="fas fa-eye"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="display: flex; justify-content: space-between">
                                        <div class="remember-me-container">
                                            <input style="width: 13px;" type="checkbox" id="remember-me" name="remember-me">
                                            <label style="margin: auto; color: #00509D" for="remember-me">Remember
                                                me</label>
                                        </div>
                                        <a style="color: #00509D; font-weight: bold;"
                                            href="{{ route('merchant.forgotPassword') }}">Forgot
                                            Password?</a>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-block"
                                                style="background-color: #00509D; color: #fff;">
                                                </i> Login
                                            </button>
                                        </div>

                                    </div>
                                    <p class="mt-3" style="text-align: center;">Don't have an account? <a
                                            href="/merchant-registration" style="color: #00509D; font-weight: bold;">Sign
                                            Up</a></p>
                                </form>
                            </div>
                        </div>

                        <div style="text-align: center;">
                            <strong style="color: #00509D">Download Our App</strong> &nbsp; &nbsp; <a
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
    </div>
@endsection
<style>
    .remember-me-container {
        display: inline-flex;
        align-items: center;
    }

    .remember-me-container input {
        margin-right: 5px;
        /* Adds a small space between the checkbox and the label */
    }


    .login-box,
    .register-box {
        width: 500px !important;
    }

    .login-card-body,
    .register-card-body {
        padding: 30px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 22px !important;
    }

    .login-page {
        display: flex;
        justify-content: center;
        /* Centers horizontally */
        align-items: center;
        /* Centers vertically */
        /* height: 100vh; */
        /* Makes the container take the full height of the viewport */
    }

    @media only screen and (max-width: 600px) {
        .auth-image {
            height: 200px !important;
            min-width: 100%;
        }

        .login-page {
            height: auto;
        }
    }
</style>

@push('script_js')
    <script>
        $('.alert').delay(5000).slideUp('slow', function() {
            $(this).alert('close');
        });
        $(function() {
            if ($(".select2").length > 0) $('.select2').select2();
        });

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
    </script>
@endpush
