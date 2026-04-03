const form = document.getElementById('upload-form');

form.addEventListener('submit', async (event) => {
  event.preventDefault();
  const formData = new FormData(form);

  try {
    const response = await fetch('api/upload.php', {
      method: 'POST',
      body: formData,
    });
    const result = await response.json();

    console.log(result);
  } catch (error) {
    console.error('Error uploading video:', error);
  }
});
