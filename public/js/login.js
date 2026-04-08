const form = document.getElementById('login-form');
const errorElement = document.getElementById('error-message');

// Reset the form to clear any pre-filled values
form.reset();

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
    const response = await fetch('/api/auth-user.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.text();
    console.log('Server response:', result);

    if (response.ok) {
      window.location.href = '/index.php';
      console.log('Login successful, redirecting to index.php');
    } else {
      displayError(result.error || 'Login failed. Please try again.');
    }
  } catch (error) {
    console.error('Error:', error.text);
    displayError('An error occurred while logging in. Please try again later.');
  }
});

function validateForm(data) {
  if (!data.get('username')) {
    displayError('Username is required');
    return false;
  } else if (!data.get('password')) {
    displayError('Password is required');
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
