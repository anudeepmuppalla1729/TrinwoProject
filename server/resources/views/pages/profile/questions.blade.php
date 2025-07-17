@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush
@section('title', 'Questions - User Profile')

@section('main_content')
<h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Questions</h2>
@forelse($questions as $question)
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">{{ $question->title }}</h3>
            <div class="card-date">{{ $question->created_at->format('M d, Y') }}</div>
        </div>
        <div class="card-content">
            {{ $question->description }}
        </div>
        <div class="card-stats">
            <span><i class="fas fa-comment"></i> {{ $question->answers->count() }} answers</span>
        </div>
        <div class="card-actions">
            <button class="btn btn-primary"><i class="fas fa-edit"></i> Edit</button>
            <button class="btn btn-outline"><i class="fas fa-trash"></i> Delete</button>
        </div>
    </div>
@empty
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">You have not posted any questions yet.</h3>
        </div>
    </div>
@endforelse
@endsection 