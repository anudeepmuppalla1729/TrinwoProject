@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush
@section('title', 'Following - User Profile')

@section('main_content')
<h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">People You Are Following</h2>
<div class="user-grid">
    <div class="user-card">
        <div class="user-header"></div>
        <div class="user-body">
            <div class="user-name">John Doe</div>
            <div class="user-title">Backend Developer</div>
            <div class="user-stats">
                <div class="user-stat">
                    <div class="user-stat-value">54</div>
                    <div class="user-stat-label">Posts</div>
                </div>
                <div class="user-stat">
                    <div class="user-stat-value">1.1K</div>
                    <div class="user-stat-label">Followers</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add more following users as needed -->
</div>
@endsection 