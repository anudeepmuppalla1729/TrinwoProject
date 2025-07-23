@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
<style>
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    
    .delete-btn {
        color: #dc3545;
        border-color: #dc3545;
        transition: all 0.3s ease;
    }
    
    .delete-btn:hover {
        background-color: #dc3545;
        color: white;
    }
</style>
@endpush
@section('title', 'Questions - User Profile')

@php
use Illuminate\Support\Str;
@endphp

@section('main_content')
<div class="yque">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Questions</h2>
</div>


@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if(count($questions) > 0)
    @foreach($questions as $question)
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title"><a href="{{ route('question', ['id' => $question['id']]) }}">{{ $question['title'] }}</a></h3>
            <div class="card-date">{{ $question['created_at'] }}</div>
        </div>
        <div class="card-content">
            {{ Str::limit($question['description'], 200) }}
        </div>
        <div class="card-stats">
            <span><i class="fas fa-comment"></i> {{ $question['answers'] }} answers</span>
            <span><i class="fas fa-thumbs-up"></i> {{ $question['upvotes'] }} upvotes</span>
        </div>
        <div class="card-actions">
            <a href="{{ route('questions.edit', ['id' => $question['id']]) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
            <form action="{{ route('questions.destroy', ['id' => $question['id']]) }}" method="POST" style="display: inline;" class="delete-question-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline delete-btn"><i class="fas fa-trash"></i> Delete</button>
            </form>
        </div>
    </div>
    @endforeach
@else
    <div class="empty-state">
        <p>You haven't asked any questions yet.</p>
        <a href="{{ route('questions.create') }}" class="btn btn-primary">Ask a Question</a>
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to all delete buttons
        document.querySelectorAll('.delete-question-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                if (confirm('Are you sure you want to delete this question? This action cannot be undone and will delete all associated answers and votes.')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush
