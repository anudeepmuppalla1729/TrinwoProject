@extends('layouts.app')
@section('title', 'Questions | TRINWOPJ')

@push('styles')
<style>
    .questions-container {
        width: 100%;
        padding: 20px;
        font-family: 'Segoe UI', sans-serif;
        background: #fff;

        border-radius: 12px;
    }
    
    h1 {
        font-size: 28px;
        font-weight: bold;
        color: #333;
        margin-bottom: 25px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }
    
    .question-card {
        background-color: rgb(236, 234, 234);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .question-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    
    .question-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        border-radius: 0 0 0 100px;
        z-index: 0;
    }
    
    .question-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        position: relative;
        z-index: 1;
        line-height: 1.3;
    }
    
    .question-title a {
        color: #333;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .question-title a:hover {
        color:  rgb(42, 60, 98);
        text-decoration: underline;
    }
    
    .question-meta {
        display: flex;
        justify-content: space-between;
        color: #777;
        font-size: 13px;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid black;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eaeaea;
    }
    
    .user-avatar i {
        font-size: 18px;
        color:  rgb(42, 60, 98);
    }
    
    .user-name {
        font-weight: 600;
        color: #333;
    }
    
    .question-excerpt {
        color: #444;
        line-height: 1.5;
        margin-bottom: 15px;
        font-size: 14px;
    }
    
    .question-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 13px;
        padding: 10px 0;
        margin-top: 10px;
        border-top: 1px solid black;
    }
    
    .stat {
        display: flex;
        align-items: center;
        gap: 5px;
        transition: transform 0.2s;
    }
    
    .stat:hover {

        color:  rgb(42, 60, 98);
    }
    
    .stat i {
        font-size: 16px;
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
        transition: all 0.2s ease;
    }
    
    .tag:hover {
        background-color: #d0e3f1;
        border-color: #39739d;
        transform: scale(1.05);
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 40px;
    }
    
    .page-btn {
        background: linear-gradient(135deg,  rgb(42, 60, 98),  rgb(42, 60, 98));
        border: none;
        color: white;
        padding: 10px 18px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: 500;
        box-shadow: 0 4px 10px  rgb(42, 60, 98);
        transition: all 0.3s ease;
        min-width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .page-btn:hover {
        background: linear-gradient(135deg,  rgb(42, 60, 98),  rgb(42, 60, 98));
        transform: translateY(-2px);
        box-shadow: 0 6px 15px  rgb(42, 60, 98);
    }
    
    .page-btn.disabled {
        background: linear-gradient(135deg, #d4d4d4, #bbbbbb);
        border: none;
        color: #888;
        cursor: not-allowed;
        box-shadow: none;
        opacity: 0.7;
    }
    
    .questions-header {
        margin-bottom: 30px;
    }
    
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .search-filter-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }
    
    .search-box {
    display: flex;
    width: 30vw;
    height: 5vh;
    align-items: center;
    background: #f5f5f5;
    border-radius: 30px;
    padding: 0 10px;
    border: 1px solid #eee;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
    
    .search-box input {
        border: none;
        background: transparent;
        flex: 1;
        padding: 12px 10px;
        font-size: 15px;
        outline: none;
    }
    
    .search-box i {
        color:  rgb(42, 60, 98);
        font-size: 16px;
    }
    
    .question-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid black;
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        background: none;
        border: none;
        color: #555;
        cursor: pointer;
        font-size: 13px;
        padding: 5px 8px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    
    .action-btn:hover {
        color:  rgb(42, 60, 98);
    }
    
    .bookmark-btn.bookmarked {
        color:  rgb(42, 60, 98);
    }
    
    .bookmark-btn.bookmarked i {
        color:  rgb(42, 60, 98);
    }
    
    .filter-options select {
        padding: 12px 20px;
        border: 1px solid #ddd;
        border-radius: 30px;
        font-size: 15px;
        background-color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23c92ae0' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        padding-right: 40px;
    }
    
    .filter-options select:focus {
        outline: none;
        border-color:  rgb(42, 60, 98);
        box-shadow: 0 2px 12px rgba(201, 42, 224, 0.15);
    }
    
    .ask-question-btn {
        background:  rgb(42, 60, 98);
        border: none;
        color: white;
        padding: 12px 24px;
        border-radius: 30px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px  rgb(42, 60, 98);
        transition: all 0.3s ease;
    }
    
    .ask-question-btn i {
        font-size: 18px;
    }
    
    .ask-question-btn:hover {
        background: linear-gradient(135deg,  rgb(42, 60, 98),  rgb(42, 60, 98));
        transform: translateY(-2px);
        box-shadow: 0 6px 20px  rgb(42, 60, 98);
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #555;
        text-decoration: none;
        margin-bottom: 20px;
        font-weight: 500;
        padding: 8px 15px;
        border-radius: 20px;
        transition: all 0.3s ease;
        background-color: #f5f5f5;
    }
    
    .back-btn i {
        transition: transform 0.3s ease;
    }
    
    .back-btn:hover {
        color:  rgb(42, 60, 98);
        background-color: #f0f0f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .back-btn:hover i {
        transform: translateX(-3px);
    }
   @media (max-width: 600px) {
    .search-filter-container{
      align-items:left;
    }
    .filter-options{
        width:75px;
     
    }
    #sort-questions{
        font-size:small;
        width:90px;
    }
    .stat span{
        font-size:13px;
        
    }
   }
</style>
@endpush

@section('content')
<div class="home_content">
    <div class="questions-container">

        
        <div class="questions-header">

            <div class="search-filter-container">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search questions..." id="question-search">
                </div>
                <div class="filter-options">
                    <select id="sort-questions">
                        <option value="newest">New</option>
                        <option value="popular">Most Popular</option>
                        <option value="answered">Most Answered</option>
                    </select>
                </div>
            </div>
        </div>
        
        {{-- Questions data is now passed from the controller --}}
        
        <div class="questions-list">
            @if(count($questions) > 0)
                @foreach($questions as $question)
                <div class="question-card">
                    <div class="question-meta">
                        <div class="user-info">
                            <a href="{{ route('user.profile', parameters: $question['user_id']) }}" style="text-decoration: none;">
                                <div class="user-avatar">
                                    @if($question['avatar'])
                                        <img src="{{ $question['avatar'] }}" alt="{{ $question['user'] }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    @else
                                        <i class="bi bi-person-fill"></i>
                                    @endif
                                </div>
                            </a>
                            <a href="{{ route('user.profile', $question['user_id']) }}" style="text-decoration: none;">
                                <span class="user-name">{{ $question['user'] }}</span>
                            </a>
                        </div>
                        <span>{{ $question['created_at'] }}</span>
                    </div>
                    <h2 class="question-title">
                        <a href="{{ route('question', ['id' => $question['id']]) }}">{{ $question['title'] }}</a>
                    </h2>
                    <div class="question-tags">
                        @foreach($question['tags'] as $tag)
                        <span class="tag">{{ $tag }}</span>
                        @endforeach
                    </div>

                    <div class="question-actions">
                        @auth
                        <form method="POST" action="{{ route('questions.bookmark', ['id' => $question['id']]) }}" class="d-inline bookmark-form">
                            @csrf
                            <button type="submit" class="action-btn bookmark-btn {{ $question['is_bookmarked'] ? 'bookmarked' : '' }}">
                                <i class="bi {{ $question['is_bookmarked'] ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                                <span>{{ $question['is_bookmarked'] ? 'Bookmarked' : 'Bookmark' }}</span>
                                
                            </button>
                        </form>
                        @endauth
                        <div class="stat" >
                            <i class="bi bi-chat-left-text"></i>
                            <span>{{ $question['answers'] }} answers</span>
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
                @endforeach
         
            @endif
        </div>

    </div>
</div>
    @auth
    <!-- Report Modal -->
    <div id="reportModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
        <div class="modal-content" style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:400px; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
            <button type="button" class="close-modal" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:rgb(42, 60, 98); cursor:pointer;">&times;</button>
            <h3 style="color:rgb(42, 60, 98); margin-bottom:1rem;">Report Question</h3>
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
                <button type="submit" class="submit-report-btn" style="background:rgb(42, 60, 98); color:#fff; border:none; border-radius:6px; padding:0.6rem 1.5rem; font-weight:600; font-size:1rem; cursor:pointer; transition:background 0.2s;">Submit Report</button>
                <div id="reportError" style="color:#dc3545; margin-top:0.7rem; display:none;"></div>
            </form>
        </div>
    </div>
    @endauth
@endsection

@push('scripts')
<script src="{{ asset('js/questions.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle pagination clicks
        document.querySelectorAll('.pagination .page-btn').forEach(button => {
            button.addEventListener('click', function() {
                if (!this.classList.contains('disabled')) {
                    showToast('Pagination feature coming soon!', 'info');
                }
            });
        });

        // Bookmark functionality with AJAX
        const bookmarkForms = document.querySelectorAll('.bookmark-form');
        bookmarkForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const url = this.getAttribute('action');
                const button = this.querySelector('.bookmark-btn');
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
        });

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
                currentAction = "{{ route('questions.report', ['id' => '__ID__']) }}".replace('__ID__', id);
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