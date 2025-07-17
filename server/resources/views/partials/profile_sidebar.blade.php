<div class="sidebar">
    <div class="profile-header">
        <div class="profile-pic"></div>
        <h2 class="profile-name">Alex Morgan</h2>
        <p class="profile-title">Senior Developer & Tech Enthusiast</p>
        <div class="stats">
            <div class="stat-item">
                <span class="sidebar-stat-value">1.2K</span>
                <span class="sidebar-stat-label">Followers</span>
            </div>
            <div class="stat-item">
                <span class="sidebar-stat-value">850</span>
                <span class="sidebar-stat-label">Following</span>
            </div>
        </div>
    </div>
    <div class="nav-links">
        <a href="{{ route('profile.dashboard') }}" class="nav-item @if(Route::is('profile.dashboard')) active @endif">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('profile.answers') }}" class="nav-item @if(Route::is('profile.answers')) active @endif">
            <i class="fas fa-comment-dots"></i>
            <span>Answers</span>
        </a>
        <a href="{{ route('profile.questions') }}" class="nav-item @if(Route::is('profile.questions')) active @endif">
            <i class="fas fa-question-circle"></i>
            <span>Questions</span>
        </a>
        <a href="{{ route('profile.posts') }}" class="nav-item @if(Route::is('profile.posts')) active @endif">
            <i class="fas fa-file-alt"></i>
            <span>Posts</span>
        </a>
        <a href="{{ route('profile.followers') }}" class="nav-item @if(Route::is('profile.followers')) active @endif">
            <i class="fas fa-users"></i>
            <span>Followers</span>
        </a>
        <a href="{{ route('profile.following') }}" class="nav-item @if(Route::is('profile.following')) active @endif">
            <i class="fas fa-user-friends"></i>
            <span>Following</span>
        </a>
        <a href="{{ route('profile.bookmarks') }}" class="nav-item @if(Route::is('profile.bookmarks')) active @endif">
            <i class="fas fa-bookmark"></i>
            <span>Bookmarks</span>
        </a>
        <a href="{{ route('profile.settings') }}" class="nav-item @if(Route::is('profile.settings')) active @endif">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>
    </div>
    <!-- <div class="nav-links">
            <div class="nav-item active" data-page="dashboard">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </div>
            <div class="nav-item" data-page="answers">
                <i class="fas fa-comment-dots"></i>
                <span>Answers</span>
            </div>
            <div class="nav-item" data-page="questions">
                <i class="fas fa-question-circle"></i>
                <span>Questions</span>
            </div>
            <div class="nav-item" data-page="posts">
                <i class="fas fa-file-alt"></i>
                <span>Posts</span>
            </div>
            <div class="nav-item" data-page="followers">
                <i class="fas fa-users"></i>
                <span>Followers</span>
            </div>
            <div class="nav-item" data-page="following">
                <i class="fas fa-user-friends"></i>
                <span>Following</span>
            </div>
            <div class="nav-item" data-page="bookmarks">
                <i class="fas fa-bookmark"></i>
                <span>Bookmarks</span>
            </div>
        </div> -->
</div> 
<div class="menu-toggle">
        <i class="fas fa-bars"></i>
</div>