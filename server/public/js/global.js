// Global JavaScript for handling modals across all pages
document.addEventListener('DOMContentLoaded', function() {
    // Modal handling for Ask Question
    const askButton = document.querySelector('.ask-btn'); // top navbar ask button
    const sidebarAskButton = document.querySelector('.sidebar-ask-btn'); // sidebar ask button
    const askQuestionButtons = document.querySelectorAll('.ask-question-btn'); // buttons on questions page
    const askModal = document.getElementById('askModal');
    
    if (askModal) {
        const closeButton = askModal.querySelector('.close-btn');
        const cancelButton = askModal.querySelector('.cancel-btn');
        const postButton = askModal.querySelector('.post-btn');
        const questionTextarea = askModal.querySelector('.question-textarea');
        const questionDescription = askModal.querySelector('.question-description');
        const privacySelect = askModal.querySelector('.privacy-select');
        
        // Show modal on top navbar and sidebar ask buttons
        if (askButton) {
            askButton.addEventListener('click', () => (askModal.style.display = 'flex'));
        }
        
        if (sidebarAskButton) {
            sidebarAskButton.addEventListener('click', (e) => {
                e.preventDefault();
                askModal.style.display = 'flex';
            });
        }
        
        // Show modal on ask question buttons (used on questions page)
        if (askQuestionButtons.length > 0) {
            askQuestionButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    askModal.style.display = 'flex';
                });
            });
        }
        
        if (closeButton) {
            closeButton.addEventListener('click', () => (askModal.style.display = 'none'));
        }
        
        if (cancelButton) {
            cancelButton.addEventListener('click', () => (askModal.style.display = 'none'));
        }
        
        // close on outside click
        askModal.addEventListener('click', (e) => {
            if (e.target === askModal) askModal.style.display = 'none';
        });
        
        // Handle form submission
        if (postButton) {
            postButton.addEventListener('click', function() {
                const question = questionTextarea.value.trim();
                const description = questionDescription.value.trim();
                const privacy = privacySelect.value;
                
                if (question === '') {
                    alert('Please enter your question');
                    return;
                }
                
                // Here you would typically send the data to the server
                // For now, we'll just show an alert with the collected data
                alert(`Question submitted!\nQuestion: ${question}\nDescription: ${description}\nPrivacy: ${privacy}`);
                
                // Clear the form and close the modal
                questionTextarea.value = '';
                questionDescription.value = '';
                askModal.style.display = 'none';
            });
        }
    }
    
    // Modal handling for Post Insight
    const insightButton = document.querySelector('.insight-btn');
    const questionInput = document.querySelector('.question-input');
    const insightModal = document.getElementById('insightModal');
    
    if (insightModal) {
        const icloseButton = insightModal.querySelector('.close-btn');
        const icancelButton = insightModal.querySelector('.cancel-btn');
        const ipostButton = insightModal.querySelector('.post-btn');
        const insightHeading = insightModal.querySelector('.insight-heading');
        const insightTextarea = insightModal.querySelector('.i-question-textarea');
        const insightPrivacySelect = insightModal.querySelector('.privacy-select');
        
        // Handle both the dedicated insight button and the question input field
        if (insightButton) {
            insightButton.addEventListener('click', () => {
                insightModal.style.display = 'flex';
            });
        }
        
        if (questionInput) {
            questionInput.addEventListener('click', () => {
                insightModal.style.display = 'flex';
            });
        }
        
        if (icloseButton) {
            icloseButton.addEventListener('click', () => {
                insightModal.style.display = 'none';
            });
        }
        
        if (icancelButton) {
            icancelButton.addEventListener('click', () => {
                insightModal.style.display = 'none';
            });
        }
        
        // Handle form submission
        if (ipostButton) {
            ipostButton.addEventListener('click', function() {
                const heading = insightHeading.value.trim();
                const insight = insightTextarea.value.trim();
                const privacy = insightPrivacySelect.value;
                
                if (insight === '') {
                    alert('Please enter your insight');
                    return;
                }
                
                // Here you would typically send the data to the server
                // For now, we'll just show an alert with the collected data
                alert(`Insight submitted!\nHeading: ${heading}\nInsight: ${insight}\nPrivacy: ${privacy}`);
                
                // Clear the form and close the modal
                insightHeading.value = '';
                insightTextarea.value = '';
                insightModal.style.display = 'none';
            });
        }
        
        insightModal.addEventListener('click', (e) => {
            if (e.target === insightModal) {
                insightModal.style.display = 'none';
            }
        });
    }
    
    // Hamburger menu for mobile sidebar (shared for all pages)
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const dashboardItems = document.querySelector('.dashboard_items');
    
    if (hamburgerBtn && dashboardItems) {
        hamburgerBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            document.body.classList.toggle('sidebar-open');
        });
        
        // Close sidebar when clicking outside (on overlay)
        document.addEventListener('click', function(e) {
            if (
                document.body.classList.contains('sidebar-open') &&
                !dashboardItems.contains(e.target) &&
                e.target !== hamburgerBtn &&
                !hamburgerBtn.contains(e.target)
            ) {
                document.body.classList.remove('sidebar-open');
            }
        });
    }
});