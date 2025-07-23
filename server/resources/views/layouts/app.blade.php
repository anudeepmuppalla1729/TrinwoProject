<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Q&A Forum')</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.mobile.css') }}">

   
    <link rel="stylesheet" href="{{ asset('css/flash.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/interests.mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/signup.mobile.css') }}">
    <style>
        /* Responsive adjustments for layout, navbar, and sidebar */
        @media (max-width: 768px) {
            body {
                padding: 0;
                margin: 0;
                background: #faf6fb;
            }

            .container {
                padding: 0 0.5rem;
                width: 100vw;
                max-width: 100vw;
            }

            .navbar,
            .sidebar {
                border-radius: 0 !important;
                box-shadow: none !important;
            }

            .sidebar {
                position: fixed !important;
                left: 0;
                top: 56px;
                width: 100vw !important;
                height: auto !important;
                background: #fff !important;
                z-index: 1000;
                display: none;
                border-top: 1px solid #eee;
            }

            .sidebar.active {
                display: block;
            }

            .navbar {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                z-index: 1100;
                background: #fff;
                border-bottom: 1px solid #eee;
            }

            .main-content,
            .container>.main-content {
                margin-top: 60px !important;
            }

            .sidebar-toggle {
                display: inline-block !important;
                background: none;
                border: none;
                font-size: 1.7rem;
                color: #a522b7;
                margin-right: 1rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 0.2rem;
            }

            .navbar-brand {
                font-size: 1.1rem !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @include('partials.navbar')
    @include('partials.sidebar')
    @include('partials.modals')
    <script>
        // Mobile sidebar toggle logic
        document.addEventListener('DOMContentLoaded', function () {
            var sidebar = document.querySelector('.sidebar');
            var toggleBtn = document.querySelector('.sidebar-toggle');
            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
    <div class="container">

        <!-- <main class="main-content"> -->
        @yield('content')
        <!-- </main> -->
    </div>
    @stack('scripts')
    <script src="{{ asset('js/global.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
</body>

</html>