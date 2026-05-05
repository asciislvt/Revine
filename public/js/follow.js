const followButton = document.getElementById('follow-button');
const errorMessage = document.getElementById('follow-error');

fetchFollowStatus();

followButton.addEventListener('click', async (event) => {
  event.preventDefault();
  console.log('Follow button clicked');

  try {
    const userToFollow = followButton.dataset.username;
    const response = await fetch('api/follow-user.php', {
      method: 'POST',
      body: JSON.stringify({ username: userToFollow }),
    });

    if (response.ok) {
      fetchFollowStatus();
    } else {
      const errorData = await response.json();
      if (errorMessage) {
        errorMessage.textContent = errorData.message || 'An error occurred. Please try again.';
        errorMessage.style.display = 'block';
      }
    }
  } catch (error) {
    console.error('Error following/unfollowing user:', error);
  }
});

async function fetchFollowStatus() {
  try {
    const response = await fetch('api/get-follow-status.php?' +
      new URLSearchParams({ username: followButton.dataset.username }));

    if (response.ok) {
      const data = await response.json();
      console.log('Follow status:', data.isFollowing);
      if (data.isFollowing) {
        followButton.classList.remove('follow');
        followButton.classList.add('unfollow');
        followButton.textContent = 'Unfollow';
      } else {
        followButton.classList.remove('unfollow');
        followButton.classList.add('follow');
        followButton.textContent = 'Follow';
      }
    }
  } catch (error) {
  }
}
