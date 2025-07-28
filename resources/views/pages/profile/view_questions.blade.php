@extends('layouts.user_profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush
@section('title', $profileUser->name . ' - Questions')

@section('main_content')
<div class="top-bar">
    <h1 class="page-title">
        <i class="fas fa-question-circle"></i>
        <span>{{ $profileUser->name }}'s Questions</span>
    </h1>

</div>


<div class="profile-navigation">
    <a href="{{ route('user.profile', $profileUser->user_id) }}">Overview</a>
    <a href="{{ route('user.posts', $profileUser->user_id) }}">Posts</a>
    <a href="{{ route('user.questions', $profileUser->user_id) }}" class="active">Questions</a>
    <a href="{{ route('user.answers', $profileUser->user_id) }}">Answers</a>
</div>

<div class="page-content active">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Questions</h2>
    
    @forelse($questions as $question)
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">{{ $question['title'] }}</h3>
                <div class="card-date">{{ $question['created_at'] }}</div>
            </div>
            <div class="card-content">
                {!! Str::limit(strip_tags($question['description']), 200) !!}
            </div>
            <div class="card-stats">
                <span><i class="fas fa-comment"></i> {{ $question['answers'] }} answers</span>
                @if(!empty($question['tags']))
                    <div class="question-tags">
                        @foreach($question['tags'] as $tag)
                            <span class="tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('question', $question['id']) }}" class="view-link">View Question</a>
            </div>
        </div>
    @empty
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">No questions found.</h3>
            </div>
            <div class="card-content">
                This user hasn't asked any questions yet.
            </div>
        </div>
    @endforelse
</div>
@endsection

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
    
    .question-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .tag {
        background-color: #f0f0f0;
        color: #555;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
    }
    
    .view-link {
        margin-left: auto;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    .view-link:hover {
        text-decoration: underline;
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
        
        .card-stats {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .view-link {
            margin-left: 0;
        }
    }
</style>
@endpush