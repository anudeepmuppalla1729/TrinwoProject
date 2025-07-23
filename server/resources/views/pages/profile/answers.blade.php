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

@section('title', 'Answers - User Profile')

@section('main_content')
<div class="yans">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Answers</h2>
</div>



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
            <a href="{{ route('question', ['id' => $answer->question_id]) }}" class="btn btn-primary" style="text-decoration: none;"><i class="fas fa-eye" ></i> View</a>
            <form action="{{ route('answers.destroy', ['id' => $answer->answer_id]) }}" method="POST" style="display: inline;" class="delete-answer-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-outline delete-btn" data-answer-id="{{ $answer->answer_id }}"><i class="fas fa-trash"></i> Delete</button>
            </form>
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

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; opacity:0; transition:opacity 0.3s ease;">
    <div class="modal-content" style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:400px; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative; transform:translateY(20px); transition:transform 0.3s ease;">
        <button type="button" class="close-delete-modal" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:var(--primary-color); cursor:pointer;">&times;</button>
        <h3 style="color:#dc3545; margin-bottom:1rem;"><i class="fas fa-exclamation-triangle"></i> Delete Answer</h3>
        <p style="margin-bottom:1.5rem; color:#333;">Are you sure you want to delete this answer? This action cannot be undone.</p>
        <div style="display:flex; justify-content:flex-end; gap:1rem;">
            <button type="button" class="cancel-delete-btn" style="background:none; border:1px solid #ccc; border-radius:6px; padding:0.6rem 1.5rem; font-weight:600; font-size:1rem; cursor:pointer; transition:all 0.2s;" onmouseover="this.style.backgroundColor='#f8f8f8'" onmouseout="this.style.backgroundColor='transparent'">Cancel</button>
            <button type="button" class="confirm-delete-btn" style="background-color:#dc3545; color:#fff; border:none; border-radius:6px; padding:0.6rem 1.5rem; font-weight:600; font-size:1rem; cursor:pointer; transition:background 0.2s;" onmouseover="this.style.backgroundColor='#c82333'" onmouseout="this.style.backgroundColor='#dc3545'">Delete</button>
        </div>
        <input type="hidden" id="deleteAnswerId">
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation modal functionality
        let deleteConfirmModal = document.getElementById('deleteConfirmModal');
        let deleteAnswerId = document.getElementById('deleteAnswerId');
        
        // Delete button functionality
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let answerId = this.getAttribute('data-answer-id');
                deleteAnswerId.value = answerId;
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
            let answerId = deleteAnswerId.value;
            let form = document.querySelector(`.delete-btn[data-answer-id="${answerId}"]`).closest('form');
            form.submit();
        });
        
        // Close delete modal on outside click
        deleteConfirmModal.addEventListener('click', function(e) {
            if(e.target === deleteConfirmModal) closeDeleteModal();
        });
    });
</script>
@endpush