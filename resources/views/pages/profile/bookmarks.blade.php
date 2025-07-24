@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
<link rel="stylesheet" href="{{ asset('css/bookmarks.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush
@section('title', 'Bookmarks - User Profile')

@section('main_content')
<div class="bookmarkheading">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Bookmarks</h2>
</div>
@if($bookmarkedPosts->isEmpty() && $bookmarkedQuestions->isEmpty())
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">No bookmarks yet</h3>
        </div>
        <div class="card-content">
            You haven't bookmarked any posts or questions yet.
        </div>
    </div>
@else
    <div class="posts-container">
        @foreach($bookmarkedPosts as $post)
            <div class="post" data-id="post-{{ $post->post_id }}">
                <div class="post-header">
                    <div class="profile">
                        <i class="bi bi-person-circle" style="font-size: 2rem; margin-right: 7px;"></i>
                        <div>
                            <strong>{{ $post->user->name }}</strong><br>
                            <small style="font-size: 1rem;">{{ $post->user->studying_in ?? 'Member' }} - {{ $post->user->expert_in ?? 'Member' }}</small>
                        </div>
                    </div>
                    <div>
                        <span class="options">⋮
                            <div class="options-menu">
                                <button class="remove-bookmark" data-post-id="{{ $post->post_id }}">Remove Bookmark</button><hr>
                                <button>Copy Link</button>
                            </div>
                        </span>
                    </div>
                </div>
                <hr>
                <h2>{{ $post->heading }}</h2>
                @php
                    $imgUrl = $post->images->first() ? $post->images->first()->image_url : '';
                @endphp
                @if(!empty($imgUrl))
                    <div class="post-image">
                        <img src="{{ Storage::disk('s3')->url($imgUrl) }}" alt="{{ $post->heading }}" style="max-width: 100%; border-radius: 8px;">
                    </div>
                @endif
                <p>{{ $post->details }}</p>
                <div class="post-meta">
                    <small>Posted on {{ $post->created_at->format('M d, Y') }}</small>
                </div>
                <div class="post-actions" style="display: flex; justify-content: space-between; padding: 10px 0;">
                    <a href="{{ route('posts.show', $post->post_id) }}" class="action-btn" style="display: flex; align-items: center; background: none; border: none; color: #555; font-size: 0.9rem; padding: 8px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
                        <i class="bi bi-eye" style="font-size: 1.2rem; margin-right: 5px;"></i>
                        <span>View Post</span>
                    </a>
                    <button class="action-btn comment-count-btn" style="display: flex; align-items: center; background: none; border: none; color: #555; font-size: 0.9rem; padding: 8px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
                        <i class="bi bi-chat-dots" style="font-size: 1.2rem; margin-right: 5px;"></i>
                        <span>{{ $post->comments->count() }}</span>
                    </button>
                    <button class="action-btn bookmark-btn active" style="display: flex; align-items: center; background: none; border: none; color: rgb(45, 60, 95); font-size: 0.9rem; padding: 8px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
                        <i class="bi bi-bookmark-fill" style="font-size: 1.2rem;"></i>
                    </button>
                </div>
            </div>
        @endforeach
        @foreach($bookmarkedQuestions as $question)
            <div class="post" data-id="question-{{ $question->question_id }}">
                <div class="post-header">
                    <div class="profile">
                        <i class="bi bi-person-circle" style="font-size: 2rem; margin-right: 7px;"></i>
                        <div>
                            <strong>{{ $question->user->name }}</strong><br>
                            <small style="font-size: 1rem;">{{ $question->user->studying_in ?? 'Member' }} - {{ $question->user->expert_in ?? 'Member' }}</small>
                        </div>
                    </div>
                </div>
                <hr>
                <h2>{{ $question->title }}</h2>
                <p>{{ $question->description }}</p>
                <div class="post-meta">
                    <small>Asked on {{ $question->created_at->format('M d, Y') }}</small>
                </div>
                <div class="post-actions" style="display: flex; justify-content: space-between; padding: 10px 0;">
                    <a href="{{ route('question', $question->question_id) }}" class="action-btn" style="display: flex; align-items: center; background: none; border: none; color: #555; font-size: 0.9rem; padding: 8px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
                        <i class="bi bi-eye" style="font-size: 1.2rem; margin-right: 5px;"></i>
                        <span>View Question</span>
                    </a>
                    <button class="action-btn comment-count-btn" style="display: flex; align-items: center; background: none; border: none; color: #555; font-size: 0.9rem; padding: 8px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
                        <i class="bi bi-chat-dots" style="font-size: 1.2rem; margin-right: 5px;"></i>
                        <span>{{ $question->answers->count() }}</span>
                    </button>
                    <button class="action-btn bookmark-btn active" style="display: flex; align-items: center; background: none; border: none; color: #a522b7; font-size: 0.9rem; padding: 8px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
                        <i class="bi bi-bookmark-fill" style="font-size: 1.2rem;"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to handle bookmark removal
        function removeBookmark(postId, postElement) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Send request to remove bookmark
            fetch(`/posts/${postId}/bookmark`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && !data.isBookmarked) {
                    // Remove the post from the page
                    postElement.remove();
                    
                    // Check if there are no more posts
                    if (document.querySelectorAll('.post').length === 0) {
                        // Replace with no bookmarks message
                        const container = document.querySelector('.posts-container');
                        container.innerHTML = `
                            <div class="content-card">
                                <div class="card-header">
                                    <h3 class="card-title">No bookmarks yet</h3>
                                </div>
                                <div class="card-content">
                                    You haven't bookmarked any posts or questions yet.
                                </div>
                            </div>
                        `;
                    }
                }
            })
            .catch(error => {
                console.error('Error removing bookmark:', error);
            });
        }
        
        // Handle remove bookmark buttons in dropdown menu
        document.querySelectorAll('.remove-bookmark').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                const postElement = this.closest('.post');
                removeBookmark(postId, postElement);
            });
        });
        
        // Handle bookmark buttons in post actions
        document.querySelectorAll('.bookmark-btn.active').forEach(button => {
            button.addEventListener('click', function() {
                const postElement = this.closest('.post');
                const dataId = postElement.getAttribute('data-id');
                let postId;
                
                if (dataId.startsWith('post-')) {
                    postId = dataId.replace('post-', '');
                } else if (dataId.startsWith('question-')) {
                    postId = dataId.replace('question-', '');
                }
                
                if (postId) {
                    removeBookmark(postId, postElement);
                }
            });
        });
        
        // Options menu toggle
        document.querySelectorAll('.options').forEach((opt) => {
            opt.addEventListener('click', (e) => {
                e.stopPropagation();
                closeAllMenus();
                opt.querySelector('.options-menu').classList.toggle('active');
            });
        });
        
        // Close all menus when clicking outside
        document.addEventListener('click', closeAllMenus);
        
        function closeAllMenus() {
            document.querySelectorAll('.options-menu').forEach((menu) => {
                menu.classList.remove('active');
            });
        }
    });
</script>
@endpush