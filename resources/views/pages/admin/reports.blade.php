@extends('layouts.admin')

@section('title', 'Reports | Inqube')

@section('content')
<div class="header">
    <h1><i class="fas fa-flag"></i> Reported Content Management</h1>
</div>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(67, 97, 238, 0.1); color: var(--primary);">
            <i class="fas fa-flag"></i>
        </div>
        <div class="stat-info">
            <h3 id="total-reports">-</h3>
            <p>Total Reports</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(247, 37, 133, 0.1); color: var(--danger);">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="stat-info">
            <h3 id="pending-reports">-</h3>
            <p>Pending Reports</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3 id="resolved-reports">-</h3>
            <p>Resolved Reports</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-list"></i> Recent Reports</h2>
        <div>
            <button class="btn btn-outline" onclick="showFilters()">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="btn btn-primary" onclick="refreshReports()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
        </div>
    </div>
    
    <div class="card-body">
        <div id="reports-table-container">
            <div class="loading">Loading reports...</div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div id="filter-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Filter Reports</h3>
            <button class="modal-close" onclick="closeFilterModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="filter-form" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Report Type</label>
                        <select name="type" class="form-control">
                            <option value="">All Types</option>
                            <option value="question">Question</option>
                            <option value="answer">Answer</option>
                            <option value="post">Post</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="review">Under Review</option>
                            <option value="resolved">Resolved</option>
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
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <button type="button" class="btn btn-outline" onclick="resetFilters()">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Reports specific JavaScript
class ReportsManager {
    constructor() {
        this.currentFilters = {};
        this.init();
    }

    init() {
        this.loadStats();
        this.loadReports();
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Filter form submission
        document.getElementById('filter-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.applyFilters();
        });
    }

    async loadStats() {
        try {
            const response = await fetch('/admin/api/reports/stats', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const stats = await response.json();
                document.getElementById('total-reports').textContent = stats.total;
                document.getElementById('pending-reports').textContent = stats.pending;
                document.getElementById('resolved-reports').textContent = stats.resolved;
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    async loadReports(filters = {}) {
        const container = document.getElementById('reports-table-container');
        container.innerHTML = '<div class="loading">Loading reports...</div>';

        try {
            const queryString = new URLSearchParams(filters).toString();
            const response = await fetch(`/admin/api/reports?${queryString}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const responseData = await response.json();
                const reports = responseData.data || responseData; // Handle both new and old format
                container.innerHTML = this.generateReportsTable(reports, responseData.pagination);
            } else {
                container.innerHTML = '<div class="error">Error loading reports</div>';
            }
        } catch (error) {
            console.error('Error:', error);
            container.innerHTML = '<div class="error">Network error</div>';
        }
    }

    generateReportsTable(reports, pagination = null) {
        if (!reports || !reports.length) {
            return '<div class="no-data">No reports found</div>';
        }

        let html = `
            <table class="table">
                <thead>
                    <tr>
                        <th>Content Title</th>
                        <th>Type</th>
                        <th>Reported By</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        reports.forEach(report => {
            html += `
                <tr>
                    <td>${this.truncateText(report.content_title || report.content, 50)}</td>
                    <td><span class="badge badge-${this.getTypeBadgeClass(report.type)}">${report.type}</span></td>
                    <td>${report.reporter_name}</td>
                    <td>${this.formatDate(report.created_at)}</td>
                    <td><span class="badge badge-${this.getStatusBadgeClass(report.status)}">${report.status}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn btn-view" onclick="reportsManager.viewReport('${report.type}', ${report.id})" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${report.status !== 'resolved' ? `
                                <button class="action-btn btn-edit" onclick="reportsManager.resolveReport('${report.type}', ${report.id})" title="Resolve Report">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="action-btn btn-delete" onclick="reportsManager.deleteContent('${report.type}', ${report.id})" title="Delete Content">
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        
        // Add pagination if available
        if (pagination) {
            html += this.generatePagination(pagination);
        }
        
        return html;
    }

    getTypeBadgeClass(type) {
        const classes = {
            'question': 'warning',
            'answer': 'success',
            'post': 'primary'
        };
        return classes[type] || 'gray';
    }

    getStatusBadgeClass(status) {
        const classes = {
            'pending': 'danger',
            'review': 'warning',
            'resolved': 'success',
            'dismissed': 'gray'
        };
        return classes[status] || 'gray';
    }

    generatePagination(pagination) {
        if (!pagination || pagination.last_page <= 1) {
            return '';
        }

        let html = '<div class="pagination">';
        
        // Previous button
        if (pagination.current_page > 1) {
            html += `<button class="btn btn-outline" onclick="reportsManager.loadPage(${pagination.current_page - 1})">Previous</button>`;
        }
        
        // Page numbers
        for (let i = 1; i <= pagination.last_page; i++) {
            if (i === pagination.current_page) {
                html += `<button class="btn btn-primary" disabled>${i}</button>`;
            } else {
                html += `<button class="btn btn-outline" onclick="reportsManager.loadPage(${i})">${i}</button>`;
            }
        }
        
        // Next button
        if (pagination.current_page < pagination.last_page) {
            html += `<button class="btn btn-outline" onclick="reportsManager.loadPage(${pagination.current_page + 1})">Next</button>`;
        }
        
        html += '</div>';
        return html;
    }

    async loadPage(page) {
        const filters = { ...this.currentFilters, page };
        await this.loadReports(filters);
    }

    async viewReport(type, id) {
        // Show loading modal
        const loadingModal = this.showLoadingModal('Loading report details...');
        
        try {
            const response = await fetch(`/admin/api/reports/${type}/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const report = await response.json();
                loadingModal.remove();
                this.showReportModal(report);
            } else {
                loadingModal.remove();
                window.adminDashboard.showNotification('Error loading report details', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            loadingModal.remove();
            window.adminDashboard.showNotification('Network error', 'error');
        }
    }

    async resolveReport(type, id) {
        console.log('resolveReport called with:', { type, id });
        
        try {
            const confirmed = await this.showConfirmModal(
                'Resolve Report',
                'Are you sure you want to resolve this report without taking action?',
                'Resolve',
                'warning'
            );
            
            console.log('Confirm modal result:', confirmed);
            
            if (!confirmed) {
                console.log('User cancelled resolve action');
                return;
            }
        } catch (error) {
            console.error('Error in showConfirmModal:', error);
            return;
        }

        const loadingModal = this.showLoadingModal('Resolving report...');

        try {
            console.log('Making request to:', `/admin/api/reports/${type}/${id}/status`);
            const response = await fetch(`/admin/api/reports/${type}/${id}/status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: 'resolved' })
            });

            console.log('Response status:', response.status);
            const responseData = await response.text();
            console.log('Response data:', responseData);

            loadingModal.remove();

            if (response.ok) {
                window.adminDashboard.showNotification('Report resolved successfully', 'success');
                // Automatically refresh the reports list
                setTimeout(() => {
                    this.refreshReports();
                }, 1000);
            } else {
                window.adminDashboard.showNotification('Error resolving report: ' + responseData, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            loadingModal.remove();
            window.adminDashboard.showNotification('Network error', 'error');
        }
    }

    async deleteContent(type, id) {
        console.log('deleteContent called with:', { type, id });
        
        try {
            const confirmed = await this.showConfirmModal(
                'Delete Content',
                'Are you sure you want to delete this content? This action cannot be undone and will send notification emails to both the content author and reporter.',
                'Delete',
                'danger'
            );
            
            console.log('Confirm modal result:', confirmed);
            
            if (!confirmed) {
                console.log('User cancelled delete action');
                return;
            }
        } catch (error) {
            console.error('Error in showConfirmModal:', error);
            return;
        }

        const loadingModal = this.showLoadingModal('Deleting content...');

        try {
            console.log('Making request to:', `/admin/api/reports/${type}/${id}/content`);
            const response = await fetch(`/admin/api/reports/${type}/${id}/content`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            console.log('Response status:', response.status);
            const responseData = await response.text();
            console.log('Response data:', responseData);

            loadingModal.remove();

            if (response.ok) {
                window.adminDashboard.showNotification('Content deleted successfully', 'success');
                // Automatically refresh the reports list
                setTimeout(() => {
                    this.refreshReports();
                }, 1000);
            } else {
                window.adminDashboard.showNotification('Error deleting content: ' + responseData, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            loadingModal.remove();
            window.adminDashboard.showNotification('Network error', 'error');
        }
    }

    showReportModal(report) {
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.style.display = 'flex';
        
        // Avatar logic
        let authorName = report.content && (report.content.author || report.content.author_name || report.content.user_name || 'User');
        let avatar = report.content && (report.content.avatar || '');
        let avatarHtml = '';
        if (avatar && avatar.length > 0) {
            avatarHtml = `<img src="${window.s3BaseUrl + avatar}" alt="${authorName}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">`;
        } else {
            avatarHtml = `<img src="https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&size=32" alt="${authorName}">`;
        }

        // Content image logic (for posts, etc.)
        let contentImage = report.content && (report.content.image_url || report.content.image || '');
        let contentImageHtml = '';
        if (contentImage && contentImage.length > 0) {
            if (contentImage.startsWith('http')) {
                contentImageHtml = `<img src="${contentImage}" alt="Content Image" style="max-width:100%;border-radius:8px;margin-top:8px;">`;
            } else {
                contentImageHtml = `<img src="${window.s3BaseUrl + contentImage}" alt="Content Image" style="max-width:100%;border-radius:8px;margin-top:8px;">`;
            }
        }

        // Determine full view link/button
        let viewFullBtn = '';
        if (report.type === 'post' && report.content && report.content.id) {
            viewFullBtn = `<a href="/posts/${report.content.id}" class="btn btn-info" target="_blank" style="margin-right:8px;">View Full Post</a>`;
        } else if (report.type === 'question' && report.content && report.content.id) {
            viewFullBtn = `<a href="/questions/${report.content.id}" class="btn btn-info" target="_blank" style="margin-right:8px;">View Full Question</a>`;
        } else if (report.type === 'answer' && report.content && report.content.id && report.content.question_id) {
            viewFullBtn = `<a href="/questions/${report.content.question_id}#answer-${report.content.id}" class="btn btn-info" target="_blank" style="margin-right:8px;">View Full Question</a>`;
        }

        const content = `
            <div class="modal-content" style="max-width: 600px;">
                <div class="modal-header">
                    <h3>Report Details - ${report.type.toUpperCase()}</h3>
                    <button class="modal-close" onclick="this.closest('.modal').remove()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="report-details">
                        <div class="detail-section">
                            <h4>Report Information</h4>
                            <p><strong>Reported by:</strong> ${report.reporter.name}</p>
                            <p><strong>Reason:</strong> ${report.reason}</p>
                            <p><strong>Status:</strong> <span class="badge badge-${this.getStatusBadgeClass(report.status)}">${report.status}</span></p>
                            <p><strong>Date:</strong> ${this.formatDate(report.created_at)}</p>
                        </div>
                        <div class="detail-section">
                            <h4>Content Details</h4>
                            <p><strong>Title:</strong> ${report.content.title || report.content.question_title || 'N/A'}</p>
                            <p><strong>Author:</strong> ${authorName} ${avatarHtml}</p>
                            <p><strong>Created:</strong> ${this.formatDate(report.content.created_at)}</p>
                            <div class="content-preview">
                                <strong>Content:</strong>
                                <div class="content-text">${report.content.content}</div>
                                ${contentImageHtml}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline" onclick="this.closest('.modal').remove()">Close</button>
                    ${viewFullBtn}
                    ${report.status !== 'resolved' ? `<button class="btn btn-primary" onclick="reportsManager.resolveReportFromModal('${report.type}', ${report.id})">Resolve Report</button>` : ''}
                    <button class="btn btn-danger" onclick="reportsManager.deleteContentFromModal('${report.type}', ${report.id})">Delete Content</button>
                </div>
            </div>
        `;
        
        modal.innerHTML = content;
        document.body.appendChild(modal);
        
        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    applyFilters() {
        const form = document.getElementById('filter-form');
        const formData = new FormData(form);
        const filters = {};

        for (const [key, value] of formData.entries()) {
            if (value) {
                filters[key] = value;
            }
        }

        // Convert date inputs to proper format
        if (filters.date_from) {
            filters.date_from = new Date(filters.date_from).toISOString().split('T')[0];
        }
        if (filters.date_to) {
            filters.date_to = new Date(filters.date_to).toISOString().split('T')[0];
        }

        this.currentFilters = filters;
        this.loadReports(filters);
        this.closeFilterModal();
    }

    resetFilters() {
        document.getElementById('filter-form').reset();
        this.currentFilters = {};
        this.loadReports();
    }

    showFilterModal() {
        document.getElementById('filter-modal').style.display = 'flex';
    }

    closeFilterModal() {
        document.getElementById('filter-modal').style.display = 'none';
    }

    async exportReports() {
        try {
            const queryString = new URLSearchParams(this.currentFilters).toString();
            const response = await fetch(`/admin/api/reports/export?${queryString}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'reports.csv';
                a.click();
                window.URL.revokeObjectURL(url);
            }
        } catch (error) {
            console.error('Error exporting reports:', error);
            window.adminDashboard.showNotification('Error exporting reports', 'error');
        }
    }

    refreshReports() {
        this.loadStats();
        this.loadReports(this.currentFilters);
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

    showConfirmModal(title, message, confirmText = 'Confirm', type = 'warning') {
        return new Promise((resolve) => {
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.style.display = 'flex';
            
            const icon = type === 'danger' ? 'fa-exclamation-triangle' : 'fa-question-circle';
            const btnClass = type === 'danger' ? 'btn-danger' : 'btn-warning';
            
            modal.innerHTML = `
                <div class="modal-content" style="max-width: 500px;">
                    <div class="modal-header">
                        <h3><i class="fas ${icon}"></i> ${title}</h3>
                        <button class="modal-close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline cancel-btn">Cancel</button>
                        <button class="btn ${btnClass} confirm-btn">${confirmText}</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Add event listeners
            const closeBtn = modal.querySelector('.modal-close');
            const cancelBtn = modal.querySelector('.cancel-btn');
            const confirmBtn = modal.querySelector('.confirm-btn');
            
            closeBtn.addEventListener('click', () => {
                modal.remove();
                resolve(false);
            });
            
            cancelBtn.addEventListener('click', () => {
                modal.remove();
                resolve(false);
            });
            
            confirmBtn.addEventListener('click', () => {
                modal.remove();
                resolve(true);
            });
            
            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                    resolve(false);
                }
            });
        });
    }

    async resolveReportFromModal(type, id) {
        const confirmed = await this.showConfirmModal(
            'Resolve Report',
            'Are you sure you want to resolve this report without taking action?',
            'Resolve',
            'warning'
        );
        
        if (!confirmed) return;

        const loadingModal = this.showLoadingModal('Resolving report...');

        try {
            const response = await fetch(`/admin/api/reports/${type}/${id}/status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: 'resolved' })
            });

            loadingModal.remove();

            if (response.ok) {
                window.adminDashboard.showNotification('Report resolved successfully', 'success');
                // Close the report modal and refresh
                document.querySelector('.modal').remove();
                this.refreshReports();
            } else {
                window.adminDashboard.showNotification('Error resolving report', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            loadingModal.remove();
            window.adminDashboard.showNotification('Network error', 'error');
        }
    }

    async deleteContentFromModal(type, id) {
        const confirmed = await this.showConfirmModal(
            'Delete Content',
            'Are you sure you want to delete this content? This action cannot be undone and will send notification emails to both the content author and reporter.',
            'Delete',
            'danger'
        );
        
        if (!confirmed) return;

        const loadingModal = this.showLoadingModal('Deleting content...');

        try {
            const response = await fetch(`/admin/api/reports/${type}/${id}/content`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            loadingModal.remove();

            if (response.ok) {
                window.adminDashboard.showNotification('Content deleted successfully', 'success');
                // Close the report modal and refresh
                document.querySelector('.modal').remove();
                this.refreshReports();
            } else {
                window.adminDashboard.showNotification('Error deleting content', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            loadingModal.remove();
            window.adminDashboard.showNotification('Network error', 'error');
        }
    }
}

// Initialize reports manager
let reportsManager;

document.addEventListener('DOMContentLoaded', () => {
    reportsManager = new ReportsManager();
    window.reportsManager = reportsManager;
});

// Global functions for onclick handlers
function showFilters() {
    reportsManager.showFilterModal();
}

function closeFilterModal() {
    reportsManager.closeFilterModal();
}

function resetFilters() {
    reportsManager.resetFilters();
}

function exportReports() {
    reportsManager.exportReports();
}

function refreshReports() {
    reportsManager.refreshReports();
}
</script>
@endpush 