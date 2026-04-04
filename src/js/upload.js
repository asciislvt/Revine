const form = document.getElementById('upload-form');
const urlElement = document.getElementById('upload-url');

form.reset();

form.addEventListener('submit', async (event) => {
  event.preventDefault();
  const formData = new FormData(form);
  if (!formData.get('uploaded-video')) {
    console.error('No video file selected.');
    return;
  }

  try {
    const response = await fetch('/api/upload/', {
      method: 'POST',
      body: formData,
    });

    const blob = await response.blob();

    console.log('Upload response:', blob.text());

    const json = JSON.parse(await blob.text());

    if (json.url) {
      urlElement.innerHTML = `<a href="${json.url}" target="_blank">Video uploaded, view here!</a>`;
    }

    console.log(json);
  } catch (error) {
    console.error('Error uploading video:', error);
  }
});
