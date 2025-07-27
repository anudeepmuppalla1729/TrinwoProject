@php use Illuminate\Support\Str; use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.app')
@push('styles')
<style>
html, body {
    overflow-x: hidden !important;
    max-width: 100vw;
}
:root { --primary-dark: rgb(42, 60, 98); }
.custom-blog-container {
    width: 100%;
    max-width: 900px;
    padding: 2.5rem 1.5rem 3rem 1.5rem;
    margin: 0 auto;
    background: #eceaea;
    border-radius: 18px;
    box-shadow: 0 4px 20px 0 rgba(42,60,98,0.13);
    position: relative;
    z-index: 1;
    box-sizing: border-box;
    overflow-x: hidden;
}
.custom-blog-cover {
    width: 100%;
    max-width: 100%;
    height: auto;
    max-height: 400px;
    min-height: 180px;
    object-fit: cover;
    border-radius: 18px;
    margin-bottom: 2.2rem;
    background: #e9eaf0;
    box-shadow: 0 2px 12px rgba(42,60,98,0.08);
    display: block;
}
.custom-blog-title {
    color: var(--primary-dark);
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.1;
    letter-spacing: -1px;
    text-shadow: 0 2px 8px rgba(42,60,98,0.04);
    word-break: break-word;
}
.custom-blog-meta {
    color: #6b7280;
    font-size: 1.08rem;
    margin-bottom: 2.2rem;
    display: flex;
    flex-wrap: wrap;
    gap: 4rem;
    align-items: center;
    max-width: 100%;
}
.custom-blog-meta i {
    color: var(--primary-dark);
    margin-right: 0.4rem;
}
.custom-blog-meta form {
    display: inline;
}
.custom-blog-meta button {
    color: var(--primary-dark);
    background: none;
    border: none;
    font-size: 1.1rem;
    margin-right: 0.7rem;
    cursor: pointer;
    transition: color 0.2s;
    padding: 0.2rem 0.7rem;
    border-radius: 8px;
}

.custom-blog-content {
    font-size: 1.22rem;
    color: #23272f;
    line-height: 1.8;
    margin-bottom: 2.8rem;
    letter-spacing: 0.01em;
    word-break: break-word;
    border-radius: 0.8rem;
    padding: 1.2rem 1.5rem;
    background-color:rgb(222, 225, 232);
    max-width: 100%;
    overflow-x: auto;
}
.custom-comments-section {
    margin-top: 2.8rem;
    max-width: 100%;
}
.custom-comments-title {
    color: var(--primary-dark);
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1.2rem;
    letter-spacing: -0.5px;
}
.custom-comment-block {
    background: #f8fafc;
    border-radius: 0.9rem;
    padding: 1.2rem 1.5rem;
    margin-bottom: 1.3rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 8px rgba(42,60,98,0.04);
    max-width: 100%;
    word-break: break-word;
}
.custom-comment-user {
    color: var(--primary-dark);
    font-weight: 700;
    margin-right: 0.8rem;
    font-size: 1.08rem;
}
.custom-comment-date {
    color: #6b7280;
    font-size: 1rem;
}
.custom-comment-content {
    color: #23272f;
    font-size: 1.08rem;
    margin-top: 0.3rem;
    word-break: break-word;
}
.custom-comment-form textarea {
    border: 1px solid #e5e7eb;
    border-radius: 0.8rem;
    padding: 0.9rem;
    width: 100%;
    margin-bottom: 0.8rem;
    font-size: 1.13rem;
    background: #f8fafc;
    resize: vertical;
    min-height: 70px;
    max-width: 100%;
    box-sizing: border-box;
}
.custom-comment-form button {
    background: var(--primary-dark);
    color: #fff;
    border: none;
    border-radius: 0.8rem;
    padding: 0.7rem 1.7rem;
    font-size: 1.13rem;
    font-weight: 700;
    transition: background 0.2s;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(42,60,98,0.07);
    max-width: 100%;
}
.custom-comment-form button:hover {
    background: #1e293b;
}
.action-btn {
    background: none;
    border: none;
    color: #555;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 1.1rem;
    padding: 6px 12px;
    margin-right: 10px;
    border-radius: 6px;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}

.upvote-btn.voted, .upvote-btn.voted i {
    color: #2ecc71 !important;
    font-weight: bold;
}
.downvote-btn.voted, .downvote-btn.voted i {
    color: #e74c3c !important;
    font-weight: bold;
}
.upvote-btn i, .downvote-btn i {
    font-size: 1.3em;
    vertical-align: middle;
}
.author-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 1.2rem;
}
.author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
.author-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-dark);
    text-decoration: none;
}
.follow-btn {
    background: var(--primary-dark);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.3rem 1rem;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    margin-left: 35vw;
    transition: background 0.2s;
    box-shadow: 0 2px 8px rgba(42,60,98,0.07);
}
.follow-btn:hover {
    background: #1e293b;
}
.follow-btn.following {
    background: #e0e0e0;
    color: #333;
    cursor: pointer;
}
.follow-btn.following:hover {
    background: #d0d0d0;
}
@media (max-width: 900px) {
    .custom-blog-container { max-width: 100vw; padding: 1.2rem 0.2rem 2rem 0.2rem; }
    .custom-blog-title { font-size: 2rem; }
    .custom-blog-meta, .custom-comments-section, .custom-blog-content { max-width: 100vw; }
    .date{
        display: none;
    }
}
@media (max-width: 600px) {
    .custom-blog-title { font-size: 1.3rem; }
    .custom-blog-meta { font-size: 0.98rem; gap: 1rem; }
    .custom-blog-container { padding: 0.5rem 0.1rem 1rem 0.1rem; }
}
</style>
@endpush
@section('title', $post->title)
@section('content')
<div class="home_content">
    <div class="custom-blog-container">
        @if($post->cover_image)
            <img src="{{ Str::startsWith($post->cover_image, ['http://', 'https://']) ? $post->cover_image : Storage::disk('s3')->url($post->cover_image) }}" alt="Cover Image" class="custom-blog-cover">
        @endif
        <div class="author-row" style="margin-bottom: 1.2rem;">
            @if($post->user->avatar_url)
                <img src="{{ $post->user->avatar_url }}" class="author-avatar" alt="{{ $post->user->name }}">
            @else
                <div class="author-avatar" style="display:flex;align-items:center;justify-content:center;background:#e0e0e0;color:#2a3c62;font-weight:700;font-size:1.1rem;">{{ $post->authorInitials }}</div>
            @endif
            <a href="/user/{{ $post->user->user_id }}" class="author-name">{{ $post->user->name }}</a>
            @if(Auth::id() !== $post->user->user_id)
                <button class="follow-btn{{ $post->isFollowing ? ' following' : '' }}" data-user-id="{{ $post->user->user_id }}">{{ $post->isFollowing ? 'Following' : 'Follow' }}</button>
            @endif
        </div>
        <div style="margin-bottom: 20px; margin-left: 10px;">
        <h1 class="custom-blog-title" style="margin-bottom: 5px;">{{ $post->title }} </h1>
        <span  style="font-size:0.9rem; margin-bottom: 10px;"><i class="fas fa-calendar-alt" ></i> {{ $post->created_at->format('M d, Y') }}</span>
        </div>
        <div class="custom-blog-meta">
            <form id="upvoteForm" method="POST" action="{{ route('posts.upvote', $post->post_id) }}" style="display:inline;">
                @csrf
                <button type="submit" id="upvoteBtn" class="action-btn upvote-btn{{ (isset($userVote) && $userVote === 'upvote') ? ' voted' : '' }}" style="font-size:1.2rem;">
                    <i class="bi bi-arrow-up-circle"></i>
                    <span id="upvoteCount">{{ $post->upvotes ?? 0 }}</span>
                </button>
            </form>
            <form id="downvoteForm" method="POST" action="{{ route('posts.downvote', $post->post_id) }}" style="display:inline;">
                @csrf
                <button type="submit" id="downvoteBtn" class="action-btn downvote-btn{{ (isset($userVote) && $userVote === 'downvote') ? ' voted' : '' }}" style="font-size:1.2rem;">
                    <i class="bi bi-arrow-down-circle"></i>
                    <span id="downvoteCount">{{ $post->downvotes ?? 0 }}</span>
                </button>
            </form>

            
            <form id="bookmarkForm" method="POST" action="{{ route('posts.bookmark', $post->post_id) }}" style="display:inline;">
                @csrf
                <button type="submit" id="bookmarkBtn" title="Bookmark" class="{{ $post->isBookmarked ? 'bookmarked' : '' }}">
                    <i class="{{ $post->isBookmarked ? 'fas fa-bookmark' : 'far fa-bookmark' }}"></i>
                </button>
            </form>
            <button type="button" id="reportBtn" title="Report" class="report-btn"><i class="fas fa-flag"></i></button>
        </div>
        <div class="custom-blog-content">{!! $post->content !!}</div>
        <div class="custom-comments-section">
            <div class="custom-comments-title">Comments</div>
            @foreach($post->comments as $comment)
                <div class="custom-comment-block">
                    <div>
                        <span class="custom-comment-user">{{ $comment->user->name }}</span>
                        <span class="custom-comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="custom-comment-content">{{ $comment->comment_text }}</div>
                </div>
            @endforeach
            <form id="commentForm" method="POST" action="{{ route('comments.store', $post->post_id) }}" class="custom-comment-form mt-6">
                @csrf
                <textarea id="commentContent" name="content" rows="3" placeholder="Add a comment..."></textarea>
                <button type="submit" style="margin-top: 10px;">Post Comment</button>
            </form>
        </div>
    </div>
</div>
<!-- Report Modal -->
<div id="reportModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:400px; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative; margin:auto;">
        <button type="button" id="closeReportModal" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:rgb(45, 60, 95); cursor:pointer;">&times;</button>
        <h3 style="color:rgb(45, 60, 95); margin-bottom:1rem;">Report Post</h3>
        <form id="reportForm" method="POST" action="{{ route('posts.report', $post->post_id) }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <label for="reportReason" style="font-weight:600; color:#333;">Reason</label>
                <select id="reportReason" name="reason" required style="width:100%; padding:0.5rem; border-radius:6px; border:1px solid #ccc; margin-top:0.5rem;">
                    <option value="">Select a reason</option>
                    <option value="Spam">Spam</option>
                    <option value="Abusive">Abusive or harmful</option>
                    <option value="Off-topic">Off-topic</option>
                    <option value="Inappropriate">Inappropriate content</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label for="reportDetails" style="font-weight:600; color:#333;">Details (optional)</label>
                <textarea id="reportDetails" name="details" rows="3" style="width:100%; border-radius:6px; border:1px solid #ccc; padding:0.5rem;"></textarea>
            </div>
            <button type="submit" class="submit-report-btn" style="background:rgb(45, 60, 95); color:#fff; border:none; border-radius:6px; padding:0.6rem 1.5rem; font-weight:600; font-size:1rem; cursor:pointer; transition:background 0.2s;">Submit Report</button>
            <div id="reportError" style="color:#dc3545; margin-top:0.7rem; display:none;"></div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const upvoteForm = document.getElementById('upvoteForm');
    const downvoteForm = document.getElementById('downvoteForm');
    const upvoteBtn = document.getElementById('upvoteBtn');
    const downvoteBtn = document.getElementById('downvoteBtn');
    const upvoteCount = document.getElementById('upvoteCount');
    const downvoteCount = document.getElementById('downvoteCount');
    function showToast(msg, type) {
        const toast = document.createElement('div');
        toast.textContent = msg;
        toast.style.position = 'fixed';
        toast.style.bottom = '30px';
        toast.style.left = '50%';
        toast.style.transform = 'translateX(-50%)';
        toast.style.background = type === 'error' ? '#e74c3c' : '#2ecc71';
        toast.style.color = '#fff';
        toast.style.padding = '12px 24px';
        toast.style.borderRadius = '8px';
        toast.style.zIndex = 9999;
        toast.style.fontWeight = 'bold';
        toast.style.boxShadow = '0 2px 8px rgba(0,0,0,0.12)';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    }
    function handleVote(form, type) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const url = form.getAttribute('action');
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(async response => {
                let data;
                try {
                    data = await response.clone().json();
                } catch (err) {
                    // Try to parse as text and show error
                    const text = await response.text();
                    showToast('Vote failed: ' + (text || 'Unknown error'), 'error');
                    return;
                }
                if (data.success) {
                    upvoteCount.textContent = data.upvotes;
                    downvoteCount.textContent = data.downvotes;
                    upvoteBtn.classList.remove('voted');
                    downvoteBtn.classList.remove('voted');
                    if (data.userVote === 'upvote') {
                        upvoteBtn.classList.add('voted');
                    } else if (data.userVote === 'downvote') {
                        downvoteBtn.classList.add('voted');
                    }
                } else {
                    showToast(data.message || 'Vote failed', 'error');
                }
            })
            .catch(error => {
                showToast('Vote failed: ' + error, 'error');
            });
        });
    }
    if (upvoteForm && upvoteBtn) handleVote(upvoteForm, 'upvote');
    if (downvoteForm && downvoteBtn) handleVote(downvoteForm, 'downvote');

    // Comment AJAX
    const commentForm = document.getElementById('commentForm');
    const commentContent = document.getElementById('commentContent');
    if (commentForm && commentContent) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const url = commentForm.getAttribute('action');
            const content = commentContent.value.trim();
            if (!content) {
                showToast('Please enter a comment', 'error');
                return;
            }
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ comment_text: content })
            })
            .then(async response => {
                let data;
                try {
                    data = await response.clone().json();
                } catch (err) {
                    const text = await response.text();
                    showToast('Comment failed: ' + (text || 'Unknown error'), 'error');
                    return;
                }
                if (data.success && data.comment) {
                    // Append new comment
                    const commentsSection = commentForm.closest('.custom-comments-section');
                    const newComment = document.createElement('div');
                    newComment.className = 'custom-comment-block';
                    newComment.innerHTML = `<div><span class='custom-comment-user'>${data.comment.user}</span> <span class='custom-comment-date'>just now</span></div><div class='custom-comment-content'>${data.comment.text || content}</div>`;
                    commentsSection.insertBefore(newComment, commentForm);
                    commentContent.value = '';
                    showToast('Comment added!', 'success');
                } else {
                    showToast(data.message || 'Failed to add comment', 'error');
                }
            })
            .catch(error => {
                showToast('Comment failed: ' + error, 'error');
            });
        });
    }

    // Bookmark AJAX
    const bookmarkForm = document.getElementById('bookmarkForm');
    const bookmarkBtn = document.getElementById('bookmarkBtn');
    if (bookmarkForm && bookmarkBtn) {
        bookmarkForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const url = bookmarkForm.getAttribute('action');
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(async response => {
                let data;
                try {
                    data = await response.clone().json();
                } catch (err) {
                    const text = await response.text();
                    showToast('Bookmark failed: ' + (text || 'Unknown error'), 'error');
                    return;
                }
                if (data.success) {
                    if (data.isBookmarked) {
                        bookmarkBtn.innerHTML = '<i class="fas fa-bookmark"></i>';
                        bookmarkBtn.classList.add('bookmarked');
                    } else {
                        bookmarkBtn.innerHTML = '<i class="far fa-bookmark"></i>';
                        bookmarkBtn.classList.remove('bookmarked');
                    }
                    showToast(data.message || 'Bookmark updated!', 'success');
                } else {
                    showToast(data.message || 'Bookmark failed', 'error');
                }
            })
            .catch(error => {
                showToast('Bookmark failed: ' + error, 'error');
            });
        });
    }

    // Report Modal logic
    const reportBtn = document.getElementById('reportBtn');
    const reportModal = document.getElementById('reportModal');
    const closeReportModal = document.getElementById('closeReportModal');
    const reportForm = document.getElementById('reportForm');
    const reportReason = document.getElementById('reportReason');
    const reportDetails = document.getElementById('reportDetails');
    const reportError = document.getElementById('reportError');
    if (reportBtn && reportModal && closeReportModal && reportForm) {
        reportBtn.addEventListener('click', function() {
            reportModal.style.display = 'flex';
            reportError.style.display = 'none';
            reportReason.value = '';
            reportDetails.value = '';
        });
        closeReportModal.addEventListener('click', function() {
            reportModal.style.display = 'none';
        });
        reportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!reportReason.value) {
                reportError.textContent = 'Please select a reason.';
                reportError.style.display = 'block';
                return;
            }
            fetch(reportForm.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reason: reportReason.value, details: reportDetails.value })
            })
            .then(async response => {
                let data;
                try {
                    data = await response.clone().json();
                } catch (err) {
                    const text = await response.text();
                    reportError.textContent = text || 'Unknown error';
                    reportError.style.display = 'block';
                    return;
                }
                if (data.success) {
                    showToast('Report submitted!', 'success');
                    reportModal.style.display = 'none';
                } else {
                    reportError.textContent = data.message || 'Failed to submit report.';
                    reportError.style.display = 'block';
                }
            })
            .catch(error => {
                reportError.textContent = 'Report failed: ' + error;
                reportError.style.display = 'block';
            });
        });
    }

    // Follow/Unfollow button logic
    const followBtn = document.querySelector('.follow-btn[data-user-id]');
    if (followBtn) {
        followBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');
            const isFollowing = this.classList.contains('following');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = isFollowing ? `/user/${userId}/unfollow` : `/user/${userId}/follow`;
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(async response => {
                let data;
                try {
                    data = await response.clone().json();
                } catch (err) {
                    const text = await response.text();
                    showToast('Follow failed: ' + (text || 'Unknown error'), 'error');
                    return;
                }
                if (data.success) {
                    if (!isFollowing) {
                        this.textContent = 'Following';
                        this.classList.add('following');
                        showToast(data.message || 'Now following', 'success');
                    } else {
                        this.textContent = 'Follow';
                        this.classList.remove('following');
                        showToast(data.message || 'Unfollowed', 'success');
                    }
                } else {
                    showToast(data.message || 'Follow failed', 'error');
                }
            })
            .catch(error => {
                showToast('Follow failed: ' + error, 'error');
            });
        });
    }
});
</script>
@endpush

