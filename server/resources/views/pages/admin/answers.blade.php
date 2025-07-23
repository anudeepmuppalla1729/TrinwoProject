@extends('layouts.admin')

@section('title', 'Answers')

@section('content')

<div class="header">
<h1 class="page-title"><i class="fas fa-comments"></i> Answers Management</h1>
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
            <i class="fas fa-comments"></i>
        </div>
        <div class="stat-info">
            <h3 id="total-answers">-</h3>
            <p>Total Answers</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success);">
            <i class="fas fa-thumbs-up"></i>
        </div>
        <div class="stat-info">
            <h3 id="accepted-answers">-</h3>
            <p>Accepted</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(248, 150, 30, 0.1); color: var(--warning);">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-info">
            <h3 id="top-rated">-</h3>
            <p>Top Rated</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-list"></i> Recent Answers</h2>
        <div>
            <button class="btn btn-outline" onclick="showAnswerFilters()">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="btn btn-primary" onclick="refreshAnswers()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
    
    <div class="card-body">
        <div id="answers-table-container">
            <div class="loading">Loading answers...</div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div id="answer-filter-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Filter Answers</h3>
            <button class="modal-close" onclick="closeAnswerFilterModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="answer-filter-form" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="accepted">Accepted</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Rating</label>
                        <select name="rating" class="form-control">
                            <option value="">All Ratings</option>
                            <option value="high">High Rated (4-5)</option>
                            <option value="medium">Medium Rated (2-3)</option>
                            <option value="low">Low Rated (0-1)</option>
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
                        <option value="most_votes">Most Votes</option>
                        <option value="highest_rated">Highest Rated</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <button type="button" class="btn btn-outline" onclick="resetAnswerFilters()">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Answer Modal -->
<div id="answer-details-modal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3><i class="fas fa-comments"></i> Answer Details</h3>
            <button class="modal-close" onclick="closeAnswerDetailsModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="answer-details-content">
                <div class="loading">Loading answer details...</div>
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
            <p>Are you sure you want to delete this answer? This action cannot be undone.</p>
            <div class="form-group">
                <button id="confirm-delete-btn" class="btn btn-danger">Delete</button>
                <button class="btn btn-outline" onclick="closeConfirmDeleteModal()">Cancel</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Answers specific JavaScript
class AnswersManager {
    constructor() {
        this.currentFilters = {};
        this.currentAnswerId = null;
        this.init();
    }

    init() {
        this.loadStats();
        this.loadAnswers();
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Filter form submission
        document.getElementById('answer-filter-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.applyFilters();
        });
    }

    setupTableEventListeners() {
        // View answer
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const answerId = e.currentTarget.dataset.id;
                this.viewAnswerDetails(answerId);
            });
        });
        // Delete answer
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const answerId = e.currentTarget.dataset.id;
                this.confirmDeleteAnswer(answerId);
            });
        });
    }

    async loadStats() {
        try {
            const response = await fetch('/admin/api/answers/stats', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const stats = await response.json();
                document.getElementById('total-answers').textContent = stats.total;
                document.getElementById('accepted-answers').textContent = stats.accepted;
                document.getElementById('top-rated').textContent = stats.top_rated;
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    async loadAnswers(filters = {}) {
        const container = document.getElementById('answers-table-container');
        container.innerHTML = '<div class="loading">Loading answers...</div>';

        try {
            const queryString = new URLSearchParams(filters).toString();
            const response = await fetch(`/admin/api/answers?${queryString}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const answers = await response.json();
                container.innerHTML = this.generateAnswersTable(answers.data || []);
                this.setupTableEventListeners();
            } else {
                container.innerHTML = '<div class="error">Error loading answers</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            container.innerHTML = '<div class="error">Network error</div>';
        }
    }

    generateAnswersTable(answers) {
        if (!answers.length) {
            return '<div class="no-data">No answers found</div>';
        }

        let html = `
            <table class="table">
                <thead>
                    <tr>
                        <th>Answer Preview</th>
                        <th>Question</th>
                        <th>Author</th>
                        <th>Date</th>
                        <th>Votes</th>
                        <th>Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        answers.forEach(answer => {
            html += `
                <tr data-answer-id="${answer.id}">
                    <td>
                        <div style="font-weight: 500; margin-bottom: 5px;">${this.truncateText(answer.content, 80)}</div>
                    </td>
                    <td>
                        <div style="font-size: 0.85rem; color: var(--gray);">${this.truncateText(answer.question.title, 50)}</div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <img src="${answer.user.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(answer.user.name || answer.user.username || 'User') + '&size=30&background=random'}" alt="User" style="border-radius: 50%; width: 30px; height: 30px;">
                            <span>${answer.user.name || answer.user.username || 'Unknown User'}</span>
                        </div>
                    </td>
                    <td>${this.formatDate(answer.created_at)}</td>
                    <td>${answer.votes_count || 0}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <span style="color: #f8961e;">★</span>
                            <span>${answer.rating || 0}</span>
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn btn-view" data-id="${answer.id}" data-type="answer">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn btn-edit" data-id="${answer.id}" data-type="answer">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete" data-id="${answer.id}" data-type="answer">
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

    async viewAnswerDetails(answerId) {
        const modal = document.getElementById('answer-details-modal');
        const content = document.getElementById('answer-details-content');
        modal.style.display = 'flex';
        content.innerHTML = '<div class="loading">Loading answer details...</div>';
        try {
            const response = await fetch(`/admin/api/answers/${answerId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const data = await response.json();
                content.innerHTML = this.generateAnswerDetailsHTML(data, answerId);
                // Highlight the answer row in the main table
                document.querySelectorAll('tr[data-answer-id]').forEach(row => {
                    row.classList.remove('highlighted-answer');
                });
                const row = document.querySelector(`tr[data-answer-id='${answerId}']`);
                if (row) row.classList.add('highlighted-answer');
            } else {
                content.innerHTML = '<div class="error">Failed to load answer details</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            content.innerHTML = '<div class="error">Network error</div>';
        }
    }

    generateAnswerDetailsHTML(data, answerId) {
        return `
            <div class="question-details">
                <h4>Question: ${data.question.title}</h4>
                <div style="margin-bottom: 1rem; color: #666;">Asked by: ${data.question.user_name || data.question.user_username || 'Unknown User'}</div>
                <div style="margin-bottom: 1rem;">${data.question.content || ''}</div>
                <h5>Answer:</h5>
                <div class="answer-item highlighted-answer">
                    <div style="font-weight: 500;">${data.content}</div>
                    <div style="color: #888; font-size: 0.9rem;">By: ${data.user.name || data.user.username || 'Unknown User'} | ${this.formatDate(data.created_at)}</div>
                </div>
            </div>
        `;
    }

    applyFilters() {
        const form = document.getElementById('answer-filter-form');
        const formData = new FormData(form);
        const filters = {};

        for (const [key, value] of formData.entries()) {
            if (value) {
                filters[key] = value;
            }
        }

        this.currentFilters = filters;
        this.loadAnswers(filters);
        this.closeAnswerFilterModal();
    }

    resetAnswerFilters() {
        document.getElementById('answer-filter-form').reset();
        this.currentFilters = {};
        this.loadAnswers();
    }

    showAnswerFilterModal() {
        document.getElementById('answer-filter-modal').style.display = 'flex';
    }

    closeAnswerFilterModal() {
        document.getElementById('answer-filter-modal').style.display = 'none';
    }

    refreshAnswers() {
        this.loadStats();
        this.loadAnswers(this.currentFilters);
    }

    confirmDeleteAnswer(answerId) {
        this.currentAnswerId = answerId;
        document.getElementById('confirm-delete-modal').style.display = 'flex';
        document.getElementById('confirm-delete-btn').onclick = () => this.deleteAnswer(answerId);
    }

    async deleteAnswer(answerId) {
        const modal = document.getElementById('confirm-delete-modal');
        modal.style.display = 'none';
        try {
            const response = await fetch(`/admin/api/answers/${answerId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                this.refreshAnswers();
            } else {
                alert('Failed to delete answer');
            }
        } catch (error) {
            alert('Network error');
        }
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

// Add highlight style
const style = document.createElement('style');
style.innerHTML = `.highlighted-answer { background: #e6f7ff !important; border-left: 4px solid #1890ff; }`;
document.head.appendChild(style);

// Initialize answers manager
let answersManager;

document.addEventListener('DOMContentLoaded', () => {
    answersManager = new AnswersManager();
});

// Global functions for onclick handlers
function showAnswerFilters() {
    answersManager.showAnswerFilterModal();
}

function closeAnswerFilterModal() {
    answersManager.closeAnswerFilterModal();
}

function resetAnswerFilters() {
    answersManager.resetAnswerFilters();
}

function refreshAnswers() {
    answersManager.refreshAnswers();
}
function closeAnswerDetailsModal() {
    document.getElementById('answer-details-modal').style.display = 'none';
    // Remove highlight from all rows
    document.querySelectorAll('tr[data-answer-id]').forEach(row => {
        row.classList.remove('highlighted-answer');
    });
}
function closeConfirmDeleteModal() {
    document.getElementById('confirm-delete-modal').style.display = 'none';
}
</script>
@endpush 