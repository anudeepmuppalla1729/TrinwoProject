// Question detail page specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Voting is now handled by form submissions
    const answerSection = document.querySelector('.answers-section');

    if (typeof showToast !== 'function') {
        function showToast(message, type = 'info') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.style.position = 'fixed';
                container.style.top = '24px';
                container.style.right = '24px';
                container.style.zIndex = '99999';
                container.style.display = 'flex';
                container.style.flexDirection = 'column';
                container.style.gap = '12px';
                document.body.appendChild(container);
            }
            const toast = document.createElement('div');
            toast.className = 'toast toast-' + type;
            toast.style.minWidth = '220px';
            toast.style.maxWidth = '350px';
            toast.style.padding = '14px 22px';
            toast.style.borderRadius = '6px';
            toast.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
            toast.style.fontSize = '1rem';
            toast.style.display = 'flex';
            toast.style.alignItems = 'center';
            toast.style.gap = '12px';
            toast.style.animation = 'fadeIn 0.3s';
            toast.style.background = type === 'success' ? '#e6f9f0' : type === 'error' ? '#fff0f0' : '#f0f4ff';
            toast.style.color = type === 'success' ? '#1b7f5a' : type === 'error' ? '#b91c1c' : '#1a237e';
            toast.style.borderLeft = '5px solid ' + (type === 'success' ? '#1b7f5a' : type === 'error' ? '#b91c1c' : '#1a237e');
            toast.innerHTML = (type === 'success' ? '<i class="fas fa-check-circle"></i>' : type === 'error' ? '<i class="fas fa-exclamation-circle"></i>' : '<i class="fas fa-info-circle"></i>') + message;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => container.removeChild(toast), 300);
            }, 2500);
        }
    }
    
    // Handle comment buttons
    const commentButtons = document.querySelectorAll('.comment-btn');
    commentButtons.forEach(button => {
        button.addEventListener('click', function() {
            showToast('Comment feature coming soon!', 'info');
        });
    });
    
    // Handle share buttons
    const shareButtons = document.querySelectorAll('.share-btn');
    shareButtons.forEach(button => {
        button.addEventListener('click', function() {
            showToast('Share feature coming soon!', 'info');
        });
    });
    
    // Handle sort options
    const sortOptions = document.querySelectorAll('.sort-option');
    sortOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            sortOptions.forEach(opt => opt.classList.remove('active'));
            // Add active class to clicked option
            this.classList.add('active');
            showToast('Sort feature coming soon!', 'info');
        });
    });
});