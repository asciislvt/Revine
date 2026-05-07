import { createVideoCard } from './VideoCard.js';

const videoGrid = document.getElementById('video-grid');
const statusElement = document.getElementById('status');
const recentButton = document.getElementById('recent-button');
const followingButton = document.getElementById('following-button');
const trendingButton = document.getElementById('trending-button');
// const mostViewedButton = document.getElementById('most-viewed-button');

fetchVideos();
setActiveButton(recentButton);

recentButton.addEventListener('click', () => {
  clearActiveButtons();
  statusElement.textContent = 'Loading recent videos...';
  statusElement.style.display = 'block';
  videoGrid.innerHTML = '';
  fetchVideos('recent');
  setActiveButton(recentButton);
});

if (followingButton) {
  followingButton.addEventListener('click', () => {
    statusElement.textContent = 'Loading videos from followed channels...';
    statusElement.style.display = 'block';
    videoGrid.innerHTML = '';
    fetchVideos('following');
    setActiveButton(followingButton);
  });
}

trendingButton.addEventListener('click', () => {
  statusElement.textContent = 'Loading trending videos...';
  statusElement.style.display = 'block';
  videoGrid.innerHTML = '';
  fetchVideos('trending');
  setActiveButton(trendingButton);
});

// mostViewedButton.addEventListener('click', () => {
//   statusElement.textContent = 'Loading most viewed videos...';
//   statusElement.style.display = 'block';
//   videoGrid.innerHTML = '';
//   fetchVideos('most_viewed');
// });

async function fetchVideos(fetchType = 'recent') {
  try {
    const response = await fetch(`/api/get-videos.php?fetchType=${fetchType}`);
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

function clearActiveButtons() {
  recentButton.classList.remove('active');
  if (followingButton) {
    followingButton.classList.remove('active');
  }
  trendingButton.classList.remove('active');
}

function setActiveButton(button) {
  clearActiveButtons();
  button.classList.add('active');
}