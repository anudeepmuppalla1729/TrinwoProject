/**
 * Global Loading Functionality
 * This script adds a loading modal and loading bar for all database interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Create loading modal element
    const loadingModal = document.createElement('div');
    loadingModal.className = 'loading-modal';
    loadingModal.innerHTML = `
        <div class="loading-modal-content">
            <div class="loading-spinner"></div>
            <p id="loading-message">Processing your request...</p>
        </div>
    `;
    document.body.appendChild(loadingModal);

    // Create loading bar container and bar
    const loadingBarContainer = document.createElement('div');
    loadingBarContainer.className = 'loading-bar-container';
    loadingBarContainer.innerHTML = '<div class="loading-bar"></div>';
    document.body.appendChild(loadingBarContainer);

    const loadingBar = loadingBarContainer.querySelector('.loading-bar');

    // Global loading state
    window.globalLoading = {
        isLoading: false,
        activeRequests: 0,
        
        // Show loading modal with custom message
        showModal: function(message = 'Processing your request...') {
            document.getElementById('loading-message').textContent = message;
            loadingModal.classList.add('active');
            this.isLoading = true;
        },
        
        // Hide loading modal
        hideModal: function() {
            loadingModal.classList.remove('active');
            this.isLoading = false;
        },
        
        // Start loading bar animation
        startLoadingBar: function() {
            this.activeRequests++;
            loadingBar.style.width = '15%';
            
            // Animate to 85% (reserving the last 15% for completion)
            setTimeout(() => {
                if (this.activeRequests > 0) {
                    loadingBar.style.width = '85%';
                }
            }, 50);
        },
        
        // Complete loading bar animation
        completeLoadingBar: function() {
            this.activeRequests--;
            
            if (this.activeRequests <= 0) {
                this.activeRequests = 0;
                loadingBar.style.width = '100%';
                
                // Reset after completion animation
                setTimeout(() => {
                    loadingBar.style.transition = 'none';
                    loadingBar.style.width = '0%';
                    setTimeout(() => {
                        loadingBar.style.transition = 'width 0.3s ease';
                    }, 50);
                }, 300);
            }
        }
    };

    // Intercept all fetch requests
    const originalFetch = window.fetch;
    window.fetch = function() {
        const fetchArgs = arguments;
        const url = arguments[0];
        
        // Start loading indicators
        window.globalLoading.startLoadingBar();
        
        // For certain API endpoints that might take longer, show the modal too
        const shouldShowModal = (
            typeof url === 'string' && (
                url.includes('/api/') ||
                url.includes('/admin/api/') ||
                (fetchArgs[1] && fetchArgs[1].method && fetchArgs[1].method !== 'GET')
            )
        );
        
        if (shouldShowModal) {
            window.globalLoading.showModal();
        }
        
        // Call the original fetch
        return originalFetch.apply(this, fetchArgs)
            .then(response => {
                // Complete loading indicators on response
                window.globalLoading.completeLoadingBar();
                if (shouldShowModal) {
                    window.globalLoading.hideModal();
                }
                return response;
            })
            .catch(error => {
                // Complete loading indicators on error
                window.globalLoading.completeLoadingBar();
                if (shouldShowModal) {
                    window.globalLoading.hideModal();
                }
                throw error;
            });
    };

    // Also intercept XMLHttpRequest for legacy code
    const originalXhrOpen = XMLHttpRequest.prototype.open;
    const originalXhrSend = XMLHttpRequest.prototype.send;

    XMLHttpRequest.prototype.open = function() {
        this._url = arguments[1]; // Store URL for later use
        return originalXhrOpen.apply(this, arguments);
    };

    XMLHttpRequest.prototype.send = function() {
        const xhr = this;
        const url = this._url;
        
        // Start loading indicators
        window.globalLoading.startLoadingBar();
        
        // For certain API endpoints that might take longer, show the modal too
        const shouldShowModal = (
            typeof url === 'string' && (
                url.includes('/api/') ||
                url.includes('/admin/api/') ||
                xhr._method === 'POST' || xhr._method === 'PUT' || xhr._method === 'DELETE'
            )
        );
        
        if (shouldShowModal) {
            window.globalLoading.showModal();
        }
        
        // Store original onreadystatechange
        const originalOnReadyStateChange = xhr.onreadystatechange;
        
        // Override onreadystatechange
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) { // 4 = DONE
                // Complete loading indicators
                window.globalLoading.completeLoadingBar();
                if (shouldShowModal) {
                    window.globalLoading.hideModal();
                }
            }
            
            // Call original handler
            if (originalOnReadyStateChange) {
                originalOnReadyStateChange.apply(this, arguments);
            }
        };
        
        return originalXhrSend.apply(this, arguments);
    };
});