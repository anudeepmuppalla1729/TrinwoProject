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
  
  posts.forEach((post, index) => {
    // Format comments if they exist
    const commentsHtml = post.comments && post.comments.length > 0 
      ? post.comments.map(comment => `
        <div class="comment-item" data-comment-id="${comment.id}" style="padding: 10px; margin-bottom: 10px; border: 1px solid #e0e0e0; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
          <div class="comment-header" style="display: flex; align-items: center; margin-bottom: 5px; justify-content: space-between;">
            <div style="display: flex; align-items: center;">
              <i class="bi bi-person-circle" style="font-size: 1.5rem; margin-right: 10px;"></i>
              <div>
                <strong style="color: #a522b7;">${comment.user}</strong>
                <small style="display: block; color: #777; font-size: 0.8rem;">Posted on ${comment.created_at}</small>
              </div>
            </div>
            ${comment.is_owner ? `<button class="delete-comment-btn" data-comment-id="${comment.id}" style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 0.9rem;"><i class="bi bi-trash"></i></button>` : ''}
          </div>
          <div class="comment-text" style="max-height: 100px; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; word-wrap: break-word;">${comment.text}</div>
        </div>
      `).join('') 
      : `<p style="text-align: center; color: #777; font-style: italic; padding: 10px;">No comments yet</p>`;
      
    // Add style to hide scrollbar for Webkit browsers (Chrome, Safari, newer Edge)
    const styleElement = document.createElement('style');
    styleElement.textContent = `
      .comment-text::-webkit-scrollbar {
        display: none;
      }
    `;
    document.head.appendChild(styleElement);
    
    // Image HTML if available
    const imageHtml = post.imageUrl 
      ? `<div class="post-image" style><img src="${post.imageUrl}" alt="${post.title}" style="max-width: 100%; margin-top: 10px; border-radius: 8px;"></div>` 
      : '';
    
    postsContainer.innerHTML += `
      <div class="post" data-index="${index}" data-id="post-${post.id}">
        <div class="post-header">
          <div class="profile">
            <i class="bi bi-person-circle" style="font-size: 2rem; margin-right: 7px;"></i>
            <div>
              <strong>${post.profileName}</strong><br>
              <small style="font-size: 1rem;">${post.studyingIn} - ${post.expertIn}</small>
              <button style=" 
               border: 2px solid #a522b7;
               color: black;
               text-color: black;
               border-radius: 4px;
               cursor: pointer;
               margin-left: 5px;
               font-size: 0.9rem;">Follow</button>
            </div>
          </div>
          <div>
            <span class="options">⋮
              <div class="options-menu">
                <button>Comment</button><hr>
                <button>Not interested</button><hr>
                <button>Bookmark</button><hr>
                <button>Copy Link</button><hr>
                ${window.isAuthenticated ? `<button class="report-btn" data-post-id="${post.id}"><i class="fas fa-flag"></i> Report</button>` : ''}
              </div>
            </span>
            <span class="close-post">×</span>
          </div>
        </div>
        <hr>
        <h2>${post.title}</h2>
        
        <p>${post.body}</p> /* Post body */
        ${imageHtml}
        <div class="post-meta">
          <small>Posted on ${post.created_at}
        </div>
        <div class="comments-container" style="display: none; margin-top: 15px;">
          ${commentsHtml}
        </div>
        <div class="post-actions" style="display: flex; justify-content: space-between; padding: 10px 0;">
          <button class="action-btn upvote-btn" style="display: flex; align-items: center; background: none; border: none; color: ${post.userVote === 'upvote' ? '#a522b7' : '#555'}; font-size: 0.9rem; padding: 8px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
            <i class="bi ${post.userVote === 'upvote' ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up'}" style="font-size: 1.2rem; margin-right: 5px;"></i>
            <span>${post.upvotes || 0}</span>
          </button>
          <button class="action-btn comment-count-btn" style="display: flex; align-items: center; background: none; border: none; color: #555; font-size: 0.9rem; padding: 8px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
            <i class="bi bi-chat-dots" style="font-size: 1.2rem; margin-right: 5px;"></i>
            <span>${post.commentCount || 0}</span>
          </button>
          <button class="action-btn downvote-btn" style="display: flex; align-items: center; background: none; border: none; color: ${post.userVote === 'downvote' ? '#dc3545' : '#555'}; font-size: 0.9rem; padding: 8px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
            <i class="bi ${post.userVote === 'downvote' ? 'bi-hand-thumbs-down-fill' : 'bi-hand-thumbs-down'}" style="font-size: 1.2rem; margin-right: 5px;"></i>
            <span>${post.downvotes || 0}
          </button>
          <button class="action-btn comment-btn" style="display: flex; align-items: center; background: none; border: none; color: #555; font-size: 0.9rem; padding: 8px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
            <i class="bi bi-pencil-square" style="font-size: 1.2rem;"></i>
          </button>
          <button class="action-btn bookmark-btn" style="display: flex; align-items: center; background: none; border: none; color: ${post.isBookmarked ? '#a522b7' : '#555'}; font-size: 0.9rem; padding: 8px 12px; border-radius: 20px; cursor: pointer; transition: all 0.2s;">
            <i class="bi ${post.isBookmarked ? 'bi-bookmark-fill' : 'bi-bookmark'}" style="font-size: 1.2rem;"></i>
          </button>
        </div>
      </div>
    `;
  });

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
    color: #a522b7;
  }
  .comment-count-btn:hover, .comment-btn:hover {
    color: #0d6efd;
  }
  .downvote-btn:hover {
    color: #dc3545;
  }
  .bookmark-btn:hover {
    color: #a522b7;
  }
`;
document.head.appendChild(actionButtonStyles);

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
            this.style.color = '#a522b7';
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
  
  // Bookmark button
  document.querySelectorAll('.bookmark-btn').forEach((btn) => {
    btn.addEventListener('click', function() {
      const postElement = this.closest('.post');
      const postId = postElement.dataset.id.replace('post-', '');
      
      // Get CSRF token
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      
      // Send AJAX request to bookmark the post
      fetch(`/posts/${postId}/bookmark`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update the bookmark button based on the response
          if (data.isBookmarked) {
            // Post is now bookmarked
            this.style.color = '#a522b7';
            this.querySelector('i').classList.remove('bi-bookmark');
            this.querySelector('i').classList.add('bi-bookmark-fill');
          } else {
            // Bookmark was removed
            this.style.color = '#555';
            this.querySelector('i').classList.remove('bi-bookmark-fill');
            this.querySelector('i').classList.add('bi-bookmark');
          }
        } else {
          console.error('Failed to bookmark post:', data.message);
        }
      })
      .catch(error => {
        console.error('Error bookmarking post:', error);
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
        <button class="submit-comment" style="background-color: #a522b7; color: white; border: none; padding: 5px 10px; border-radius: 4px; margin-top: 5px; cursor: pointer;">Submit Comment</button>
      `;
      
      // Insert form after post actions
      postElement.querySelector('.post-actions').insertAdjacentElement('afterend', commentForm);
      
      // Add event listener to submit button
      commentForm.querySelector('.submit-comment').addEventListener('click', function() {
        const commentText = commentForm.querySelector('textarea').value.trim();
        if (!commentText) {
          alert('Please enter a comment');
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
            alert('Report submitted successfully!');
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
            <i class="bi bi-person-circle" style="font-size: 1.5rem; margin-right: 10px;"></i>
            <div>
              <strong style="color: #a522b7;">${data.comment.user}</strong>
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
      alert(data.message || 'Failed to add comment');
    }
  } catch (error) {
    console.error('Error submitting comment:', error);
    alert('Failed to submit comment. Please try again.');
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
      alert(data.message || 'Failed to delete comment');
    }
  } catch (error) {
    console.error('Error deleting comment:', error);
    alert('Failed to delete comment. Please try again.');
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
  alert('You chose to pass on this question.');
});
document.getElementById('qas-bookmark').addEventListener('click', () => {
  alert('Question bookmarked! (Feature coming soon)');
});