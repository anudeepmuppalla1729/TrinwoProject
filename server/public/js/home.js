// Home page specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
  // Any home-specific functionality can go here
  // The modal and hamburger menu functionality has been moved to global.js
});


// Fetch posts from API
let posts = [];

// Function to fetch posts from the server
async function fetchPosts() {
  try {
    const response = await fetch('/api/dashboard/posts');
    if (!response.ok) {
      throw new Error('Failed to fetch posts');
    }
    posts = await response.json();
    console.log(posts);
    renderPosts();
  } catch (error) {
    console.error('Error fetching posts:', error);
    // Show error message to user
    const postsContainer = document.getElementById('postsContainer');
    if (postsContainer) {
      postsContainer.innerHTML = `
        <div class="alert alert-danger">
          <p>Failed to load posts. Please try again later.</p>
        </div>
      `;
    }
  }
}

// Render posts
const postsContainer = document.getElementById('postsContainer');

function renderPosts() {
  // Check if postsContainer exists
  if (!postsContainer) {
    console.error('Posts container element not found');
    return;
  }
  
  postsContainer.innerHTML = '';
  
  if (posts.length === 0) {
    postsContainer.innerHTML = `
      <div class="alert alert-info">
        <p>No posts available. Be the first to share an insight!</p>
      </div>
    `;
    return;
  }
  
  // Use the new blog-feed-section wrapper
  let html = '<div class="blog-feed-section">';
  const currentUserId = window.currentUserId;
  posts.forEach((post, index) => {
    const isBookmarked = post.isBookmarked;
    const bookmarkIcon = isBookmarked ? '<i class="fas fa-bookmark"></i>' : '<i class="far fa-bookmark"></i>';
    const bookmarkBtnClass = isBookmarked ? 'bookmarked' : '';
    const coverImageHtml = post.cover_image
      ? `<div class="blog-post-cover-wrapper"><img src="${post.cover_image}" alt="Cover Image" class="blog-post-cover"></div>`
      : '';
    const excerpt = post.content ? post.content.replace(/<[^>]+>/g, '').substring(0, 180) + (post.content.length > 180 ? '...' : '') : '';
    const minToRead = post.content ? Math.ceil(post.content.replace(/<[^>]+>/g, '').split(/\s+/).length / 200) : 1;
    const showFollow = currentUserId && String(post.user_id) !== String(currentUserId);
    let followBtnHtml = '';
    if (showFollow) {
      if (post.isFollowing) {
        followBtnHtml = `<button class="follow-btn following" data-user-id="${post.user_id}">Following</button>`;
      } else {
        followBtnHtml = `<button class="follow-btn" data-user-id="${post.user_id}">Follow</button>`;
      }
    }
    // Avatar rendering logic
    let authorAvatarHtml = '';
    if (post.avatar) {
      authorAvatarHtml = `<img src="${post.avatar}" class="author-avatar" alt="${post.profileName}">`;
    } else {
      // Compute initials from profileName
      const initials = post.profileName.split(' ').map(w => w[0]?.toUpperCase() || '').join('');
      authorAvatarHtml = `<div class="author-avatar" style="display:flex;align-items:center;justify-content:center;background:#e0e0e0;color:#2a3c62;font-weight:700;font-size:1.1rem;">${initials}</div>`;
    }
    const authorHtml = `
      <div class="author-row">
        ${authorAvatarHtml}
        <a href="/user/${post.user_id}" class="author-name">${post.profileName}</a>
        ${followBtnHtml}
      </div>
    `;
    html += `
      <div class="blog-post-card" data-post-id="${post.post_id}">
        ${coverImageHtml}
        <div class="blog-post-content">
          ${authorHtml}
          <a href="/posts/${post.post_id}" class="blog-post-link" style="text-decoration:none;display:block;">
            <div>
              <div class="blog-post-title">${post.title || ''}</div>
              <div class="blog-post-excerpt">${excerpt}</div>
            </div>
          </a>
          <div class="blog-post-meta">
            <span><i class="fas fa-eye"></i> ${post.views || 0} views</span>
            <span><i class="fas fa-clock"></i> ${minToRead} min</span>
            <button class="bookmark-btn ${bookmarkBtnClass}" data-post-id="${post.post_id}" title="Bookmark" style="background:none;border:none;outline:none;cursor:pointer;">${bookmarkIcon}</button>
            <button class="report-btn ml-2" data-post-id="${post.post_id}" title="Report this post" style="background:none;border:none;outline:none;cursor:pointer;"><i class="fas fa-flag"></i></button>
          </div>
        </div>
      </div>
    `;
  });
  html += '</div>';
  postsContainer.innerHTML = html;
  attachPostEvents();
}

// Add styles for action buttons
const actionButtonStyles = document.createElement('style');
actionButtonStyles.textContent = `
  .action-btn:hover {
    background-color: #f0f0f0;
    transform: translateY(-2px);
  }
  .upvote-btn:hover {
    color: rgb(49, 60, 95);
  }
  .comment-count-btn:hover, .comment-btn:hover {
    color: #0d6efd;
  }
  .downvote-btn:hover {
    color: #dc3545;
  }
  .bookmark-btn:hover {
    color: rgb(49, 60, 95);
  }
`;
document.head.appendChild(actionButtonStyles);

// Add CSS for .bookmarked
const blogCardBookmarkStyle = document.createElement('style');
blogCardBookmarkStyle.textContent = `.bookmark-btn.bookmarked { color: rgb(42, 60, 98) !important; }`;
document.head.appendChild(blogCardBookmarkStyle);

// Fetch posts when page loads
fetchPosts();

// Attach events for options menus, close buttons, and comment functionality
function attachPostEvents() {
  // Options menu toggle
  document.querySelectorAll('.options').forEach((opt) => {
    opt.addEventListener('click', (e) => {
      e.stopPropagation();
      closeAllMenus();
      opt.querySelector('.options-menu').classList.toggle('active');
    });
  });
  
  // Upvote button
  document.querySelectorAll('.upvote-btn').forEach((btn) => {
    btn.addEventListener('click', function() {
      const postElement = this.closest('.post');
      const postId = postElement.dataset.id.replace('post-', '');
      const countSpan = this.querySelector('span');
      
      // Get CSRF token
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      
      // Send AJAX request to upvote the post
      fetch(`/posts/${postId}/upvote`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update the upvote count with the value from the server
          countSpan.textContent = data.upvotes;
          
          // Check the userVote status from the response
          if (data.userVote === 'upvote') {
            // User upvoted
            this.style.color = 'rgb(49, 60, 95)';
            this.querySelector('i').classList.remove('bi-hand-thumbs-up');
            this.querySelector('i').classList.add('bi-hand-thumbs-up-fill');
            
            // Reset downvote if it was active
            const downvoteBtn = postElement.querySelector('.downvote-btn');
            downvoteBtn.style.color = '#555';
            downvoteBtn.querySelector('i').classList.remove('bi-hand-thumbs-down-fill');
            downvoteBtn.querySelector('i').classList.add('bi-hand-thumbs-down');
          } else if (data.userVote === null) {
            // Vote was removed
            this.style.color = '#555';
            this.querySelector('i').classList.remove('bi-hand-thumbs-up-fill');
            this.querySelector('i').classList.add('bi-hand-thumbs-up');
          }
        } else {
          console.error('Failed to upvote post:', data.message);
        }
      })
      .catch(error => {
        console.error('Error upvoting post:', error);
      });
    });
  });
  
  // Downvote button
  document.querySelectorAll('.downvote-btn').forEach((btn) => {
    btn.addEventListener('click', function() {
      const postElement = this.closest('.post');
      const postId = postElement.dataset.id.replace('post-', '');
      const countSpan = this.querySelector('span');
      
      // Get CSRF token
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      
      // Send AJAX request to downvote the post
      fetch(`/posts/${postId}/downvote`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update the downvote count with the value from the server
          countSpan.textContent = data.downvotes;
          
          // Check the userVote status from the response
          if (data.userVote === 'downvote') {
            // User downvoted
            this.style.color = '#dc3545';
            this.querySelector('i').classList.remove('bi-hand-thumbs-down');
            this.querySelector('i').classList.add('bi-hand-thumbs-down-fill');
            
            // Reset upvote if it was active
            const upvoteBtn = postElement.querySelector('.upvote-btn');
            upvoteBtn.style.color = '#555';
            upvoteBtn.querySelector('i').classList.remove('bi-hand-thumbs-up-fill');
            upvoteBtn.querySelector('i').classList.add('bi-hand-thumbs-up');
          } else if (data.userVote === null) {
            // Vote was removed
            this.style.color = '#555';
            this.querySelector('i').classList.remove('bi-hand-thumbs-down-fill');
            this.querySelector('i').classList.add('bi-hand-thumbs-down');
          }
        } else {
          console.error('Failed to downvote post:', data.message);
        }
      })
      .catch(error => {
        console.error('Error downvoting post:', error);
      });
    });
  });
  
  // Bookmark button AJAX
  document.querySelectorAll('.bookmark-btn').forEach((btn) => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const postId = this.getAttribute('data-post-id');
      const isBookmarked = this.classList.contains('bookmarked');
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch(`/posts/${postId}/bookmark`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          if (data.isBookmarked) {
            this.classList.add('bookmarked');
            this.innerHTML = '<i class="fas fa-bookmark"></i>';
            this.style.color = 'rgb(42, 60, 98)';
          } else {
            this.classList.remove('bookmarked');
            this.innerHTML = '<i class="far fa-bookmark"></i>';
            this.style.color = '';
          }
        }
      });
    });
  });
  
  // Follow button AJAX
  document.querySelectorAll('.follow-btn').forEach((btn) => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const userId = this.getAttribute('data-user-id');
      const isFollowing = this.classList.contains('following');
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const url = isFollowing ? `/user/${userId}/unfollow` : `/user/${userId}/follow`;
      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({})
      })
      .then(async response => {
        let data;
        try {
          data = await response.clone().json();
        } catch (err) {
          const text = await response.text();
          showToast('Follow failed: ' + (text || 'Unknown error'), 'error');
          return;
        }
        if (data.success) {
          if (!isFollowing) {
            this.textContent = 'Following';
            this.classList.add('following');
            showToast(data.message || 'Now following', 'success');
          } else {
            this.textContent = 'Follow';
            this.classList.remove('following');
            showToast(data.message || 'Unfollowed', 'success');
          }
        } else {
          showToast(data.message || 'Follow failed', 'error');
        }
      })
      .catch(error => {
        showToast('Follow failed: ' + error, 'error');
      });
    });
  });

  // Close post button
  document.querySelectorAll('.close-post').forEach((btn) => {
    btn.addEventListener('click', () => btn.closest('.post').remove());
  });

  // Comment count button - toggle comments visibility
  document.querySelectorAll('.comment-count-btn').forEach((btn) => {
    btn.addEventListener('click', function() {
      const postElement = this.closest('.post');
      const commentsContainer = postElement.querySelector('.comments-container');
      
      // Toggle comments visibility
      if (commentsContainer.style.display === 'none' || commentsContainer.style.display === '') {
        commentsContainer.style.display = 'block';
      } else {
        commentsContainer.style.display = 'none';
      }
    });
  });

  // Delete comment buttons
  document.querySelectorAll('.delete-comment-btn').forEach((btn) => {
    btn.addEventListener('click', function() {
      const commentId = this.dataset.commentId;
      const commentElement = this.closest('.comment-item');
      const postElement = this.closest('.post');
      
      if (confirm('Are you sure you want to delete this comment?')) {
        deleteComment(commentId, commentElement, postElement);
      }
    });
  });

  // Comment buttons
  document.querySelectorAll('.comment-btn').forEach((btn) => {
    btn.addEventListener('click', function() {
      const postElement = this.closest('.post');
      const postId = postElement.dataset.id.replace('post-', '');
      const commentsContainer = postElement.querySelector('.comments-container');
      
      // Show comments container when comment button is clicked
      commentsContainer.style.display = 'block';
      
      // Check if comment form already exists
      if (postElement.querySelector('.comment-form')) {
        return;
      }
      
      // Create comment form
      const commentForm = document.createElement('div');
      commentForm.className = 'comment-form';
      commentForm.innerHTML = `
        <textarea placeholder="Write your comment here..." rows="3" style="width: 100%; padding: 8px; margin-top: 10px; border-radius: 4px; border: 1px solid #ccc;"></textarea>
        <button class="submit-comment" style="background-color: rgb(49, 60, 95); color: white; border: none; padding: 5px 10px; border-radius: 4px; margin-top: 5px; cursor: pointer;">Submit Comment</button>
      `;
      
      // Insert form after post actions
      postElement.querySelector('.post-actions').insertAdjacentElement('afterend', commentForm);
      
      // Add event listener to submit button
      commentForm.querySelector('.submit-comment').addEventListener('click', function() {
        const commentText = commentForm.querySelector('textarea').value.trim();
        if (!commentText) {
          showToast('Please enter a comment', 'error');
          return;
        }
        
        submitComment(postId, commentText, postElement, commentForm);
      });
    });
  });

  // Report button logic
  document.querySelectorAll('.report-btn').forEach((btn) => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const postId = btn.getAttribute('data-post-id');
      // Open modal
      const reportModal = document.getElementById('reportModal');
      const reportForm = document.getElementById('reportForm');
      const reportIdInput = document.getElementById('reportIdInput');
      const reasonSelect = document.getElementById('reasonSelect');
      const detailsInput = document.getElementById('detailsInput');
      const reportError = document.getElementById('reportError');
      reportIdInput.value = postId;
      reportForm.action = `/posts/${postId}/report`;
      reasonSelect.value = '';
      detailsInput.value = '';
      reportError.style.display = 'none';
      reportModal.style.display = 'flex';
    });
  });
  // Modal close logic
  const reportModal = document.getElementById('reportModal');
  if (reportModal) {
    const closeModalBtn = reportModal.querySelector('.close-modal');
    if (closeModalBtn) {
      closeModalBtn.addEventListener('click', function() {
        reportModal.style.display = 'none';
      });
    }
    // Close on outside click
    reportModal.addEventListener('click', function(e) {
      if (e.target === reportModal) reportModal.style.display = 'none';
    });
    // Handle form submit
    const reportForm = document.getElementById('reportForm');
    if (reportForm) {
      reportForm.addEventListener('submit', function(e) {
        const reasonSelect = document.getElementById('reasonSelect');
        const detailsInput = document.getElementById('detailsInput');
        const reportError = document.getElementById('reportError');
        if (!reasonSelect.value) {
          e.preventDefault();
          reportError.textContent = 'Please select a reason.';
          reportError.style.display = 'block';
          return false;
        }
        e.preventDefault();
        // Submit via AJAX
        const postId = document.getElementById('reportIdInput').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/posts/${postId}/report`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          credentials: 'same-origin', // Ensure cookies/session are sent
          body: JSON.stringify({
            reason: reasonSelect.value,
            details: detailsInput.value
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            reportModal.style.display = 'none';
            
            // Create and show success notification
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success';
            alertDiv.textContent = 'Report submitted successfully!';
            alertDiv.style.padding = '10px 15px';
            alertDiv.style.marginBottom = '15px';
            alertDiv.style.borderRadius = '5px';
            alertDiv.style.backgroundColor = '#d4edda';
            alertDiv.style.color = '#155724';
            alertDiv.style.border = '1px solid #c3e6cb';
            alertDiv.style.position = 'fixed';
            alertDiv.style.top = '20px';
            alertDiv.style.left = '50%';
            alertDiv.style.transform = 'translateX(-50%)';
            alertDiv.style.zIndex = '9999';
            alertDiv.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
            
            document.body.appendChild(alertDiv);
            
            // Remove alert after 2 seconds
            setTimeout(() => {
              alertDiv.remove();
            }, 2000);
          } else {
            reportError.textContent = data.message || 'Failed to submit report.';
            reportError.style.display = 'block';
          }
        })
        .catch(() => {
          reportError.textContent = 'Failed to submit report.';
          reportError.style.display = 'block';
        });
      });
    }
  }

  document.addEventListener('click', closeAllMenus);
}

// Function to submit a comment via AJAX
async function submitComment(postId, commentText, postElement, commentForm) {
  try {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const response = await fetch(`/posts/${postId}/comments`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({
        comment_text: commentText
      })
    });
    
    if (!response.ok) {
      throw new Error('Failed to submit comment');
    }
    
    const data = await response.json();
    
    if (data.success) {
      // Get the comments container
      const commentsContainer = postElement.querySelector('.comments-container');
      
      // Make sure the comments container is visible
      commentsContainer.style.display = 'block';
      
      // Remove the "No comments yet" message if it exists
      const noCommentsMsg = commentsContainer.querySelector('p[style*="text-align: center"]');
      if (noCommentsMsg) {
        noCommentsMsg.remove();
      }
      
      // Create the new comment element with styling
      const newComment = document.createElement('div');
      newComment.className = 'comment-item';
      newComment.dataset.commentId = data.comment.id;
      newComment.style.cssText = 'padding: 10px; margin-bottom: 10px; border: 1px solid #e0e0e0; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);';
      
      newComment.innerHTML = `
        <div class="comment-header" style="display: flex; align-items: center; margin-bottom: 5px; justify-content: space-between;">
          <div style="display: flex; align-items: center;">
            ${data.comment.avatar && data.comment.avatar.length > 0
              ? `<img src="${data.comment.avatar}" alt="Profile" style="width:1.5rem;height:1.5rem;border-radius:50%;object-fit:cover;margin-right:10px;">`
              : `<i class="bi bi-person-circle" style="font-size: 1.5rem; margin-right: 10px;"></i>`}
            <div>
              <strong style="color: rgb(49, 60, 95);">${data.comment.user}</strong>
              <small style="display: block; color: #777; font-size: 0.8rem;">Posted on ${data.comment.created_at}</small>
            </div>
          </div>
          <button class="delete-comment-btn" data-comment-id="${data.comment.id}" style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 0.9rem;"><i class="bi bi-trash"></i></button>
        </div>
        <div class="comment-text" style="max-height: 100px; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; word-wrap: break-word;">${data.comment.text}</div>
      `;
      
      // Add the new comment to the comments container
      commentsContainer.appendChild(newComment);
      
      // Update comment count
      const commentCountBtn = postElement.querySelector('.comment-count-btn');
      const currentCount = parseInt(commentCountBtn.querySelector('span').textContent || 0);
      commentCountBtn.querySelector('span').textContent = currentCount + 1;
      
      // Remove the comment form
      commentForm.remove();
    } else {
      showToast(data.message || 'Failed to add comment', 'error');
    }
  } catch (error) {
    console.error('Error submitting comment:', error);
    showToast('Failed to submit comment. Please try again.', 'error');
  }
}

function closeAllMenus() {
  document
    .querySelectorAll('.options-menu')
    .forEach((menu) => menu.classList.remove('active'));
}

// Function to delete a comment via AJAX
async function deleteComment(commentId, commentElement, postElement) {
  try {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const response = await fetch(`/comments/${commentId}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      }
    });
    
    if (!response.ok) {
      throw new Error('Failed to delete comment');
    }
    
    const data = await response.json();
    
    if (data.success) {
      // Remove the comment element from the DOM
      commentElement.remove();
      
      // Update comment count
      const commentCountBtn = postElement.querySelector('.comment-count-btn');
      const currentCount = parseInt(commentCountBtn.querySelector('span').textContent || 0);
      commentCountBtn.querySelector('span').textContent = Math.max(0, currentCount - 1);
      
      // If no comments left, add the "No comments yet" message
      const commentsContainer = postElement.querySelector('.comments-container');
      if (commentsContainer.querySelectorAll('.comment-item').length === 0) {
        commentsContainer.innerHTML = `<p style="text-align: center; color: #777; font-style: italic; padding: 10px;">No comments yet</p>`;
      }
    } else {
      showToast(data.message || 'Failed to delete comment', 'error');
    }
  } catch (error) {
    console.error('Error deleting comment:', error);
    showToast('Failed to delete comment. Please try again.', 'error');
  }
}

// Toast notification function (copied from global.js/signup.js)
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

// Remove the custom event handler for the Answer Question link in the sidebar
// This allows the default behavior of the link to work properly, redirecting to the questions page
// The link in sidebar.blade.php already has the correct route: {{ route('questions') }}


// Client/js/home.js
document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.question-card');

  cards.forEach(card => {
    card.addEventListener('click', () => {
      const questionData = {
        id: card.dataset.id,
        title: card.dataset.title,
        body: card.dataset.body
      };

      // Save question to localStorage
      localStorage.setItem('selectedQuestion', JSON.stringify(questionData));

      // Go to question details page
      window.location.href = 'question.html';
    });
  });
});


const newsFeedButton = document.querySelector('.dashboard_items .menu a'); // First <a> in .menu is News Feed

newsFeedButton.addEventListener('click', function(e) {
  e.preventDefault();
  renderPosts();
});

// Handle click on hardcoded question cards (bottom of home.html)
document.querySelectorAll('.question-card').forEach(card => {
  card.addEventListener('click', () => {
    const id = card.getAttribute('data-id');
    const title = card.getAttribute('data-title');
    const body = card.getAttribute('data-body');
    const question = { id, title, body };
    localStorage.setItem('selectedQuestion', JSON.stringify(question));
    window.location.href = 'question.html';
  });
});

document.getElementById('qas-answer').addEventListener('click', () => {
  document.getElementById('answer-input').focus();
});
document.getElementById('qas-pass').addEventListener('click', () => {
  showToast('You chose to pass on this question.', 'info');
});
document.getElementById('qas-bookmark').addEventListener('click', () => {
  showToast('Question bookmarked! (Feature coming soon)', 'info');
});