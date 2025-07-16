@extends('layouts.app')
@section('title', 'Questions | TRINWOPJ')

@push('styles')
<style>
    .questions-container {
        width: 100%;

        padding: 20px;
        font-family: 'Segoe UI', sans-serif;
        background: white;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
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
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 25px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
        border-left: 5px solid #c92ae0;
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
        background: linear-gradient(135deg, transparent 70%, rgba(201, 42, 224, 0.1) 100%);
        border-radius: 0 0 0 100px;
        z-index: 0;
    }
    
    .question-title {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        margin-bottom: 12px;
        position: relative;
        z-index: 1;
    }
    
    .question-title a {
        color: #333;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .question-title a:hover {
        color: #c92ae0;
        text-decoration: underline;
    }
    
    .question-meta {
        display: flex;
        justify-content: space-between;
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #eee;
    }
    
    .question-meta span:first-child {
        font-weight: 500;

    }
    
    .question-excerpt {
        color: #444;
        line-height: 1.7;
        margin-bottom: 18px;
        font-size: 15px;
    }
    
    .question-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 14px;
        background-color: #f9f9f9;
        padding: 12px 15px;
        border-radius: 8px;
        margin-top: 15px;
    }
    
    .stat {
        display: flex;
        align-items: center;
        gap: 5px;
        transition: transform 0.2s;
    }
    
    .stat:hover {
        transform: translateY(-2px);
        color: #c92ae0;
    }
    
    .stat i {
        font-size: 16px;
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
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 12px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
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
        background: linear-gradient(135deg, #c92ae0, #a522b7);
        border: none;
        color: white;
        padding: 10px 18px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: 500;
        box-shadow: 0 4px 10px rgba(201, 42, 224, 0.3);
        transition: all 0.3s ease;
        min-width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .page-btn:hover {
        background: linear-gradient(135deg, #a522b7, #8e1e9e);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(201, 42, 224, 0.4);
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
        color: #c92ae0;
        font-size: 16px;
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
        border-color: #c92ae0;
        box-shadow: 0 2px 12px rgba(201, 42, 224, 0.15);
    }
    
    .ask-question-btn {
        background: linear-gradient(135deg, #c92ae0, #a522b7);
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
        box-shadow: 0 4px 15px rgba(201, 42, 224, 0.3);
        transition: all 0.3s ease;
    }
    
    .ask-question-btn i {
        font-size: 18px;
    }
    
    .ask-question-btn:hover {
        background: linear-gradient(135deg, #a522b7, #8e1e9e);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(201, 42, 224, 0.4);
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
        color: #c92ae0;
        background-color: #f0f0f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .back-btn:hover i {
        transform: translateX(-3px);
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
                        <option value="newest">Newest</option>
                        <option value="popular">Most Popular</option>
                        <option value="answered">Most Answered</option>
                    </select>
                </div>
            </div>
        </div>
        
        @php
        // Dummy questions data
        $questions = [
            [
                'id' => 1,
                'title' => 'How does artificial intelligence impact job markets?',
                'excerpt' => 'I am curious about the long-term effects of AI on employment across different sectors. Which industries are most vulnerable to automation?',
                'user' => 'TechEnthusiast',
                'created_at' => '2 days ago',
                'views' => 120,
                'answers' => 5,
                'upvotes' => 15,
                'downvotes' => 2,
                'tags' => ['artificial-intelligence', 'jobs', 'automation']
            ],
            [
                'id' => 2,
                'title' => 'What are the best practices for sustainable agriculture?',
                'excerpt' => 'Looking for modern techniques that balance productivity with environmental conservation in farming.',
                'user' => 'EcoFarmer',
                'created_at' => '1 week ago',
                'views' => 85,
                'answers' => 3,
                'upvotes' => 10,
                'downvotes' => 0,
                'tags' => ['agriculture', 'sustainability', 'farming']
            ],
            [
                'id' => 3,
                'title' => 'How will Blockchain Technology impact the future of industries?',
                'excerpt' => 'Blockchain technology is more than just the foundation of cryptocurrencies. It represents a decentralized, tamper-proof digital ledger capable of transforming how industries operate.',
                'user' => 'InnovTech',
                'created_at' => '3 days ago',
                'views' => 210,
                'answers' => 8,
                'upvotes' => 25,
                'downvotes' => 3,
                'tags' => ['blockchain', 'finance', 'technology']
            ],
            [
                'id' => 4,
                'title' => 'What are effective strategies for mental health management?',
                'excerpt' => 'Seeking evidence-based approaches for maintaining good mental health in high-stress environments.',
                'user' => 'WellnessAdvocate',
                'created_at' => '5 days ago',
                'views' => 175,
                'answers' => 12,
                'upvotes' => 32,
                'downvotes' => 1,
                'tags' => ['mental-health', 'wellness', 'stress-management']
            ],
            [
                'id' => 5,
                'title' => 'How can renewable energy be integrated into existing power grids?',
                'excerpt' => 'What are the technical and policy challenges of incorporating solar and wind energy into traditional electricity distribution systems?',
                'user' => 'GreenEngineer',
                'created_at' => '1 day ago',
                'views' => 65,
                'answers' => 2,
                'upvotes' => 8,
                'downvotes' => 0,
                'tags' => ['renewable-energy', 'power-grid', 'sustainability']
            ]
        ];
        @endphp
        
        <div class="questions-list">
            @foreach($questions as $question)
            <div class="question-card">
                <h2 class="question-title">
                    <a href="{{ route('question', ['id' => $question['id']]) }}">{{ $question['title'] }}</a>
                </h2>
                <div class="question-meta">
                    <span>Asked by {{ $question['user'] }}</span>
                    <span>{{ $question['created_at'] }}</span>
                </div>
                <div class="question-excerpt">{{ $question['excerpt'] }}</div>
                <div class="question-tags">
                    @foreach($question['tags'] as $tag)
                    <span class="tag">{{ $tag }}</span>
                    @endforeach
                </div>
                <div class="question-stats">
                    <div class="stat">
                        <i class="bi bi-eye"></i>
                        <span>{{ $question['views'] }} views</span>
                    </div>
                    <div class="stat">
                        <i class="bi bi-chat-left-text"></i>
                        <span>{{ $question['answers'] }} answers</span>
                    </div>
                    <div class="stat">
                        <i class="bi bi-arrow-up"></i>
                        <span>{{ $question['upvotes'] }} upvotes</span>
                    </div>
                    <div class="stat">
                        <i class="bi bi-arrow-down"></i>
                        <span>{{ $question['downvotes'] }} downvotes</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="pagination">
            <button class="page-btn disabled">Previous</button>
            <button class="page-btn">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">Next</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/questions.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle pagination clicks
        document.querySelectorAll('.pagination .page-btn').forEach(button => {
            button.addEventListener('click', function() {
                if (!this.classList.contains('disabled')) {
                    alert('Pagination feature coming soon!');
                }
            });
        });
    });
</script>
@endpush