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
      alert('Password reset functionality would be implemented here.');
    });
  }
});