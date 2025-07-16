@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
@endpush

@section('title', 'Answers - User Profile')

@section('main_content')
<h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Answers</h2>
<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">Re: How to center a div?</h3>
        <div class="card-date">May 15, 2023</div>
    </div>
    <div class="card-content">
        There are several ways to center a div. The modern approach is to use flexbox. Simply apply "display: flex; justify-content: center; align-items: center;" to the parent container.
    </div>
    <div class="card-stats">
        <span><i class="fas fa-heart"></i> 64 upvotes</span>
        <span><i class="fas fa-check-circle"></i> Accepted answer</span>
    </div>
    <div class="card-actions">
        <button class="btn btn-primary"><i class="fas fa-edit"></i> Edit</button>
        <button class="btn btn-outline"><i class="fas fa-trash"></i> Delete</button>
    </div>
</div>
<div class="content-card">
    <div class="card-header">
        <h3 class="card-title">Re: Difference between let, const and var</h3>
        <div class="card-date">May 12, 2023</div>
    </div>
    <div class="card-content">
        The main differences are in scoping and reassignment. "var" is function-scoped, while "let" and "const" are block-scoped. "const" cannot be reassigned after declaration...
    </div>
    <div class="card-stats">
        <span><i class="fas fa-heart"></i> 42 upvotes</span>
    </div>
    <div class="card-actions">
        <button class="btn btn-primary"><i class="fas fa-edit"></i> Edit</button>
        <button class="btn btn-outline"><i class="fas fa-trash"></i> Delete</button>
    </div>
</div>
@endsection 