@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush
@section('title', 'Following - User Profile | Inqube')

@section('main_content')
<div class="fol">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">People You Are Following</h2>
</div>

<div class="user-grid">
    @forelse($following as $followed)
        <div class="user-card">
            <div class="user-header"></div>
            <div class="user-body">
                <div class="user-name">{{ $followed->user->name ?? 'Unknown' }}</div>
                <div class="user-title">{{ $followed->user->expert_in ?? 'No title' }}</div>
                <div class="user-stats">
                    <div class="user-stat">
                        <div class="user-stat-value">{{ $followed->user->posts->count() ?? 0 }}</div>
                        <div class="user-stat-label">Posts</div>
                    </div>
                    <div class="user-stat">
                        <div class="user-stat-value">{{ $followed->user->followers->count() ?? 0 }}</div>
                        <div class="user-stat-label">Followers</div>
                    </div>
                </div>
                <button class="unfollow-btn" data-user-id="{{ $followed->user_id }}" style="margin-top: 10px; background-color: #a522b7; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer;">Unfollow</button>
            </div>
        </div>
    @empty
        <div class="user-card">
            <div class="user-header"></div>
            <div class="user-body">
                <div class="user-name">You are not following anyone yet.</div>
            </div>
        </div>
    @endforelse
</div>
@endsection
