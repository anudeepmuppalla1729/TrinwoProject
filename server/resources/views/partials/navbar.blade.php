<nav class="navbar">
    <div class="nav-left">
        <button class="hamburger" id="hamburgerBtn">
            <i class="bi bi-list"></i>
        </button>
        <span class="logo"><img src="{{ asset('/assets/logo.png') }}" width="200px" height="38px" alt="logo"></span>
        <i class="bi bi-house-fill d-block d-md-none"></i>
        <button class="ask-btn aks"><i class="bi bi-question-circle"></i>&nbsp;Ask Question</button>
    </div>
    <div class="search-box">
        <input type="text" id="navbar-search" placeholder="Search users..." autocomplete="off" />
        <i class="fa-solid fa-magnifying-glass"></i>
        <div id="search-results" class="search-results-dropdown" style="display: none;"></div>
    </div>
    <div class="nav-links">
        <a href="#"><i class="fa-regular fa-bell"></i></a>
        @auth
            <a href="{{ route('profile.dashboard') }}">
                @if(!empty(Auth::user()->avatar))
                    <img src="{{ Storage::disk('s3')->url(Auth::user()->avatar) }}" alt="Profile" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                @else
                    <i class="bi bi-person-circle"></i>
                @endif
            </a>
        @else
            <a href="{{ route('register') }}" class="signup-btn">Sign Up</a>
        @endauth
    </div>
</nav>