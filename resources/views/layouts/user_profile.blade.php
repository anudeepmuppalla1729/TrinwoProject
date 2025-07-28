<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Profile - Inqube')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-only.png') }}">
    <script src="https://kit.fontawesome.com/447522222b.js" crossorigin="anonymous"></script>
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/profile_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user_profile_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flash.css') }}">
</head>
<body>
    @include('partials.user_profile_sidebar')
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
                {{ session('status') }}
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.flash-toast').forEach(function(el) {
            setTimeout(function() {
                el.style.opacity = '0';
                setTimeout(function() { el.style.display = 'none'; }, 500);
            }, 4000);
        });
        
        // Follow button functionality
        const followButton = document.querySelector('.follow-button');
        if (followButton) {
            const userId = followButton.getAttribute('data-user-id');
            const followIcon = document.getElementById('followIcon');
            const followText = document.getElementById('followText');
            
            // Check if already following
            fetch(`/user/${userId}/follow-status`)
                .then(response => response.json())
                .then(data => {
                    if (data.following) {
                        followButton.classList.add('followed');
                        followIcon.classList.remove('fa-user-plus');
                        followIcon.classList.add('fa-user-check');
                        followText.textContent = 'Following';
                    }
                });
            
            followButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                const isFollowing = followButton.classList.contains('followed');
                const url = isFollowing ? `/user/${userId}/unfollow` : `/user/${userId}/follow`;
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (isFollowing) {
                            // Change to follow state
                            followButton.classList.remove('followed');
                            followIcon.classList.remove('fa-user-check');
                            followIcon.classList.add('fa-user-plus');
                            followText.textContent = 'Follow';
                        } else {
                            // Change to following state
                            followButton.classList.add('followed');
                            followIcon.classList.remove('fa-user-plus');
                            followIcon.classList.add('fa-user-check');
                            followText.textContent = 'Following';
                        }
                        
                        // Show success message
                        const flashContainer = document.createElement('div');
                        flashContainer.className = 'alert alert-success custom-flash-message flash-toast';
                        flashContainer.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message} <button class="flash-dismiss" onclick="this.parentElement.style.display='none'">&times;</button>`;
                        document.querySelector('.main-content').prepend(flashContainer);
                        
                        setTimeout(function() {
                            flashContainer.style.opacity = '0';
                            setTimeout(function() { flashContainer.style.display = 'none'; }, 500);
                        }, 4000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
    });
    </script>
    <footer>
        @stack('footer-scripts')
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