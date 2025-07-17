 @extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile_posts.css') }}">
@endpush
@section('title', 'Posts - User Profile')

@section('main_content')
<div class="top-bar">
    <h1 class="page-title">
        <i class="fas fa-file-alt"></i>
        <span>Your Posts</span>
    </h1>
    <div class="filter-bar">
        <button class="filter-btn active">All Posts</button>
        <button class="filter-btn">Popular</button>
        <button class="filter-btn">Tech</button>
        <button class="filter-btn">Design</button>
        <button class="filter-btn">Tutorials</button>
    </div>
</div>
<!-- Posts Statistics -->
<div class="posts-stats">
    <div class="stat-card">
        <div class="stat-value">42</div>
        <div class="stat-label">Total Posts</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">24.8K</div>
        <div class="stat-label">Total Views</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">1.4K</div>
        <div class="stat-label">Total Comments</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">3.7K</div>
        <div class="stat-label">Total Shares</div>
    </div>
</div>
<!-- Posts Grid -->
<div class="posts-grid">
    @forelse($posts as $post)
        <div class="post-card">
            <div class="post-header">
                {{-- You can add post image and category here if available --}}
            </div>
            <div class="post-body">
                <div class="post-meta">
                    <div class="post-date">
                        <i class="far fa-calendar"></i> {{ $post->created_at->format('M d, Y') }}
                    </div>
                </div>
                <h3 class="post-title">{{ $post->heading }}</h3>
                <p class="post-excerpt">{{ $post->details }}</p>
            </div>
            <div class="post-footer">
                <div class="post-stats">
                    <div class="post-stat">
                        <i class="far fa-comment"></i> {{ $post->comments->count() }} comments
                    </div>
                </div>
                <div class="post-actions">
                    <button class="action-btn">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @if($post->comments->count())
                <div class="post-comments">
                    <strong>Comments:</strong>
                    <ul>
                        @foreach($post->comments as $comment)
                            <li>{{ $comment->comment_text }} <small>— {{ $comment->user->name ?? 'Unknown' }}</small></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @empty
        <div class="post-card">
            <div class="post-header">
                <h3 class="post-title">You have not posted anything yet.</h3>
            </div>
        </div>
    @endforelse
</div>
@endsection 