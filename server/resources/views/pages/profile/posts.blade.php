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
</div>



<div class="profile-navigation">
    <a href="{{ route('profile.dashboard', Auth::id()) }}">Overview</a>
    <a href="{{ route('profile.posts', Auth::id()) }}" class="active">Posts</a>
    <a href="{{ route('profile.questions', Auth::id()) }}">Questions</a>
    <a href="{{ route('profile.answers', Auth::id()) }}">Answers</a>
</div>

<div class="page-content active">
    @forelse($posts as $post)
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">{{ $post->title }}</h3>
                <div class="card-date">{{ $post->created_at->format('M d, Y') }}</div>
            </div>
            <div class="card-content">
                {{ Str::limit(strip_tags($post->content), 200) }}
            </div>
            <div class="card-stats">
                <span><i class="fas fa-heart"></i> {{ $post->upvotes ?? 0 }} upvotes</span>
                <span><i class="fas fa-comment"></i> {{ $post->comments->count() }} comments</span>
                <div class="card-actions">
                    <a href="{{ route('posts.show', $post->post_id) }}" class="view-link">View Post</a>
                    <form method="POST" action="{{ route('posts.destroy', $post->post_id) }}" class="delete-post-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="action-btn delete-btn" title="Delete post" data-post-id="{{ $post->post_id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @auth
                    <span class="post-options">
                        <i class="bi bi-three-dots-vertical"></i>
                        <div class="options-menu">
                            <button class="remove-bookmark" data-post-id="{{ $post->post_id }}">Remove Bookmark</button><hr>
                            <button>Copy Link</button><hr>
                            <form method="POST" action="{{ route('posts.report', ['id' => $post->post_id]) }}" class="d-inline report-form">
                                @csrf
                                <button type="button" class="action-btn report-btn" data-type="post" data-id="{{ $post->post_id }}"> 
                                    <i class="fas fa-flag"></i> Report
                                </button>
                            </form>
                        </div>
                    </span>
                    @endauth
                </div>
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
                You haven't created any posts yet.
            </div>
        </div>
    @endforelse
</div>
@auth
<!-- Report Modal -->
<div id="reportModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; opacity:0; transition:opacity 0.3s ease;">
    <div class="modal-content" style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:400px; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative; transform:translateY(20px); transition:transform 0.3s ease;">
        <button type="button" class="close-modal" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:var(--primary-color); cursor:pointer;">&times;</button>
        <h3 style="color:var(--primary-color); margin-bottom:1rem;">Report Post</h3>
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
            <button type="submit" class="submit-report-btn" style="background-color:var(--primary-color); color:#fff; border:none; border-radius:6px; padding:0.6rem 1.5rem; font-weight:600; font-size:1rem; cursor:pointer; transition:background 0.2s;">Submit Report</button>
            <div id="reportError" style="color:#dc3545; margin-top:0.7rem; display:none;"></div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; opacity:0; transition:opacity 0.3s ease;">
    <div class="modal-content" style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:400px; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative; transform:translateY(20px); transition:transform 0.3s ease;">
        <button type="button" class="close-delete-modal" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:var(--primary-color); cursor:pointer;">&times;</button>
        <h3 style="color:#dc3545; margin-bottom:1rem;"><i class="fas fa-exclamation-triangle"></i> Delete Post</h3>
        <p style="margin-bottom:1.5rem; color:#333;">Are you sure you want to delete this post? This action cannot be undone.</p>
        <div style="display:flex; justify-content:flex-end; gap:1rem;">
            <button type="button" class="cancel-delete-btn" style="background:none; border:1px solid #ccc; border-radius:6px; padding:0.6rem 1.5rem; font-weight:600; font-size:1rem; cursor:pointer; transition:all 0.2s;" onmouseover="this.style.backgroundColor='#f8f8f8'" onmouseout="this.style.backgroundColor='transparent'">Cancel</button>
            <button type="button" class="confirm-delete-btn" style="background-color:#dc3545; color:#fff; border:none; border-radius:6px; padding:0.6rem 1.5rem; font-weight:600; font-size:1rem; cursor:pointer; transition:background 0.2s;" onmouseover="this.style.backgroundColor='#c82333'" onmouseout="this.style.backgroundColor='#dc3545'">Delete</button>
        </div>
        <input type="hidden" id="deletePostId">
    </div>
</div>
@endauth
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
    
    .content-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .card-title {
        margin: 0;
        color: var(--primary-dark);
        font-size: 1.25rem;
    }
    
    .card-date {
        color: #777;
        font-size: 0.9rem;
    }
    
    .card-content {
        margin-bottom: 1.5rem;
        color: #444;
        line-height: 1.6;
    }
    
    .card-stats {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        color: #666;
        font-size: 0.9rem;
    }
    
    .card-stats span {
        margin-right: 1.5rem;
    }
    
    .card-stats i {
        margin-right: 0.5rem;
        color: var(--primary-color);
    }
    
    .card-actions {
        display: flex;
        align-items: center;
        margin-left: auto;
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
        margin-right: 10px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    .view-link:hover {
        text-decoration: underline;
    }
    
    .action-btn.delete-btn {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 1rem;
        padding: 0.25rem 0.5rem;
        margin-right: 10px;
    }
    
    .post-options {
        position: relative;
        cursor: pointer;
    }
    
    .post-options .bi-three-dots-vertical {
        font-size: 1.2rem;
        cursor: pointer;
    }
    
    .options-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 24px;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        min-width: 120px;
        z-index: 10;
    }
    
    .options-menu button {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
    }
    
    .options-menu .report-btn {
        color: #dc3545;
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Report modal functionality
        let reportModal = document.getElementById('reportModal');
        let reportForm = document.getElementById('reportForm');
        let reportIdInput = document.getElementById('reportIdInput');
        let reasonSelect = document.getElementById('reasonSelect');
        let detailsInput = document.getElementById('detailsInput');
        let reportError = document.getElementById('reportError');
        let currentAction = '';
        
        // Delete confirmation modal functionality
        let deleteConfirmModal = document.getElementById('deleteConfirmModal');
        let deletePostId = document.getElementById('deletePostId');
        
        // Delete button functionality
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let postId = this.getAttribute('data-post-id');
                deletePostId.value = postId;
                deleteConfirmModal.style.display = 'flex';
                // Trigger reflow to ensure transition works
                void deleteConfirmModal.offsetWidth;
                deleteConfirmModal.style.opacity = '1';
                // Animate modal content
                const modalContent = deleteConfirmModal.querySelector('.modal-content');
                setTimeout(() => {
                    modalContent.style.transform = 'translateY(0)';
                }, 10);
            });
        });
        
        // Close delete modal button
        document.querySelector('.close-delete-modal').addEventListener('click', function() {
            closeDeleteModal();
        });
        
        // Cancel delete button
        document.querySelector('.cancel-delete-btn').addEventListener('click', function() {
            closeDeleteModal();
        });
        
        // Function to close delete modal with animation
        function closeDeleteModal() {
            deleteConfirmModal.style.opacity = '0';
            const modalContent = deleteConfirmModal.querySelector('.modal-content');
            modalContent.style.transform = 'translateY(20px)';
            setTimeout(() => {
                deleteConfirmModal.style.display = 'none';
            }, 300); // Match transition duration
        }
        
        // Confirm delete button
        document.querySelector('.confirm-delete-btn').addEventListener('click', function() {
            let postId = deletePostId.value;
            let form = document.querySelector(`.delete-post-form button[data-post-id="${postId}"]`).closest('form');
            form.submit();
        });
        
        // Close delete modal on outside click
        deleteConfirmModal.addEventListener('click', function(e) {
            if(e.target === deleteConfirmModal) closeDeleteModal();
        });
        
        // Options menu toggle
        document.querySelectorAll('.post-options .bi-three-dots-vertical').forEach(dots => {
            dots.addEventListener('click', function(e) {
                e.stopPropagation();
                const optionsMenu = this.nextElementSibling;
                // Close all other open menus first
                document.querySelectorAll('.options-menu').forEach(menu => {
                    if (menu !== optionsMenu) {
                        menu.style.display = 'none';
                    }
                });
                // Toggle this menu
                optionsMenu.style.display = optionsMenu.style.display === 'none' ? 'block' : 'none';
            });
        });
        
        // Close options menu when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.options-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        });
        
        // Prevent menu from closing when clicking inside it
        document.querySelectorAll('.options-menu').forEach(menu => {
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
        
        // Report button functionality
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
                // Trigger reflow to ensure transition works
                void reportModal.offsetWidth;
                reportModal.style.opacity = '1';
                // Animate modal content
                const modalContent = reportModal.querySelector('.modal-content');
                setTimeout(() => {
                    modalContent.style.transform = 'translateY(0)';
                }, 10);
            });
        });
        
        // Function to close report modal with animation
        function closeReportModal() {
            reportModal.style.opacity = '0';
            const modalContent = reportModal.querySelector('.modal-content');
            modalContent.style.transform = 'translateY(20px)';
            setTimeout(() => {
                reportModal.style.display = 'none';
            }, 300); // Match transition duration
        }
        
        // Close report modal button
        document.querySelector('.close-modal').addEventListener('click', function() {
            closeReportModal();
        });
        
        // Report form validation
        reportForm.addEventListener('submit', function(e) {
            if(!reasonSelect.value) {
                e.preventDefault();
                reportError.textContent = 'Please select a reason.';
                reportError.style.display = 'block';
                return false;
            }
        });
        
        // Close report modal on outside click
        reportModal.addEventListener('click', function(e) {
            if(e.target === reportModal) closeReportModal();
        });
        
        // Copy link functionality
        document.querySelectorAll('.options-menu button').forEach(button => {
            if (button.textContent.trim() === 'Copy Link') {
                button.addEventListener('click', function() {
                    const postId = this.closest('.options').querySelector('.remove-bookmark').getAttribute('data-post-id');
                    const url = `${window.location.origin}/posts/${postId}`;
                    navigator.clipboard.writeText(url).then(() => {
                        alert('Link copied to clipboard!');
                    }).catch(err => {
                        console.error('Could not copy text: ', err);
                    });
                });
            }
        });
    });
</script>
@endpush
@endsection