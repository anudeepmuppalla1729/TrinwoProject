// Global JavaScript for handling modals across all pages
document.addEventListener('DOMContentLoaded', function () {
    // Global toast notification function
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
    // Modal handling for Ask Question
    const askButton = document.querySelector('.aks'); // top navbar ask button
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
            askButton.addEventListener('click', () => {
                showTinyMcePopups();
                askModal.style.display = 'flex';
                document.body.style.overflow = 'hidden'; // Prevent body scroll
            });
        }

        if (sidebarAskButton) {
            sidebarAskButton.addEventListener('click', (e) => {
                e.preventDefault();
                showTinyMcePopups();
                askModal.style.display = 'flex';
                document.body.style.overflow = 'hidden'; // Prevent body scroll
            });
        }

        // Show modal on ask question buttons (used on questions page)
        if (askQuestionButtons.length > 0) {
            askQuestionButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    showTinyMcePopups();
                    askModal.style.display = 'flex';
                });
            });
        }

        // Function to close modal with transition
        const closeAskModal = () => {
            hideTinyMcePopups();
            askModal.style.opacity = '0';
            setTimeout(() => {
                askModal.style.display = 'none';
                askModal.style.opacity = ''; // Reset opacity for next open
                document.body.style.overflow = ''; // Restore body scroll
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

        // Handle modal scrolling - prevent body scroll when modal is open
        const modalBox = askModal.querySelector('.modal-box');
        if (modalBox) {
            // The modal box now has overflow-y: auto in CSS for scrolling
            // Body scroll prevention is handled in the click event listeners above
        }

        // Handle login button click in the login required message
        const loginBtn = askModal.querySelector('.login-btn');
        if (loginBtn) {
            loginBtn.addEventListener('click', () => {
                closeAskModal();
            });
        }

        // Tags functionality for Ask Question modal
        const tagsInput = askModal.querySelector('.tags-input');
        const tagsSuggestions = askModal.querySelector('.tags-suggestions');
        const selectedTags = askModal.querySelector('.selected-tags');
        let tags = [];

        // Common tags suggestions
        const commonTags = [
            'technology', 'education', 'science', 'health', 'business',
            'art', 'history', 'sports', 'food', 'travel', 'music', 'movies',
            'programming', 'design', 'finance', 'politics', 'environment'
        ];

        // Function to render selected tags
        const renderTags = () => {
            selectedTags.innerHTML = '';
            tags.forEach(tag => {
                const tagElement = document.createElement('div');
                tagElement.classList.add('tag');
                tagElement.innerHTML = `
                    ${tag}
                    <span class="remove-tag">×</span>
                `;

                // Add event listener to remove tag
                tagElement.querySelector('.remove-tag').addEventListener('click', () => {
                    tags = tags.filter(t => t !== tag);
                    renderTags();
                });

                selectedTags.appendChild(tagElement);
            });
        };

        // Show suggestions when input is focused
        if (tagsInput) {
            tagsInput.addEventListener('click', () => {
                // Filter suggestions based on input
                const inputValue = tagsInput.value.toLowerCase();
                const filteredTags = commonTags.filter(tag =>
                    tag.toLowerCase().includes(inputValue) && !tags.includes(tag)
                );

                // Render suggestions
                if (filteredTags.length > 0) {
                    tagsSuggestions.innerHTML = '';
                    filteredTags.forEach(tag => {
                        const suggestion = document.createElement('div');
                        suggestion.textContent = tag;
                        suggestion.addEventListener('click', () => {
                            if (!tags.includes(tag)) {
                                tags.push(tag);
                                renderTags();
                                tagsInput.value = '';
                                tagsSuggestions.style.display = 'none';
                            }
                        });
                        tagsSuggestions.appendChild(suggestion);
                    });
                    tagsSuggestions.style.display = 'block';
                } else {
                    tagsSuggestions.style.display = 'none';
                }
            });

            // Update suggestions as user types
            tagsInput.addEventListener('input', () => {
                const inputValue = tagsInput.value.toLowerCase();
                const filteredTags = commonTags.filter(tag =>
                    tag.toLowerCase().includes(inputValue) && !tags.includes(tag)
                );

                if (filteredTags.length > 0 && inputValue.length > 0) {
                    tagsSuggestions.innerHTML = '';
                    filteredTags.forEach(tag => {
                        const suggestion = document.createElement('div');
                        suggestion.textContent = tag;
                        suggestion.addEventListener('click', () => {
                            if (!tags.includes(tag)) {
                                tags.push(tag);
                                renderTags();
                                tagsInput.value = '';
                                tagsSuggestions.style.display = 'none';
                            }
                        });
                        tagsSuggestions.appendChild(suggestion);
                    });
                    tagsSuggestions.style.display = 'block';
                } else {
                    tagsSuggestions.style.display = 'none';
                }
            });

            // Add tag when Enter is pressed
            tagsInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const inputValue = tagsInput.value.trim();
                    if (inputValue && !tags.includes(inputValue)) {
                        tags.push(inputValue);
                        renderTags();
                        tagsInput.value = '';
                        tagsSuggestions.style.display = 'none';
                    }
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', (e) => {
                if (!tagsInput.contains(e.target) && !tagsSuggestions.contains(e.target)) {
                    tagsSuggestions.style.display = 'none';
                }
            });
        }

        // Handle form submission
        if (postButton) {
            postButton.addEventListener('click', function () {
                const question = questionTextarea.value.trim();
                // Get description from TinyMCE editor if available, otherwise from textarea
                let description = '';
                if (tinymce.get('.question-description')) {
                    description = tinymce.get('.question-description').getContent();
                } else {
                    description = questionDescription.value.trim();
                }
                const privacy = privacySelect.value;
                const tagsHiddenField = document.getElementById('tags-hidden');

                if (question === '') {
                    showToast('Please enter your question', 'error');
                    return;
                }

                // Update hidden tags field with current tags
                tagsHiddenField.value = tags.join(',');

                // Show loading indicator
                postButton.textContent = 'Posting...';
                postButton.disabled = true;

                // Get the form
                const form = document.getElementById('askQuestionForm');

                // Create form data from the form
                const formData = new FormData(form);
                // Update the description in form data with TinyMCE content
                formData.set('description', description);

                // Clear any existing error messages
                document.querySelectorAll('.error-message').forEach(el => el.remove());

                // Send data to server using fetch API
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            showToast('Question posted successfully!', 'success');

                            // Clear the form and close the modal
                            questionTextarea.value = '';
                            // Clear TinyMCE editor if available
                            if (tinymce.get('.question-description')) {
                                tinymce.get('.question-description').setContent('');
                            } else {
                                questionDescription.value = '';
                            }
                            tags = [];
                            renderTags();
                            closeAskModal();
                        } else {
                            // Handle validation errors
                            if (data.errors) {
                                // Display validation errors
                                Object.keys(data.errors).forEach(field => {
                                    const message = data.errors[field][0];
                                    const input = form.querySelector(`[name="${field}"]`);
                                    if (input) {
                                        const errorDiv = document.createElement('div');
                                        errorDiv.className = 'error-message';
                                        errorDiv.textContent = message;
                                        input.parentNode.insertBefore(errorDiv, input.nextSibling);
                                    }
                                });
                            } else {
                                // Show general error message
                                showToast('Error posting question: ' + (data.message || 'Unknown error'), 'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error posting question:', error);
                        showToast('Error posting question. Please try again.', 'error');
                    })
                    .finally(() => {
                        // Reset button state
                        postButton.textContent = 'Ask';
                        postButton.disabled = false;
                    });
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
                showTinyMcePopups();
                insightModal.style.display = 'flex';
                document.body.style.overflow = 'hidden'; // Prevent body scroll
            });
        }

        if (questionInput) {
            questionInput.addEventListener('click', () => {
                showTinyMcePopups();
                insightModal.style.display = 'flex';
                document.body.style.overflow = 'hidden'; // Prevent body scroll
            });
        }

        // Function to close insight modal with transition
        const closeInsightModal = () => {
            hideTinyMcePopups();
            insightModal.style.opacity = '0';
            setTimeout(() => {
                insightModal.style.display = 'none';
                insightModal.style.opacity = ''; // Reset opacity for next open
                document.body.style.overflow = ''; // Restore body scroll
            }, 300); // Match transition duration in CSS
        };

        if (icloseButton) {
            icloseButton.addEventListener('click', closeInsightModal);
        }

        // Handle login button click in the login required message
        const loginBtn = insightModal.querySelector('.login-btn');
        if (loginBtn) {
            loginBtn.addEventListener('click', () => {
                closeInsightModal();
            });
        }

        if (icancelButton) {
            icancelButton.addEventListener('click', closeInsightModal);
        }

        // Handle modal scrolling for insight modal - prevent body scroll when modal is open
        const insightModalBox = insightModal.querySelector('.modal-box');
        if (insightModalBox) {
            // The modal box now has overflow-y: auto in CSS for scrolling
            // Body scroll prevention is handled in the click event listeners above
        }

        // Handle form submission
        const createPostForm = document.getElementById('createPostForm');
        if (createPostForm) {
            createPostForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent default form submission

                const title = insightHeading.value.trim();
                // Get content from TinyMCE editor if available, otherwise from textarea
                let content = '';
                if (tinymce.get('.i-question-textarea')) {
                    content = tinymce.get('.i-question-textarea').getContent();
                } else {
                    content = insightTextarea.value.trim();
                }
                const privacy = insightPrivacySelect.value;

                if (content === '' || title === '') {
                    showToast('Please enter both title and content', 'error');
                    return;
                }

                const formData = new FormData(createPostForm);
                // Update the content in form data with TinyMCE content
                formData.set('content', content);
                if (selectedImage) {
                    formData.set('cover_image', selectedImage);
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                            showToast(data.message, 'success');
                            insightHeading.value = '';
                            // Clear TinyMCE editor if available
                            if (tinymce.get('.i-question-textarea')) {
                                tinymce.get('.i-question-textarea').setContent('');
                            } else {
                                insightTextarea.value = '';
                            }
                            selectedImage = null;
                            selectedImageContainer.style.display = 'none';
                            closeInsightModal();
                            setTimeout(() => { location.reload(); }, 1200);
                        } else {
                            showToast('Error: ' + (data.message || 'Failed to submit post'), 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred while submitting your post', 'error');
                    });
            });
        }

        // Handle image button click
        if (imageButton) {
            imageButton.addEventListener('click', function (e) {
                e.preventDefault();
                imageInput.click();
            });
        }

        // Handle image selection
        if (imageInput) {
            imageInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileType = file.type;

                    // Validate file type
                    if (fileType !== 'image/png' && fileType !== 'image/jpeg' && fileType !== 'image/jpg') {
                        showToast('Please select only PNG, JPEG, or JPG image files', 'error');
                        this.value = '';
                        return;
                    }

                    selectedImage = file;

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        selectedImagePreview.src = e.target.result;
                        selectedImageContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Handle remove image button
        if (removeImageButton) {
            removeImageButton.addEventListener('click', function (e) {
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
        hamburgerBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            document.body.classList.toggle('sidebar-open');
        });

        // Close sidebar when clicking outside (on overlay)
        document.addEventListener('click', function (e) {
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

function hideTinyMcePopups() {
    // Hide all TinyMCE popups/toolbars, but NOT the editor itself
    document.querySelectorAll('.tox-silver-sink, .tox-tinymce-aux, .tox-menu, .tox-toolbar__overflow').forEach(el => {
        el.style.display = 'none';
    });
}

function showTinyMcePopups() {
    // Restore display property for all TinyMCE popups/toolbars
    document.querySelectorAll('.tox-silver-sink, .tox-tinymce-aux, .tox-menu, .tox-toolbar__overflow').forEach(el => {
        el.style.display = '';
    });
}
