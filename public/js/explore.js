import { createVideoCard } from './VideoCard.js';

const videoGrid = document.getElementById('video-grid');
const statusElement = document.getElementById('status');
const recentButton = document.getElementById('recent-button');
const followingButton = document.getElementById('following-button');
const trendingButton = document.getElementById('trending-button');
const mostViewedButton = document.getElementById('most-viewed-button');

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
