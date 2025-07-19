@extends('layouts.admin')

@section('title', 'Questions')

@section('content')
<div class="header">
<h1 class="page-title"><i class="fas fa-question-circle"></i> Questions Management</h1>
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
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-list"></i> Recent Questions</h2>
        <div>
            <button class="btn btn-outline" onclick="showQuestionFilters()">
                <i class="fas fa-filter"></i> Filter
            </button>
            <input type="text" id="question-search" class="form-control" placeholder="Search questions..." style="width: 250px; display: inline-block; margin-right: 10px;">
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
@endsection

@push('scripts')
<script>
// Questions specific JavaScript
class QuestionsManager {
    constructor() {
        this.currentFilters = {};
        this.searchTimeout = null;
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

        // Search functionality
        document.getElementById('question-search').addEventListener('input', (e) => {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.searchQuestions(e.target.value);
            }, 500);
        });
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
            } else {
                container.innerHTML = '<div class="error">Error loading questions</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            container.innerHTML = '<div class="error">Network error</div>';
        }
    }

    generateQuestionsTable(questions) {
        if (!questions.length) {
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

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString();
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
</script>
@endpush 