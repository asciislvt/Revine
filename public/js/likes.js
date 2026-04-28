const likeButton = document.getElementById('like-button');
const likeCount = document.getElementById('like-count');

fetchLikes();

likeButton.addEventListener('click', async (event) => {
  postLike(true);
});

async function postLike(isLike) {
  try {
    const response = await fetch('api/post-like.php', {
      method: 'POST',
      body: JSON.stringify({ videoId, isLike }),
    })
    const result = await response.json();
    fetchLikes();
  } catch (error) {
    console.error("Error posting like:", error);
  }
}

async function fetchLikes() {
  try {
    const response = await fetch('api/get-likes.php?videoId=' + videoId);
    const result = await response.json();
    updateLikeButton(result.hasLiked);
    likeCount.textContent = `${result.likeCount} Likes`;
  } catch (error) {
    console.error("Error fetching likes:", error);
  }
}

function updateLikeButton(hasLiked) {
  if (hasLiked === 1) {
    likeButton.textContent = 'Liked';
    likeButton.classList.add('liked');
  } else {
    likeButton.textContent = 'Like';
    likeButton.classList.remove('liked');
  }
}
