@extends('layouts.app')
@section('title', 'Home | TRINWOPJ')
@section('content')
<div class="home_content">
    <div class="question-box">
        <input type="text" class="question-input" placeholder="Type Your Question or Insight here" />
        <i class="bi bi-person-circle user-icon"></i>
        <div class="question-actions">
            <button class="image-btn">
                <i class="fa-solid fa-image"></i> Add Image
            </button>
            <select class="privacy-select">
                <option value="public">Public</option>
                <option value="private">Private</option>
            </select>
            <button class="post-btn">Post</button>
        </div>
    </div>
    <div id="askModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <span class="modal-title">Ask Question</span>
                <button class="close-btn">&times;</button>
                <i class="fa-solid fa-image image-upload-icon"></i>
            </div>
            <hr />
            <div class="modal-options">
                <i class="bi bi-person-circle" style="font-size:1.8rem;"></i>
                <select class="privacy-select">
                    <option>Public</option>
                    <option>Private</option>
                </select>
            </div>
            <textarea class="question-textarea" placeholder="Type your question and insight here..."></textarea>
            <div class="modal-actions">
                <button class="cancel-btn">Cancel</button>
                <button class="post-btn">Post</button>
            </div>
        </div>
    </div>
    <div class="posts-container" id="postsContainer">
        <!-- Posts will be rendered here by JavaScript -->
    </div>
</div>
@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endpush
@endsection 