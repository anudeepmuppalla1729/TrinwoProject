@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
<style>
:root { --primary-dark: rgb(42, 60, 98); }
.blog-feed-section {
    width: 100%;
    max-width: 900px;
    margin: 0 auto 2.5rem auto;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 2.2rem;
}
.blog-post-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 32px 0 rgba(42,60,98,0.13);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    display: flex;
    flex-direction: row;
    transition: transform 0.18s cubic-bezier(.4,0,.2,1), box-shadow 0.18s cubic-bezier(.4,0,.2,1);
    cursor: pointer;
    min-height: 180px;
    position: relative;
}
.blog-post-card:hover {
    transform: scale(1.018);
    box-shadow: 0 12px 36px 0 rgba(42,60,98,0.18);
    border-color: #c7d0e6;
}
.blog-post-cover {
    width: 260px;
    min-width: 180px;
    max-width: 320px;
    height: 100%;
    object-fit: cover;
    background: #e9eaf0;
    display: block;
}
.blog-post-content {
    flex: 1;
    padding: 2.1rem 2.2rem 2.1rem 2.2rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.blog-post-title {
    color: var(--primary-dark);
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.6rem;
    line-height: 1.1;
    letter-spacing: -0.5px;
    word-break: break-word;
    transition: color 0.18s;
}
.blog-post-card:hover .blog-post-title {
    color: #1e293b;
}
.blog-post-excerpt {
    color: #23272f;
    font-size: 1.13rem;
    margin-bottom: 1.2rem;
    line-height: 1.7;
    word-break: break-word;
}
.blog-post-meta {
    display: flex;
    align-items: center;
    gap: 2.1rem;
    color: #6b7280;
    font-size: 1.08rem;
    margin-top: 1.2rem;
    flex-wrap: wrap;
}
.blog-post-meta i {
    color: var(--primary-dark);
    margin-right: 0.4rem;
}
.blog-post-meta form {
    display: inline;
}
.blog-post-meta button {
    color: var(--primary-dark);
    background: none;
    border: none;
    font-size: 1.1rem;
    margin-right: 0.7rem;
    cursor: pointer;
    transition: color 0.2s, background 0.2s;
    padding: 0.2rem 0.7rem;
    border-radius: 8px;
}
.blog-post-meta button:hover {
    color: #1e293b;
    background: #f0f4fa;
}
@media (max-width: 900px) {
    .blog-feed-section { max-width: 100vw; gap: 1.2rem; }
    .blog-post-card { flex-direction: column; min-height: 120px; }
    .blog-post-cover { width: 100%; min-width: 100px; max-width: 100vw; height: 180px; }
    .blog-post-content { padding: 1.2rem 1.1rem; }
    .blog-post-title { font-size: 1.3rem; }
    .blog-post-meta { font-size: 0.98rem; gap: 1rem; }
}
</style>
@endpush
@section('title', 'Dashboard - User Profile')

@section('main_content')
<div class="top-bar">
    <h1 class="page-title">
        <i class="fas fa-th-large"></i>
        <span>Dashboard Overview</span>
    </h1>
    <div class="search-bar">
        <input type="text" placeholder="Search content...">
        <button><i class="fas fa-search"></i></button>  
    </div>
</div>
<!-- Dashboard Page -->
<div id="dashboard" class="page-content active">
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
    <!-- <h2 class="blog-feed-title">Latest Blog Posts</h2> -->
    {{--
    <div class="blog-feed-section">
        @forelse($blogPosts as $post)
            <a href="{{ route('posts.show', $post->post_id) }}" class="blog-post-link" style="text-decoration:none;">
                <div class="blog-post-card">
                    @if($post->cover_image)
                        <img src="{{ Str::startsWith($post->cover_image, ['http://', 'https://']) ? $post->cover_image : Storage::disk('s3')->url($post->cover_image) }}" alt="Cover Image" class="blog-post-cover">
                    @endif
                    <div class="blog-post-content">
                        <div>
                            <div class="blog-post-title">{{ $post->title }}</div>
                            <div class="blog-post-excerpt">{{ Str::limit(strip_tags($post->content), 180) }}</div>
                        </div>
                        <div class="blog-post-meta">
                            <span><i class="fas fa-eye"></i> {{ $post->viewCount() }} views</span>
                            <span><i class="fas fa-clock"></i> {{ ceil(str_word_count(strip_tags($post->content))/200) }} min read</span>
                            <form method="POST" action="{{ route('posts.bookmark', $post->post_id) }}">
                                @csrf
                                <button type="submit" title="Bookmark"><i class="far fa-bookmark"></i></button>
                            </form>
                            <form method="POST" action="{{ route('posts.report', $post->post_id) }}">
                                @csrf
                                <button type="submit" title="Report"><i class="fas fa-flag"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">No new blog posts to show. Check back later!</div>
        @endforelse
    </div>
    --}}
    <div id="postsContainer"></div>
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