<div class="dashboard_items">
    <div class="sidebar-corner sidebar-top-left"></div>
    <div class="sidebar-corner sidebar-top-right"></div>
    <div class="sidebar-corner sidebar-bottom-left"></div>
    <div class="sidebar-corner sidebar-bottom-right"></div>
    <nav class="menu">
        <a href="#"><i class="bi bi-newspaper"></i> News Feed</a>
        <a href="#" class="sidebar-ask-btn"><i class="bi bi-question-circle"></i> Ask Question</a>
        <a href="#"><i class="bi bi-pencil-square"></i> Answer Question</a>
        <a href="#"><i class="bi bi-bookmark"></i> Bookmark</a>
        <a href="#"><i class="bi bi-people"></i> Following</a>
        <a href="#"><i class="bi bi-star"></i> Interests</a>
    </nav>
    <div class="settings-logout">
        <a href="#"><i class="bi bi-gear"></i> Settings</a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right"></i> Log Out</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div> 