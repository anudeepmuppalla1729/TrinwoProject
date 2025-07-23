@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush
@section('title', 'Followers - User Profile')

@section('main_content')
<div class="flw">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);" >Your Followers</h2>
</div>

<div class="user-grid">
    @forelse($followers as $follower)
        <div class="user-card">
            <div class="user-header"></div>
            <div class="user-body">
                <div class="user-name">{{ $follower->follower->name ?? 'Unknown' }}</div>
                <div class="user-title">{{ $follower->follower->expert_in ?? 'No title' }}</div>
                <div class="user-stats">
                    <div class="user-stat">
                        <div class="user-stat-value">{{ $follower->follower->posts->count() ?? 0 }}</div>
                        <div class="user-stat-label">Posts</div>
                    </div>
                    <div class="user-stat">
                        <div class="user-stat-value">{{ $follower->follower->followers->count() ?? 0 }}</div>
                        <div class="user-stat-label">Followers</div>
                    </div>
                </div>
                <button class="remove-follower-btn" data-follower-id="{{ $follower->follower_id }}" data-user-id="{{ $follower->follower_user_id }}" style="margin-top: 10px; background-color: #a522b7; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer;">Remove</button>
            </div>
        </div>
    @empty
        <div class="user-card">
            <div class="user-header"></div>
            <div class="user-body">
                <div class="user-name">You have no followers yet.</div>
            </div>
        </div>
    @endforelse
</div>
@endsection
