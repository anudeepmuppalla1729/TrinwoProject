<div id="askModal" class="modal-overlay">
    <div class="modal-box" style="height: auto; min-height: 60vh; padding-bottom: 80px;">
        <div class="modal-header">
            <span class="modal-title">Ask Question</span>
            <button class="close-btn">&times;</button>
        </div>
        <hr />
        <form id="askQuestionForm" method="POST" action="{{ route('questions.store') }}">
            @csrf
            <div class="modal-options">
                <div class="user-info">
                    <i class="bi bi-person-circle" style="font-size:1.8rem;"></i>
                    <span class="username">{{ Auth::user() ? Auth::user()->name : 'Username' }}</span>
                </div>
                <select class="privacy-select" name="privacy">
                    <option>Public</option>
                    <option>Private</option>
                </select>
            </div>
            <textarea class="question-textarea" name="title" placeholder="Type your question here..."></textarea>
            @error('title')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <textarea class="question-description" name="description" placeholder="Add a description for your question..."></textarea>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="tags-input-container">
                <input type="text" class="tags-input" placeholder="Add tags (e.g., technology, education)" />
                <input type="hidden" name="tags" id="tags-hidden" />
                <div class="tags-suggestions" style="display: none;"></div>
                <div class="selected-tags"></div>
            </div>
            @error('tags')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="modal-actions">
                <button type="button" class="cancel-btn">Cancel</button>
                <button type="button" class="ask-btn ">Ask</button>
            </div>
        </form>
    </div>
</div>

<div id="insightModal" class="modal-overlay">
    <div class="modal-box" style="height: auto; min-height: 60vh; padding-bottom: 80px;">
        <div class="modal-header">
            <span class="modal-title">Post Insight</span>
            <button class="close-btn">&times;</button>
        </div>
        <hr />
        <div class="modal-options">
            <div class="user-info">
                <i class="bi bi-person-circle" style="font-size:1.8rem;"></i>
                <span class="username">{{ Auth::user() ? Auth::user()->name : 'Username' }}</span>
            </div>
            <select class="privacy-select">
                <option value="public">Public</option>
                <option value="private">Private</option>
            </select>
        </div>
        <textarea class="insight-heading" placeholder="Heading for your Insight..."></textarea>
        <textarea class="i-question-textarea" placeholder="Share your thoughts and insights..."></textarea>
        
        <div class="selected-image-container" style="display: none; margin: 10px 0;">
            <img id="selected-image-preview" src="#" alt="Selected Image" style="max-width: 100%; max-height: 200px; object-fit: contain;">
            <button class="remove-image-btn" style="margin-left: 10px; background: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                <i class="fa-solid fa-times"></i> Remove
            </button>
        </div>
        
        <input type="file" id="insight-image-input" style="display: none;" accept="image/*">
        
        <div class="modal-actions">
            <button class="cancel-btn">Cancel</button>
            <button class="image-btn">
                <i class="fa-solid fa-image"></i> Add Image
            </button>
            <button class="post-btn">Post</button>
        </div>
    </div>
</div>