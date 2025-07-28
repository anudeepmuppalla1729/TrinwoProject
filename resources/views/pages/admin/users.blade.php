@extends('layouts.admin')

@section('title', 'Users | Inqube')

@section('content')

<div class="header">
<h1 class="page-title"><i class="fas fa-users"></i> User Management</h1>
    <div class="header-actions">
        <button class="btn btn-primary" onclick="refreshUsers()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>
</div>
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(67, 97, 238, 0.1); color: var(--primary);">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3 id="total-accounts">-</h3>
            <p>Total Accounts</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success);">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-info">
            <h3 id="active-users">-</h3>
            <p>Active (Last 7 Days)</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(248, 150, 30, 0.1); color: var(--warning);">
            <i class="fas fa-user-clock"></i>
        </div>
        <div class="stat-info">
            <h3 id="new-users">-</h3>
            <p>New This Month</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(220, 53, 69, 0.1); color: var(--danger);">
            <i class="fas fa-user-slash"></i>
        </div>
        <div class="stat-info">
            <h3 id="banned-users">-</h3>
            <p>Banned Users</p>
        </div>
    </div>
</div>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(40, 167, 69, 0.1); color: var(--success);">
            <i class="fas fa-user"></i>
        </div>
        <div class="stat-info">
            <h3 id="regular-users">-</h3>
            <p>Regular Users</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1); color: var(--warning);">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-info">
            <h3 id="inactive-users">-</h3>
            <p>Inactive (7+ Days)</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(108, 117, 125, 0.1); color: var(--secondary);">
            <i class="fas fa-user-cog"></i>
        </div>
        <div class="stat-info">
            <h3 id="admins">-</h3>
            <p>Admins</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(23, 162, 184, 0.1); color: var(--info);">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-info">
            <h3 id="new-today">-</h3>
            <p>New Today</p>
        </div>
    </div>
</div>

<div class="tabs" id="user-admin-tabs">
    <div class="tab active" id="users-tab" onclick="usersManager.switchTab('users')">Users</div>
    <div class="tab" id="admins-tab" onclick="usersManager.switchTab('admins')">Admins</div>
</div>

<div id="users-section">
    <div class="card">
        <div class="card-header">
            <div class="card-title-section">
                <h2 class="card-title"><i class="fas fa-user-friends"></i> Active Users</h2>
                <div id="search-results-info" class="search-results-info" style="display: none;">
                    <span id="search-results-count">0</span> results found
                </div>
            </div>
            <div class="header-actions">
                <div class="search-container">
                    <input type="text" id="user-search-input" class="search-input" placeholder="Search users..." onkeyup="usersManager.handleSearch(event)">
                    <i class="fas fa-search search-icon"></i>
                    <button type="button" id="clear-search-btn" class="clear-search-btn" onclick="usersManager.clearSearch()" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                    <div id="search-loading" class="search-loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </div>
                <button class="btn btn-outline" onclick="showUserFilters()">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <button class="btn btn-primary" onclick="showAddAdminModal()">
                    <i class="fas fa-user-plus"></i> Add Admin
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="users-table-container">
                <div class="loading">Loading users...</div>
            </div>
        </div>
    </div>
</div>

<div id="admins-section" style="display: none;">
    <div class="card">
        <div class="card-header">
            <div class="card-title-section">
                <h2 class="card-title"><i class="fas fa-user-shield"></i> Admins</h2>
            </div>
        </div>
        <div class="card-body">
            <div id="admins-table-container">
                <div class="loading">Loading admins...</div>
            </div>
        </div>
    </div>
</div>

<!-- Add Admin Modal -->
<div id="add-admin-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Admin</h3>
            <button class="modal-close" onclick="closeAddAdminModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-admin-form" class="admin-form" action="/admin/api/admins" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Authenticate (Your Password)</label>
                    <input type="password" name="auth_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add Admin</button>
                    <button type="button" class="btn btn-outline" onclick="closeAddAdminModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div id="user-filter-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Filter Users</h3>
            <button class="modal-close" onclick="closeUserFilterModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="user-filter-form" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="">All Roles</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="banned">Banned</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Joined From</label>
                        <input type="date" name="joined_from" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Joined To</label>
                        <input type="date" name="joined_to" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, or username">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <button type="button" class="btn btn-outline" onclick="resetUserFilters()">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div id="user-details-modal" class="modal" style="display: none;">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h3>User Details</h3>
            <button class="modal-close" onclick="closeUserDetailsModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="user-details-content">
                <div class="loading">Loading user details...</div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="edit-user-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit User</h3>
            <button class="modal-close" onclick="closeEditUserModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="edit-user-form" class="admin-form">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="banned">Banned</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Bio</label>
                    <textarea name="bio" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <button type="button" class="btn btn-outline" onclick="closeEditUserModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirm Action Modal -->
<div id="confirm-action-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="confirm-action-title">Confirm Action</h3>
            <button class="modal-close" onclick="closeConfirmActionModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirm-action-message">Are you sure you want to perform this action?</p>
            <div class="form-group">
                <button id="confirm-action-btn" class="btn btn-danger">Confirm</button>
                <button class="btn btn-outline" onclick="closeConfirmActionModal()">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Admin Delete Modal -->
<div id="confirm-admin-delete-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Delete</h3>
            <button class="modal-close" onclick="closeConfirmAdminDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this admin?</p>
            <div class="form-group">
                <button id="confirm-admin-delete-btn" class="btn btn-danger">Delete</button>
                <button class="btn btn-outline" onclick="closeConfirmAdminDeleteModal()">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Users specific JavaScript
class UsersManager {
    constructor() {
        this.currentFilters = {};
        this.currentUserId = null;
        this.searchTimeout = null;
        this.currentTab = 'users';
        this.currentAdminId = null;
        this.init();
    }

    init() {
        this.loadStats();
        this.loadUsers();
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Filter form submission
        document.getElementById('user-filter-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.applyFilters();
        });

        // Add user form submission
        document.getElementById('add-admin-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.addAdmin();
        });

        // Edit user form submission
        document.getElementById('edit-user-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.updateUser();
        });

        // Confirm action button
        document.getElementById('confirm-action-btn').addEventListener('click', () => {
            this.executeConfirmedAction();
        });

        // Search input event listener
        const searchInput = document.getElementById('user-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.handleSearch(e);
            });
        }
    }

    async loadStats() {
        try {
            const response = await fetch('/admin/api/users/stats', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const stats = await response.json();
                document.getElementById('total-accounts').textContent = stats.total_accounts;
                document.getElementById('active-users').textContent = stats.active;
                document.getElementById('inactive-users').textContent = stats.inactive;
                document.getElementById('new-users').textContent = stats.new_this_month;
                document.getElementById('banned-users').textContent = stats.banned;
                document.getElementById('regular-users').textContent = stats.by_role.users;
                document.getElementById('admins').textContent = stats.by_role.admins;
                document.getElementById('new-today').textContent = stats.new_today;
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    async loadUsers(filters = {}) {
        const container = document.getElementById('users-table-container');
        container.innerHTML = '<div class="loading">Loading users...</div>';

        try {
            const queryString = new URLSearchParams(filters).toString();
            const response = await fetch(`/admin/api/users?${queryString}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const users = await response.json();
                container.innerHTML = this.generateUsersTable(users);
                this.setupTableEventListeners();
                this.updateSearchResultsInfo(users.data ? users.data.length : 0, filters.search);
            } else {
                container.innerHTML = '<div class="error">Error loading users</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            container.innerHTML = '<div class="error">Network error</div>';
        }
    }

    generateUsersTable(users) {
        if (!users.data || !users.data.length) {
            return '<div class="no-data">No users found</div>';
        }

        let html = `
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Contributions</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        users.data.forEach(user => {
            html += `
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="${user.avatar && user.avatar.length > 0 ? window.s3BaseUrl + user.avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name || user.username || 'User') + '&size=40&background=random'}" alt="User" style="border-radius: 50%; width: 40px; height: 40px;">
                            <div>
                                <div>${user.name || 'N/A'}</div>
                                <div style="font-size: 0.85rem; color: var(--gray);">@${user.username || 'N/A'}</div>
                                <div style="font-size: 0.8rem; color: var(--gray);">${user.email}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-${this.getRoleBadgeClass(user.role)}">${user.role}</span></td>
                    <td>${this.formatDate(user.created_at)}</td>
                    <td>
                        <div style="font-size: 0.9rem;">
                            <div>Q: ${user.questions_count || 0} | A: ${user.answers_count || 0} | P: ${user.posts_count || 0}</div>
                            <div style="color: var(--gray);">Total: ${user.total_contributions || 0}</div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-${user.status === 'banned' ? 'danger' : this.getStatusBadgeClass(this.getActiveStatus(user.last_login_at))}">
                            ${user.status === 'banned' ? 'Banned' : (this.getActiveStatus(user.last_login_at) === 'active' ? 'Active (Last 7 Days)' : 'Inactive (7+ Days)')}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn btn-view" data-id="${user.id}" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${user.status === 'banned' ? 
                                `<button class="action-btn btn-unban" data-id="${user.id}" title="Unban User">
                                    <i class="fas fa-user-check"></i>
                                </button>` :
                                `<button class="action-btn btn-ban" data-id="${user.id}" title="Ban User">
                                    <i class="fas fa-user-slash"></i>
                                </button>`
                            }
                            <button class="action-btn btn-delete" data-id="${user.id}" title="Delete User">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        
        // Add pagination if available
        if (users.links) {
            html += this.generatePagination(users);
        }
        
        return html;
    }

    generatePagination(users) {
        let html = '<div class="pagination-container">';
        if (users.prev_page_url) {
            html += `<button class="btn btn-outline" onclick="usersManager.loadUsers({...usersManager.currentFilters, page: ${users.current_page - 1}})">Previous</button>`;
        }
        html += `<span class="pagination-info">Page ${users.current_page} of ${users.last_page}</span>`;
        if (users.next_page_url) {
            html += `<button class="btn btn-outline" onclick="usersManager.loadUsers({...usersManager.currentFilters, page: ${users.current_page + 1}})">Next</button>`;
        }
        html += '</div>';
        return html;
    }

    setupTableEventListeners() {
        // View user details
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const userId = e.currentTarget.dataset.id;
                this.viewUserDetails(userId);
            });
        });

        // Edit user
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const userId = e.currentTarget.dataset.id;
                this.editUser(userId);
            });
        });

        // Ban user
        document.querySelectorAll('.btn-ban').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const userId = e.currentTarget.dataset.id;
                this.confirmAction('ban', userId, 'Are you sure you want to ban this user?');
            });
        });

        // Unban user
        document.querySelectorAll('.btn-unban').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const userId = e.currentTarget.dataset.id;
                this.confirmAction('unban', userId, 'Are you sure you want to unban this user?');
            });
        });

        // Delete user
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const userId = e.currentTarget.dataset.id;
                this.confirmAction('delete', userId, 'Are you sure you want to delete this user? This action cannot be undone.');
            });
        });
    }

    async viewUserDetails(userId) {
        const modal = document.getElementById('user-details-modal');
        const content = document.getElementById('user-details-content');
        
        modal.style.display = 'flex';
        content.innerHTML = '<div class="loading">Loading user details...</div>';

        try {
            const response = await fetch(`/admin/api/users/${userId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.error) {
                    content.innerHTML = `<div class="error">${data.error}</div>`;
                } else {
                    content.innerHTML = this.generateUserDetailsHTML(data.user);
                }
            } else {
                const errorData = await response.json();
                content.innerHTML = `<div class="error">Error loading user details: ${errorData.message || 'Unknown error'}</div>`;
            }
        } catch (error) {
            console.error('Error:', error);
            content.innerHTML = '<div class="error">Network error</div>';
        }
    }

    generateUserDetailsHTML(user) {
        return `
            <div class="user-details">
                <div class="user-header">
                    <img src="${user.avatar && user.avatar.length > 0 ? window.s3BaseUrl + user.avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name || user.username || 'User') + '&size=100&background=random'}" alt="User" style="border-radius: 50%; width: 100px; height: 100px;">
                    <div class="user-info">
                        <h3>${user.name || 'N/A'}</h3>
                        <p><strong>Username:</strong> @${user.username || 'N/A'}</p>
                        <p><strong>Email:</strong> ${user.email}</p>
                        <p><strong>Role:</strong> <span class="badge badge-${this.getRoleBadgeClass(user.role)}">${user.role}</span></p>
                        <p><strong>Status:</strong> <span class="badge badge-${this.getStatusBadgeClass(user.status)}">${user.status}</span></p>
                        <p><strong>Joined:</strong> ${this.formatDate(user.created_at)}</p>
                        ${user.last_login_at ? `<p><strong>Last Login:</strong> ${this.formatDate(user.last_login_at)}</p>` : ''}
                    </div>
                </div>
                
                ${user.bio ? `<div class="user-bio"><strong>Bio:</strong> ${user.bio}</div>` : ''}
                
                <div class="user-stats">
                    <h4>Statistics</h4>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-number">${user.questions_count}</span>
                            <span class="stat-label">Questions</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">${user.answers_count}</span>
                            <span class="stat-label">Answers</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">${user.posts_count}</span>
                            <span class="stat-label">Posts</span>
                        </div>
                    </div>
                </div>

                ${user.recent_questions && user.recent_questions.length > 0 ? `
                    <div class="recent-activity">
                        <h4>Recent Questions</h4>
                        <div class="activity-list">
                            ${user.recent_questions.map(q => `
                                <div class="activity-item">
                                    <a href="/question/${q.id}" target="_blank">${q.title}</a>
                                    <span class="activity-date">${this.formatDate(q.created_at)}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}

                ${user.recent_answers && user.recent_answers.length > 0 ? `
                    <div class="recent-activity">
                        <h4>Recent Answers</h4>
                        <div class="activity-list">
                            ${user.recent_answers.map(a => `
                                <div class="activity-item">
                                    <span class="activity-content">${a.content}</span>
                                    <span class="activity-date">${this.formatDate(a.created_at)}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
    }

    async editUser(userId) {
        this.currentUserId = userId;
        
        try {
            const response = await fetch(`/admin/api/users/${userId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.populateEditForm(data.user);
                this.showEditUserModal();
            } else {
                window.adminDashboard.showNotification('Error loading user data', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        }
    }

    populateEditForm(user) {
        const form = document.getElementById('edit-user-form');
        form.querySelector('[name="name"]').value = user.name || '';
        form.querySelector('[name="email"]').value = user.email || '';
        form.querySelector('[name="username"]').value = user.username || '';
        form.querySelector('[name="role"]').value = user.role || 'user';
        form.querySelector('[name="status"]').value = user.status || 'active';
        form.querySelector('[name="bio"]').value = user.bio || '';
    }

    async updateUser() {
        if (!this.currentUserId) return;

        const form = document.getElementById('edit-user-form');
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        submitButton.disabled = true;
        submitButton.textContent = 'Updating...';

        try {
            const response = await fetch(`/admin/api/users/${this.currentUserId}`, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const result = await response.json();
                window.adminDashboard.showNotification(result.message || 'User updated successfully', 'success');
                this.closeEditUserModal();
                this.loadUsers(this.currentFilters);
                this.loadStats();
            } else {
                const error = await response.json();
                window.adminDashboard.showNotification(error.message || 'Error updating user', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }

    confirmAction(action, userId, message) {
        this.currentUserId = userId;
        this.currentAction = action;
        
        document.getElementById('confirm-action-title').textContent = action.charAt(0).toUpperCase() + action.slice(1) + ' User';
        document.getElementById('confirm-action-message').textContent = message;
        document.getElementById('confirm-action-modal').style.display = 'flex';
    }

    async executeConfirmedAction() {
        if (!this.currentUserId || !this.currentAction) return;

        const action = this.currentAction;
        const userId = this.currentUserId;

        try {
            let url, method;
            
            switch (action) {
                case 'ban':
                    url = `/admin/api/users/${userId}/ban`;
                    method = 'POST';
                    break;
                case 'unban':
                    url = `/admin/api/users/${userId}/unban`;
                    method = 'POST';
                    break;
                case 'delete':
                    url = `/admin/api/users/${userId}`;
                    method = 'DELETE';
                    break;
                default:
                    return;
            }

            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const result = await response.json();
                window.adminDashboard.showNotification(result.message || `User ${action}ed successfully`, 'success');
                this.closeConfirmActionModal();
                this.loadUsers(this.currentFilters);
                this.loadStats();
            } else {
                const error = await response.json();
                window.adminDashboard.showNotification(error.message || `Error ${action}ing user`, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        }
    }

    getRoleBadgeClass(role) {
        const classes = {
            'user': 'primary',
            'admin': 'danger'
        };
        return classes[role] || 'gray';
    }

    getStatusBadgeClass(status) {
        const classes = {
            'active': 'success',
            'inactive': 'warning',
            'banned': 'danger'
        };
        return classes[status] || 'gray';
    }

    applyFilters() {
        const form = document.getElementById('user-filter-form');
        const formData = new FormData(form);
        const filters = {};

        for (const [key, value] of formData.entries()) {
            if (value) {
                filters[key] = value;
            }
        }

        this.currentFilters = filters;
        this.loadUsers(filters);
        this.closeUserFilterModal();
    }

    resetUserFilters() {
        document.getElementById('user-filter-form').reset();
        this.currentFilters = {};
        this.loadUsers();
    }

    handleSearch(event) {
        const searchTerm = event.target.value.trim();
        const clearBtn = document.getElementById('clear-search-btn');
        const searchLoading = document.getElementById('search-loading');
        
        // Show/hide clear button
        if (searchTerm) {
            clearBtn.style.display = 'flex';
        } else {
            clearBtn.style.display = 'none';
        }
        
        // Clear any existing search timeout
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
        
        // Add search term to current filters
        if (searchTerm) {
            this.currentFilters.search = searchTerm;
        } else {
            delete this.currentFilters.search;
        }
        
        // Show loading indicator if there's a search term
        if (searchTerm) {
            searchLoading.classList.add('show');
        } else {
            searchLoading.classList.remove('show');
        }
        
        // Debounce the search to avoid too many API calls
        this.searchTimeout = setTimeout(() => {
            this.loadUsers(this.currentFilters);
            searchLoading.classList.remove('show');
        }, 300);
    }

    clearSearch() {
        const searchInput = document.getElementById('user-search-input');
        const clearBtn = document.getElementById('clear-search-btn');
        const searchLoading = document.getElementById('search-loading');
        
        searchInput.value = '';
        clearBtn.style.display = 'none';
        searchLoading.classList.remove('show');
        
        // Remove search from filters
        delete this.currentFilters.search;
        
        // Reload users without search
        this.loadUsers(this.currentFilters);
    }

    updateSearchResultsInfo(count, searchTerm) {
        const searchResultsInfo = document.getElementById('search-results-info');
        const searchResultsCount = document.getElementById('search-results-count');
        
        if (searchTerm && searchTerm.trim()) {
            searchResultsCount.textContent = count;
            searchResultsInfo.style.display = 'block';
        } else {
            searchResultsInfo.style.display = 'none';
        }
    }

    async addAdmin() {
        const form = document.getElementById('add-admin-form');
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        submitButton.disabled = true;
        submitButton.textContent = 'Adding...';

        try {
            const response = await fetch('/admin/api/admins', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const result = await response.json();
                window.adminDashboard.showNotification(result.message || 'Admin added successfully', 'success');
                this.closeAddAdminModal();
                this.loadStats();
                this.loadUsers(this.currentFilters);
                form.reset();
            } else {
                const error = await response.json();
                window.adminDashboard.showNotification(error.message || 'Error adding admin', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }

    switchTab(tab) {
        this.currentTab = tab;
        document.getElementById('users-tab').classList.remove('active');
        document.getElementById('admins-tab').classList.remove('active');
        document.getElementById('users-section').style.display = 'none';
        document.getElementById('admins-section').style.display = 'none';
        if (tab === 'users') {
            document.getElementById('users-tab').classList.add('active');
            document.getElementById('users-section').style.display = '';
            this.loadUsers(this.currentFilters);
        } else {
            document.getElementById('admins-tab').classList.add('active');
            document.getElementById('admins-section').style.display = '';
            this.loadAdmins();
        }
    }

    async loadAdmins() {
        const container = document.getElementById('admins-table-container');
        container.innerHTML = '<div class="loading">Loading admins...</div>';
        try {
            const response = await fetch('/admin/api/admins', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const admins = await response.json();
                container.innerHTML = this.generateAdminsTable(admins);
            } else {
                container.innerHTML = '<div class="error">Error loading admins</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            container.innerHTML = '<div class="error">Network error</div>';
        }
    }

    generateAdminsTable(admins) {
        if (!admins.length) {
            return '<div class="no-data">No admins found</div>';
        }
        let html = `
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;
        admins.forEach(admin => {
            html += `
                <tr>
                    <td>${admin.username}</td>
                    <td>${admin.name}</td>
                    <td><span class="badge badge-${admin.status === 'active' ? 'success' : 'warning'}">${admin.status}</span></td>
                    <td>${this.formatDate(admin.created_at)}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="usersManager.deleteAdmin(${admin.id})"><i class="fas fa-trash"></i> Delete</button>
                    </td>
                </tr>
            `;
        });
        html += '</tbody></table>';
        return html;
    }

    async deleteAdmin(adminId) {
        this.currentAdminId = adminId;
        document.getElementById('confirm-admin-delete-modal').style.display = 'flex';
        document.getElementById('confirm-admin-delete-btn').onclick = async () => {
            await this.confirmDeleteAdmin();
        };
    }

    async confirmDeleteAdmin() {
        if (!this.currentAdminId) return;
        try {
            const response = await fetch(`/admin/api/admins/${this.currentAdminId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const result = await response.json();
                window.adminDashboard.showNotification(result.message || 'Admin deleted successfully', 'success');
                this.closeConfirmAdminDeleteModal();
                this.loadAdmins();
                this.loadStats();
            } else {
                const error = await response.json();
                window.adminDashboard.showNotification(error.message || 'Error deleting admin', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        }
    }

    // Modal management
    showAddAdminModal() {
        document.getElementById('add-admin-modal').style.display = 'flex';
    }

    closeAddAdminModal() {
        document.getElementById('add-admin-modal').style.display = 'none';
    }

    showEditUserModal() {
        document.getElementById('edit-user-modal').style.display = 'flex';
    }

    closeEditUserModal() {
        document.getElementById('edit-user-modal').style.display = 'none';
        this.currentUserId = null;
    }

    showUserFilterModal() {
        document.getElementById('user-filter-modal').style.display = 'flex';
    }

    closeUserFilterModal() {
        document.getElementById('user-filter-modal').style.display = 'none';
    }

    closeUserDetailsModal() {
        document.getElementById('user-details-modal').style.display = 'none';
    }

    closeConfirmActionModal() {
        document.getElementById('confirm-action-modal').style.display = 'none';
        this.currentUserId = null;
        this.currentAction = null;
    }

    closeConfirmAdminDeleteModal() {
        document.getElementById('confirm-admin-delete-modal').style.display = 'none';
        this.currentAdminId = null;
    }

    // Utility methods
    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString();
    }

    getActiveStatus(lastLoginAt) {
        if (!lastLoginAt) return 'inactive';
        const lastLogin = new Date(lastLoginAt);
        const now = new Date();
        const diffDays = (now - lastLogin) / (1000 * 60 * 60 * 24);
        return diffDays <= 7 ? 'active' : 'inactive';
    }
}

// Initialize users manager
let usersManager;

document.addEventListener('DOMContentLoaded', () => {
    usersManager = new UsersManager();
});

// Global functions for onclick handlers
function showAddAdminModal() {
    usersManager.showAddAdminModal();
}

function closeAddAdminModal() {
    usersManager.closeAddAdminModal();
}

function showUserFilters() {
    usersManager.showUserFilterModal();
}

function closeUserFilterModal() {
    usersManager.closeUserFilterModal();
}

function resetUserFilters() {
    usersManager.resetUserFilters();
}

function closeUserDetailsModal() {
    usersManager.closeUserDetailsModal();
}

function closeEditUserModal() {
    usersManager.closeEditUserModal();
}

function closeConfirmActionModal() {
    usersManager.closeConfirmActionModal();
}

function closeConfirmAdminDeleteModal() {
    usersManager.closeConfirmAdminDeleteModal();
}

function clearUserSearch() {
    usersManager.clearSearch();
}

function refreshUsers() {
    usersManager.loadStats();
    usersManager.loadUsers(usersManager.currentFilters);
}
</script>
@endpush 