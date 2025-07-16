@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush
@section('title', 'Followers - User Profile')

@section('main_content')
<h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Followers</h2>
<div class="user-grid">
    <div class="user-card">
        <div class="user-header"></div>
        <div class="user-body">
            <div class="user-name">Sarah Johnson</div>
            <div class="user-title">Frontend Developer</div>
            <div class="user-stats">
                <div class="user-stat">
                    <div class="user-stat-value">142</div>
                    <div class="user-stat-label">Posts</div>
                </div>
                <div class="user-stat">
                    <div class="user-stat-value">1.2K</div>
                    <div class="user-stat-label">Followers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="user-card">
        <div class="user-header"></div>
        <div class="user-body">
            <div class="user-name">Michael Chen</div>
            <div class="user-title">UX Designer</div>
            <div class="user-stats">
                <div class="user-stat">
                    <div class="user-stat-value">87</div>
                    <div class="user-stat-label">Posts</div>
                </div>
                <div class="user-stat">
                    <div class="user-stat-value">845</div>
                    <div class="user-stat-label">Followers</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 