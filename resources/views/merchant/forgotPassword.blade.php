@extends('layouts.frontend.app')

@section('content')
    <div class="container-fluid" style="margin-top: 100px">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-sm-12">
                <img class="auth-image" src="{{ asset('image/reset.png') }}" style="max-width: 100%;">
            </div>

            <div class="col-md-5 col-md-5 col-sm-12">
                <div class="hold-transition login-page">
                    <div class="login-box">
                        <div class="login-logo" style="margin-bottom: 30px;">
                        </div>
                        <div class="text-center">
                            <div class="login-card-body">
                                <h3 style="color: #00509D; font-weight: bold; margin-bottom: 20px;" class="login-box-msg">
                                    Forgot Password?
                                </h3>
                                <p style="color: #00509D; font-weight: bold; margin-bottom: 20px;" class="login-box-msg">
                                    Please enter the email address associated
                                    with your account, and we'll send you
                                    instructions to reset your password.

                                </p>
                                @include('layouts.merchant_layout.merchant_session_alert')

                                <form action="{{ route('merchant.forgotPassword') }}" method="post">
                                    @csrf
                                    <div class="input-group mb-4">
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Enter your register email" value="{{ old('email') }}" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-block"
                                                style="background-color: #00509D; color: #fff;">
                                                Reset Password
                                            </button>
                                        </div>
                                    </div>
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
        height: 100vh;
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
