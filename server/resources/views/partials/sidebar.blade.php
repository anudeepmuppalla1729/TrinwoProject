<div class="dashboard_items" style="overflow: hidden;">
   
    <nav class="menu">
        <a href="{{ route('dashboard') }}"><i class="bi bi-newspaper"></i> My Feed</a>
        <a href="#" class="sidebar-ask-btn"><i class="bi bi-question-circle"></i> Ask </a>
        <a href="{{ route('questions') }}"><i class="bi bi-pencil-square"></i> Answer </a>
        <a href="{{ route('profile.bookmarks') }}"><i class="bi bi-bookmark"></i> Bookmark</a>
        <a href="{{ route('profile.following') }}"><i class="bi bi-people"></i> Following</a>
        <a href="{{ route('profile.bookmarks') }}"><i class="bi bi-star"></i> Interests</a>
    </nav>
    <div class="settings-logout">
        <a href="#"><i class="bi bi-gear"></i> Settings</a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right"></i> Log Out</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>