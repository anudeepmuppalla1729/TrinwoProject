@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush
@section('title', 'Questions - User Profile')

@section('main_content')
<h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Questions</h2>
<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">How to implement JWT authentication securely?</h3>
        <div class="card-date">May 14, 2023</div>
    </div>
    <div class="card-content">
        I'm building a Node.js application and want to implement JWT authentication. What are the security best practices I should follow? How should I handle token expiration and refresh tokens?
    </div>
    <div class="card-stats">
        <span><i class="fas fa-eye"></i> 1.2K views</span>
        <span><i class="fas fa-comment"></i> 8 answers</span>
    </div>
    <div class="card-actions">
        <button class="btn btn-primary"><i class="fas fa-edit"></i> Edit</button>
        <button class="btn btn-outline"><i class="fas fa-trash"></i> Delete</button>
    </div>
</div>
@endsection 