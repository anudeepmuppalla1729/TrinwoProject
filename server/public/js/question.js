// Question detail page specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Voting is now handled by form submissions
    const answerSection = document.querySelector('.answers-section');

    
    // Handle comment buttons
    const commentButtons = document.querySelectorAll('.comment-btn');
    commentButtons.forEach(button => {
        button.addEventListener('click', function() {
            alert('Comment feature coming soon!');
        });
    });
    
    // Handle share buttons
    const shareButtons = document.querySelectorAll('.share-btn');
    shareButtons.forEach(button => {
        button.addEventListener('click', function() {
            alert('Share feature coming soon!');
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
            alert('Sort feature coming soon!');
        });
    });
});