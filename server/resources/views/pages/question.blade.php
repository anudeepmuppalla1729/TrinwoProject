@extends('layouts.app')
@section('title', 'Question Detail | TRINWOPJ')

@push('styles')
<style>
    .question-detail-container {
        width: 915px;
        margin: 5px auto;
        padding: 20px;
        font-family: 'Segoe UI', sans-serif;
        background: white;
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
        margin-bottom: 20px;
    }
    
    .question-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }
    
    .question-meta {
        display: flex;
        justify-content: space-between;
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #c92ae0;
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
    }
    
    .user-location {
        font-size: 12px;
        color: #666;
    }
    
    .question-content {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .question-description {
        color: #444;
        line-height: 1.6;
        margin-bottom: 15px;
    }
    
    .question-actions {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        background: none;
        border: none;
        color: #555;
        cursor: pointer;
        font-size: 14px;
    }
    
    .action-btn:hover {
        color: #c92ae0;
    }
    
    .question-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 15px;
    }
    
    .tag {
        background-color: #e1ecf4;
        color: #39739d;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    
    .answers-section {
        margin-top: 30px;
    }
    
    .answers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .answers-count {
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }
    
    .sort-options {
        display: flex;
        gap: 10px;
    }
    
    .sort-option {
        background: none;
        border: none;
        color: #555;
        cursor: pointer;
        font-size: 14px;
        padding: 5px;
    }
    
    .sort-option.active {
        color: #c92ae0;
        border-bottom: 2px solid #c92ae0;
    }
    
    .answer-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
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
        line-height: 1.6;
        margin-bottom: 15px;
    }
    
    .answer-meta {
        display: flex;
        justify-content: space-between;
        color: #666;
        font-size: 14px;
        margin-bottom: 10px;
    }
    
    .answer-user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .answer-user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
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
        display: flex;
        gap: 15px;
        margin-top: 15px;
    }
    
    .answer-form {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-top: 30px;
    }
    
    .answer-form textarea {
        width: 100%;
        height: 150px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        resize: vertical;
        margin-bottom: 15px;
        font-family: 'Segoe UI', sans-serif;
        font-size: 14px;
    }
    
    .submit-answer {
        background: #c92ae0;
        border: 2px solid #a522b7;
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .submit-answer:hover {
        background-color: #a522b7;
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #555;
        text-decoration: none;
        margin-bottom: 15px;
    }
    
    .back-btn:hover {
        color: #c92ae0;
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
        <h1 class="question-title">{{ $question['title'] }}</h1>
        <div class="question-meta">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="user-details">
                    <span class="user-name">{{ $question['user'] }}</span>
                    <span class="user-location">{{ $question['user_location'] }}</span>
                </div>
            </div>
            <span>{{ $question['created_at'] }}</span>
        </div>
    </div>
    
    <div class="question-content">
        <div class="question-description">{{ $question['description'] }}</div>
        <div class="question-tags">
            @foreach($question['tags'] as $tag)
            <span class="tag">{{ $tag }}</span>
            @endforeach
        </div>
        <div class="question-actions">
            <button class="action-btn bookmark-btn">
                <i class="bi bi-bookmark"></i>
                <span>Bookmark</span>
            </button>
            <button class="action-btn share-btn">
                <i class="bi bi-share"></i>
                <span>Share</span>
            </button>
            <div class="stat">
                <i class="bi bi-hand-thumbs-up"></i>
                <span>{{ $question['upvotes'] }} upvotes</span>
            </div>
            <div class="stat">
                <i class="bi bi-hand-thumbs-down"></i>
                <span>{{ $question['downvotes'] }} downvotes</span>
            </div>
            @auth
            <form method="POST" action="{{ route('questions.report', ['id' => $question['id']]) }}" class="d-inline report-form">
                @csrf
                <button type="button" class="action-btn report-btn" data-type="question" data-id="{{ $question['id'] }}">
                    <i class="fas fa-flag"></i> Report
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
                    <div class="answer-user-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span class="answer-user-name">{{ $answer['user'] }}</span>
                </div>
                <span>{{ $answer['created_at'] }}</span>
            </div>
            <div class="answer-content">{{ $answer['content'] }}</div>
            <div class="answer-actions">
                <form action="{{ route('answers.upvote', $answer['id']) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="action-btn upvote-btn">
                        <i class="bi bi-arrow-up"></i>
                        <span>{{ $answer['upvotes'] }}</span>
                    </button>
                </form>
                <form action="{{ route('answers.downvote', $answer['id']) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="action-btn downvote-btn">
                        <i class="bi bi-arrow-down"></i>
                        <span>{{ $answer['downvotes'] }}</span>
                    </button>
                </form>
                <button class="action-btn comment-btn">
                    <i class="bi bi-chat"></i>
                    <span>Comment</span>
                </button>
                <button class="action-btn share-btn">
                    <i class="bi bi-share"></i>
                    <span>Share</span>
                </button>
                <form method="POST" action="{{ route('answers.report', ['id' => $answer['id']]) }}" class="d-inline report-form">
                    @csrf
                    <button type="button" class="action-btn report-btn" data-type="answer" data-id="{{ $answer['id'] }}">
                        <i class="fas fa-flag"></i> Report
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
    });
</script>
@endpush