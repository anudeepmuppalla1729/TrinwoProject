@extends('layouts.admin')

@section('title', 'Questions')

@section('content')
<div class="header">
<h1 class="page-title"><i class="fas fa-question-circle"></i> Questions Management</h1>
    <div class="header-actions">
        <button class="btn btn-outline" onclick="exportReports()">
            <i class="fas fa-download"></i> Export
        </button>
        <button class="btn btn-primary" onclick="refreshQuestions()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>
</div>
<div class="stats-container stats-4-per-row">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(67, 97, 238, 0.1); color: var(--primary);">
            <i class="fas fa-question-circle"></i>
        </div>
        <div class="stat-info">
            <h3 id="total-questions">-</h3>
            <p>Total Questions</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3 id="answered-questions">-</h3>
            <p>Answered</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(248, 150, 30, 0.1); color: var(--warning);">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3 id="unanswered-questions">-</h3>
            <p>Unanswered</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(220, 53, 69, 0.1); color: var(--danger);">
            <i class="fas fa-lock"></i>
        </div>
        <div class="stat-info">
            <h3 id="closed-questions">-</h3>
            <p>Closed</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(102, 16, 242, 0.1); color: #6610f2;">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-info">
            <h3 id="questions-today">-</h3>
            <p>Today</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(253, 126, 20, 0.1); color: #fd7e14;">
            <i class="fas fa-calendar-week"></i>
        </div>
        <div class="stat-info">
            <h3 id="questions-this-week">-</h3>
            <p>This Week</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(220, 53, 69, 0.1); color: var(--danger);">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
            <h3 id="questions-this-month">-</h3>
            <p>This Month</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(40, 167, 69, 0.1); color: var(--success);">
            <i class="fas fa-percentage"></i>
        </div>
        <div class="stat-info">
            <h3 id="answer-rate">-</h3>
            <p>Answer Rate</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title-section">
            <h2 class="card-title"><i class="fas fa-list"></i> Questions Management</h2>
            <div id="search-results-info" class="search-results-info" style="display: none;">
                <span id="search-results-count">0</span> questions found
            </div>
        </div>
        <div class="header-actions">
            <div class="search-container">
                <input type="text" id="question-search-input" class="search-input" placeholder="Search questions..." onkeyup="questionsManager.handleSearch(event)">
                <i class="fas fa-search search-icon"></i>
                <button type="button" id="clear-search-btn" class="clear-search-btn" onclick="questionsManager.clearSearch()" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
                <div id="search-loading" class="search-loading" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
            <button class="btn btn-outline" onclick="showQuestionFilters()">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="btn btn-primary" onclick="refreshQuestions()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
    
    <div class="card-body">
        <div id="questions-table-container">
            <div class="loading">Loading questions...</div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div id="question-filter-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Filter Questions</h3>
            <button class="modal-close" onclick="closeQuestionFilterModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="question-filter-form" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="answered">Answered</option>
                            <option value="unanswered">Unanswered</option>
                            <option value="closed">Closed</option>
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
                    <label>Sort By</label>
                    <select name="sort" class="form-control">
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                        <option value="most_answers">Most Answers</option>
                        <option value="most_views">Most Views</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <button type="button" class="btn btn-outline" onclick="resetQuestionFilters()">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Question Details Modal -->
<div id="question-details-modal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3><i class="fas fa-question-circle"></i> Question Details</h3>
            <button class="modal-close" onclick="closeQuestionDetailsModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="question-details-content">
                <div class="loading">Loading question details...</div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Action Modal -->
<div id="confirm-action-modal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Confirm Action</h3>
            <button class="modal-close" onclick="closeConfirmActionModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirm-message">Are you sure you want to perform this action?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeConfirmActionModal()">Cancel</button>
            <button id="confirm-action-btn" class="btn btn-danger" onclick="executeConfirmedAction()">Confirm</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Questions specific JavaScript
class QuestionsManager {
    constructor() {
        this.currentFilters = {};
        this.searchTimeout = null;
        this.currentQuestionId = null;
        this.currentAction = null;
        this.init();
    }

    init() {
        this.loadStats();
        this.loadQuestions();
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Filter form submission
        document.getElementById('question-filter-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.applyFilters();
        });

        // Search input event listener
        const searchInput = document.getElementById('question-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.handleSearch(e);
            });
        }
    }

    async loadStats() {
        try {
            const response = await fetch('/admin/api/questions/stats', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const stats = await response.json();
                document.getElementById('total-questions').textContent = stats.total;
                document.getElementById('answered-questions').textContent = stats.answered;
                document.getElementById('unanswered-questions').textContent = stats.unanswered;
                document.getElementById('closed-questions').textContent = stats.closed;
                document.getElementById('questions-today').textContent = stats.questions_today;
                document.getElementById('questions-this-week').textContent = stats.questions_this_week;
                document.getElementById('questions-this-month').textContent = stats.questions_this_month;
                document.getElementById('answer-rate').textContent = stats.answer_rate + '%';
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    async loadQuestions(filters = {}) {
        const container = document.getElementById('questions-table-container');
        container.innerHTML = '<div class="loading">Loading questions...</div>';

        try {
            const queryString = new URLSearchParams(filters).toString();
            const response = await fetch(`/admin/api/questions?${queryString}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const questions = await response.json();
                container.innerHTML = this.generateQuestionsTable(questions);
                this.updateSearchResultsInfo(questions.length, filters.search);
                this.setupTableEventListeners();
            } else {
                container.innerHTML = '<div class="error">Error loading questions</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            container.innerHTML = '<div class="error">Network error</div>';
        }
    }

    generateQuestionsTable(questions) {
        if (!questions || !questions.length) {
            return '<div class="no-data">No questions found</div>';
        }

        let html = `
            <table class="table">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Author</th>
                        <th>Date</th>
                        <th>Answers</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        questions.forEach(question => {
            html += `
                <tr>
                    <td>
                        <div>
                            <div style="font-weight: 500; margin-bottom: 5px;">${this.truncateText(question.title, 60)}</div>
                            <div style="font-size: 0.85rem; color: var(--gray);">${this.truncateText(question.content, 80)}</div>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <img src="${question.user.avatar || 'https://i.pravatar.cc/30?img=' + question.user.id}" alt="User" style="border-radius: 50%; width: 30px; height: 30px;">
                            <span>${question.user.username}</span>
                        </div>
                    </td>
                    <td>${this.formatDate(question.created_at)}</td>
                    <td>${question.answers_count || 0}</td>
                    <td>${question.views_count || 0}</td>
                    <td><span class="badge badge-${this.getStatusBadgeClass(question.status)}">${question.status}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn btn-view" data-id="${question.id}" data-type="question">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn btn-edit" data-id="${question.id}" data-type="question">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete" data-id="${question.id}" data-type="question">
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
            'answered': 'success',
            'unanswered': 'warning',
            'closed': 'danger'
        };
        return classes[status] || 'gray';
    }

    applyFilters() {
        const form = document.getElementById('question-filter-form');
        const formData = new FormData(form);
        const filters = {};

        for (const [key, value] of formData.entries()) {
            if (value) {
                filters[key] = value;
            }
        }

        this.currentFilters = filters;
        this.loadQuestions(filters);
        this.closeQuestionFilterModal();
    }

    resetQuestionFilters() {
        document.getElementById('question-filter-form').reset();
        this.currentFilters = {};
        this.loadQuestions();
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
            this.loadQuestions(this.currentFilters);
            searchLoading.classList.remove('show');
        }, 300);
    }

    clearSearch() {
        const searchInput = document.getElementById('question-search-input');
        const clearBtn = document.getElementById('clear-search-btn');
        const searchLoading = document.getElementById('search-loading');
        
        searchInput.value = '';
        clearBtn.style.display = 'none';
        searchLoading.classList.remove('show');
        
        // Remove search from filters
        delete this.currentFilters.search;
        
        // Reload questions without search
        this.loadQuestions(this.currentFilters);
    }

    searchQuestions(query) {
        if (query.trim()) {
            this.currentFilters.search = query;
        } else {
            delete this.currentFilters.search;
        }
        this.loadQuestions(this.currentFilters);
    }

    showQuestionFilterModal() {
        document.getElementById('question-filter-modal').style.display = 'flex';
    }

    closeQuestionFilterModal() {
        document.getElementById('question-filter-modal').style.display = 'none';
    }

    // Utility methods
    truncateText(text, maxLength = 100) {
        if (!text) return 'N/A';
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
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

    setupTableEventListeners() {
        // View question details
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const questionId = e.currentTarget.dataset.id;
                this.viewQuestionDetails(questionId);
            });
        });

        // Edit question
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const questionId = e.currentTarget.dataset.id;
                this.editQuestion(questionId);
            });
        });

        // Delete question
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const questionId = e.currentTarget.dataset.id;
                this.showConfirmActionModal('delete', questionId, 'Are you sure you want to delete this question? This action cannot be undone.');
            });
        });

        // Toggle status
        document.querySelectorAll('.btn-toggle-status').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const questionId = e.currentTarget.dataset.id;
                this.toggleQuestionStatus(questionId);
            });
        });

        // Toggle featured
        document.querySelectorAll('.btn-toggle-featured').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const questionId = e.currentTarget.dataset.id;
                this.toggleQuestionFeatured(questionId);
            });
        });
    }

    async viewQuestionDetails(questionId) {
        const modal = document.getElementById('question-details-modal');
        const content = document.getElementById('question-details-content');
        
        modal.style.display = 'flex';
        content.innerHTML = '<div class="loading">Loading question details...</div>';

        try {
            const response = await fetch(`/admin/api/questions/${questionId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                content.innerHTML = this.generateQuestionDetailsHTML(data);
            } else {
                content.innerHTML = '<div class="error">Failed to load question details</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            content.innerHTML = '<div class="error">Network error</div>';
        }
    }

    generateQuestionDetailsHTML(question) {
        return `
            <div class="question-details">
                <div class="question-header">
                    <h4>${question.title}</h4>
                    <div class="question-status">
                        <span class="badge badge-${this.getStatusBadgeClass(question.status)}">${question.status}</span>
                        ${question.is_closed ? '<span class="badge badge-danger">Closed</span>' : ''}
                    </div>
                </div>
                
                <div class="question-content">
                    <p>${question.description}</p>
                </div>
                
                <div class="question-meta">
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span>By: ${question.user.first_name} ${question.user.last_name} (@${question.user.username})</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>Created: ${this.formatDate(question.created_at)}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-comments"></i>
                        <span>Answers: ${question.answers_count}</span>
                    </div>
                </div>
                
                ${question.tags && question.tags.length > 0 ? `
                    <div class="question-tags">
                        <h5>Tags:</h5>
                        ${question.tags.map(tag => `<span class="badge badge-outline">${tag}</span>`).join('')}
                    </div>
                ` : ''}
                
                ${question.answers && question.answers.length > 0 ? `
                    <div class="question-answers">
                        <h5>Answers (${question.answers.length}):</h5>
                        ${question.answers.map(answer => `
                            <div class="answer-item">
                                <div class="answer-header">
                                    <span class="answer-author">${answer.user.first_name} ${answer.user.last_name} (@${answer.user.username})</span>
                                    <span class="answer-date">${this.formatDate(answer.created_at)}</span>
                                    ${answer.is_accepted ? '<span class="badge badge-success">Accepted</span>' : ''}
                                </div>
                                <div class="answer-content">
                                    <p>${this.truncateText(answer.content, 200)}</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                ` : '<p class="no-answers">No answers yet.</p>'}
            </div>
        `;
    }

    async toggleQuestionStatus(questionId) {
        try {
            const response = await fetch(`/admin/api/questions/${questionId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                this.showSuccess('Question status updated successfully');
                this.loadQuestions(this.currentFilters);
            } else {
                this.showError('Failed to update question status');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Network error');
        }
    }

    async toggleQuestionFeatured(questionId) {
        try {
            const response = await fetch(`/admin/api/questions/${questionId}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                this.showSuccess('Question featured status updated successfully');
                this.loadQuestions(this.currentFilters);
            } else {
                this.showError('Failed to update question featured status');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Network error');
        }
    }

    confirmAction(action, questionId, message) {
        this.currentQuestionId = questionId;
        // Show confirmation modal
        if (confirm(message)) {
            this.executeAction(action, questionId);
        }
    }

    async executeAction(action, questionId) {
        try {
            let url = `/admin/api/questions/${questionId}`;
            let method = 'DELETE';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                this.showSuccess('Question deleted successfully');
                this.loadQuestions(this.currentFilters);
            } else {
                this.showError('Failed to delete question');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Network error');
        }
    }

    showSuccess(message) {
        // Create success notification
        const notification = document.createElement('div');
        notification.className = 'notification success';
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    showError(message) {
        // Create error notification
        const notification = document.createElement('div');
        notification.className = 'notification error';
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString();
    }

    showConfirmActionModal(action, questionId, message) {
        this.currentAction = action;
        this.currentQuestionId = questionId;
        
        const modal = document.getElementById('confirm-action-modal');
        const confirmMessage = document.getElementById('confirm-message');
        const confirmBtn = document.getElementById('confirm-action-btn');
        
        confirmMessage.textContent = message;
        
        // Set button text and class based on action
        if (action === 'delete') {
            confirmBtn.textContent = 'Delete';
            confirmBtn.className = 'btn btn-danger';
        } else if (action === 'toggle-status') {
            confirmBtn.textContent = 'Toggle Status';
            confirmBtn.className = 'btn btn-warning';
        } else if (action === 'toggle-featured') {
            confirmBtn.textContent = 'Toggle Featured';
            confirmBtn.className = 'btn btn-primary';
        }
        
        modal.style.display = 'flex';
    }

    closeConfirmActionModal() {
        document.getElementById('confirm-action-modal').style.display = 'none';
        this.currentAction = null;
        this.currentQuestionId = null;
    }

    async executeConfirmedAction() {
        if (!this.currentAction || !this.currentQuestionId) return;

        const loadingModal = this.showLoadingModal('Processing...');

        try {
            let response;
            
            switch (this.currentAction) {
                case 'delete':
                    response = await fetch(`/admin/api/questions/${this.currentQuestionId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    break;
                case 'toggle-status':
                    response = await fetch(`/admin/api/questions/${this.currentQuestionId}/status`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ action: 'toggle' })
                    });
                    break;
                case 'toggle-featured':
                    response = await fetch(`/admin/api/questions/${this.currentQuestionId}/featured`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ action: 'toggle' })
                    });
                    break;
            }

            loadingModal.remove();

            if (response && response.ok) {
                this.showSuccess(`${this.currentAction.charAt(0).toUpperCase() + this.currentAction.slice(1)} successful`);
                this.closeConfirmActionModal();
                this.loadStats(); // <-- update stats after action
                this.loadQuestions(this.currentFilters);
            } else {
                this.showError('Action failed');
            }
        } catch (error) {
            console.error('Error:', error);
            loadingModal.remove();
            this.showError('Network error');
        }
    }

    showLoadingModal(message = 'Loading...') {
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.style.display = 'flex';
        
        modal.innerHTML = `
            <div class="modal-content" style="max-width: 400px;">
                <div class="modal-body text-center">
                    <div class="loading-spinner"></div>
                    <p style="margin-top: 1rem;">${message}</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        return modal;
    }

    getStatusBadgeClass(status) {
        switch (status) {
            case 'answered': return 'success';
            case 'unanswered': return 'warning';
            case 'closed': return 'danger';
            default: return 'secondary';
        }
    }
}

// Initialize questions manager
let questionsManager;

document.addEventListener('DOMContentLoaded', () => {
    questionsManager = new QuestionsManager();
});

// Global functions for onclick handlers
function showQuestionFilters() {
    questionsManager.showQuestionFilterModal();
}

function closeQuestionFilterModal() {
    questionsManager.closeQuestionFilterModal();
}

function resetQuestionFilters() {
    questionsManager.resetQuestionFilters();
}

function refreshQuestions() {
    questionsManager.loadStats();
    questionsManager.loadQuestions();
}

function closeQuestionDetailsModal() {
    document.getElementById('question-details-modal').style.display = 'none';
}

function closeConfirmActionModal() {
    questionsManager.closeConfirmActionModal();
}

function executeConfirmedAction() {
    questionsManager.executeConfirmedAction();
}
</script>
@endpush 