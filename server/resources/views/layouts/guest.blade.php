<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Q&A Forum')</title>
    @stack('styles')
</head>
<body>
    <main class="main-content">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html> 