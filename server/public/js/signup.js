document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('.signup-form');
  
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

  // Add input event listeners to clear errors on input
  const inputs = form.querySelectorAll('input');
  inputs.forEach(input => {
    input.addEventListener('input', function() {
      clearError(this);
      
      // Special handling for password confirmation
      if (input.name === 'password_confirmation' || input.name === 'password') {
        const passwordInput = form.querySelector('input[name="password"]');
        const confirmInput = form.querySelector('input[name="password_confirmation"]');
        
        if (passwordInput && confirmInput && confirmInput.value) {
          if (passwordInput.value !== confirmInput.value) {
            showError(confirmInput, 'Passwords do not match');
          } else {
            clearError(confirmInput);
          }
        }
      }
    });
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const nameInput = form.querySelector('input[name="name"]');
    const emailInput = form.querySelector('input[name="email"]');
    const passwordInput = form.querySelector('input[name="password"]');
    const confirmPasswordInput = form.querySelector('input[name="password_confirmation"]');
    
    // Reset all errors
    clearAllErrors();

    let isValid = true;

    // Name Validation
    if (nameInput.value.trim() === '') {
      showError(nameInput, 'Name is required');
      isValid = false;
    } else if (nameInput.value.trim().length < 2) {
      showError(nameInput, 'Name must be at least 2 characters');
      isValid = false;
    }

    // Email Validation
    if (emailInput.value.trim() === '') {
      showError(emailInput, 'Email is required');
      isValid = false;
    } else if (!isValidEmail(emailInput.value.trim())) {
      showError(emailInput, 'Please enter a valid email address');
      isValid = false;
    }

    // Password Validation
    if (passwordInput.value === '') {
      showError(passwordInput, 'Password is required');
      isValid = false;
    } else {
      const passwordValidation = validatePassword(passwordInput.value);
      if (!passwordValidation.valid) {
        showError(passwordInput, passwordValidation.message);
        isValid = false;
      }
    }

    // Confirm Password Validation
    if (confirmPasswordInput.value === '') {
      showError(confirmPasswordInput, 'Please confirm your password');
      isValid = false;
    } else if (passwordInput.value !== confirmPasswordInput.value) {
      showError(confirmPasswordInput, 'Passwords do not match');
      isValid = false;
    }

    // If form is valid, submit it
    if (isValid) {
      form.submit();
    }
  });

  // Helper Functions
  function showError(input, message) {
    const formGroup = input.closest('.form-group');
    const errorDiv = formGroup.querySelector('.error-message');
    if (errorDiv) {
      errorDiv.textContent = message;
    }
    input.classList.add('is-invalid');
    input.style.borderColor = 'red';
  }

  function clearError(input) {
    const formGroup = input.closest('.form-group');
    const errorDiv = formGroup.querySelector('.error-message');
    if (errorDiv) {
      errorDiv.textContent = '';
    }
    input.classList.remove('is-invalid');
    input.style.borderColor = '';
  }

  function clearAllErrors() {
    const errorDivs = form.querySelectorAll('.error-message');
    errorDivs.forEach(div => {
      div.textContent = '';
    });
    const allInputs = form.querySelectorAll('input');
    allInputs.forEach(input => {
      input.classList.remove('is-invalid');
      input.style.borderColor = '';
    });
  }

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function validatePassword(password) {
    if (password.length < 8) {
      return { valid: false, message: 'Password must be at least 8 characters' };
    }
    if (!/[A-Z]/.test(password)) {
      return { valid: false, message: 'Password must contain at least one uppercase letter' };
    }
    if (!/[a-z]/.test(password)) {
      return { valid: false, message: 'Password must contain at least one lowercase letter' };
    }
    if (!/[0-9]/.test(password)) {
      return { valid: false, message: 'Password must contain at least one number' };
    }
    return { valid: true };
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
