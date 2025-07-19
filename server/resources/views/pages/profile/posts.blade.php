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
                    @auth
                    <span class="options" style="position:relative;">
                        <i class="bi bi-three-dots-vertical" style="font-size:1.2rem; cursor:pointer;"></i>
                        <div class="options-menu" style="display:none; position:absolute; right:0; top:24px; background:#fff; border:1px solid #eee; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.08); min-width:120px; z-index:10;">
                            <button class="remove-bookmark" data-post-id="{{ $post->post_id }}">Remove Bookmark</button><hr>
                            <button>Copy Link</button><hr>
                            <form method="POST" action="{{ route('posts.report', ['id' => $post->post_id]) }}" class="d-inline report-form" style="width:100%;">
                                @csrf
                                <button type="button" class="action-btn report-btn" data-type="post" data-id="{{ $post->post_id }}" style="width:100%; text-align:left; color:#dc3545; background:none; border:none; padding:8px 12px;"> <i class="fas fa-flag"></i> Report</button>
                            </form>
                        </div>
                    </span>
                    @endauth
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
@auth
<!-- Report Modal -->
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let reportModal = document.getElementById('reportModal');
        let reportForm = document.getElementById('reportForm');
        let reportIdInput = document.getElementById('reportIdInput');
        let reasonSelect = document.getElementById('reasonSelect');
        let detailsInput = document.getElementById('detailsInput');
        let reportError = document.getElementById('reportError');
        let currentAction = '';
        document.querySelectorAll('.report-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let id = btn.getAttribute('data-id');
                reportIdInput.value = id;
                // Set form action
                currentAction = "{{ route('posts.report', ['id' => '__ID__']) }}".replace('__ID__', id);
                reportForm.action = currentAction;
                reasonSelect.value = '';
                detailsInput.value = '';
                reportError.style.display = 'none';
                reportModal.style.display = 'flex';
            });
        });
        document.querySelector('.close-modal').addEventListener('click', function() {
            reportModal.style.display = 'none';
        });
        reportForm.addEventListener('submit', function(e) {
            if(!reasonSelect.value) {
                e.preventDefault();
                reportError.textContent = 'Please select a reason.';
                reportError.style.display = 'block';
                return false;
            }
        });
        // Close modal on outside click
        reportModal.addEventListener('click', function(e) {
            if(e.target === reportModal) reportModal.style.display = 'none';
        });
    });
</script>
@endpush
@endsection 