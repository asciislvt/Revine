const player = document.getElementById('player');
const viewCountElement = document.getElementById('view-count');

if (player && viewCountElement) {
  player.addEventListener('play', async () => {
    try {
      const videoId = player.dataset.videoId;
      const response = await fetch(`/api/increment-view.php?videoId=${videoId}`, {
        method: 'POST'
      });
      const data = await response.json();
      if (response.ok) {
        viewCountElement.textContent = `${data.viewCount} views`;
      } else {
        console.error('Failed to increment view count:', data.error);
      }
    } catch (error) {
      console.error('Error incrementing view count:', error);
    }
  });
}