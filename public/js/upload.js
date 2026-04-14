const form = document.getElementById('upload-form');
const errorElement = document.getElementById('error-message');

form.reset();

form.addEventListener('submit', async (event) => {
  event.preventDefault();
  clearError();
  const formData = new FormData(form);

  const isValid = validateForm(formData);
  if (!isValid) {
    return;
  }

  try {
    const response = await fetch('/api/upload-video.php', {
      method: 'POST',
      body: formData
    });

    // console.log('Response status:', response.text());

    if (response.ok) {
      const result = await response.json();
      console.log('Upload successful:', result);
    } else {
      displayError('Upload failed. Please try again.');
    }
  } catch (error) {
    displayError('An error occurred while uploading. Please try again later.');
    console.error('Error uploading video:', error);
  }
});

function validateForm(data) {
  if (!data.get('title')) {
    displayError('Title is required');
    return false;
  }
  if (!data.get('video-file')) {
    displayError('File is required');
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
