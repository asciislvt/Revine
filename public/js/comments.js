const commentsContainer = document.getElementById('comments-list');
const commentForm = document.getElementById('comment-form');
const errorElement = document.getElementById('comment-error');
const videoId = commentForm.dataset.videoId;

reloadComments();

commentForm.addEventListener('submit', async (event) => {
  event.preventDefault();
  const formData = new FormData(commentForm);
  formData.append('video_id', videoId);

  try {
    const response = await fetch('api/post-comment.php', {
      method: 'POST',
      body: formData
    });
  } catch (error) {
    errorElement.textContent = 'An error occurred while posting your comment. Please try again later.';
  } finally {
    reloadComments();
    commentForm.reset();
  }
});

async function reloadComments() {
  const videoId = commentForm.dataset.videoId;
  commentsContainer.innerHTML = '';

  try {
    const response = await fetch(`api/get-comments.php?video_id=${videoId}`);
    const comments = await response.json();

    if (comments.length === 0) {
      commentsContainer.appendChild(document.createElement('p')).textContent = 'No comments yet. Be the first to comment!';
    }

    comments.forEach(comment => {
      const commentElement = document.createElement('div');
      const usernameElement = document.createElement('strong');
      const timestampElement = document.createElement('small');
      const textElement = document.createElement('p');
      const date = new Date(comment.comment_date).toLocaleDateString("en-US", {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
      });

      usernameElement.textContent = comment.username;
      timestampElement.textContent = date;
      textElement.textContent = comment.comment;

      commentElement.appendChild(usernameElement);
      commentElement.appendChild(timestampElement);
      commentElement.appendChild(textElement);

      commentsContainer.appendChild(commentElement);
    });
  } catch (error) {
    console.error('Error loading comments:', error);
  }
}
