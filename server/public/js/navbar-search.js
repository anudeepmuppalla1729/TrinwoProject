document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('navbar-search');
    const searchResults = document.getElementById('search-results');
    let debounceTimer;

    // Function to handle search input
    function handleSearch() {
        const query = searchInput.value.trim();
        
        // Clear previous timer
        clearTimeout(debounceTimer);
        
        // Hide results if query is empty
        if (query === '') {
            searchResults.style.display = 'none';
            return;
        }
        
        // Set a debounce timer to avoid too many requests
        debounceTimer = setTimeout(() => {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Make API request
            fetch(`/api/users/search?query=${encodeURIComponent(query)}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data.users);
            })
            .catch(error => {
                console.error('Error searching users:', error);
            });
        }, 300); // 300ms debounce
    }

    // Function to display search results
    function displaySearchResults(users) {
        // Clear previous results
        searchResults.innerHTML = '';
        
        if (users.length === 0) {
            searchResults.innerHTML = '<div class="no-results">No users found</div>';
            searchResults.style.display = 'block';
            return;
        }
        
        // Create result items
        users.forEach(user => {
            const resultItem = document.createElement('div');
            resultItem.className = 'search-result-item';
            
            // Create avatar element
            const avatar = document.createElement('div');
            avatar.className = 'search-result-avatar';
            
            if (user.avatar_url) {
                const img = document.createElement('img');
                img.src = user.avatar_url;
                img.alt = user.name;
                avatar.appendChild(img);
            } else {
                avatar.innerHTML = '<i class="bi bi-person-circle"></i>';
            }
            
            // Create user info element
            const userInfo = document.createElement('div');
            userInfo.className = 'search-result-info';
            userInfo.innerHTML = `
                <div class="search-result-name">${user.name}</div>
            `;
            
            // Add elements to result item
            resultItem.appendChild(avatar);
            resultItem.appendChild(userInfo);
            
            // Add click event to navigate to user profile
            resultItem.addEventListener('click', () => {
                window.location.href = `/user/${user.user_id}`;
            });
            
            // Add result item to results container
            searchResults.appendChild(resultItem);
        });
        
        // Show results
        searchResults.style.display = 'block';
    }

    // Add event listeners
    searchInput.addEventListener('input', handleSearch);
    
    // Hide results when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    // Show results when input is focused and has value
    searchInput.addEventListener('focus', function() {
        if (searchInput.value.trim() !== '') {
            handleSearch();
        }
    });
});