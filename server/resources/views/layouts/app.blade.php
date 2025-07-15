<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Q&A Forum')</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user_information.css') }}">
    <link rel="stylesheet" href="{{ asset('css/interests.css') }}">
    <link rel="stylesheet" href="{{ asset('css/interests.mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/question.css') }}">
    @stack('styles')
</head>
<body>
    @include('partials.navbar')
    <div class="container">
        @include('partials.sidebar')
        <main class="main-content">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html> 