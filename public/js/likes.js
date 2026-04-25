const likeButton = document.getElementById('like-button');
const dislikeButton = document.getElementById('dislike-button');

likeButton.addEventListener('click', async (event) => {
  postLike(true);
});

dislikeButton.addEventListener('click', async (event) => {
  postLike(false);
});

async function postLike(isLike) {
  console.log(`Posting ${isLike ? 'like' : 'dislike'} for video ID: ${videoId}`);
  try {
    const response = await fetch('api/post-like.php', {
      method: 'POST',
      body: JSON.stringify({ videoId, isLike }),
    })
    const result = await response.json();
    console.log(result);
  } catch (error) {
    console.error("Error posting like:", error);
  }
}
