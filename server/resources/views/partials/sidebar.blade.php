<<<<<<< HEAD
<div class="dashboard_items">
   
    <nav class="menu">
=======
<div class="dashboard_items" style="min-width:220px;max-width:270px;">
    <div class="sidebar-corner sidebar-top-left"></div>
    <div class="sidebar-corner sidebar-top-right"></div>
    <div class="sidebar-corner sidebar-bottom-left"></div>
    <div class="sidebar-corner sidebar-bottom-right"></div>
    <nav class="menu" style="display:flex;flex-direction:column;gap:0.7rem;">
>>>>>>> Anudeep
        <a href="{{ route('dashboard') }}"><i class="bi bi-newspaper"></i> My Feed</a>
        <a href="#" class="sidebar-ask-btn"><i class="bi bi-question-circle"></i> Ask Question</a>
        <a href="{{ route('questions') }}"><i class="bi bi-pencil-square"></i> Answer Question</a>
        <a href="{{ route('profile.bookmarks') }}"><i class="bi bi-bookmark"></i> Bookmark</a>
        <a href="{{ route('profile.following') }}"><i class="bi bi-people"></i> Following</a>
        <a href="{{ route('profile.bookmarks') }}"><i class="bi bi-star"></i> Interests</a>
    </nav>
    <div class="settings-logout" style="margin-top:1.5rem;display:flex;flex-direction:column;gap:0.5rem;">
        <a href="#"><i class="bi bi-gear"></i> Settings</a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                class="bi bi-box-arrow-right"></i> Log Out</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    <style>
        /* Hide scrollbar for sidebar */
        .dashboard_items {
            overflow-y: auto;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE 10+ */
        }

        .dashboard_items::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        @media (max-width: 768px) {
            .dashboard_items {
                min-width: 100vw !important;
                max-width: 100vw !important;
                padding: 1.2rem 1.1rem 1.5rem 1.1rem !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }

            .menu {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }

            .settings-logout {
                flex-direction: row !important;
                gap: 1.2rem !important;
                justify-content: space-between;
            }
        }

        @media (max-width: 480px) {
            .dashboard_items {
                padding: 0.7rem 0.2rem 1rem 0.2rem !important;
            }

            .menu a {
                font-size: 1.05rem !important;
                padding: 0.5rem 0.2rem !important;
            }
        }
    </style>
</div>