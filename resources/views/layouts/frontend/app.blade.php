<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- set your title -->
    <title>{{ $application->name }} </title>


    @if (!empty($application->logo))
    <link rel="icon" type="image/png" href="{{ asset('assets/favicon.ico') }}"
        alt="{{ $application->name ?? config('app.name', 'Express') }}">
    <!--<link rel="shortcut icon" type="image/png" href="{{ asset('uploads/application/') . '/' . $application->logo }}" alt="{{ $application->name ?? config('app.name', 'Express') }}" >-->
    <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
    @else
    <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
    @endif

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="{{ $application->meta_description }}" />
    <meta name="keywords" content="{{ $application->meta_description }}">
    <meta name="author" content="{{ $application->meta_description }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="article:modified_time" content="2024-12-18T07:31:37+00:00" />
    <meta property="og:image" content="https://parceldex.com.bd/public/assets/logofull.jpg" />
    <meta property="og:image:width" content="1534" />
    <meta property="og:image:height" content="747" />

    <!-- replace favicon path or source here -->
    <!-- <link rel="icon" href="images/logo/favicon.ico"> -->

    <link rel="preload" as="style" href="/frontend/css/app-041e359a.css" />
    <link rel="preload" as="style" href="/frontend/css/app-05cbb568.css" />
    <link rel="modulepreload" href="/frontend/js/app-b0be635f.js" />
    <link rel="stylesheet" href="/frontend/css/app-041e359a.css" />
    <link rel="stylesheet" href="/frontend/css/app-05cbb568.css" />
    <script type="module" src="/frontend/js/app-b0be635f.js"></script>

    <!-- bootstrap and font-awesome cdn link -->
    <link rel="stylesheet" href="/frontend/css/cdn.jsdelivr.net/npm/bootstrap%405.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/frontend/css/cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <link rel="stylesheet" href="/frontend/css/cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" />
    <!-- end bootstrap and font-awesome cdn link -->

    <link rel="stylesheet" href="/frontend/css/master.css">
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="stylesheet" href="/frontend/css/override.css">

    <!-- animation cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'assets/css/www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-98358584-1', 'auto');
        ga('send', 'pageview');
    </script>

    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'assets/css/connect.facebook.net/en_US/fbevents.js');
        fbq('init', '243380070815993');
        fbq('track', 'PageView');
    </script>

    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=243380070815993&amp;ev=PageView&amp;noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->

</head>

<body class="body-class">
    <div class="app" id="app">

        @include('layouts.frontend.header')

        @yield('content')

        @include('layouts.frontend.footer')

    </div>

    <!-- bootstrap and jquery cdn link -->
    <script data-cfasync="false" src="cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="/frontend/css/cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" defer></script>
    <script src="/frontend/css/cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.js" defer></script>
    <script src="/frontend/css/cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.js" defer></script>
    <!-- end bootstrap and jquery cdn link -->


    <!-- custom javascript -->
    <script src="/frontend/js/custom.js" defer></script>


    <!-- custom javascript -->
    <script>
        (function() {
            function c() {
                var b = a.contentDocument || a.contentWindow.document;
                if (b) {
                    var d = b.createElement('script');
                    d.innerHTML =
                        "window.__CF$cv$params={r:'900d79dded0e3366',t:'MTczNjY4ODM2My4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='cdn-cgi/challenge-platform/h/b/scripts/jsd/e0c90b6a3ed1/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);";
                    b.getElementsByTagName('head')[0].appendChild(d)
                }
            }
            if (document.body) {
                var a = document.createElement('iframe');
                a.height = 1;
                a.width = 1;
                a.style.position = 'absolute';
                a.style.top = 0;
                a.style.left = 0;
                a.style.border = 'none';
                a.style.visibility = 'hidden';
                document.body.appendChild(a);
                if ('loading' !== document.readyState) c();
                else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c);
                else {
                    var e = document.onreadystatechange || function() {};
                    document.onreadystatechange = function(b) {
                        e(b);
                        'loading' !== document.readyState && (document.onreadystatechange = e, c())
                    }
                }
            }
        })();
    </script>
</body>

</html>