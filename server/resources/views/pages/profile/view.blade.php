@extends('layouts.user_profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush
@section('title', $profileUser->name . ' - User Profile')

@section('main_content')
<div class="top-bar">
    <h1 class="page-title">
        <i class="fas fa-user"></i>
        <span>{{ $profileUser->name }}'s Profile</span>
    </h1>

</div>
<!-- Profile Page -->
<div id="dashboard" class="page-content active">
    <div class="user-profile-header">
        <div class="profile-image">
            @if(!empty($profileUser->avatar))
                <img src="{{ Storage::disk('s3')->url($profileUser->avatar) }}" alt="{{ $profileUser->name }}">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($profileUser->name) }}&size=100" alt="{{ $profileUser->name }}">
            @endif
        </div>
        <div class="profile-info">
            <h2>{{ $profileUser->name }}</h2>
            @if($profileUser->bio)
                <p class="bio">{{ $profileUser->bio }}</p>
            @endif
            <div class="profile-details">
                @if($profileUser->studying_in)
                    <div class="detail-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span>{{ $profileUser->studying_in }}</span>
                    </div>
                @endif
                @if($profileUser->expert_in)
                    <div class="detail-item">
                        <i class="fas fa-star"></i>
                        <span>Expert in {{ $profileUser->expert_in }}</span>
                    </div>
                @endif
                @if($profileUser->interests)
                    <div class="detail-item">
                        <i class="fas fa-heart"></i>
                        <span>Interests: {{ implode(', ', json_decode($profileUser->interests)) }}</span>

                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <i class="fas fa-question-circle"></i>
            <div class="value">{{ $questionsCount }}</div>
            <div class="label">Questions Asked</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-comments"></i>
            <div class="value">{{ $answersCount }}</div>
            <div class="label">Answers Given</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-file-alt"></i>
            <div class="value">{{ $postsCount }}</div>
            <div class="label">Posts Created</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-star"></i>
            <div class="value">{{ $totalUpvotes }}</div>
            <div class="label">Total Upvotes</div>
        </div>
    </div>
    
    <div class="profile-navigation">
        <a href="{{ route('user.profile', $profileUser->user_id) }}" class="active">Overview</a>
        <a href="{{ route('user.posts', $profileUser->user_id) }}">Posts</a>
        <a href="{{ route('user.questions', $profileUser->user_id) }}">Questions</a>
        <a href="{{ route('user.answers', $profileUser->user_id) }}">Answers</a>
    </div>
    
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Recent Activity</h2>
    @forelse($recentActivity as $activity)
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">
                    @if(isset($activity->title))
                        {{ $activity->title }}
                    @elseif(isset($activity->heading))
                        {{ $activity->heading }}
                    @else
                        Answered: {{ Str::limit(strip_tags($activity->content ?? ''), 40) }}
                    @endif
                </h3>
                <div class="card-date">{{ $activity->created_at->format('M d, Y') }}</div>
            </div>
            <div class="card-content">
                @if(isset($activity->description))
                    {{ Str::limit(strip_tags($activity->description), 120) }}
                @elseif(isset($activity->details))
                    {{ Str::limit(strip_tags($activity->details), 120) }}
                @elseif(isset($activity->content))
                    {{ Str::limit(strip_tags($activity->content), 120) }}
                @endif
            </div>
            <div class="card-stats">
                @if(isset($activity->heading) || isset($activity->content))
                    @if(isset($activity->upvotes))
                        <span><i class="fas fa-heart"></i> {{ $activity->upvotes }} upvotes</span>
                    @endif
                @endif
                @if(isset($activity->title) && isset($activity->answers))
                    <span><i class="fas fa-comment"></i> {{ $activity->answers->count() }} responses</span>
                @endif
            </div>
        </div>
    @empty
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">No recent activity found.</h3>
            </div>
        </div>
    @endforelse
</div>
@endsection

@push('styles')
<style>
    .user-profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .profile-image {
        width: 120px;
        height: 120px;
        margin-right: 2rem;
    }
    
    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    
    .default-avatar {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--primary-color);
        color: white;
        font-size: 3rem;
        border-radius: 50%;
    }
    
    .profile-info {
        flex: 1;
    }
    
    .profile-info h2 {
        margin-bottom: 0.5rem;
        color: var(--primary-dark);
    }
    
    .bio {
        margin-bottom: 1rem;
        color: #555;
    }
    
    .profile-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        color: #666;
    }
    
    .detail-item i {
        margin-right: 0.5rem;
        color: var(--primary-color);
    }
    
    .profile-navigation {
        display: flex;
        margin-bottom: 2rem;
        border-bottom: 1px solid #ddd;
    }
    
    .profile-navigation a {
        padding: 1rem 1.5rem;
        color: #555;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .profile-navigation a:hover {
        color: var(--primary-color);
    }
    
    .profile-navigation a.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
    }
    
    @media (max-width: 768px) {
        .user-profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-image {
            margin-right: 0;
            margin-bottom: 1.5rem;
        }
        
        .profile-details {
            justify-content: center;
        }
        
        .profile-navigation {
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>
@endpush