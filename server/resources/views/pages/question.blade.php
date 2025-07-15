@extends('layouts.app')
@section('title', 'Question Detail | TRINWOPJ')

@push('styles')
<style>
    .question-detail-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        font-family: 'Segoe UI', sans-serif;
        background: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
    <a href="{{ route('questions') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i> Back to Questions
    </a>
    
    @php
    // Dummy question data
    $question = [
        'id' => 3,
        'title' => 'How will Blockchain Technology impact the future of industries?',
        'description' => 'Blockchain technology is more than just the foundation of cryptocurrencies. It represents a decentralized, tamper-proof digital ledger capable of transforming how industries operate. From financial institutions to healthcare providers and supply chains, the impact of blockchain is expected to be revolutionary.',
        'user' => 'InnovTech',
        'user_location' => 'Bengaluru, Karnataka',
        'created_at' => '3 days ago',
        'views' => 210,
        'upvotes' => 25,
        'downvotes' => 3,
        'tags' => ['blockchain', 'finance', 'technology']
    ];
    
    // Dummy answers
    $answers = [
        [
            'id' => 1,
            'content' => 'Blockchain helps remove intermediaries, reduce fraud, and secure transactions using its decentralized ledger system. It\'s transforming supply chains, finance, and even voting systems.',
            'user' => 'TechExpert',
            'created_at' => '2 days ago',
            'upvotes' => 12,
            'downvotes' => 1,
            'is_accepted' => false
        ],
        [
            'id' => 2,
            'content' => 'Blockchain will fundamentally change how data and transactions are verified, secured, and shared across industries. Its decentralized and transparent nature eliminates the need for middlemen, reduces fraud, and enables real-time traceability. In the future, companies will use blockchain to improve security, streamline processes, and enhance trust between parties — unlocking new efficiencies and creating entirely new business models.',
            'user' => 'BlockchainDev',
            'created_at' => '1 day ago',
            'upvotes' => 18,
            'downvotes' => 0,
            'is_accepted' => true
        ],
        [
            'id' => 3,
            'content' => 'While blockchain has potential, we should be cautious about overhyping it. Many industries are still figuring out practical applications, and challenges like scalability and energy consumption remain. It\'s promising but not a silver bullet for every problem.',
            'user' => 'RealisticAnalyst',
            'created_at' => '12 hours ago',
            'upvotes' => 5,
            'downvotes' => 2,
            'is_accepted' => false
        ]
    ];
    @endphp
    
    <div class="question-header">
        <h1 class="question-title">{{ $question['title'] }}</h1>
        <div class="question-meta">
            <span>Asked by {{ $question['user'] }} from {{ $question['user_location'] }}</span>
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
            <button class="action-btn upvote-btn">
                <i class="bi bi-arrow-up"></i>
                <span>{{ $question['upvotes'] }}</span>
            </button>
            <button class="action-btn downvote-btn">
                <i class="bi bi-arrow-down"></i>
                <span>{{ $question['downvotes'] }}</span>
            </button>
            <button class="action-btn bookmark-btn">
                <i class="bi bi-bookmark"></i>
                <span>Bookmark</span>
            </button>
            <button class="action-btn share-btn">
                <i class="bi bi-share"></i>
                <span>Share</span>
            </button>
            <div class="stat">
                <i class="bi bi-eye"></i>
                <span>{{ $question['views'] }} views</span>
            </div>
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
                <span>Answered by {{ $answer['user'] }}</span>
                <span>{{ $answer['created_at'] }}</span>
            </div>
            <div class="answer-content">{{ $answer['content'] }}</div>
            <div class="answer-actions">
                <button class="action-btn upvote-btn">
                    <i class="bi bi-arrow-up"></i>
                    <span>{{ $answer['upvotes'] }}</span>
                </button>
                <button class="action-btn downvote-btn">
                    <i class="bi bi-arrow-down"></i>
                    <span>{{ $answer['downvotes'] }}</span>
                </button>
                <button class="action-btn comment-btn">
                    <i class="bi bi-chat"></i>
                    <span>Comment</span>
                </button>
                <button class="action-btn share-btn">
                    <i class="bi bi-share"></i>
                    <span>Share</span>
                </button>
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
@endsection

@push('scripts')
<script src="{{ asset('js/question.js') }}"></script>
@endpush