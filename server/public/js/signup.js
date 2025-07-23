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
