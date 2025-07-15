@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush
@section('title', 'Bookmarks - User Profile')

@section('main_content')
<h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Bookmarks</h2>
<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">No bookmarks yet</h3>
    </div>
    <div class="card-content">
        You haven't bookmarked any posts or questions yet.
    </div>
</div>
@endsection 