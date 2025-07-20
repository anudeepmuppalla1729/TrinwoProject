// Questions page specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    const questionCards = document.querySelectorAll('.question-card');
    const searchInput = document.getElementById('question-search');
    const sortSelect = document.getElementById('sort-questions');
    
    // Add staggered animation to question cards on page load
    if (questionCards.length > 0) {
        questionCards.forEach((card, index) => {
            // Add initial state
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            // Animate with delay based on index
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
        });
    }
    
    // Add hover effect to stats
    const stats = document.querySelectorAll('.stat');
    stats.forEach(stat => {
        const icon = stat.querySelector('i');
        const text = stat.querySelector('span');
        
        stat.addEventListener('mouseenter', () => {
            if (icon) icon.style.transform = 'scale(1.2)';
            if (text) text.style.fontWeight = '500';
        });
        
        stat.addEventListener('mouseleave', () => {
            if (icon) icon.style.transform = 'scale(1)';
            if (text) text.style.fontWeight = 'normal';
        });
    });
    
    // Add click effect to tags
    const tags = document.querySelectorAll('.tag');
    tags.forEach(tag => {
        tag.addEventListener('click', (e) => {
            e.preventDefault();
            // Add pulse effect
            tag.style.animation = 'none';
            setTimeout(() => {
                tag.style.animation = 'pulse 0.5s';
            }, 10);
            
            // In the future, this could filter questions by tag
            console.log('Tag clicked:', tag.textContent.trim());
        });
    });
    
    // Add keyframe animation for pulse effect
    if (!document.getElementById('questions-animations')) {
        const style = document.createElement('style');
        style.id = 'questions-animations';
        style.textContent = `
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            questionCards.forEach(card => {
                const title = card.querySelector('.question-title').textContent.toLowerCase();
                const excerpt = card.querySelector('.question-excerpt').textContent.toLowerCase();
                const tags = Array.from(card.querySelectorAll('.tag')).map(tag => tag.textContent.toLowerCase());
                
                // Check if search term is in title, excerpt or tags
                const matchesSearch = 
                    title.includes(searchTerm) || 
                    excerpt.includes(searchTerm) || 
                    tags.some(tag => tag.includes(searchTerm));
                
                // Show/hide card with animation
                if (matchesSearch) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300); // Match transition duration
                }
            });
        });
    }
    
    // Sort functionality
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const questionsList = document.querySelector('.questions-list');
            const cardsArray = Array.from(questionCards);
            
            // Sort cards based on selected option
            cardsArray.sort((a, b) => {
                if (sortValue === 'newest') {
                    // Sort by date (assuming the date is in the meta section)
                    const dateA = a.querySelector('.question-meta span:last-child').textContent;
                    const dateB = b.querySelector('.question-meta span:last-child').textContent;
                    // Simple string comparison for demo purposes
                    // In a real app, you'd parse these dates properly
                    return dateA > dateB ? -1 : 1;
                } 
                else if (sortValue === 'popular') {
                    // Sort by views instead of upvotes
                    const viewsA = parseInt(a.querySelector('.stat:nth-child(1) span').textContent);
                    const viewsB = parseInt(b.querySelector('.stat:nth-child(1) span').textContent);
                    return viewsB - viewsA;
                }
                else if (sortValue === 'answered') {
                    // Sort by number of answers
                    const answersA = parseInt(a.querySelector('.stat:nth-child(2) span').textContent);
                    const answersB = parseInt(b.querySelector('.stat:nth-child(2) span').textContent);
                    return answersB - answersA;
                }
                return 0;
            });
            
            // Remove all cards and re-append in sorted order
            cardsArray.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
            });
            
            setTimeout(() => {
                questionsList.innerHTML = '';
                cardsArray.forEach((card, index) => {
                    questionsList.appendChild(card);
                    
                    // Animate cards back in with staggered delay
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50 * index);
                });
            }, 300);
        });
    }
});