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
    
    .close-btn {
        color: #dc3545;
        border-color: #dc3545;
        transition: all 0.3s ease;
        margin-left: 8px;
    }
    
    .close-btn:hover {
        background-color: #dc3545;
        color: white;
    }
    
    .reopen-btn {
        color: #28a745;
        border-color: #28a745;
        transition: all 0.3s ease;
        margin-left: 8px;
    }
    
    .reopen-btn:hover {
        background-color: #28a745;
        color: white;
    }
    
    .closed-badge {
        display: inline-block;
        background-color: #dc3545;
        color: white;
        font-size: 12px;
        padding: 3px 6px;
        border-radius: 4px;
        margin-left: 8px;
        vertical-align: middle;
    }
</style>
@endpush
@section('title', 'Questions - User Profile | Inqube')

@php
use Illuminate\Support\Str;
@endphp

@section('main_content')
<div class="yque">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Questions</h2>
</div>




@if(count($questions) > 0)
    @foreach($questions as $question)
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">
                <a href="{{ route('question', ['id' => $question['id']]) }}">{{ $question['title'] }}</a>
                @if($question['is_closed'])
                <span class="closed-badge"><i class="fas fa-lock"></i> Closed</span>
                @endif
            </h3>
            <div class="card-date">{{ $question['created_at'] }}</div>
        </div>
        <div class="card-content">
            {!! Str::limit(strip_tags($question['description']), 200) !!}
        </div>
        <div class="card-stats">
            <span><i class="fas fa-comment"></i> {{ $question['answers'] }} answers</span>
            <span><i class="fas fa-thumbs-up"></i> {{ $question['upvotes'] }} upvotes</span>
        </div>
        <div class="card-actions">
            <form action="{{ route('questions.destroy', ['id' => $question['id']]) }}" method="POST" style="display: inline;" class="delete-question-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-outline delete-btn" data-question-id="{{ $question['id'] }}"><i class="fas fa-trash"></i> Delete</button>
            </form>
            
            @if($question['is_closed'])
                <form method="POST" action="{{ route('questions.reopen', ['id' => $question['id']]) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline reopen-btn"><i class="bi bi-unlock"></i> Reopen</button>
                </form>
            @else
                <form method="POST" action="{{ route('questions.close', ['id' => $question['id']]) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline close-btn"><i class="bi bi-lock"></i> Close</button>
                </form>
            @endif
        </div>
    </div>
    @endforeach
@else
    <div class="empty-state">
        <p>You haven't asked any questions yet.</p>
        <br>
        <a href="{{ route('questions.create') }}" class="btn btn-primary" style="text-decoration: none;">Ask a Question</a>
    </div>
@endif
@endsection

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; opacity:0; transition:opacity 0.3s ease;">
    <div class="modal-content" style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:400px; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative; transform:translateY(20px); transition:transform 0.3s ease;">
        <button type="button" class="close-delete-modal" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:var(--primary-color); cursor:pointer;">&times;</button>
        <h3 style="color:#dc3545; margin-bottom:1rem;"><i class="fas fa-exclamation-triangle"></i> Delete Question</h3>
        <p style="margin-bottom:1.5rem; color:#333;">Are you sure you want to delete this question? This action cannot be undone and will delete all associated answers and votes.</p>
        <div style="display:flex; justify-content:flex-end; gap:1rem;">
            <button type="button" class="cancel-delete-btn" style="background:none; border:1px solid #ccc; border-radius:6px; padding:0.6rem 1.5rem; font-weight:600; font-size:1rem; cursor:pointer; transition:all 0.2s;" onmouseover="this.style.backgroundColor='#f8f8f8'" onmouseout="this.style.backgroundColor='transparent'">Cancel</button>
            <button type="button" class="confirm-delete-btn" style="background-color:#dc3545; color:#fff; border:none; border-radius:6px; padding:0.6rem 1.5rem; font-weight:600; font-size:1rem; cursor:pointer; transition:background 0.2s;" onmouseover="this.style.backgroundColor='#c82333'" onmouseout="this.style.backgroundColor='#dc3545'">Delete</button>
        </div>
        <input type="hidden" id="deleteQuestionId">
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation modal functionality
        let deleteConfirmModal = document.getElementById('deleteConfirmModal');
        let deleteQuestionId = document.getElementById('deleteQuestionId');
        
        // Delete button functionality
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let questionId = this.getAttribute('data-question-id');
                deleteQuestionId.value = questionId;
                deleteConfirmModal.style.display = 'flex';
                // Trigger reflow to ensure transition works
                void deleteConfirmModal.offsetWidth;
                deleteConfirmModal.style.opacity = '1';
                // Animate modal content
                const modalContent = deleteConfirmModal.querySelector('.modal-content');
                setTimeout(() => {
                    modalContent.style.transform = 'translateY(0)';
                }, 10);
            });
        });
        
        // Function to close delete modal with animation
        function closeDeleteModal() {
            deleteConfirmModal.style.opacity = '0';
            const modalContent = deleteConfirmModal.querySelector('.modal-content');
            modalContent.style.transform = 'translateY(20px)';
            setTimeout(() => {
                deleteConfirmModal.style.display = 'none';
            }, 300); // Match transition duration
        }
        
        // Close delete modal button
        document.querySelector('.close-delete-modal').addEventListener('click', function() {
            closeDeleteModal();
        });
        
        // Cancel delete button
        document.querySelector('.cancel-delete-btn').addEventListener('click', function() {
            closeDeleteModal();
        });
        
        // Confirm delete button
        document.querySelector('.confirm-delete-btn').addEventListener('click', function() {
            let questionId = deleteQuestionId.value;
            let form = document.querySelector(`.delete-btn[data-question-id="${questionId}"]`).closest('form');
            form.submit();
        });
        
        // Close delete modal on outside click
        deleteConfirmModal.addEventListener('click', function(e) {
            if(e.target === deleteConfirmModal) closeDeleteModal();
        });
    });
</script>
@endpush
