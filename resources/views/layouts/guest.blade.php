<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inqube')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-only.png') }}">
    @stack('styles')
</head>
<body>
    <main class="main-content">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>