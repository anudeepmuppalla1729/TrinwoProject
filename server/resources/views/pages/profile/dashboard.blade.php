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
            <div class="value">142</div>
            <div class="label">Questions Asked</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-comments"></i>
            <div class="value">327</div>
            <div class="label">Answers Given</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-file-alt"></i>
            <div class="value">42</div>
            <div class="label">Posts Created</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-star"></i>
            <div class="value">1,248</div>
            <div class="label">Total Upvotes</div>
        </div>
    </div>
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Recent Activity</h2>
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">How to optimize React performance?</h3>
            <div class="card-date">May 12, 2023</div>
        </div>
        <div class="card-content">
            I've been working on a large React application and noticed some performance issues, especially with rendering large lists. What are the best practices for optimizing React performance in such scenarios?
        </div>
        <div class="card-stats">
            <span><i class="fas fa-eye"></i> 2.4K views</span>
            <span><i class="fas fa-comment"></i> 24 answers</span>
            <span><i class="fas fa-heart"></i> 142 upvotes</span>
        </div>
    </div>
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Answered: Best practices for REST API security</h3>
            <div class="card-date">May 10, 2023</div>
        </div>
        <div class="card-content">
            When building RESTful APIs, security should be a top priority. Always implement HTTPS to encrypt data in transit. Use API keys for simple authentication between servers...
        </div>
        <div class="card-stats">
            <span><i class="fas fa-eye"></i> 3.1K views</span>
            <span><i class="fas fa-heart"></i> 87 upvotes</span>
            <span><i class="fas fa-check-circle"></i> Accepted answer</span>
        </div>
    </div>
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">The future of web development in 2023</h3>
            <div class="card-date">May 8, 2023</div>
        </div>
        <div class="card-content">
            Web development continues to evolve at a rapid pace. This year, we're seeing significant advancements in serverless architectures, edge computing, and AI-powered development tools...
        </div>
        <div class="card-stats">
            <span><i class="fas fa-eye"></i> 5.7K views</span>
            <span><i class="fas fa-comment"></i> 32 comments</span>
            <span><i class="fas fa-share"></i> 84 shares</span>
        </div>
    </div>
</div>
@endsection 