@extends('layouts.user_profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush
@section('title', $profileUser->name . ' - Posts')

@section('main_content')
<div class="top-bar">
    <h1 class="page-title">
        <i class="fas fa-file-alt"></i>
        <span>{{ $profileUser->name }}'s Posts</span>
    </h1>
    <div class="search-bar">
        <input type="text" placeholder="Search posts...">
        <button><i class="fas fa-search"></i></button>  
    </div>
</div>

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
    </div>
</div>

<div class="profile-navigation">
    <a href="{{ route('user.profile', $profileUser->user_id) }}">Overview</a>
    <a href="{{ route('user.posts', $profileUser->user_id) }}" class="active">Posts</a>
    <a href="{{ route('user.questions', $profileUser->user_id) }}">Questions</a>
    <a href="{{ route('user.answers', $profileUser->user_id) }}">Answers</a>
</div>

<div class="page-content active">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Public Posts</h2>
    
    @forelse($posts as $post)
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">{{ $post->heading }}</h3>
                <div class="card-date">{{ $post->created_at->format('M d, Y') }}</div>
            </div>
            <div class="card-content">
                {{ Str::limit(strip_tags($post->details), 200) }}
            </div>
            <div class="card-stats">
                <span><i class="fas fa-heart"></i> {{ $post->upvotes ?? 0 }} upvotes</span>
                <span><i class="fas fa-comment"></i> {{ $post->comments->count() }} comments</span>
                <a href="{{ route('posts.show', $post->post_id) }}" class="view-link">View Post</a>
            </div>
            @if($post->images->count() > 0)
                <div class="card-images">
                    @foreach($post->images as $image)
                        @php
                            $imgUrl = $image->image_url;
                        @endphp
                        @if(!empty($imgUrl))
                            @if(Str::startsWith($imgUrl, 'http'))
                                <img src="{{ $imgUrl }}" alt="Post image">
                            @else
                                <img src="{{ Storage::disk('s3')->url($imgUrl) }}" alt="Post image">
                            @endif
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">No posts found.</h3>
            </div>
            <div class="card-content">
                This user hasn't created any public posts yet.
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
    
    .card-images {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }
    
    .card-images img {
        max-width: 150px;
        max-height: 150px;
        object-fit: cover;
        border-radius: 4px;
    }
    
    .view-link {
        margin-left: auto;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    .view-link:hover {
        text-decoration: underline;
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
        
        .profile-navigation {
            overflow-x: auto;
            white-space: nowrap;
        }
        
        .card-images img {
            max-width: 100px;
            max-height: 100px;
        }
    }
</style>
@endpush