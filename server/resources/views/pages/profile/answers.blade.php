@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush

@section('title', 'Answers - User Profile')

@section('main_content')
<h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Answers</h2>
@forelse($answers as $answer)
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Re: {{ $answer->question->title ?? 'Question deleted' }}</h3>
            <div class="card-date">{{ $answer->created_at->format('M d, Y') }}</div>
        </div>
        <div class="card-content">
            {{ $answer->content }}
        </div>
        <div class="card-stats">
            <span><i class="fas fa-heart"></i> {{ $answer->upvotes }} upvotes</span>
            @if($answer->isAccepted())
                <span><i class="fas fa-check-circle" style="color:green;"></i> Accepted answer</span>
            @endif
        </div>
        <div class="card-actions">
            <button class="btn btn-primary"><i class="fas fa-edit"></i> Edit</button>
            <button class="btn btn-outline"><i class="fas fa-trash"></i> Delete</button>
        </div>
    </div>
@empty
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">You have not posted any answers yet.</h3>
        </div>
    </div>
@endforelse
@endsection 