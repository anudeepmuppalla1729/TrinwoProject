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
    <!-- Post 1 -->
    <div class="post-card">
        <div class="post-header">
            <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Web Development" class="post-image">
            <div class="post-category">Tutorial</div>
        </div>
        <div class="post-body">
            <div class="post-meta">
                <div class="post-date">
                    <i class="far fa-calendar"></i> May 15, 2023
                </div>
                <div class="post-read-time">
                    <i class="far fa-clock"></i> 8 min read
                </div>
            </div>
            <h3 class="post-title">Mastering React Hooks: A Comprehensive Guide</h3>
            <p class="post-excerpt">
                Learn how to effectively use React Hooks to simplify your components and manage state without classes. This guide covers useState, useEffect, and custom hooks with practical examples.
            </p>
        </div>
        <div class="post-footer">
            <div class="post-stats">
                <div class="post-stat">
                    <i class="far fa-eye"></i> 2.4K
                </div>
                <div class="post-stat">
                    <i class="far fa-comment"></i> 87
                </div>
                <div class="post-stat">
                    <i class="fas fa-heart"></i> 324
                </div>
            </div>
            <div class="post-actions">
                <button class="action-btn">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Post 2 -->
    <div class="post-card">
        <div class="post-header">
            <img src="https://images.unsplash.com/photo-1547658719-da2b51169166?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="UI Design" class="post-image">
            <div class="post-category">Design</div>
        </div>
        <div class="post-body">
            <div class="post-meta">
                <div class="post-date">
                    <i class="far fa-calendar"></i> May 10, 2023
                </div>
                <div class="post-read-time">
                    <i class="far fa-clock"></i> 6 min read
                </div>
            </div>
            <h3 class="post-title">The Future of UI: Neumorphism and Beyond</h3>
            <p class="post-excerpt">
                Explore the latest trends in UI design, from neumorphism to glassmorphism. Discover how these styles create depth and realism in digital interfaces and when to use them effectively.
            </p>
        </div>
        <div class="post-footer">
            <div class="post-stats">
                <div class="post-stat">
                    <i class="far fa-eye"></i> 3.7K
                </div>
                <div class="post-stat">
                    <i class="far fa-comment"></i> 124
                </div>
                <div class="post-stat">
                    <i class="fas fa-heart"></i> 512
                </div>
            </div>
            <div class="post-actions">
                <button class="action-btn">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Post 3 -->
    <div class="post-card">
        <div class="post-header">
            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="JavaScript" class="post-image">
            <div class="post-category">Tech</div>
        </div>
        <div class="post-body">
            <div class="post-meta">
                <div class="post-date">
                    <i class="far fa-calendar"></i> May 5, 2023
                </div>
                <div class="post-read-time">
                    <i class="far fa-clock"></i> 10 min read
                </div>
            </div>
            <h3 class="post-title">JavaScript Performance Optimization Techniques</h3>
            <p class="post-excerpt">
                Discover advanced techniques to optimize your JavaScript applications. Learn about lazy loading, memoization, debouncing, and other methods to improve your app's performance.
            </p>
        </div>
        <div class="post-footer">
            <div class="post-stats">
                <div class="post-stat">
                    <i class="far fa-eye"></i> 5.2K
                </div>
                <div class="post-stat">
                    <i class="far fa-comment"></i> 92
                </div>
                <div class="post-stat">
                    <i class="fas fa-heart"></i> 687
                </div>
            </div>
            <div class="post-actions">
                <button class="action-btn">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Post 4 -->
    <div class="post-card">
        <div class="post-header">
            <img src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Web Design" class="post-image">
            <div class="post-category">Design</div>
        </div>
        <div class="post-body">
            <div class="post-meta">
                <div class="post-date">
                    <i class="far fa-calendar"></i> Apr 28, 2023
                </div>
                <div class="post-read-time">
                    <i class="far fa-clock"></i> 7 min read
                </div>
            </div>
            <h3 class="post-title">Responsive Design Principles for Modern Websites</h3>
            <p class="post-excerpt">
                Learn the core principles of responsive web design that every developer should know. From fluid grids to flexible images, master the techniques that make websites work on any device.
            </p>
        </div>
        <div class="post-footer">
            <div class="post-stats">
                <div class="post-stat">
                    <i class="far fa-eye"></i> 4.1K
                </div>
                <div class="post-stat">
                    <i class="far fa-comment"></i> 76
                </div>
                <div class="post-stat">
                    <i class="fas fa-heart"></i> 421
                </div>
            </div>
            <div class="post-actions">
                <button class="action-btn">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Post 5 -->
    <div class="post-card">
        <div class="post-header">
            <img src="https://images.unsplash.com/photo-1550439062-609e1531270e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Node.js" class="post-image">
            <div class="post-category">Backend</div>
        </div>
        <div class="post-body">
            <div class="post-meta">
                <div class="post-date">
                    <i class="far fa-calendar"></i> Apr 22, 2023
                </div>
                <div class="post-read-time">
                    <i class="far fa-clock"></i> 9 min read
                </div>
            </div>
            <h3 class="post-title">Building RESTful APIs with Node.js and Express</h3>
            <p class="post-excerpt">
                Step-by-step guide to creating robust RESTful APIs using Node.js and Express. Learn about routing, middleware, error handling, and best practices for API development.
            </p>
        </div>
        <div class="post-footer">
            <div class="post-stats">
                <div class="post-stat">
                    <i class="far fa-eye"></i> 3.2K
                </div>
                <div class="post-stat">
                    <i class="far fa-comment"></i> 58
                </div>
                <div class="post-stat">
                    <i class="fas fa-heart"></i> 289
                </div>
            </div>
            <div class="post-actions">
                <button class="action-btn">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Post 6 -->
    <div class="post-card">
        <div class="post-header">
            <img src="https://images.unsplash.com/photo-1579403124614-197f69d8187b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="CSS" class="post-image">
            <div class="post-category">Frontend</div>
        </div>
        <div class="post-body">
            <div class="post-meta">
                <div class="post-date">
                    <i class="far fa-calendar"></i> Apr 15, 2023
                </div>
                <div class="post-read-time">
                    <i class="far fa-clock"></i> 5 min read
                </div>
            </div>
            <h3 class="post-title">Advanced CSS Grid Techniques for Layouts</h3>
            <p class="post-excerpt">
                Unlock the full potential of CSS Grid with these advanced techniques. Learn how to create complex responsive layouts with minimal code and maximum flexibility.
            </p>
        </div>
        <div class="post-footer">
            <div class="post-stats">
                <div class="post-stat">
                    <i class="far fa-eye"></i> 2.9K
                </div>
                <div class="post-stat">
                    <i class="far fa-comment"></i> 42
                </div>
                <div class="post-stat">
                    <i class="fas fa-heart"></i> 198
                </div>
            </div>
            <div class="post-actions">
                <button class="action-btn">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection 