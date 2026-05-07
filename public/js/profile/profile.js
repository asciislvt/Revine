import { createMinimalVideoCard } from '../VideoCard.js';

const videoGrid = document.getElementById('video-grid');
const statusElement = document.getElementById('status');
const username = videoGrid.dataset.username;

fetchVideos();

async function fetchVideos() {
  try {
    const response = await fetch('/api/get-videos.php?fetchType=byUser&uploader=' + encodeURIComponent(username));
    const videos = await response.json();

    if (videos.length === 0) {
      videoGrid.appendChild(document.createElement('p')).textContent = 'No videos uploaded yet...';
    }
    videos.forEach(video => {
      videoGrid.appendChild(
        createMinimalVideoCard(video.video_id, video.title)
      );
    });
  } catch (error) {
    console.error('Error fetching videos:', error);
  } finally {
    statusElement.style.display = 'none';
  }
}
