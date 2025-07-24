<div class="sidebar">
    <div class="profile-header">
        <div class="profile-pic profile-picture" @if(!$profileUser->avatar) data-initials="{{ strtoupper(collect(explode(' ', $profileUser->name))->map(fn($w)=>$w[0])->join('')) }}" @endif>
            @if(!empty($profileUser->avatar))
                <img src="{{ Storage::disk('s3')->url($profileUser->avatar) }}" alt="Profile Picture" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($profileUser->name) }}&size=100" alt="{{ $profileUser->name }}">
            @endif
        </div>
        <h2 class="profile-name">{{ $profileUser->name }}</h2>
        <p class="profile-title">
            @if($profileUser->bio)
                {{ $profileUser->bio }}
            @elseif($profileUser->expert_in || $profileUser->studying_in)
                {{ $profileUser->studying_in }}{{ $profileUser->studying_in && $profileUser->expert_in ? ' - ' : '' }}{{ $profileUser->expert_in }}
            @else
                Member
            @endif
        </p>
        <div class="stats">
            <div class="stat-item">
                <span class="sidebar-stat-value">{{ $profileUser->followers()->count() }}</span>
                <span class="sidebar-stat-label">Followers</span>
            </div>
            <div class="stat-item">
                <span class="sidebar-stat-value">{{ $profileUser->following()->count() }}</span>
                <span class="sidebar-stat-label">Following</span>
            </div>
        </div>
    </div>
    <div class="nav-links">
        <a href="{{ route('user.profile', $profileUser->user_id) }}" class="nav-item @if(Route::is('user.profile')) active @endif">
            <i class="fas fa-th-large"></i>
            <span>Overview</span>
        </a>
        <a href="{{ route('user.answers', $profileUser->user_id) }}" class="nav-item @if(Route::is('user.answers')) active @endif">
            <i class="fas fa-comment-dots"></i>
            <span>Answers</span>
        </a>
        <a href="{{ route('user.questions', $profileUser->user_id) }}" class="nav-item @if(Route::is('user.questions')) active @endif">
            <i class="fas fa-question-circle"></i>
            <span>Questions</span>
        </a>
        <a href="{{ route('user.posts', $profileUser->user_id) }}" class="nav-item @if(Route::is('user.posts')) active @endif">
            <i class="fas fa-file-alt"></i>
            <span>Posts</span>
        </a>
        @if(Auth::check() && Auth::id() != $profileUser->user_id)
        <a href="#" class="nav-item follow-button" id="followButton" data-user-id="{{ $profileUser->user_id }}">
            <i class="fas fa-user-plus" id="followIcon"></i>
            <span id="followText">Follow</span>
        </a>
        @endif
        <a href="{{ route('dashboard') }}" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Back to Home</span>
        </a>
    </div>
</div>
<div class="menu-toggle">
    <i class="fas fa-bars"></i>
</div>