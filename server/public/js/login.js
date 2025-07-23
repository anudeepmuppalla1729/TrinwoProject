document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('.signup-form');
  const emailInput = form.querySelector('input[type="email"]');
  const passwordInput = form.querySelector('input[type="password"]');
  const errorMessages = form.querySelectorAll('.error-message');

  // Clear error messages when inputs change
  emailInput.addEventListener('input', () => {
    emailInput.parentElement.nextElementSibling.textContent = '';
  });

  passwordInput.addEventListener('input', () => {
    passwordInput.parentElement.nextElementSibling.textContent = '';
  });

  // Form submission
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    let isValid = true;

    // Reset error messages
    errorMessages.forEach(el => el.textContent = '');

    // Validate email
    if (!emailInput.value.trim()) {
      emailInput.parentElement.nextElementSibling.textContent = 'Email is required';
      isValid = false;
    } else if (!isValidEmail(emailInput.value.trim())) {
      emailInput.parentElement.nextElementSibling.textContent = 'Please enter a valid email';
      isValid = false;
    }

    // Validate password
    if (!passwordInput.value.trim()) {
      passwordInput.parentElement.nextElementSibling.textContent = 'Password is required';
      isValid = false;
    }

    // If form is valid, proceed with login
    if (isValid) {
      // Here you would typically send the data to your server
      console.log('Login form submitted');
      console.log('Email:', emailInput.value.trim());
      console.log('Password:', passwordInput.value.trim());
      
      // For demo purposes, redirect to home page
      // In a real application, you would verify credentials first
      setTimeout(() => {
        window.location.href = 'home.html';
      }, 1000);
    }
  });

  // Email validation helper function
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  // Social login buttons (placeholders)
  const socialButtons = document.querySelectorAll('.btn-social');
  socialButtons.forEach(button => {
    button.addEventListener('click', function() {
      const provider = this.textContent.trim();
      console.log(`Login with ${provider}`);
      // Implement social login logic here
    });
  });

  // Forgot password link
  const forgotPasswordLink = document.querySelector('a[href="#"]');
  if (forgotPasswordLink) {
    forgotPasswordLink.addEventListener('click', function(e) {
      e.preventDefault();
      console.log('Forgot password clicked');
      // Implement password reset logic here
      showToast('Password reset functionality would be implemented here.', 'info');
    });
  }
});

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