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
@endsection

@push('scripts')
<script>
// Answers specific JavaScript
class AnswersManager {
    constructor() {
        this.currentFilters = {};
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
                container.innerHTML = this.generateAnswersTable(answers);
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
                <tr>
                    <td>
                        <div style="font-weight: 500; margin-bottom: 5px;">${this.truncateText(answer.content, 80)}</div>
                    </td>
                    <td>
                        <div style="font-size: 0.85rem; color: var(--gray);">${this.truncateText(answer.question.title, 50)}</div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <img src="${answer.user.avatar || 'https://i.pravatar.cc/30?img=' + answer.user.id}" alt="User" style="border-radius: 50%; width: 30px; height: 30px;">
                            <span>${answer.user.username}</span>
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
</script>
@endpush 