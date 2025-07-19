@extends('layouts.admin')

@section('title', 'Posts')

@section('content')

<div class="header">
<h1 class="page-title"><i class="fas fa-newspaper"></i> Posts Management</h1>
    <div class="header-actions">
        <button class="btn btn-outline" onclick="exportReports()">
            <i class="fas fa-download"></i> Export
        </button>
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
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3 id="published-posts">-</h3>
            <p>Published</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(248, 150, 30, 0.1); color: var(--warning);">
            <i class="fas fa-edit"></i>
        </div>
        <div class="stat-info">
            <h3 id="draft-posts">-</h3>
            <p>Drafts</p>
        </div>
    </div>
</div>

<div class="tabs">
    <div class="tab active" data-target="all-posts">All Posts</div>
    <div class="tab" data-target="published-posts">Published</div>
    <div class="tab" data-target="draft-posts">Drafts</div>
    <div class="tab" data-target="archived-posts">Archived</div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-file-alt"></i> Forum Posts</h2>
        <div>
            <button class="btn btn-outline" onclick="showPostFilters()">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="btn btn-primary" onclick="showAddPostModal()">
                <i class="fas fa-plus"></i> Create Post
            </button>
        </div>
    </div>
    
    <div class="card-body">
        <div id="posts-table-container">
            <div class="loading">Loading posts...</div>
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
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="">All Categories</option>
                            <option value="technology">Technology</option>
                            <option value="programming">Programming</option>
                            <option value="design">Design</option>
                            <option value="business">Business</option>
                            <option value="general">General</option>
                        </select>
                    </div>
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
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by title or content">
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
        this.currentTab = 'all-posts';
        this.init();
    }

    init() {
        this.loadStats();
        this.loadPosts();
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Filter form submission
        document.getElementById('post-filter-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.applyFilters();
        });

        // Add post form submission
        document.getElementById('add-post-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.addPost();
        });

        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                this.switchTab(e.target);
            });
        });
    }

    switchTab(clickedTab) {
        // Remove active class from all tabs
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        
        // Add active class to clicked tab
        clickedTab.classList.add('active');
        
        // Update current tab
        this.currentTab = clickedTab.getAttribute('data-target');
        
        // Update filters and reload
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
                document.getElementById('published-posts').textContent = stats.published;
                document.getElementById('draft-posts').textContent = stats.draft;
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
                container.innerHTML = this.generatePostsTable(posts);
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
                        <th>Views</th>
                        <th>Comments</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        posts.forEach(post => {
            html += `
                <tr>
                    <td>
                        <div>
                            <div style="font-weight: 500; margin-bottom: 5px;">${this.truncateText(post.title, 60)}</div>
                            <div style="font-size: 0.85rem; color: var(--gray);">${this.truncateText(post.content, 80)}</div>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <img src="${post.user.avatar || 'https://i.pravatar.cc/30?img=' + post.user.id}" alt="User" style="border-radius: 50%; width: 30px; height: 30px;">
                            <span>${post.user.username}</span>
                        </div>
                    </td>
                    <td>${this.formatDate(post.created_at)}</td>
                    <td>${post.views_count || 0}</td>
                    <td>${post.comments_count || 0}</td>
                    <td><span class="badge badge-${this.getStatusBadgeClass(post.status)}">${post.status}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn btn-view" data-id="${post.id}" data-type="post">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn btn-edit" data-id="${post.id}" data-type="post">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete" data-id="${post.id}" data-type="post">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        return html;
    }

    getStatusBadgeClass(status) {
        const classes = {
            'published': 'success',
            'draft': 'warning',
            'archived': 'danger'
        };
        return classes[status] || 'gray';
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

    // Utility methods
    truncateText(text, maxLength = 100) {
        if (!text) return 'N/A';
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString();
    }
}

// Initialize posts manager
let postsManager;

document.addEventListener('DOMContentLoaded', () => {
    postsManager = new PostsManager();
});

// Global functions for onclick handlers
function showAddPostModal() {
    postsManager.showAddPostModal();
}

function closeAddPostModal() {
    postsManager.closeAddPostModal();
}

function showPostFilters() {
    postsManager.showPostFilterModal();
}

function closePostFilterModal() {
    postsManager.closePostFilterModal();
}

function resetPostFilters() {
    postsManager.resetPostFilters();
}
</script>
@endpush 