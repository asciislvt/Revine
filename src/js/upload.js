const form = document.getElementById('upload-form');
form.reset();

form.addEventListener('submit', async (event) => {
  event.preventDefault();
  const formData = new FormData(form);
  // if (!formData.get('uploaded-video')) {
  //   console.error('No video file selected.');
  //   return;
  // }

  try {
    const response = await fetch('api/upload.php', {
      method: 'POST',
      body: formData,
    });
    const result = await response.text();
    // console.log(result);
    const json = JSON.parse(result);
    console.log(json);
  } catch (error) {
    console.error('Error uploading video:', error);
  }
});
