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

  // Forgot password modal logic
  const forgotPasswordLink = document.getElementById('forgotPasswordLink');
  const forgotPasswordModal = document.getElementById('forgotPasswordModal');
  const closeForgotModal = document.getElementById('closeForgotModal');
  const forgotStepEmail = document.getElementById('forgotStepEmail');
  const forgotStepOtp = document.getElementById('forgotStepOtp');
  const forgotStepReset = document.getElementById('forgotStepReset');
  const forgotError = document.getElementById('forgotError');
  const forgotSuccess = document.getElementById('forgotSuccess');
  const sendOtpBtn = document.getElementById('sendOtpBtn');
  const verifyOtpBtn = document.getElementById('verifyOtpBtn');
  const resetPasswordBtn = document.getElementById('resetPasswordBtn');
  let forgotEmail = '';

  if (forgotPasswordLink && forgotPasswordModal) {
    forgotPasswordLink.addEventListener('click', function(e) {
      e.preventDefault();
      forgotPasswordModal.style.display = 'flex';
      forgotStepEmail.style.display = 'block';
      forgotStepOtp.style.display = 'none';
      forgotStepReset.style.display = 'none';
      forgotError.style.display = 'none';
      forgotSuccess.style.display = 'none';
    });
    closeForgotModal.addEventListener('click', function() {
      forgotPasswordModal.style.display = 'none';
    });
    forgotPasswordModal.addEventListener('click', function(e) {
      if (e.target === forgotPasswordModal) forgotPasswordModal.style.display = 'none';
    });
  }

  // Send OTP
  if (sendOtpBtn) {
    sendOtpBtn.addEventListener('click', function() {
      const email = document.getElementById('forgotEmail').value.trim();
      forgotError.style.display = 'none';
      forgotSuccess.style.display = 'none';
      if (!email) {
        forgotError.textContent = 'Please enter your email.';
        forgotError.style.display = 'block';
        return;
      }
      sendOtpBtn.disabled = true;
      sendOtpBtn.textContent = 'Sending...';
      fetch('/forgot-password/send-otp', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ email })
      })
      .then(res => res.json())
      .then(data => {
        sendOtpBtn.disabled = false;
        sendOtpBtn.textContent = 'Send OTP';
        if (data.success) {
          forgotEmail = email;
          forgotStepEmail.style.display = 'none';
          forgotStepOtp.style.display = 'block';
          forgotStepReset.style.display = 'none';
          forgotSuccess.textContent = data.message || 'OTP sent to your email.';
          forgotSuccess.style.display = 'block';
        } else {
          forgotError.textContent = data.message || 'Failed to send OTP.';
          forgotError.style.display = 'block';
        }
      })
      .catch(() => {
        sendOtpBtn.disabled = false;
        sendOtpBtn.textContent = 'Send OTP';
        forgotError.textContent = 'Failed to send OTP.';
        forgotError.style.display = 'block';
      });
    });
  }

  // Verify OTP
  if (verifyOtpBtn) {
    verifyOtpBtn.addEventListener('click', function() {
      const otp = document.getElementById('forgotOtp').value.trim();
      forgotError.style.display = 'none';
      forgotSuccess.style.display = 'none';
      if (!otp) {
        forgotError.textContent = 'Please enter the OTP.';
        forgotError.style.display = 'block';
        return;
      }
      verifyOtpBtn.disabled = true;
      verifyOtpBtn.textContent = 'Verifying...';
      fetch('/forgot-password/verify-otp', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ email: forgotEmail, otp })
      })
      .then(res => res.json())
      .then(data => {
        verifyOtpBtn.disabled = false;
        verifyOtpBtn.textContent = 'Verify OTP';
        if (data.success) {
          forgotStepEmail.style.display = 'none';
          forgotStepOtp.style.display = 'none';
          forgotStepReset.style.display = 'block';
          forgotSuccess.textContent = data.message || 'OTP verified. Enter your new password.';
          forgotSuccess.style.display = 'block';
        } else {
          forgotError.textContent = data.message || 'Invalid OTP.';
          forgotError.style.display = 'block';
        }
      })
      .catch(() => {
        verifyOtpBtn.disabled = false;
        verifyOtpBtn.textContent = 'Verify OTP';
        forgotError.textContent = 'Failed to verify OTP.';
        forgotError.style.display = 'block';
      });
    });
  }

  // Password strength meter and confirm password validation
  const forgotNewPassword = document.getElementById('forgotNewPassword');
  const forgotConfirmPassword = document.getElementById('forgotConfirmPassword');
  const passwordStrength = document.getElementById('passwordStrength');

  function getPasswordStrength(pw) {
    let score = 0;
    if (pw.length >= 8) score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[a-z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;
    return score;
  }
  function getStrengthLabel(score) {
    if (score <= 2) return { label: 'Weak', color: '#e74c3c' };
    if (score === 3) return { label: 'Medium', color: '#f39c12' };
    if (score >= 4) return { label: 'Strong', color: '#28a745' };
  }
  if (forgotNewPassword) {
    forgotNewPassword.addEventListener('input', function() {
      const pw = forgotNewPassword.value;
      const score = getPasswordStrength(pw);
      const { label, color } = getStrengthLabel(score);
      passwordStrength.textContent = 'Strength: ' + label;
      passwordStrength.style.color = color;
    });
  }

  // Reset Password (with strength and confirm validation)
  if (resetPasswordBtn) {
    resetPasswordBtn.addEventListener('click', function() {
      const newPassword = forgotNewPassword.value.trim();
      const confirmPassword = forgotConfirmPassword.value.trim();
      forgotError.style.display = 'none';
      forgotSuccess.style.display = 'none';
      const score = getPasswordStrength(newPassword);
      if (!newPassword) {
        forgotError.textContent = 'Please enter a new password.';
        forgotError.style.display = 'block';
        return;
      }
      if (score < 4) {
        forgotError.textContent = 'Password is not strong enough. Use at least 8 characters, uppercase, lowercase, number, and symbol.';
        forgotError.style.display = 'block';
        return;
      }
      if (!confirmPassword) {
        forgotError.textContent = 'Please confirm your new password.';
        forgotError.style.display = 'block';
        return;
      }
      if (newPassword !== confirmPassword) {
        forgotError.textContent = 'Passwords do not match.';
        forgotError.style.display = 'block';
        return;
      }
      resetPasswordBtn.disabled = true;
      resetPasswordBtn.textContent = 'Resetting...';
      fetch('/forgot-password/reset', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ email: forgotEmail, new_password: newPassword })
      })
      .then(res => res.json())
      .then(data => {
        resetPasswordBtn.disabled = false;
        resetPasswordBtn.textContent = 'Reset Password';
        if (data.success) {
          forgotSuccess.textContent = data.message || 'Password reset successfully. You can now log in.';
          forgotSuccess.style.display = 'block';
          forgotStepEmail.style.display = 'block';
          forgotStepOtp.style.display = 'none';
          forgotStepReset.style.display = 'none';
          setTimeout(() => {
            window.location.href = '/login';
          }, 2000);
        } else {
          forgotError.textContent = data.message || 'Failed to reset password.';
          forgotError.style.display = 'block';
        }
      })
      .catch(() => {
        resetPasswordBtn.disabled = false;
        resetPasswordBtn.textContent = 'Reset Password';
        forgotError.textContent = 'Failed to reset password.';
        forgotError.style.display = 'block';
      });
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