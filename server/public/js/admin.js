// Admin Dashboard JavaScript
class AdminDashboard {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Add any global event listeners here
    }

    showNotification(message, type = 'info', duration = 5000) {
        const container = document.getElementById('notification-container');
        if (!container) return;

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icon = this.getNotificationIcon(type);
        
        notification.innerHTML = `
            <div class="icon">${icon}</div>
            <div class="message">${message}</div>
            <button class="close" onclick="this.parentElement.remove()">&times;</button>
        `;

        container.appendChild(notification);

        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, duration);
        }
    }

    getNotificationIcon(type) {
        const icons = {
            success: '<i class="fas fa-check-circle"></i>',
            error: '<i class="fas fa-exclamation-circle"></i>',
            warning: '<i class="fas fa-exclamation-triangle"></i>',
            info: '<i class="fas fa-info-circle"></i>'
        };
        return icons[type] || icons.info;
    }

    // Utility methods
    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString();
    }

    truncateText(text, maxLength = 100) {
        if (!text) return 'N/A';
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }

    confirmAction(message) {
        return confirm(message);
    }
}

// Initialize admin dashboard
let adminDashboard;

document.addEventListener('DOMContentLoaded', () => {
    adminDashboard = new AdminDashboard();
    window.adminDashboard = adminDashboard;
});

// Global functions for onclick handlers
function showFilters() {
    if (typeof reportsManager !== 'undefined') {
        reportsManager.showFilterModal();
    }
}

function closeFilterModal() {
    if (typeof reportsManager !== 'undefined') {
        reportsManager.closeFilterModal();
    }
}

function resetFilters() {
    if (typeof reportsManager !== 'undefined') {
        reportsManager.resetFilters();
    }
}

function exportReports() {
    if (typeof reportsManager !== 'undefined') {
        reportsManager.exportReports();
    }
}

function refreshReports() {
    if (typeof reportsManager !== 'undefined') {
        reportsManager.refreshReports();
    }
} 