@extends('layouts.app')
@push('styles')
<style>
:root {
    --primary: rgb(42, 60, 98);
    --primary-light: rgba(42, 60, 98, 0.1);
    --primary-dark: rgb(32, 46, 77);
    --accent: #f39c12;
    --light-bg: #f8f9fa;
    --text: #333;
    --text-light: #6c757d;
    --border: #eaeaea;
    --success: #28a745;
    --danger: #dc3545;
    --white: #ffffff;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.blog-feed-section {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto 2.5rem auto;
    margin-top: 10px;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 2.2rem;
}

.blog-post-card {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    background: #fff;
    border-radius: 18px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    cursor: pointer;
    min-height: 0;
    position: relative;
    box-sizing: border-box;
    padding-bottom: 0;
    transform: translateY(0);
    background: linear-gradient(to bottom right, #ffffff, #f8f9fa);
}

.blog-post-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(42, 60, 98, 0.2);
    border-color: rgba(42, 60, 98, 0.2);
}

.blog-post-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, transparent 70%, var(--primary-light) 100%);
    border-radius: 0 0 0 100px;
    z-index: 0;
}

.blog-post-cover-wrapper {
    width: 100%;
    height: 260px;
    display: flex;
    overflow: hidden;
    position: relative;
    z-index: 1;
}

.blog-post-cover {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    background: linear-gradient(45deg, var(--primary-light), #f0f4fa);
    border-radius: 0;
    transition: all 0.5s ease;
}

.blog-post-card:hover .blog-post-cover {
    /* No hover effect */
}

.blog-post-content {
    flex: 1 1 0%;
    min-width: 0;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
    padding: 36px 32px 28px 32px;
    position: relative;
    z-index: 2;
}

.author-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.1rem;
}
.author-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--primary-light);
    background: #f0f0f0;
}
.author-name {
    font-weight: 600;
    color: var(--primary-dark);
    font-size: 1.08rem;
    text-decoration: none;
    margin-right: 0.7rem;
}
.follow-btn {
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.4rem 1.1rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    margin-left: auto;
}
.follow-btn.following {
    background: #eaeaea;
    color: var(--primary-dark);
    border: 1px solid var(--primary-light);
}
.follow-btn:hover {
    background: var(--accent);
    color: #fff;
}

.blog-post-title {
    color: var(--primary-dark);
    font-size: 1.8rem;
    font-weight: 800;
    margin-bottom: 0.8rem;
    line-height: 1.3;
    letter-spacing: -0.5px;
    word-break: break-word;
    transition: color 0.3s;
    position: relative;
    padding-bottom: 10px;
}

.blog-post-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: var(--accent);
    border-radius: 3px;
}

.blog-post-card:hover .blog-post-title {
    color: var(--primary);
}

.blog-post-excerpt {
    color: var(--text);
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    line-height: 1.7;
    word-break: break-word;
    position: relative;
    padding-left: 15px;
}

.blog-post-excerpt::before {
    content: '';
    position: absolute;
    left: 0;
    top: 8px;
    height: 60%;
    width: 3px;
    background: var(--primary-light);
    border-radius: 3px;
}

.blog-post-meta {
    display: flex;
    align-items: center;
    gap: 1.8rem;
    color: var(--text-light);
    font-size: 1rem;
    margin-top: 1rem;
    flex-wrap: wrap;
    padding: 0.9rem 0 0.3rem;
    box-sizing: border-box;
    margin-bottom: 0;
    border-top: 1px solid var(--border);
    justify-content: flex-start;
}

.blog-post-meta i {
    color: var(--primary);
    margin-right: 0.5rem;
    font-size: 1.1rem;
}

.blog-post-meta form {
    display: inline;
    margin-left: auto;
}

.blog-post-meta button {
    color: var(--primary);
    background: none;
    border: none;
    font-size: 1.1rem;
    margin-right: 0.7rem;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.blog-post-meta button:hover {
    color: var(--white);
    background: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(42, 60, 98, 0.2);
}

.bookmark-btn.bookmarked {
    color: var(--accent) !important;
    background: rgba(243, 156, 18, 0.1);
}

.bookmark-btn.bookmarked:hover {
    background: var(--accent) !important;
    color: white !important;
}

.blog-post-meta span {
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    padding: 5px 10px;
    border-radius: 6px;
}

.blog-post-meta span:hover {
    background: var(--primary-light);
    color: var(--primary);
}

@media (max-width: 900px) {
    .blog-feed-section {
        max-width: 100vw;
        gap: 1.8rem;
        padding: 0 15px;
    }
    
    .blog-post-card {
        flex-direction: column;
        min-height: auto;
        padding-bottom: 0;
    }
    
    .blog-post-cover-wrapper {
        width: 100%;
        height: 180px;
        min-width: 0;
        max-width: 100vw;
        flex: none;
        align-self: auto;
    }
    
    .blog-post-cover {
        width: 100%;
        height: 180px;
    }
    
    .blog-post-content {
        padding: 24px 14px 16px 14px;
    }
    
    .blog-post-title {
        font-size: 1.5rem;
        padding-bottom: 8px;
    }
    
    .blog-post-excerpt {
        font-size: 1rem;
        margin-bottom: 1.2rem;
    }
    
    .blog-post-meta {
        font-size: 0.95rem;
        gap: 1rem;
        padding: 0.8rem 0 0.2rem;
    }
    
    .blog-post-meta button {
        padding: 0.4rem 0.8rem;
    }
}

@media (max-width: 576px) {
    .blog-post-card {
        border-radius: 12px;
    }
    
    .blog-post-cover-wrapper {
        height: 160px;
    }
    
    .blog-post-cover {
        height: 160px;
    }
    
    .blog-post-title {
        font-size: 1.3rem;
    }
    
    .blog-post-meta {
        gap: 0.8rem;
        justify-content: space-between;
    }
    
    .blog-post-meta span {
        padding: 3px 8px;
    }
    
    .blog-post-meta form {
        margin-left: 0;
        width: 100%;
        display: flex;
        justify-content: flex-end;
        margin-top: 5px;
    }
}
</style>
@endpush
@section('title', 'Home | TRINWOPJ')
@section('content')
<div class="home_content">
    <div class="question-box">
        <input type="text" class="insight-btn question-input" placeholder="Type Your Question or Insight here" readonly/>
        @auth
        @if(!empty(Auth::user()->avatar))
            <img src="{{ Storage::disk('s3')->url(Auth::user()->avatar) }}" alt="Profile" class="user-icon" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
        @else
            <i class="bi bi-person-circle user-icon"></i>
        @endif
        @endauth
    </div>
    <div class="blog-feed-section" id="postsContainer">
        @foreach($blogPosts ?? [] as $post)
            <div class="blog-post-card">
                @if($post->cover_image)
                    <div class="blog-post-cover-wrapper">
                        <img src="{{ Str::startsWith($post->cover_image, ['http://', 'https://']) ? $post->cover_image : Storage::disk('s3')->url($post->cover_image) }}" alt="Cover Image" class="blog-post-cover">
                    </div>
                @endif
                <div class="blog-post-content">
                    <div class="author-row">
                        @php
                            $avatarUrl = $post->user->avatar_url;
                            $authorName = $post->user->name;
                            $initials = collect(explode(' ', $authorName))->map(fn($w) => strtoupper($w[0] ?? ''))->join('');
                            $isCurrentUser = Auth::check() && Auth::id() == $post->user->user_id;
                        @endphp
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" class="author-avatar" alt="{{ $authorName }}">
                        @else
                            <div class="author-avatar" style="display:flex;align-items:center;justify-content:center;background:#e0e0e0;color:#2a3c62;font-weight:700;font-size:1.1rem;">{{ $initials }}</div>
                        @endif
                        <a href="/user/{{ $post->user->user_id }}" class="author-name">{{ $authorName }}</a>
                        @auth
                            @if(!$isCurrentUser)
                                <button class="follow-btn{{ $post->isFollowing ? ' following' : '' }}" data-user-id="{{ $post->user->user_id }}">
                                    {{ $post->isFollowing ? 'Following' : 'Follow' }}
                                </button>
                            @endif
                        @endauth
                    </div>
                    <a href="/posts/{{ $post->post_id }}" class="blog-post-link" style="text-decoration:none;display:block;">
                        <div>
                            <div class="blog-post-title">{{ $post->title }}</div>
                            <div class="blog-post-excerpt">{{ Str::limit(strip_tags($post->content), 180) }}</div>
                        </div>
                    </a>
                </div>
                <div class="blog-post-meta">
                    <span><i class="fas fa-eye"></i> {{ $post->viewCount() }} views</span>
                    <span><i class="fas fa-clock"></i> {{ ceil(str_word_count(strip_tags($post->content))/200) }} min read</span>
                    <form method="POST" action="{{ route('posts.bookmark', $post->post_id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="bookmark-btn"><i class="far fa-bookmark"></i></button>
                    </form>
                    <form method="POST" action="{{ route('posts.report', $post->post_id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="report-btn ml-2" title="Report this post"><i class="fas fa-flag"></i></button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@auth
<!-- Report Modal for Posts -->
<div id="reportModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
    <div class="modal-content" style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:400px; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
        <button type="button" class="close-modal" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:#c92ae0; cursor:pointer;">&times;</button>
        <h3 style="color:#c92ae0; margin-bottom:1rem;">Report Post</h3>
        <form id="reportForm" method="POST">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="report_id" id="reportIdInput">
            <div style="margin-bottom:1rem;">
                <label for="reason" style="font-weight:600; color:#333;">Reason</label>
                <select name="reason" id="reasonSelect" required style="width:100%; padding:0.5rem; border-radius:6px; border:1px solid #ccc; margin-top:0.5rem;">
                    <option value="">Select a reason</option>
                    <option value="Spam">Spam</option>
                    <option value="Abusive">Abusive or harmful</option>
                    <option value="Off-topic">Off-topic</option>
                    <option value="Inappropriate">Inappropriate content</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label for="details" style="font-weight:600; color:#333;">Details (optional)</label>
                <textarea name="details" id="detailsInput" rows="3" style="width:100%; border-radius:6px; border:1px solid #ccc; padding:0.5rem;"></textarea>
            </div>
            <button type="submit" class="submit-report-btn" style="background:linear-gradient(135deg,#c92ae0,#a522b7); color:#fff; border:none; border-radius:6px; padding:0.6rem 1.5rem; font-weight:600; font-size:1rem; cursor:pointer; transition:background 0.2s;">Submit Report</button>
            <div id="reportError" style="color:#dc3545; margin-top:0.7rem; display:none;"></div>
        </form>
    </div>
</div>
@endauth
@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
<script>
    window.isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
    window.currentUserId = {{ Auth::check() ? Auth::id() : 'null' }};
</script>
@endpush
@endsection