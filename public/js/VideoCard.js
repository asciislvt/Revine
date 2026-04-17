export function createVideoCard(videoId, title, uploader, date) {
  const videoCard = document.createElement('div');
  // const contentDiv = document.createElement('div');
  const thumbnailDiv = document.createElement('div');
  const infoDiv = document.createElement('div');

  const videoLink = document.createElement('a');
  const thumbnailImg = document.createElement('img');
  const titleElement = document.createElement('h3');
  const uploaderElement = document.createElement('p');
  const dateElement = document.createElement('p');

  videoLink.href = `/video.php?id=${videoId}`

  thumbnailImg.src = `/videos/${videoId}/thumbnail.jpg`
  thumbnailImg.onerror = () => {
    thumbnailImg.src = '/images/default-thumbnail.jpg';
  };
  thumbnailImg.alt = `${title} thumbnail`;

  if (title.length > 20) {
    title = title.substring(0, 17) + '...';
  }

  titleElement.textContent = title;
  uploaderElement.textContent = `Uploaded by ${uploader}`;
  dateElement.textContent = `${new Date(date).toLocaleDateString()}`;

  thumbnailDiv.appendChild(thumbnailImg);
  thumbnailDiv.classList.add('card-thumbnail');
  infoDiv.appendChild(titleElement);
  infoDiv.appendChild(uploaderElement);
  infoDiv.appendChild(dateElement);
  infoDiv.classList.add('card-info');

  videoLink.appendChild(thumbnailDiv);
  videoLink.appendChild(infoDiv);
  // videoLink.appendChild(contentDiv);
  videoLink.classList.add('video-link');

  videoCard.appendChild(videoLink);
  videoCard.classList.add('video-card');

  return videoCard;
}
