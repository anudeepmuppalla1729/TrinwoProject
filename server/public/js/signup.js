document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('.signup-form');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const nameInput = form.querySelector('input[type="text"]');
    const emailInput = form.querySelector('input[type="email"]');
    const passwordInput = form.querySelector('input[type="password"]');
    const termsChecked = form.querySelector('input[type="checkbox"]').checked;

    resetError(nameInput);
    resetError(emailInput);
    resetError(passwordInput);

    let isValid = true;

    // ✅ Name Validation
    if (nameInput.value.trim() === '') {
      showError(nameInput, 'Name is required.');
      isValid = false;
    }

    // ✅ Email Validation
    if (!/^\S+@\S+\.\S+$/.test(emailInput.value.trim())) {
      showError(emailInput, 'Please enter a valid email address.');
      isValid = false;
    }

    // ✅ Password Validation
    const password = passwordInput.value;
    if (password.length < 8) {
      showError(passwordInput, 'Must be at least 8 characters.');
      isValid = false;
    } else if (!/[A-Z]/.test(password)) {
      showError(passwordInput, 'Must contain at least one uppercase letter.');
      isValid = false;
    } else if (!/[a-z]/.test(password)) {
      showError(passwordInput, 'Must contain at least one lowercase letter.');
      isValid = false;
    }

    // ✅ Terms and Conditions
    if (!termsChecked) {
      showToast('Please agree to the terms and conditions.', 'error');
      isValid = false;
    }

    // ✅ Final Success
    if (isValid) {
      showToast('Signup successful!', 'success');
      form.reset();
    }
  });

  function showError(input, message) {
    const errorDiv = input.closest('.form-group').querySelector('.error-message');
    input.classList.add('is-invalid');
    errorDiv.textContent = message;
    errorDiv.style.color = 'red';
    errorDiv.style.fontSize = '0.85rem';
  }

  function resetError(input) {
    const errorDiv = input.closest('.form-group').querySelector('.error-message');
    input.classList.remove('is-invalid');
    errorDiv.textContent = '';
  }
});
