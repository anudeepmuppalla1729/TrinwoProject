@extends('layouts.app')
@section('title', 'Question Detail | TRINWOPJ')

@push('styles')
<style>
   

    .question-detail-container {
        width: 100%;
        margin: 5px auto;
        padding: 20px;
        font-family: 'Segoe UI', sans-serif;
        background: rgb(236, 234, 234);
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    .d-inline {
        display: inline;
    }
    
    .alert {
        padding: 10px 15px;
        margin-bottom: 15px;
        border-radius: 5px;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .question-header {
        margin-bottom: 5px;
    }
    
    .question-title {
        font-weight: 600;
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 5px;
        margin-left: 10px;
        line-height: 1.3;
    }
    
    .question-meta {
        display: flex;
        justify-content: space-between;
        color: #777;
        font-size: 13px;
        margin-bottom: 12px;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eaeaea;
    }
    
    .user-avatar i {
        font-size: 20px;
        color: #c92ae0;
    }
    
    .user-details {
        display: flex;
        flex-direction: column;
    }
    
    .user-name {
        font-weight: 600;
        color: #333;
        text-decoration: none;
    }
    
    .user-name:hover {
        color: #c92ae0;
        text-decoration: underline;
    }
    
    .user-location {
        font-size: 12px;
        color: #666;
    }
    
    .question-content {
        background-color: rgb(236, 234, 234);
        border-radius: 12px;
        padding-left: 15px;
        padding-right: 15px;
        padding-bottom: 15px;
        padding-top: 10px;
        margin-bottom: 20px;
        margin-top: 0;
    }
    
    .question-description {

        background-color: rgb(230, 220, 230);
    }
    
    .question-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px solid #eee;
    }
    
    .action-btn {
        background: none;
        border: none;
        color: #555;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        padding: 5px 8px;
        margin-right: 12px;
        border-radius: 4px;
        transition: background-color 0.2s, color 0.2s;
    }
    
    .action-btn:hover {
        color: #c92ae0;
        background-color: rgba(201, 42, 224, 0.05);
    }
    
    .comment-btn:hover {
        color: #f39c12;
        background-color: rgba(243, 156, 18, 0.05);
    }
    
    .upvote-btn:hover {
        color: #2ecc71;
    }
    
    .downvote-btn:hover {
        color: #e74c3c;
    }
    
    .report-btn:hover {
        color: #dc3545;
        background-color: rgba(220, 53, 69, 0.05);
    }
    
    .bookmark-btn.bookmarked {
        color: #c92ae0;
    }
    
    .bookmark-btn:hover {
        color: #c92ae0;
        background-color: rgba(201, 42, 224, 0.05);
    }
    
    .share-btn:hover {
        color: #3498db;
        background-color: rgba(52, 152, 219, 0.05);
    }
    
    .bookmark-btn.bookmarked i {
        color: #c92ae0;
    }
    
    .question-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
        margin-bottom: 10px;
    }
    
    .tag {
        background-color: #f0f0f0;
        color: #555;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 13px;
    }
    
    .answers-section {
        margin-top: 30px;
    }
    
    .answers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .answers-count {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }
    
    .sort-options {
        display: flex;
        gap: 8px;
    }
    
    .sort-option {
        background: none;
        border: 1px solid #eee;
        border-radius: 20px;
        padding: 5px 12px;
        cursor: pointer;
        color: #555;
        font-size: 13px;
        transition: all 0.2s ease;
    }
    
    .sort-option.active {
        background-color: #c92ae0;
        color: white;
        border-color: #c92ae0;
    }
    
    .sort-option:hover {
        background-color: #f5f5f5;
        border-color: #ddd;
    }
    
    .answer-card {
        background-color:rgb(253, 251, 251);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .accepted-answer {
        border-left: 4px solid #2ecc71;
    }
    
    .accepted-badge {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #2ecc71;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .answer-content {
        color: #444;
        line-height: 1.5;
        margin-bottom: 15px;
        font-size: 14px;
    }
    
    .answer-meta {
        display: flex;
        justify-content: space-between;
        color: #777;
        font-size: 13px;
        margin-bottom: 12px;
    }
    
    .answer-user-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .answer-user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eaeaea;
    }
    
    .accepted-answer .answer-user-avatar {
        border: 2px solid #2ecc71;
    }
    
    .answer-user-avatar i {
        font-size: 18px;
        color: #2ecc71;
    }
    
    .answer-user-name {
        font-weight: 600;
        color: #333;
    }
    
    .answer-actions {
        margin-top: 12px;
        padding-top: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-top: 1px solid #eee;
    }
    
    .answer-form {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        padding: 15px;
        margin-top: 25px;
    }
    
    .answer-form textarea {
        width: 100%;
        height: 150px;
        
        padding: 12px;
        border: 1px solid #eee;
        border-radius: 10px;
        resize: vertical;
        margin-bottom: 15px;
        font-family: 'Segoe UI', sans-serif;
        font-size: 14px;
        outline: none;
    }
    
    .submit-answer {
        background: linear-gradient(135deg, #c92ae0, #a522b7);
        border: none;
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .submit-answer:hover {
        background: linear-gradient(135deg, #a522b7, #8e1e9e);
        box-shadow: 0 2px 8px rgba(201, 42, 224, 0.3);
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #555;
        text-decoration: none;
        margin-bottom: 15px;
        padding: 6px 12px;
        border-radius: 20px;
        background-color: #f5f5f5;
        transition: all 0.3s ease;
    }
    
    .back-btn:hover {
        color: #c92ae0;
        background-color: #f0f0f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .back-btn:hover i {
        transform: translateX(-3px);
        transition: transform 0.3s ease;
    }
    
    @media (max-width: 600px) {
        .answers-count {
        font-size: 5px;
        font-weight: 400;
        color: #333;
    }

    }

</style>
@endpush

@section('content')
<div class="home_content">
<div class="question-detail-container">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    
    <a href="{{ route('questions') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i> Back to Questions
    </a>
    
    {{-- Question and answers data is now passed from the controller --}}
    
    <div class="question-header">
        <div class="question-meta">
            <div class="user-info">
                <a href="{{ route('user.profile', $question['user_id']) }}" style="text-decoration: none;">
                    <div class="user-avatar">
                        @if(!empty($question['avatar']))
                            <img src="{{ $question['avatar'] }}" alt="User" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($question['user']) }}&size=36" alt="User" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                        @endif
                    </div>
                </a>
                <div class="user-details">
                    <a href="{{ route('user.profile', $question['user_id']) }}" class="user-name">{{ $question['user'] }}</a>
                    <span class="user-location">{{ $question['user_location'] }}</span>
                </div>
            </div>
            <span>{{ $question['created_at'] }}</span>
        </div>
        <h2 class="question-title" >{{ $question['title'] }}</h2>
    </div>
    <div class="question-content">

        <div class="question-description">{{ $question['description'] }}</div>
        <div class="question-tags">
            @foreach($question['tags'] as $tag)
            <span class="tag">{{ $tag }}</span>
            @endforeach
        </div>
        <div class="question-actions">
            <form method="POST" action="{{ route('questions.bookmark', ['id' => $question['id']]) }}" class="d-inline bookmark-form">
                @csrf
                <button type="submit" class="action-btn bookmark-btn {{ $question['is_bookmarked'] ? 'bookmarked' : '' }}">
                    <i class="bi {{ $question['is_bookmarked'] ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                    <span>{{ $question['is_bookmarked'] ? 'Bookmarked' : 'Bookmark' }}</span>
                </button>
            </form>
            <button class="action-btn share-btn">
                <i class="bi bi-share"></i>
                <span>Share</span>
            </button>
            @auth
            <form method="POST" action="{{ route('questions.report', ['id' => $question['id']]) }}" class="d-inline report-form">
                @csrf
                <button type="button" class="action-btn report-btn" data-type="question" data-id="{{ $question['id'] }}">
                    <i class="fas fa-flag"></i>
                    <span>Report</span>
                </button>
            </form>
            @endauth
        </div>
    </div>
    
    <div class="answers-section">
        <div class="answers-header">
            <h2 class="answers-count">{{ count($answers) }} Answers</h2>
            <div class="sort-options">
                <button class="sort-option active">Highest Votes</button>
                <button class="sort-option">Newest</button>
                <button class="sort-option">Oldest</button>
            </div>
        </div>
        
        @foreach($answers as $answer)
        <div class="answer-card {{ $answer['is_accepted'] ? 'accepted-answer' : '' }}">
            @if($answer['is_accepted'])
            <div class="accepted-badge">
                <i class="bi bi-check-circle-fill"></i>
                <span>Accepted Answer</span>
            </div>
            @endif
            <div class="answer-meta">
                <div class="answer-user-info">
                    <a href="{{ route('user.profile', $answer['user_id']) }}" style="text-decoration: none;">
                        <div class="answer-user-avatar">
                            @if(!empty($answer['avatar']))
                                <img src="{{ $answer['avatar'] }}" alt="User" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($answer['user']) }}&size=32" alt="User" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                            @endif
                        </div>
                    </a>
                    <a href="{{ route('user.profile', $answer['user_id']) }}" style="text-decoration: none; font-weight: 600; color: #333;">
                        <span class="answer-user-name">{{ $answer['user'] }}</span>
                    </a>
                </div>
                <span>{{ $answer['created_at'] }}</span>
            </div>
            <div class="answer-content">{{ $answer['content'] }}</div>
            <div class="answer-actions">
                <form action="{{ route('answers.upvote', $answer['id']) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="action-btn upvote-btn">
                        <i class="bi bi-arrow-up-circle"></i>
                        <span>{{ $answer['upvotes'] }}</span>
                    </button>
                </form>
                <form action="{{ route('answers.downvote', $answer['id']) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="action-btn downvote-btn">
                        <i class="bi bi-arrow-down-circle"></i>
                        <span>{{ $answer['downvotes'] }}</span>
                    </button>
                </form>
                <button class="action-btn comment-btn">
                    <i class="bi bi-chat-dots"></i>
                    <span>Comment</span>
                </button>
                <button class="action-btn share-btn">
                    <i class="bi bi-share-fill"></i>
                    <span>Share</span>
                </button>
                <form method="POST" action="{{ route('answers.report', ['id' => $answer['id']]) }}" class="d-inline report-form">
                    @csrf
                    <button type="button" class="action-btn report-btn" data-type="answer" data-id="{{ $answer['id'] }}">
                        <i class="fas fa-flag"></i>
                        <span>Report</span>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="answer-form">
        <h3>Your Answer</h3>
        <form id="post-answer-form" action="{{ route('answers.store', $question['id']) }}" method="POST">
            @csrf
            <textarea name="content" placeholder="Write your answer here..." required></textarea>
            <button type="submit" class="submit-answer">Post Your Answer</button>
        </form>
    </div>
</div>
</div>
@auth
<!-- Report Modal -->
<div id="reportModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
    <div class="modal-content" style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:400px; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
        <button type="button" class="close-modal" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:#c92ae0; cursor:pointer;">&times;</button>
        <h3 style="color:#c92ae0; margin-bottom:1rem;">Report <span id="reportTypeLabel"></span></h3>
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
@endsection

@push('scripts')
<script src="{{ asset('js/question.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let reportModal = document.getElementById('reportModal');
        let reportForm = document.getElementById('reportForm');
        let reportTypeLabel = document.getElementById('reportTypeLabel');
        let reportIdInput = document.getElementById('reportIdInput');
        let reasonSelect = document.getElementById('reasonSelect');
        let detailsInput = document.getElementById('detailsInput');
        let reportError = document.getElementById('reportError');
        let currentAction = '';
        document.querySelectorAll('.report-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let type = btn.getAttribute('data-type');
                let id = btn.getAttribute('data-id');
                reportTypeLabel.textContent = type.charAt(0).toUpperCase() + type.slice(1);
                reportIdInput.value = id;
                // Set form action
                if(type === 'question') {
                    currentAction = "{{ route('questions.report', ['id' => '__ID__']) }}".replace('__ID__', id);
                } else if(type === 'answer') {
                    currentAction = "{{ route('answers.report', ['id' => '__ID__']) }}".replace('__ID__', id);
                }
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
        
        // Bookmark functionality with AJAX
        const bookmarkForm = document.querySelector('.bookmark-form');
        if (bookmarkForm) {
            bookmarkForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = this;
                const url = form.getAttribute('action');
                const button = form.querySelector('.bookmark-btn');
                const icon = button.querySelector('i');
                const text = button.querySelector('span');
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button appearance
                        if (data.isBookmarked) {
                            button.classList.add('bookmarked');
                            icon.classList.remove('bi-bookmark');
                            icon.classList.add('bi-bookmark-fill');
                            text.textContent = 'Bookmarked';
                        } else {
                            button.classList.remove('bookmarked');
                            icon.classList.remove('bi-bookmark-fill');
                            icon.classList.add('bi-bookmark');
                            text.textContent = 'Bookmark';
                        }
                        
                        // Show success message
                        showToast(data.message, 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
    });
</script>
@endpush
