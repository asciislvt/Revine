const form = document.getElementById('upload-form');
const submitButton = document.getElementById('submit-video');
const errorElement = document.getElementById('error-message');
const videoUrl = document.getElementById('video-url');

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
    submitButton.disabled = true;
    const response = await fetch('/api/upload-video.php', {
      method: 'POST',
      body: formData
    });

    if (response.ok) {
      const result = await response.json();

      form.reset();
      videoUrl.textContent = `Video uploaded successfully! View it here!`;
      videoUrl.href = `/watch.php?id=${result.videoId}`;
    } else {
      displayError('Upload failed. Please try again.');
      submitButton.disabled = false;
    }
  } catch (error) {
    displayError('An error occurred while uploading. Please try again later.');
    console.error('Error uploading video:', error);
    submitButton.disabled = false;
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
