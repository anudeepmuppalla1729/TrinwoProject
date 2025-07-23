// Profile page JavaScript for handling follow/unfollow and follower management
document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile.js loaded');
    
    // Handle unfollow button clicks on the following page
    const unfollowButtons = document.querySelectorAll('.unfollow-btn');
    console.log('Unfollow buttons found:', unfollowButtons.length);
    
    if (unfollowButtons.length > 0) {
        unfollowButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                console.log('Unfollowing user ID:', userId);
                if (!userId) {
                    console.error('No user ID found on button');
                    return;
                }
                
                // Send unfollow request
                fetch(`/user/${userId}/unfollow`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    console.log('Unfollow response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Unfollow response data:', data);
                    if (data.success) {
                        // Remove the user card from the grid
                        const userCard = this.closest('.user-card');
                        userCard.style.opacity = '0';
                        setTimeout(() => {
                            userCard.remove();
                            
                            // Check if there are no more users being followed
                            const remainingCards = document.querySelectorAll('.user-card');
                            if (remainingCards.length === 0) {
                                const userGrid = document.querySelector('.user-grid');
                                userGrid.innerHTML = `
                                    <div class="user-card">
                                        <div class="user-header"></div>
                                        <div class="user-body">
                                            <div class="user-name">You are not following anyone yet.</div>
                                        </div>
                                    </div>
                                `;
                            }
                        }, 300);
                        
                        // Show success message
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Failed to unfollow user', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred while unfollowing the user', 'error');
                });
            });
        });
    }
    
    // Handle remove follower button clicks on the followers page
    const removeFollowerButtons = document.querySelectorAll('.remove-follower-btn');
    console.log('Remove follower buttons found:', removeFollowerButtons.length);
    
    if (removeFollowerButtons.length > 0) {
        removeFollowerButtons.forEach(button => {
            button.addEventListener('click', function() {
                const followerId = this.getAttribute('data-follower-id');
                const userId = this.getAttribute('data-user-id');
                console.log('Removing follower ID:', followerId, 'User ID:', userId);
                
                if (!followerId || !userId) {
                    console.error('Missing follower ID or user ID');
                    return;
                }
                
                // Send remove follower request
                fetch(`/profile/followers/${followerId}/remove`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    console.log('Remove follower response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Remove follower response data:', data);
                    if (data.success) {
                        // Remove the user card from the grid
                        const userCard = this.closest('.user-card');
                        userCard.style.opacity = '0';
                        setTimeout(() => {
                            userCard.remove();
                            
                            // Check if there are no more followers
                            const remainingCards = document.querySelectorAll('.user-card');
                            if (remainingCards.length === 0) {
                                const userGrid = document.querySelector('.user-grid');
                                userGrid.innerHTML = `
                                    <div class="user-card">
                                        <div class="user-header"></div>
                                        <div class="user-body">
                                            <div class="user-name">You have no followers yet.</div>
                                        </div>
                                    </div>
                                `;
                            }
                        }, 300);
                        
                        // Show success message
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Failed to remove follower', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred while removing the follower', 'error');
                });
            });
        });
    }
    
    // Function to show notification
    function showNotification(message, type = 'success') {
        // Check if notification container exists, if not create it
        let notificationContainer = document.querySelector('.notification-container');
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.className = 'notification-container';
            notificationContainer.style.position = 'fixed';
            notificationContainer.style.top = '20px';
            notificationContainer.style.right = '20px';
            notificationContainer.style.zIndex = '9999';
            document.body.appendChild(notificationContainer);
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';
        notification.style.color = 'white';
        notification.style.padding = '15px';
        notification.style.marginBottom = '10px';
        notification.style.borderRadius = '4px';
        notification.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
        notification.style.transition = 'all 0.3s ease';
        notification.style.opacity = '0';
        notification.textContent = message;
        
        // Add close button
        const closeButton = document.createElement('span');
        closeButton.innerHTML = '&times;';
        closeButton.style.marginLeft = '10px';
        closeButton.style.float = 'right';
        closeButton.style.fontWeight = 'bold';
        closeButton.style.fontSize = '20px';
        closeButton.style.cursor = 'pointer';
        closeButton.onclick = function() {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        };
        notification.appendChild(closeButton);
        
        // Add to container and animate in
        notificationContainer.appendChild(notification);
        setTimeout(() => notification.style.opacity = '1', 10);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }
    
    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        .user-card {
            transition: opacity 0.3s ease;
        }
        .notification {
            transition: opacity 0.3s ease;
        }
    `;
    document.head.appendChild(style);
});