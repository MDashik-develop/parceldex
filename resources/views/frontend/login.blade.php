<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta property="og:image" content="https://parceldex.com.bd/public/assets/logofull.jpg" />
    <meta property="og:image:width" content="1534" />
    <meta property="og:image:height" content="747" />
    @if (!empty($application->logo))
        <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

        <!--<link rel="icon" type="image/png" href="{{ asset('uploads/application/') . '/' . $application->logo }}" alt="{{ $application->name ?? config('app.name', 'Inventory') }}" >-->
        <!--<link rel="shortcut icon" type="image/png" href="{{ asset('uploads/application/') . '/' . $application->logo }}" alt="{{ $application->name ?? config('app.name', 'Inventory') }}" >-->
    @elseif(!empty($application->photo))
        <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

        <!--<link rel="icon" type="image/png" href="{{ asset('uploads/application/') . '/' . $application->photo }}" alt="{{ $application->name ?? config('app.name', 'Inventory') }}" >-->
        <!--<link rel="shortcut icon" type="image/png" href="{{ asset('uploads/application/') . '/' . $application->photo }}" alt="{{ $application->name ?? config('app.name', 'Inventory') }}" >-->
    @endif
    <title>{{ $application->name ?? config('app.name', 'Flier Express') }}</title>
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <style>
        .login-box,
        .register-box {
            width: 422px !important;
        }

        .login-card-body,
        .register-card-body {
            padding: 30px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 22px !important;
        }

        .login-page {
            background-color: transparent !important;
            background-image: url('/image/login-bg.jpg');
            background-repeat: no-repeat;
            height: 100vh;
            width: 100vw;
            position: relative;
            background-position: center center;
        }
    </style>
</head>

<body class="hold-transition login-page">

    <div class="login-box">
        <div class="login-logo" style="margin-bottom: 30px;">
            @if (!empty($application->photo))
                <img src="{{ asset('uploads/application/') . '/' . $application->photo }}"
                    alt="{{ $application->name ?? config('app.name') }}" style="width: 70%" style="opacity: .8">
            @else
                <b>{{ $application->name ?? config('app.name') }}</b>
            @endif
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <h3 style="color: #00509D; font-weight: bold; margin-bottom: 20px;" class="login-box-msg">Login to
                    Parceldex Using
                </h3>
                @include('layouts.merchant_layout.merchant_session_alert')

                <form action="{{ route('frontend.login') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="email" class="form-control" placeholder="Email/Contact Number"
                            required>
                    </div>
                    <div class="input-group mb-1">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append" id="togglePassword">
                            <div class="input-group-text">
                                <span id="icon" class="fas fa-eye"></span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('merchant.forgotPassword') }}">Forgot Password?</a>


                    {{-- <div class="input-group mb-3">
                        <select name="user_type" id="user_type" class="form-control select2" style="width: 100%">
                            <option value="1">Merchant</option>
                            <option value="2">Branch</option>
                            <option value="3">Rider</option>
                            <option value="4">Admin</option>
                            <option value="5">Operation</option>
                            <option value="6">Warehouse</option>
                            <option value="7">Accounts</option>
                        </select>
                    </div> --}}
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-block"
                                style="background-color: #00509D; color: #fff;">
                                </i> Login
                            </button>
                        </div>

                    </div>
                    <p class="mt-3" style="text-align: center;">New to Parceldex? <a
                            href="/merchant-registration">Sign Up Now</a></p>
                </form>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            Download Our App &nbsp; &nbsp; <a
                href="https://play.google.com/store/apps/details?id=com.parceldexltd.merchant&pcampaignid=web_share"
                target="_blank"><img height="30" width="100" src="{{ asset('image/playstore.jpg') }}"></a>

            <a href="https://play.google.com/store/apps/details?id=com.parceldexltd.merchant&pcampaignid=web_share"
                target="_blank"><img height="30" width="100" src="{{ asset('image/appstore.jpg') }}"></a>
        </div>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/admin_js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
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
</body>



</html>
