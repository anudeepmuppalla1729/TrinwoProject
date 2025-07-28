@extends('layouts.admin')

@section('title', 'Posts | Inqube')

@section('content')

<div class="header">
<h1 class="page-title"><i class="fas fa-newspaper"></i> Posts Management</h1>
    <div class="header-actions">
        <button class="btn btn-primary" onclick="refreshReports()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>
</div>
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(67, 97, 238, 0.1); color: var(--primary);">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="stat-info">
            <h3 id="total-posts">-</h3>
            <p>Total Posts</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success);">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-info">
            <h3 id="today-posts">-</h3>
            <p>Today</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(248, 150, 30, 0.1); color: var(--warning);">
            <i class="fas fa-calendar-week"></i>
        </div>
        <div class="stat-info">
            <h3 id="week-posts">-</h3>
            <p>This Week</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(220, 53, 69, 0.1); color: var(--danger);">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
            <h3 id="month-posts">-</h3>
            <p>This Month</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-file-alt"></i> Forum Posts</h2>
        <div style="display: flex; align-items: center; gap: 12px;">
            <button class="btn btn-outline" onclick="showPostFilters()">
                <i class="fas fa-filter"></i> Filter
            </button>
            <form id="post-search-form" style="display: flex; align-items: center; gap: 4px; margin-left: 8px;">
                <input type="text" id="post-search-input" name="search" class="form-control" placeholder="Search posts..." style="width: 180px; height: 50px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div id="posts-table-container">
            <div class="loading">Loading posts...</div>
        </div>
    </div>
</div>

<!-- View Post Modal -->
<div id="post-details-modal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3><i class="fas fa-file-alt"></i> Post Details</h3>
            <button class="modal-close" onclick="closePostDetailsModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="post-details-content">
                <div class="loading">Loading post details...</div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div id="confirm-delete-modal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h3>Confirm Delete</h3>
            <button class="modal-close" onclick="closeConfirmDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this post? This action cannot be undone.</p>
            <div class="form-group">
                <button id="confirm-delete-btn" class="btn btn-danger">Delete</button>
                <button class="btn btn-outline" onclick="closeConfirmDeleteModal()">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Post Modal -->
<div id="add-post-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Create New Post</h3>
            <button class="modal-close" onclick="closeAddPostModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-post-form" class="admin-form" action="/admin/api/posts" method="POST">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" class="form-control" rows="8" required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="technology">Technology</option>
                            <option value="programming">Programming</option>
                            <option value="design">Design</option>
                            <option value="business">Business</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Tags (comma separated)</label>
                    <input type="text" name="tags" class="form-control" placeholder="tag1, tag2, tag3">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create Post</button>
                    <button type="button" class="btn btn-outline" onclick="closeAddPostModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div id="post-filter-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Filter Posts</h3>
            <button class="modal-close" onclick="closePostFilterModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="post-filter-form" class="admin-form">
                <div class="form-row">
                    <!-- Status filter removed -->
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date From</label>
                        <input type="date" name="date_from" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Date To</label>
                        <input type="date" name="date_to" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort" class="form-control">
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                        <option value="most_views">Most Views</option>
                        <option value="most_comments">Most Comments</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <button type="button" class="btn btn-outline" onclick="resetPostFilters()">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Posts specific JavaScript
class PostsManager {
    constructor() {
        this.currentFilters = {};
        this.currentPostId = null;
        this.init();
    }
    init() {
        this.loadStats();
        this.loadPosts();
        this.setupEventListeners();
    }
    setupEventListeners() {
        document.getElementById('post-filter-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.applyFilters();
        });
        document.getElementById('add-post-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.addPost();
        });
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                this.switchTab(e.target);
            });
        });
        // Add search form event
        const searchForm = document.getElementById('post-search-form');
        if (searchForm) {
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const searchValue = document.getElementById('post-search-input').value.trim();
                this.currentFilters = { ...this.currentFilters, search: searchValue };
                this.loadPosts(this.currentFilters);
            });
        }
    }
    switchTab(clickedTab) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        clickedTab.classList.add('active');
        this.currentTab = clickedTab.getAttribute('data-target');
        this.currentFilters.status = this.getStatusFromTab(this.currentTab);
        this.loadPosts(this.currentFilters);
    }
    getStatusFromTab(tab) {
        const statusMap = {
            'all-posts': '',
            'published-posts': 'published',
            'draft-posts': 'draft',
            'archived-posts': 'archived'
        };
        return statusMap[tab] || '';
    }
    async loadStats() {
        try {
            const response = await fetch('/admin/api/posts/stats', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const stats = await response.json();
                document.getElementById('total-posts').textContent = stats.total;
                document.getElementById('today-posts').textContent = stats.today;
                document.getElementById('week-posts').textContent = stats.week;
                document.getElementById('month-posts').textContent = stats.month;
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }
    async loadPosts(filters = {}) {
        const container = document.getElementById('posts-table-container');
        container.innerHTML = '<div class="loading">Loading posts...</div>';
        try {
            const queryString = new URLSearchParams(filters).toString();
            const response = await fetch(`/admin/api/posts?${queryString}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const posts = await response.json();
                container.innerHTML = this.generatePostsTable(posts.data || []);
                this.setupTableEventListeners();
            } else {
                container.innerHTML = '<div class="error">Error loading posts</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            container.innerHTML = '<div class="error">Network error</div>';
        }
    }
    generatePostsTable(posts) {
        if (!posts.length) {
            return '<div class="no-data">No posts found</div>';
        }
        let html = `
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Date</th>
                        <th>Comments</th>
                        <th>Upvotes</th>
                        <th>Downvotes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;
        posts.forEach(post => {
            html += `
                <tr data-post-id="${post.id}">
                    <td>${this.truncateText(post.title, 80)}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <img src="${post.user.avatar && post.user.avatar.length > 0 ? window.s3BaseUrl + post.user.avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(post.user.name || post.user.username || 'User') + '&size=30&background=random'}" alt="User" style="border-radius: 50%; width: 30px; height: 30px;">
                            <span>${post.user.name || post.user.username || 'Unknown User'}</span>
                        </div>
                    </td>
                    <td>${this.formatDate(post.created_at)}</td>
                    <td>${post.comments_count || 0}</td>
                    <td>${post.upvotes || 0}</td>
                    <td>${post.downvotes || 0}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn btn-view" data-id="${post.id}"><i class="fas fa-eye"></i></button>
                            <button class="action-btn btn-delete" data-id="${post.id}"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            `;
        });
        html += '</tbody></table>';
        return html;
    }
    setupTableEventListeners() {
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const postId = e.currentTarget.dataset.id;
                this.viewPostDetails(postId);
            });
        });
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const postId = e.currentTarget.dataset.id;
                this.confirmDeletePost(postId);
            });
        });
    }
    async viewPostDetails(postId) {
        const modal = document.getElementById('post-details-modal');
        const content = document.getElementById('post-details-content');
        modal.style.display = 'flex';
        content.innerHTML = '<div class="loading">Loading post details...</div>';
        try {
            const response = await fetch(`/admin/api/posts/${postId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const data = await response.json();
                content.innerHTML = this.generatePostDetailsHTML(data);
                // Highlight the post row in the main table
                document.querySelectorAll('tr[data-post-id]').forEach(row => {
                    row.classList.remove('highlighted-post');
                });
                const row = document.querySelector(`tr[data-post-id='${postId}']`);
                if (row) row.classList.add('highlighted-post');
            } else {
                content.innerHTML = '<div class="error">Failed to load post details</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            content.innerHTML = '<div class="error">Network error</div>';
        }
    }
    generatePostDetailsHTML(data) {
        let coverImageHtml = '';
        if (data.cover_image) {
            coverImageHtml = `<div class="post-cover-image" style="margin-bottom: 1rem;">
                <img src="${data.cover_image}" style="max-width: 100%; max-height: 260px; border-radius: 8px;">
            </div>`;
        }
        let imagesHtml = '';
        if (data.images && data.images.length) {
            imagesHtml = '<div class="post-images">' + data.images.map(url => {
                if (url && url.length > 0) {
                    if (url.startsWith('http')) {
                        return `<img src="${url}" style="max-width: 120px; margin-right: 10px;">`;
                    } else {
                        return `<img src="${window.s3BaseUrl + url}" style="max-width: 120px; margin-right: 10px;">`;
                    }
                }
                return '';
            }).join('') + '</div>';
        }
        let commentsHtml = '';
        if (data.comments && data.comments.length) {
            commentsHtml = '<div class="post-comments"><h5>Comments:</h5>' + data.comments.map(c => `<div class="comment-item"><b>${c.user}</b>: ${c.text} <span style="color:#888;font-size:0.9rem;">${this.formatDate(c.created_at)}</span></div>`).join('') + '</div>';
        }
        return `
            <div class="post-details-modal-content">
                <h4>${data.title}</h4>
                <div style="margin-bottom: 1rem; color: #666;">By: ${data.user.name || data.user.username || 'Unknown User'}</div>
                ${coverImageHtml}
                <div style="margin-bottom: 1rem;">${data.content}</div>
                ${imagesHtml}
                ${commentsHtml}
                <div style="margin-top: 1rem; color: #888; font-size: 0.9rem;">Created: ${this.formatDate(data.created_at)}</div>
            </div>
        `;
    }
    confirmDeletePost(postId) {
        this.currentPostId = postId;
        document.getElementById('confirm-delete-modal').style.display = 'flex';
        document.getElementById('confirm-delete-btn').onclick = () => this.deletePost(postId);
    }
    async deletePost(postId) {
        const modal = document.getElementById('confirm-delete-modal');
        modal.style.display = 'none';
        const loader = document.getElementById('loader-overlay');
        loader.style.display = 'flex';
        try {
            const response = await fetch(`/admin/api/posts/${postId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            loader.style.display = 'none';
            if (response.ok) {
                this.loadStats();
                this.loadPosts(this.currentFilters);
                showToast('Post deleted successfully!', 'success');
            } else {
                showToast('Failed to delete post', 'error');
            }
        } catch (error) {
            loader.style.display = 'none';
            showToast('Network error', 'error');
        }
    }
    applyFilters() {
        const form = document.getElementById('post-filter-form');
        const formData = new FormData(form);
        const filters = {};
        for (const [key, value] of formData.entries()) {
            if (value) {
                filters[key] = value;
            }
        }
        this.currentFilters = filters;
        this.loadPosts(filters);
        this.closePostFilterModal();
    }
    resetPostFilters() {
        document.getElementById('post-filter-form').reset();
        this.currentFilters = {};
        this.loadPosts();
    }
    async addPost() {
        const form = document.getElementById('add-post-form');
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        submitButton.disabled = true;
        submitButton.textContent = 'Creating...';

        try {
            const response = await fetch('/admin/api/posts', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const result = await response.json();
                window.adminDashboard.showNotification(result.message || 'Post created successfully', 'success');
                this.closeAddPostModal();
                this.loadPosts(this.currentFilters);
                this.loadStats();
                form.reset();
            } else {
                const error = await response.json();
                window.adminDashboard.showNotification(error.message || 'Error creating post', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }
    showAddPostModal() {
        document.getElementById('add-post-modal').style.display = 'flex';
    }
    closeAddPostModal() {
        document.getElementById('add-post-modal').style.display = 'none';
    }
    showPostFilterModal() {
        document.getElementById('post-filter-modal').style.display = 'flex';
    }
    closePostFilterModal() {
        document.getElementById('post-filter-modal').style.display = 'none';
    }
    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString();
    }
    truncateText(text, maxLength = 100) {
        if (!text) return 'N/A';
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }
}
// Add highlight style
const style = document.createElement('style');
style.innerHTML = `.highlighted-post { background: #e6f7ff !important; border-left: 4px solid #1890ff; }`;
document.head.appendChild(style);

// Loader Overlay
const loaderOverlay = document.createElement('div');
loaderOverlay.id = 'loader-overlay';
loaderOverlay.style.display = 'none';
loaderOverlay.style.position = 'fixed';
loaderOverlay.style.top = '0';
loaderOverlay.style.left = '0';
loaderOverlay.style.width = '100vw';
loaderOverlay.style.height = '100vh';
loaderOverlay.style.background = 'rgba(255,255,255,0.6)';
loaderOverlay.style.zIndex = '9999';
loaderOverlay.style.alignItems = 'center';
loaderOverlay.style.justifyContent = 'center';
loaderOverlay.innerHTML = '<div class="loading-spinner" style="border:4px solid #f3f3f3;border-top:4px solid #4361ee;border-radius:50%;width:40px;height:40px;animation:spin 1s linear infinite;"></div>';
document.body.appendChild(loaderOverlay);

// Toast Notification
const toastContainer = document.createElement('div');
toastContainer.id = 'toast-container';
toastContainer.style.position = 'fixed';
toastContainer.style.top = '30px';
toastContainer.style.right = '30px';
toastContainer.style.zIndex = '10000';
toastContainer.style.display = 'flex';
toastContainer.style.flexDirection = 'column';
toastContainer.style.gap = '10px';
document.body.appendChild(toastContainer);

const styleElement = document.createElement('style');
styleElement.innerHTML = `
    @keyframes spin{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}
    .toast{min-width:220px;max-width:350px;padding:16px 24px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.08);font-size:1rem;display:flex;align-items:center;gap:12px;animation:fadeIn 0.3s;}
    .toast-success{background:#e6f9f0;color:#1b7f5a;border-left:5px solid #1b7f5a;}
    .toast-error{background:#fff0f0;color:#b91c1c;border-left:5px solid #b91c1c;}
    @keyframes fadeIn{from{opacity:0;transform:translateY(-20px);}to{opacity:1;transform:translateY(0);}}
`;
document.head.appendChild(styleElement);

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.innerHTML = (type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-circle"></i>') + message;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => container.removeChild(toast), 300);
    }, 2500);
}

let postsManager;
document.addEventListener('DOMContentLoaded', () => {
    postsManager = new PostsManager();
});
function showPostFilters() {
    postsManager.showPostFilterModal();
}
function closePostFilterModal() {
    postsManager.closePostFilterModal();
}
function resetPostFilters() {
    postsManager.resetPostFilters();
}
function closePostDetailsModal() {
    document.getElementById('post-details-modal').style.display = 'none';
    document.querySelectorAll('tr[data-post-id]').forEach(row => {
        row.classList.remove('highlighted-post');
    });
}
function closeConfirmDeleteModal() {
    document.getElementById('confirm-delete-modal').style.display = 'none';
}
</script>
@endpush 