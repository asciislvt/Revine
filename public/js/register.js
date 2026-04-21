const form = document.getElementById('register-form');
const passwordInput = form.querySelector('input[name="password"]');
const passRequirements = document.getElementById('password-requirements');
const errorElement = document.getElementById('error-message');

// Reset the form to clear any pre-filled values
form.reset();

// Clear error message when typing (might not use, could be confusing)
// form.addEventListener('input', clearError);

// Show password requirements when the password field is focused
passwordInput.addEventListener('focus', () => {
  passRequirements.style.display = 'block';
});

// Hide password requirements when the password field loses focus
passwordInput.addEventListener('blur', () => {
  passRequirements.style.display = 'none';
});

// Handle form submission
form.addEventListener('submit', async (event) => {
  event.preventDefault();
  const formData = new FormData(form);

  // Validate form data before sending
  const isValid = validateForm(formData);
  if (!isValid) {
    return;
  }

  // Send form data to the server
  try {
    const response = await fetch('/api/register-user.php', {
      method: 'POST',
      body: formData
    });

    if (response.ok) {
      window.location.href = '/login.php';
    } else {
      try {
        const result = await response.json();
        displayError(result.error);
      } catch (parseError) {
        displayError('Registration failed. Please try again.');
      }

    }
  } catch (error) {
    displayError('An error occurred while registering. Please try again later.');
  }
});

function validateForm(data) {
  if (!data.get('email')) {
    displayError('Email is required');
    return false;
  } else if (!data.get('username')) {
    displayError('Username is required');
    return false;
  } else if (!data.get('password')) {
    displayError('Password is required');
    return false;
  } else if (!isPassValid(data.get('password'))) {
    return false;
  }

  return true;
}

function isPassValid(password) {
  if (password.length < 8) {
    displayError('Password must be at least 8 characters long');
    return false;
  } else if (!/[A-Z]/.test(password)) {
    displayError('Password must contain at least one uppercase letter');
    return false;
  } else if (!/[a-z]/.test(password)) {
    displayError('Password must contain at least one lowercase letter');
    return false;
  } else if (!/[0-9]/.test(password)) {
    displayError('Password must contain at least one digit');
    return false;
  }

  return true;
}

function clearError() {
  errorElement.textContent = '';
}

function displayError(message) {
  errorElement.textContent = message;
}
