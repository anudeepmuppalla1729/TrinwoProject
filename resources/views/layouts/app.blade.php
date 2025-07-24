<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-authenticated" content="{{ Auth::check() ? 'true' : 'false' }}">
    <title>@yield('title', 'Q&A Forum')</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-search.css') }}">
   
    <link rel="stylesheet" href="{{ asset('css/flash.css') }}">
    <link rel="stylesheet" href="{{ asset('css/button-hover.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @include('partials.navbar')
    @include('partials.sidebar')
    @include('partials.modals')
    <div class="container">

        <!-- <main class="main-content"> -->
            @yield('content')
        <!-- </main> -->
    </div>
    @stack('scripts')
    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/navbar-search.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>