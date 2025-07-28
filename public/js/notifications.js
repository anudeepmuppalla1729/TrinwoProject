// Notification System JavaScript
class NotificationSystem {
    constructor() {
        this.isDropdownOpen = false;
        this.currentFilter = 'all';
        this.notifications = [];
        this.unreadCount = 0;
        this.updateInterval = null;
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadUnreadCount();
        this.startAutoUpdate();
        
        // If we're on the notifications page, load notifications immediately
        if (window.location.pathname === '/notifications') {
            this.loadNotifications();
        }
    }

    bindEvents() {
        // Toggle dropdown
        const toggle = document.getElementById('notificationToggle');
        if (toggle) {
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleDropdown();
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.notification-dropdown')) {
                this.closeDropdown();
            }
        });

        // Filter buttons
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.setFilter(btn.dataset.filter);
            });
        });

        // Action buttons
        const markAllReadBtn = document.getElementById('markAllRead');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.markAllAsRead();
            });
        }



        // View all notifications
        const viewAllBtn = document.getElementById('viewAllNotifications');
        if (viewAllBtn) {
            viewAllBtn.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = '/notifications';
            });
        }
    }

    toggleDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        if (!dropdown) return;

        if (this.isDropdownOpen) {
            this.closeDropdown();
        } else {
            this.openDropdown();
        }
    }

    openDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        if (!dropdown) return;

        dropdown.classList.add('show');
        this.isDropdownOpen = true;
        this.loadNotifications();
    }

    closeDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        if (!dropdown) return;

        dropdown.classList.remove('show');
        this.isDropdownOpen = false;
    }

    async loadUnreadCount() {
        try {
            const response = await fetch('/api/notifications/unread-count', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const data = await response.json();
            
            this.unreadCount = data.count;
            this.updateBadge();
        } catch (error) {
            console.error('Error loading unread count:', error);
        }
    }

    updateBadge() {
        const badge = document.getElementById('notificationBadge');
        if (!badge) return;

        if (this.unreadCount > 0) {
            badge.textContent = this.unreadCount > 99 ? '99+' : this.unreadCount;
            badge.style.display = 'flex';
            badge.classList.add('has-notifications');
        } else {
            badge.style.display = 'none';
            badge.classList.remove('has-notifications');
        }
    }

    async loadNotifications() {
        const list = document.getElementById('notificationList');
        const empty = document.getElementById('notificationEmpty');
        
        if (!list) {
            console.log('Notification list element not found');
            return;
        }

        console.log('Loading notifications...');

        // Show loading with better styling
        list.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading your notifications...</p>
            </div>
        `;

        try {
            const response = await fetch('/api/notifications', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Notifications data:', data);
            
            this.notifications = data.notifications || [];
            this.renderNotifications();
        } catch (error) {
            console.error('Error loading notifications:', error);
            list.innerHTML = `
                <div class="loading-spinner">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Error loading notifications: ${error.message}</p>
                    <button class="btn btn-primary btn-sm mt-3" onclick="window.notificationSystem.loadNotifications()">
                        <i class="fas fa-redo"></i> Try Again
                    </button>
                </div>
            `;
        }
    }

    renderNotifications() {
        const list = document.getElementById('notificationList');
        const empty = document.getElementById('notificationEmpty');
        
        if (!list) {
            console.log('Notification list element not found in renderNotifications');
            return;
        }

        console.log('Rendering notifications. Total:', this.notifications.length, 'Filter:', this.currentFilter);

        // Filter notifications based on current filter
        let filteredNotifications = this.notifications;
        if (this.currentFilter !== 'all') {
            if (this.currentFilter === 'unread') {
                filteredNotifications = this.notifications.filter(n => !n.is_read);
            } else {
                filteredNotifications = this.notifications.filter(n => n.type === this.currentFilter);
            }
        }

        console.log('Filtered notifications:', filteredNotifications.length);

        if (filteredNotifications.length === 0) {
            console.log('No notifications to display, showing empty state');
            list.style.display = 'none';
            if (empty) {
                empty.style.display = 'block';
            }
            return;
        }

        list.style.display = 'block';
        if (empty) {
            empty.style.display = 'none';
        }

        list.innerHTML = filteredNotifications.map(notification => this.createNotificationHTML(notification)).join('');
        
        // Bind events to notification items
        this.bindNotificationEvents();
    }

    createNotificationHTML(notification) {
        const isUnread = !notification.is_read;
        const senderHTML = notification.sender ? `
            <div class="notification-sender">
                ${
                    notification.sender.avatar
                        ? `<img src="${notification.sender.avatar}" alt="${notification.sender.name}" class="sender-avatar">`
                        : `<span class="sender-avatar default-avatar">${notification.sender.name
                            ? notification.sender.name.split(' ').map(w => w[0]).join('').toUpperCase()
                            : 'U'}</span>`
                }
                <span class="sender-name">${notification.sender.name || 'User'}</span>
            </div>
        ` : '';

        const linkHTML = notification.link ? `
            <div class="notification-link">
                <a href="${notification.link}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
            </div>
        ` : '';

        return `
            <div class="notification-item ${notification.type} ${isUnread ? 'unread' : ''}" data-id="${notification.id}" data-type="${notification.type}">
                <div class="notification-icon">
                    <i class="${notification.icon}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <h4 class="notification-title">${notification.title}</h4>
                        <span class="notification-time">${notification.created_at}</span>
                    </div>
                    <p class="notification-message">${notification.message}</p>
                    ${senderHTML}
                </div>
                <div class="notification-actions">
                    ${isUnread ? `
                        <button class="btn btn-sm btn-outline-primary mark-read-btn" title="Mark as read">
                            <i class="fas fa-check"></i>
                        </button>
                    ` : ''}
                    ${linkHTML}
                </div>
            </div>
        `;
    }

    bindNotificationEvents() {
        // Mark as read buttons
        const markReadBtns = document.querySelectorAll('.mark-read-btn');
        markReadBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const item = btn.closest('.notification-item');
                const notificationId = item.dataset.id;
                this.markAsRead(notificationId);
            });
        });

        // Click on notification item
        const notificationItems = document.querySelectorAll('.notification-item');
        notificationItems.forEach(item => {
            item.addEventListener('click', (e) => {
                if (!e.target.closest('.notification-actions') && !e.target.closest('.notification-link')) {
                    const notificationId = item.dataset.id;
                    this.markAsRead(notificationId);
                }
            });
        });
    }

    setFilter(filter) {
        this.currentFilter = filter;
        
        // Update active filter button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-filter="${filter}"]`).classList.add('active');
        
        this.renderNotifications();
    }

    async markAsRead(notificationId) {
        try {
            const response = await fetch('/api/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ notification_id: notificationId })
            });

            if (response.ok) {
                // Update UI
                const item = document.querySelector(`[data-id="${notificationId}"]`);
                if (item) {
                    item.classList.remove('unread');
                    const markReadBtn = item.querySelector('.mark-read-btn');
                    if (markReadBtn) {
                        markReadBtn.remove();
                    }
                }
                
                // Update unread count
                this.loadUnreadCount();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            const response = await fetch('/api/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                // Update UI
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                    const markReadBtn = item.querySelector('.mark-read-btn');
                    if (markReadBtn) {
                        markReadBtn.remove();
                    }
                });
                
                // Update unread count
                this.loadUnreadCount();
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }





    startAutoUpdate() {
        // Update unread count every 30 seconds
        this.updateInterval = setInterval(() => {
            this.loadUnreadCount();
        }, 30000);
    }

    stopAutoUpdate() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
    }

    // Method to add a new notification (for real-time updates)
    addNotification(notification) {
        this.notifications.unshift(notification);
        this.unreadCount++;
        this.updateBadge();
        
        if (this.isDropdownOpen) {
            this.renderNotifications();
        }
    }
}

// Initialize notification system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.notificationSystem = new NotificationSystem();
});

// Export for global access
window.NotificationSystem = NotificationSystem; 