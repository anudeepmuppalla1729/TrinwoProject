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
        const postButton = askModal.querySelector('.ask-btn');
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
        
        // Function to close modal with transition
        const closeAskModal = () => {
            askModal.style.opacity = '0';
            setTimeout(() => {
                askModal.style.display = 'none';
                askModal.style.opacity = ''; // Reset opacity for next open
            }, 300); // Match transition duration in CSS
        };
        
        if (closeButton) {
            closeButton.addEventListener('click', closeAskModal);
        }
        
        if (cancelButton) {
            cancelButton.addEventListener('click', closeAskModal);
        }
        
        // close on outside click
        askModal.addEventListener('click', (e) => {
            if (e.target === askModal) closeAskModal();
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
                // alert(`Question submitted!\nQuestion: ${question}\nDescription: ${description}\nPrivacy: ${privacy}`);
                
                // Clear the form and close the modal
                questionTextarea.value = '';
                questionDescription.value = '';
                closeAskModal();
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
        const imageButton = insightModal.querySelector('.image-btn');
        const imageInput = document.getElementById('insight-image-input');
        const selectedImageContainer = insightModal.querySelector('.selected-image-container');
        const selectedImagePreview = document.getElementById('selected-image-preview');
        const removeImageButton = insightModal.querySelector('.remove-image-btn');
        
        let selectedImage = null;
        
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
        
        // Function to close insight modal with transition
        const closeInsightModal = () => {
            insightModal.style.opacity = '0';
            setTimeout(() => {
                insightModal.style.display = 'none';
                insightModal.style.opacity = ''; // Reset opacity for next open
            }, 300); // Match transition duration in CSS
        };
        
        if (icloseButton) {
            icloseButton.addEventListener('click', closeInsightModal);
        }
        
        if (icancelButton) {
            icancelButton.addEventListener('click', closeInsightModal);
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
                
                // Create form data for submission
                const formData = new FormData();
                formData.append('heading', heading);
                formData.append('details', insight);
                formData.append('visibility', privacy.toLowerCase());
                
                // Add image if one was selected
                if (selectedImage) {
                    formData.append('image', selectedImage);
                }
                
                // Get the CSRF token from the meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Submit the insight via AJAX
                fetch('/posts/ajax', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        
                        // Clear the form and close the modal
                        insightHeading.value = '';
                        insightTextarea.value = '';
                        selectedImage = null;
                        selectedImageContainer.style.display = 'none';
                        closeInsightModal();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to submit insight'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting your insight');
                });
            });
        }
        
        // Handle image button click
        if (imageButton) {
            imageButton.addEventListener('click', function(e) {
                e.preventDefault();
                imageInput.click();
            });
        }
        
        // Handle image selection
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    selectedImage = this.files[0];
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        selectedImagePreview.src = e.target.result;
                        selectedImageContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        
        // Handle remove image button
        if (removeImageButton) {
            removeImageButton.addEventListener('click', function(e) {
                e.preventDefault();
                selectedImage = null;
                imageInput.value = '';
                selectedImageContainer.style.display = 'none';
            });
        }
        
        insightModal.addEventListener('click', (e) => {
            if (e.target === insightModal) {
                closeInsightModal();
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