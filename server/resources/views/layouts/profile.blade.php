<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Profile - Q&A Forum')</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    <script src="https://kit.fontawesome.com/447522222b.js" crossorigin="anonymous"></script>
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/profile_sidebar.css') }}">
</head>
    <link rel="stylesheet" href="{{ asset('css/flash.css') }}">
<body>
    @include('partials.profile_sidebar')
    <div class="main-content">
        @if (session('success'))
            <div class="alert alert-success custom-flash-message flash-toast">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button class="flash-dismiss" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif
        @if (session('status'))
            <div class="alert alert-success custom-flash-message flash-toast">
                <i class="fas fa-check-circle"></i>
                @if (session('status') === 'password-updated')
                    Password updated successfully!
                @else
                    {{ session('status') }}
                @endif
                <button class="flash-dismiss" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger custom-flash-message flash-toast">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button class="flash-dismiss" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif
        @yield('main_content')
    </div>
    @stack('scripts')
    <script src="{{ asset('js/profile.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.flash-toast').forEach(function(el) {
            setTimeout(function() {
                el.style.opacity = '0';
                setTimeout(function() { el.style.display = 'none'; }, 500);
            }, 4000);
        });
    });
    </script>
    <footer>
        @stack('footer-scripts')
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script> -->
        <script>
       
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            
            
            
            // Mobile menu toggle
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
            
            // Close sidebar when clicking outside
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 992 && 
                    !sidebar.contains(event.target) && 
                    !menuToggle.contains(event.target) && 
                    sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            });
        </script>
    </footer>
</body>
</html>
 