@extends('layouts.admin')

@section('title', 'Users')

@section('content')

<div class="header">
<h1 class="page-title"><i class="fas fa-users"></i> User Management</h1>
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
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3 id="total-users">-</h3>
            <p>Total Users</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success);">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-info">
            <h3 id="active-users">-</h3>
            <p>Active Users</p>
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
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-user-friends"></i> Active Users</h2>
        <div>
            <button class="btn btn-outline" onclick="showUserFilters()">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="btn btn-primary" onclick="showAddUserModal()">
                <i class="fas fa-plus"></i> Add User
            </button>
        </div>
    </div>
    
    <div class="card-body">
        <div id="users-table-container">
            <div class="loading">Loading users...</div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="add-user-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New User</h3>
            <button class="modal-close" onclick="closeAddUserModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-user-form" class="admin-form" action="/admin/api/users" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="user">User</option>
                        <option value="moderator">Moderator</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add User</button>
                    <button type="button" class="btn btn-outline" onclick="closeAddUserModal()">Cancel</button>
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
                            <option value="moderator">Moderator</option>
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
@endsection

@push('scripts')
<script>
// Users specific JavaScript
class UsersManager {
    constructor() {
        this.currentFilters = {};
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
        document.getElementById('add-user-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.addUser();
        });
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
                document.getElementById('total-users').textContent = stats.total;
                document.getElementById('active-users').textContent = stats.active;
                document.getElementById('new-users').textContent = stats.new_this_month;
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
            } else {
                container.innerHTML = '<div class="error">Error loading users</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            container.innerHTML = '<div class="error">Network error</div>';
        }
    }

    generateUsersTable(users) {
        if (!users.length) {
            return '<div class="no-data">No users found</div>';
        }

        let html = `
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Joined</th>
                        <th>Questions</th>
                        <th>Answers</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        users.forEach(user => {
            html += `
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="${user.avatar || 'https://i.pravatar.cc/40?img=' + user.id}" alt="User" style="border-radius: 50%; width: 40px; height: 40px;">
                            <div>
                                <div>${user.first_name} ${user.last_name}</div>
                                <div style="font-size: 0.85rem; color: var(--gray);">@${user.username}</div>
                            </div>
                        </div>
                    </td>
                    <td>${this.formatDate(user.created_at)}</td>
                    <td>${user.questions_count || 0}</td>
                    <td>${user.answers_count || 0}</td>
                    <td><span class="badge badge-${this.getStatusBadgeClass(user.status)}">${user.status}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn btn-view" data-id="${user.id}" data-type="user">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn btn-edit" data-id="${user.id}" data-type="user">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete" data-id="${user.id}" data-type="user">
                                <i class="fas fa-ban"></i>
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

    async addUser() {
        const form = document.getElementById('add-user-form');
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        submitButton.disabled = true;
        submitButton.textContent = 'Adding...';

        try {
            const response = await fetch('/admin/api/users', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const result = await response.json();
                window.adminDashboard.showNotification(result.message || 'User added successfully', 'success');
                this.closeAddUserModal();
                this.loadUsers(this.currentFilters);
                this.loadStats();
                form.reset();
            } else {
                const error = await response.json();
                window.adminDashboard.showNotification(error.message || 'Error adding user', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }

    showAddUserModal() {
        document.getElementById('add-user-modal').style.display = 'flex';
    }

    closeAddUserModal() {
        document.getElementById('add-user-modal').style.display = 'none';
    }

    showUserFilterModal() {
        document.getElementById('user-filter-modal').style.display = 'flex';
    }

    closeUserFilterModal() {
        document.getElementById('user-filter-modal').style.display = 'none';
    }

    // Utility methods
    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString();
    }
}

// Initialize users manager
let usersManager;

document.addEventListener('DOMContentLoaded', () => {
    usersManager = new UsersManager();
});

// Global functions for onclick handlers
function showAddUserModal() {
    usersManager.showAddUserModal();
}

function closeAddUserModal() {
    usersManager.closeAddUserModal();
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
</script>
@endpush 