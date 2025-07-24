document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('.signup-form');
  const emailInput = form.querySelector('input[name="email"]');
  const passwordInput = form.querySelector('input[name="password"]');
  
  // Add error message containers if they don't exist
  const formGroups = form.querySelectorAll('.form-group');
  formGroups.forEach(group => {
    if (!group.querySelector('.error-message')) {
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-message';
      errorDiv.style.color = 'red';
      errorDiv.style.fontSize = '0.85rem';
      errorDiv.style.marginTop = '0.3rem';
      group.appendChild(errorDiv);
    }
  });

  // Clear error messages when inputs change
  emailInput.addEventListener('input', () => {
    clearError(emailInput);
  });

  passwordInput.addEventListener('input', () => {
    clearError(passwordInput);
  });

  // Form submission
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    let isValid = true;

    // Reset error messages
    clearAllErrors();

    // Validate email
    if (!emailInput.value.trim()) {
      showError(emailInput, 'Email is required');
      isValid = false;
    } else if (!isValidEmail(emailInput.value.trim())) {
      showError(emailInput, 'Please enter a valid email');
      isValid = false;
    }

    // Validate password
    if (!passwordInput.value.trim()) {
      showError(passwordInput, 'Password is required');
      isValid = false;
    }

    // If form is valid, submit it
    if (isValid) {
      form.submit();
    }
  });

  // Email validation helper function
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  // Show error message
  function showError(input, message) {
    const formGroup = input.closest('.form-group');
    const errorDiv = formGroup.querySelector('.error-message');
    if (errorDiv) {
      errorDiv.textContent = message;
    }
    input.classList.add('is-invalid');
    input.style.borderColor = 'red';
  }

  // Clear error for a specific input
  function clearError(input) {
    const formGroup = input.closest('.form-group');
    const errorDiv = formGroup.querySelector('.error-message');
    if (errorDiv) {
      errorDiv.textContent = '';
    }
    input.classList.remove('is-invalid');
    input.style.borderColor = '';
  }

  // Clear all errors
  function clearAllErrors() {
    const errorDivs = form.querySelectorAll('.error-message');
    errorDivs.forEach(div => {
      div.textContent = '';
    });
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
      input.classList.remove('is-invalid');
      input.style.borderColor = '';
    });
  }

  // Forgot password link
  const forgotPasswordLink = form.querySelector('a[href="#"]');
  if (forgotPasswordLink) {
    forgotPasswordLink.addEventListener('click', function(e) {
      e.preventDefault();
      showToast('Password reset functionality would be implemented here.', 'info');
    });
  }
});

// Toast notification function
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