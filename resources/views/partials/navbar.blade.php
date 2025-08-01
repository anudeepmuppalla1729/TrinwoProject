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
        @auth
            <div class="notification-dropdown">
                <button class="notification-toggle" id="notificationToggle">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                </button>
                <div class="notification-dropdown-menu" id="notificationDropdown">
                    <div class="notification-header">
                        <h6>Notifications</h6>
                                            <div class="notification-actions">
                        <button class="btn btn-sm btn-outline-primary" id="markAllRead">
                            <i class="fas fa-check-double"></i>
                        </button>
                    </div>
                    </div>
                    <div class="notification-filters">
                        <button class="filter-btn active" data-filter="all">All</button>
                        <button class="filter-btn" data-filter="unread">Unread</button>
                    </div>
                    <div class="notification-list" id="notificationList">
                        <div class="loading-spinner">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="notification-empty" id="notificationEmpty" style="display: none;">
                        <div class="empty-state">
                            <i class="fas fa-bell-slash"></i>
                            <p>No notifications yet</p>
                        </div>
                    </div>
                    <div class="notification-footer">
                        <a href="#" id="viewAllNotifications">View All Notifications</a>
                    </div>
                </div>
            </div>
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