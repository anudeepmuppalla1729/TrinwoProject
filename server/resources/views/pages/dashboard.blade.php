@extends('layouts.app')
@section('title', 'Home | TRINWOPJ')
@section('content')
<div class="home_content">
    <div class="question-box">
        <input type="text" class="insight-btn question-input" placeholder="Type Your Question or Insight here" />
        <i class="bi bi-person-circle user-icon"></i>

    </div>
    <div class="posts-container" id="postsContainer">
        <!-- Posts will be rendered here by JavaScript -->
    </div>
</div>
@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endpush
@endsection