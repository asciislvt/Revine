const videoGrid = document.getElementById('video-grid');
const statusElement = document.getElementById('status');

fetchVideos();

async function fetchVideos() {
  try {
    const response = await fetch('/api/get-videos.php');
    const videos = await response.json();

    videos.forEach(video => {
      videoGrid.appendChild(
        createVideoCard(video.video_id, video.title, video.username, video.uploaded_on)
      );
    });
  } catch (error) {
    console.error('Error fetching videos:', error);
  } finally {
    statusElement.style.display = 'none';
  }
}

function createVideoCard(videoId, title, uploader, date) {
  const videoElement = document.createElement('div');
  const videoLink = document.createElement('a');
  const thumbnailElement = document.createElement('img');
  const titleElement = document.createElement('h3');
  const uploaderElement = document.createElement('p');
  const dateElement = document.createElement('p');

  videoLink.href = `/video.php?id=${videoId}`
  thumbnailElement.src = `/videos/${videoId}/thumbnail.jpg`
  thumbnailElement.onerror = () => {
    thumbnailElement.src = '/images/default-thumbnail.jpg';
  };
  thumbnailElement.alt = `${title} thumbnail`;

  titleElement.textContent = title;
  uploaderElement.textContent = `Uploaded by ${uploader}`;
  dateElement.textContent = `Uploaded on ${new Date(date).toLocaleDateString()}`;

  videoLink.appendChild(titleElement);
  videoLink.appendChild(uploaderElement);
  videoLink.appendChild(dateElement);
  videoLink.appendChild(thumbnailElement);

  videoElement.appendChild(videoLink);
  return videoElement;
}
