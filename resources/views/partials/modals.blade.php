<div id="askModal" class="modal-overlay">
    <div class="modal-box" style="height: auto; min-height: 60vh;">
        <div class="modal-header">
            <span class="modal-title">Ask Question</span>
            <button class="close-btn">&times;</button>
        </div>
        <hr />
        @auth
        <form id="askQuestionForm" method="POST" action="{{ route('questions.store') }}">
            @csrf
            <div class="modal-options">
                <div class="user-info">
                    @if(!empty(Auth::user()->avatar))
                        <img src="{{ Storage::disk('s3')->url(Auth::user()->avatar) }}" alt="Profile" style="width:1.8rem;height:1.8rem;border-radius:50%;object-fit:cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=64" alt="Profile" style="width:1.8rem;height:1.8rem;border-radius:50%;object-fit:cover;">
                    @endif
                    <span class="username">{{ Auth::user()->name }}</span>
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
                <button type="button" class="ask-btn">Ask</button>
            </div>
        </form>
        @else
        <div class="login-required-message">
            <i class="bi bi-exclamation-circle" style="font-size: 3rem; color: rgb(42, 60, 98); margin-bottom: 1rem;"></i>
            <h3>Login Required</h3>
            <p>Please login to ask a question.</p>
            <div class="modal-actions">
                <button type="button" class="cancel-btn">Cancel</button>
                <a href="{{ route('login') }}" class="login-btn">Login</a>
            </div>
        </div>
        @endauth
    </div>
</div>

<div id="insightModal" class="modal-overlay">
    <div class="modal-box" style="height: auto; min-height: 60vh;">
        <div class="modal-header">
            <span class="modal-title">Create Blog Post</span>
            <button class="close-btn">&times;</button>
        </div>
        <hr />
        @auth
        <form id="createPostForm" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-options">
                <div class="user-info">
                    @if(Auth::user() && !empty(Auth::user()->avatar))
                        <img src="{{ Storage::disk('s3')->url(Auth::user()->avatar) }}" alt="Profile" style="width:1.8rem;height:1.8rem;border-radius:50%;object-fit:cover;">
                    @elseif(Auth::user())
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=64" alt="Profile" style="width:1.8rem;height:1.8rem;border-radius:50%;object-fit:cover;">
                    @else
                        <i class="bi bi-person-circle" style="font-size:1.8rem;"></i>
                    @endif
                    <span class="username">{{ Auth::user() ? Auth::user()->name : 'Username' }}</span>
                </div>
                <select class="privacy-select" name="visibility">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>
            <input type="text" name="title" class="insight-heading" placeholder="Blog post title..." required>
            @error('title')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <textarea name="content" class="i-question-textarea" placeholder="Write your blog content here..." rows="8" required></textarea>
            @error('content')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="selected-image-container" style="display: none; margin: 10px 0;">
                <img id="selected-image-preview" src="#" alt="Selected Image" style="max-width: 100%; max-height: 200px; object-fit: contain;">
                <button class="remove-image-btn" style="margin-left: 10px; background: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                    <i class="fa-solid fa-times"></i> Remove
                </button>
            </div>
            <input type="file" id="insight-image-input" name="cover_image" style="display: none;" accept="image/png,image/jpeg,image/jpg">
            <div class="modal-actions">
                <button type="button" class="cancel-btn">Cancel</button>
                <button type="button" class="image-btn">
                    <i class="fa-solid fa-image"></i> Add Image
                </button>
                <button type="submit" class="post-btn">Post</button>
            </div>
        </form>
        @else
        <div class="login-required-message">
            <i class="bi bi-exclamation-circle" style="font-size: 3rem; color: rgb(42, 60, 98); margin-bottom: 1rem;"></i>
            <h3>Login Required</h3>
            <p>Please login to post an insight.</p>
            <div class="modal-actions">
                <button type="button" class="cancel-btn">Cancel</button>
                <a href="{{ route('login') }}" class="login-btn">Login</a>
            </div>
        </div>
        @endauth
    </div>
</div>