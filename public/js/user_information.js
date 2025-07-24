document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('multi-step-form');
  const formSteps = document.querySelectorAll('.form-step');
  const nextBtn = document.querySelector('.next-btn');
  const backBtn = document.querySelector('.back-btn');
  const submitBtn = document.querySelector('.submit-btn');

  // Add error message containers after each input
  const inputs = form.querySelectorAll('input, select');
  inputs.forEach(input => {
    if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('error-message')) {
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-message';
      errorDiv.style.color = 'red';
      errorDiv.style.fontSize = '0.85rem';
      errorDiv.style.marginTop = '0.3rem';
      input.parentNode.insertBefore(errorDiv, input.nextSibling);
    }
  });

  // Clear error when input changes
  inputs.forEach(input => {
    input.addEventListener('input', function() {
      const errorDiv = this.nextElementSibling;
      if (errorDiv && errorDiv.classList.contains('error-message')) {
        errorDiv.textContent = '';
      }
    });
  });

  // Validate first step before proceeding to next step
  nextBtn.addEventListener('click', function(e) {
    const currentStep = document.querySelector('.form-step.active');
    const ageInput = currentStep.querySelector('input[name="age"]');
    const genderSelect = currentStep.querySelector('select[name="gender"]');
    
    let isValid = true;
    
    // Validate age
    if (!ageInput.value.trim()) {
      showError(ageInput, 'Age is required');
      isValid = false;
    } else if (isNaN(ageInput.value) || ageInput.value < 1 || ageInput.value > 120) {
      showError(ageInput, 'Please enter a valid age between 1 and 120');
      isValid = false;
    } else {
      clearError(ageInput);
    }
    
    // Validate gender
    if (!genderSelect.value) {
      showError(genderSelect, 'Please select your gender');
      isValid = false;
    } else {
      clearError(genderSelect);
    }
    
    if (isValid) {
      formSteps[0].classList.remove('active');
      formSteps[1].classList.add('active');
    }
  });

  // Go back to previous step
  backBtn.addEventListener('click', function() {
    formSteps[1].classList.remove('active');
    formSteps[0].classList.add('active');
  });

  // Form submission validation
  form.addEventListener('submit', function(e) {
    const currentStep = document.querySelector('.form-step.active');
    const studyingSelect = currentStep.querySelector('select[name="studying_in"]');
    const expertSelect = currentStep.querySelector('select[name="expert_in"]');
    
    let isValid = true;
    
    // Validate studying field
    if (!studyingSelect.value) {
      showError(studyingSelect, 'Please select what you are studying');
      isValid = false;
    } else {
      clearError(studyingSelect);
    }
    
    // Validate expertise field
    if (!expertSelect.value) {
      showError(expertSelect, 'Please select your field of expertise');
      isValid = false;
    } else {
      clearError(expertSelect);
    }
    
    if (!isValid) {
      e.preventDefault();
    }
  });

  // Helper functions
  function showError(input, message) {
    const errorDiv = input.nextElementSibling;
    if (errorDiv && errorDiv.classList.contains('error-message')) {
      errorDiv.textContent = message;
    }
    input.style.borderColor = 'red';
  }

  function clearError(input) {
    const errorDiv = input.nextElementSibling;
    if (errorDiv && errorDiv.classList.contains('error-message')) {
      errorDiv.textContent = '';
    }
    input.style.borderColor = '';
  }
});