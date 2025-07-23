@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush
@section('title', 'Dashboard - User Profile')

@section('main_content')
<div class="top-bar">
    <h1 class="page-title">
        <i class="fas fa-th-large"></i>
        <span>Dashboard Overview</span>
    </h1>
    <div class="search-bar">
        <input type="text" placeholder="Search content...">
        <button><i class="fas fa-search"></i></button>  
    </div>
</div>
<!-- Dashboard Page -->
<div id="dashboard" class="page-content active">
    <div class="dashboard-stats">
        <div class="stat-card">
            <i class="fas fa-question-circle"></i>
            <div class="value">{{ $questionsCount }}</div>
            <div class="label">Questions Asked</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-comments"></i>
            <div class="value">{{ $answersCount }}</div>
            <div class="label">Answers Given</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-file-alt"></i>
            <div class="value">{{ $postsCount }}</div>
            <div class="label">Posts Created</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-star"></i>
            <div class="value">{{ $totalUpvotes }}</div>
            <div class="label">Total Upvotes</div>
        </div>
    </div>
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Recent Activity</h2>
    @forelse($recentActivity as $activity)
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">
                    @if(isset($activity->title))
                        {{ $activity->title }}
                    @elseif(isset($activity->heading))
                        {{ $activity->heading }}
                    @else
                        Answered: {{ Str::limit(strip_tags($activity->content ?? ''), 40) }}
                    @endif
                </h3>
                <div class="card-date">{{ $activity->created_at->format('M d, Y') }}</div>
            </div>
            <div class="card-content">
                @if(isset($activity->description))
                    {{ Str::limit(strip_tags($activity->description), 120) }}
                @elseif(isset($activity->details))
                    {{ Str::limit(strip_tags($activity->details), 120) }}
                @elseif(isset($activity->content))
                    {{ Str::limit(strip_tags($activity->content), 120) }}
                @endif
            </div>
            <div class="card-stats">
                @if(isset($activity->heading) || isset($activity->content))
                    @if(isset($activity->upvotes))
                        <span><i class="fas fa-heart"></i> {{ $activity->upvotes }} upvotes</span>
                    @endif
                @endif
                @if(isset($activity->title) && isset($activity->answers))
                    <span><i class="fas fa-comment"></i> {{ $activity->answers->count() }} responses</span>
                @endif
            </div>
        </div>
    @empty
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">No recent activity found.</h3>
            </div>
        </div>
    @endforelse
</div>
@endsection 