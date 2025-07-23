// Questions page specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    const questionCards = document.querySelectorAll('.question-card');
    const searchInput = document.getElementById('question-search');
    const sortSelect = document.getElementById('sort-questions');
    const questionsList = document.querySelector('.questions-list');
    
    // Function to show toast notifications
    window.showToast = function(message, type = 'info') {
        // Remove any existing toasts
        const existingToasts = document.querySelectorAll('.toast-notification');
        existingToasts.forEach(toast => toast.remove());
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.style.position = 'fixed';
        toast.style.bottom = '20px';
        toast.style.right = '20px';
        toast.style.padding = '12px 20px';
        toast.style.borderRadius = '8px';
        toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        toast.style.zIndex = '9999';
        toast.style.minWidth = '250px';
        toast.style.transition = 'all 0.3s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        
        // Set background color based on type
        if (type === 'success') {
            toast.style.backgroundColor = '#d4edda';
            toast.style.color = '#155724';
            toast.style.borderLeft = '4px solid #28a745';
        } else if (type === 'error') {
            toast.style.backgroundColor = '#f8d7da';
            toast.style.color = '#721c24';
            toast.style.borderLeft = '4px solid #dc3545';
        } else if (type === 'warning') {
            toast.style.backgroundColor = '#fff3cd';
            toast.style.color = '#856404';
            toast.style.borderLeft = '4px solid #ffc107';
        } else {
            toast.style.backgroundColor = '#d1ecf1';
            toast.style.color = '#0c5460';
            toast.style.borderLeft = '4px solid #17a2b8';
        }
        
        // Set toast content
        toast.innerHTML = `
            <div style="display: flex; align-items: center;">
                <i class="bi ${type === 'success' ? 'bi-check-circle' : 
                              type === 'error' ? 'bi-exclamation-circle' : 
                              type === 'warning' ? 'bi-exclamation-triangle' : 
                              'bi-info-circle'}" 
                   style="margin-right: 10px; font-size: 1.2rem;"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add close button
        const closeBtn = document.createElement('button');
        closeBtn.style.position = 'absolute';
        closeBtn.style.top = '8px';
        closeBtn.style.right = '8px';
        closeBtn.style.background = 'none';
        closeBtn.style.border = 'none';
        closeBtn.style.color = 'inherit';
        closeBtn.style.fontSize = '1rem';
        closeBtn.style.cursor = 'pointer';
        closeBtn.style.opacity = '0.7';
        closeBtn.innerHTML = '&times;';
        closeBtn.addEventListener('click', () => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            setTimeout(() => toast.remove(), 300);
        });
        toast.appendChild(closeBtn);
        
        // Add to document
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    };
    
    // Check if there are no questions at all
    if (questionCards.length === 0 && questionsList) {
        // Create a message for no questions available
        const noQuestionsMessage = document.createElement('div');
        noQuestionsMessage.className = 'alert alert-info';
        noQuestionsMessage.style.padding = '20px';
        noQuestionsMessage.style.borderRadius = '12px';
        noQuestionsMessage.style.backgroundColor = '#f8f9fa';
        noQuestionsMessage.style.border = '1px solid #e9ecef';
        noQuestionsMessage.style.marginTop = '20px';
        noQuestionsMessage.style.textAlign = 'center';
        noQuestionsMessage.innerHTML = `
            <p style="color: #6c757d; font-size: 1.1rem; margin-bottom: 0;">
                <i class="bi bi-question-circle" style="margin-right: 10px;"></i>
                No questions available. Be the first to ask a question!
            </p>
        `;
        questionsList.appendChild(noQuestionsMessage);
    }
    
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
    
    // Search functionality with AJAX
    if (searchInput) {
        let debounceTimer;
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            
            // Clear previous timer
            clearTimeout(debounceTimer);
            
            // Hide any existing no questions message when searching
            const existingNoQuestionsMsg = questionsList.querySelector('.alert.alert-info:not(.no-search-results)');
            if (existingNoQuestionsMsg && searchTerm !== '') {
                existingNoQuestionsMsg.style.display = 'none';
            } else if (existingNoQuestionsMsg && searchTerm === '') {
                existingNoQuestionsMsg.style.display = 'block';
                
                // Show all question cards when search is cleared
                questionCards.forEach(card => {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                });
                
                // Hide any no results message
                const noResultsMessage = document.querySelector('.no-search-results');
                if (noResultsMessage) {
                    noResultsMessage.style.display = 'none';
                }
                
                return;
            }
            
            // Set a debounce timer to avoid too many requests
            debounceTimer = setTimeout(() => {
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Show loading indicator
                let loadingIndicator = document.querySelector('.search-loading');
                if (!loadingIndicator) {
                    loadingIndicator = document.createElement('div');
                    loadingIndicator.className = 'search-loading';
                    loadingIndicator.style.textAlign = 'center';
                    loadingIndicator.style.padding = '20px';
                    loadingIndicator.innerHTML = '<i class="bi bi-hourglass-split" style="font-size: 1.5rem; color: #6c757d;"></i>';
                    questionsList.appendChild(loadingIndicator);
                } else {
                    loadingIndicator.style.display = 'block';
                }
                
                // Hide all question cards while searching
                questionCards.forEach(card => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                });
                
                // Make API request
                fetch(`/api/questions/search?query=${encodeURIComponent(searchTerm)}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading indicator
                    if (loadingIndicator) {
                        loadingIndicator.style.display = 'none';
                    }
                    
                    // Check if we have results
                    if (data.questions && data.questions.length > 0) {
                        // Clear any no results message
                        const noResultsMessage = document.querySelector('.no-search-results');
                        if (noResultsMessage) {
                            noResultsMessage.style.display = 'none';
                        }
                        
                        // Display the search results
                        displaySearchResults(data.questions);
                    } else {
                        // Show no results message
                        let noResultsMessage = document.querySelector('.no-search-results');
                        
                        if (!noResultsMessage) {
                            noResultsMessage = document.createElement('div');
                            noResultsMessage.className = 'alert alert-info no-search-results';
                            noResultsMessage.style.padding = '20px';
                            noResultsMessage.style.borderRadius = '12px';
                            noResultsMessage.style.backgroundColor = '#f8f9fa';
                            noResultsMessage.style.border = '1px solid #e9ecef';
                            noResultsMessage.style.marginTop = '20px';
                            noResultsMessage.style.textAlign = 'center';
                            noResultsMessage.innerHTML = `
                                <p style="color: #6c757d; font-size: 1.1rem; margin-bottom: 0;">
                                    <i class="bi bi-search" style="margin-right: 10px;"></i>
                                    No questions found matching "${searchTerm}"
                                </p>
                            `;
                            questionsList.appendChild(noResultsMessage);
                        } else {
                            // Update existing message
                            noResultsMessage.innerHTML = `
                                <p style="color: #6c757d; font-size: 1.1rem; margin-bottom: 0;">
                                    <i class="bi bi-search" style="margin-right: 10px;"></i>
                                    No questions found matching "${searchTerm}"
                                </p>
                            `;
                            noResultsMessage.style.display = 'block';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error searching questions:', error);
                    
                    // Hide loading indicator
                    if (loadingIndicator) {
                        loadingIndicator.style.display = 'none';
                    }
                    
                    // Show error message
                    let errorMessage = document.querySelector('.search-error');
                    if (!errorMessage) {
                        errorMessage = document.createElement('div');
                        errorMessage.className = 'alert alert-danger search-error';
                        errorMessage.style.padding = '20px';
                        errorMessage.style.borderRadius = '12px';
                        errorMessage.style.marginTop = '20px';
                        errorMessage.style.textAlign = 'center';
                        errorMessage.innerHTML = `
                            <p style="color: #721c24; font-size: 1.1rem; margin-bottom: 0;">
                                <i class="bi bi-exclamation-triangle" style="margin-right: 10px;"></i>
                                Error searching questions. Please try again.
                            </p>
                        `;
                        questionsList.appendChild(errorMessage);
                    } else {
                        errorMessage.style.display = 'block';
                    }
                });
            }, 500); // 500ms debounce
        });
        
        // Function to display search results
        function displaySearchResults(questions) {
            // Clear the questions list except for messages
            const messages = Array.from(questionsList.querySelectorAll('.alert'));
            questionsList.innerHTML = '';
            
            // Add back any messages
            messages.forEach(message => {
                if (!message.classList.contains('no-search-results') && 
                    !message.classList.contains('search-error')) {
                    questionsList.appendChild(message);
                }
            });
            
            // Create and append question cards
            questions.forEach((question, index) => {
                const card = document.createElement('div');
                card.className = 'question-card';
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                // Format tags HTML
                let tagsHtml = '';
                if (question.tags && question.tags.length > 0) {
                    tagsHtml = '<div class="question-tags">';
                    question.tags.forEach(tag => {
                        tagsHtml += `<span class="tag">${tag}</span>`;
                    });
                    tagsHtml += '</div>';
                }
                
                // Format bookmark button
                let bookmarkHtml = '';
                if (document.querySelector('meta[name="user-authenticated"]')) {
                    bookmarkHtml = `
                        <form method="POST" action="/questions/${question.id}/bookmark" class="d-inline bookmark-form">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <button type="submit" class="action-btn bookmark-btn ${question.is_bookmarked ? 'bookmarked' : ''}">
                                <i class="bi ${question.is_bookmarked ? 'bi-bookmark-fill' : 'bi-bookmark'}"></i>
                                <span>${question.is_bookmarked ? 'Bookmarked' : 'Bookmark'}</span>
                            </button>
                        </form>
                    `;
                }
                
                // Format report button
                let reportHtml = '';
                if (document.querySelector('meta[name="user-authenticated"]')) {
                    reportHtml = `
                        <form method="POST" action="/questions/${question.id}/report" class="d-inline report-form">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <button type="button" class="action-btn report-btn" data-type="question" data-id="${question.id}">
                                <i class="fas fa-flag"></i> Report
                            </button>
                        </form>
                    `;
                }
                
                // Build the card HTML
                card.innerHTML = `
                    <div class="question-meta">
                        <div class="user-info">
                            <a href="/user/${question.user_id}" style="text-decoration: none;">
                                <div class="user-avatar">
                                    ${question.avatar ? 
                                        `<img src="${question.avatar}" alt="${question.user}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">` : 
                                        `<i class="bi bi-person-fill"></i>`
                                    }
                                </div>
                            </a>
                            <a href="/user/${question.user_id}" style="text-decoration: none;">
                                <span class="user-name">${question.user}</span>
                            </a>
                        </div>
                        <span>${question.created_at}</span>
                    </div>
                    <h2 class="question-title">
                        <a href="/question/${question.id}">${question.title}</a>
                    </h2>
                    <div class="question-excerpt">${question.excerpt}</div>
                    ${tagsHtml}
                    <div class="question-actions">
                        ${bookmarkHtml}
                        <div class="stat">
                            <i class="bi bi-chat-left-text"></i>
                            <span>${question.answers} answers</span>
                        </div>
                        ${reportHtml}
                    </div>
                `;
                
                // Add the card to the questions list
                questionsList.appendChild(card);
                
                // Animate the card in
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50 * index);
                
                // Add event listeners for bookmark forms
                const bookmarkForm = card.querySelector('.bookmark-form');
                if (bookmarkForm) {
                    bookmarkForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const url = this.getAttribute('action');
                        const button = this.querySelector('.bookmark-btn');
                        const icon = button.querySelector('i');
                        const text = button.querySelector('span');
                        
                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update button appearance
                                if (data.isBookmarked) {
                                    button.classList.add('bookmarked');
                                    icon.classList.remove('bi-bookmark');
                                    icon.classList.add('bi-bookmark-fill');
                                    text.textContent = 'Bookmarked';
                                } else {
                                    button.classList.remove('bookmarked');
                                    icon.classList.remove('bi-bookmark-fill');
                                    icon.classList.add('bi-bookmark');
                                    text.textContent = 'Bookmark';
                                }
                                
                                // Show success message
                                showToast(data.message, 'success');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    });
                }
                
                // Add event listeners for report buttons
                const reportBtn = card.querySelector('.report-btn');
                if (reportBtn) {
                    reportBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        let id = this.getAttribute('data-id');
                        document.getElementById('reportIdInput').value = id;
                        // Set form action
                        let currentAction = `/questions/${id}/report`;
                        document.getElementById('reportForm').action = currentAction;
                        document.getElementById('reasonSelect').value = '';
                        document.getElementById('detailsInput').value = '';
                        document.getElementById('reportError').style.display = 'none';
                        document.getElementById('reportModal').style.display = 'flex';
                    });
                }
            });
        }
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