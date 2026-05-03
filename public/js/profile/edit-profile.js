const editButton = document.getElementById('edit-profile-button');
const saveButton = document.getElementById('save-edit-button');
const cancelButton = document.getElementById('cancel-edit-button');
const imageInput = document.getElementById('profile-picture-input');
const imageCropper = document.getElementById('img-cropper');
const profileForm = document.getElementById('edit-profile-form');

let croppieInstance;

editButton.addEventListener('click', () => {
  console.log('Edit profile button clicked');
  profileForm.style.display = 'block';
});

cancelButton.addEventListener('click', () => {
  profileForm.style.display = 'none';
});

imageInput.addEventListener('change', () => {
  if (croppieInstance) {
    croppieInstance.destroy();
    croppieInstance = null;
  }

  const urlImage = URL.createObjectURL(imageInput.files[0]);

  if (urlImage) {
    const croppie = new Croppie(imageCropper, {
      viewport: {
        width: 320,
        height: 320,
        type: 'circle'
      },
      boundary: {
        width: 400,
        height: 400
      },
      showZoomer: true,
      enableResize: false,
    });

    croppie.bind({
      url: urlImage,
      points: [0, 0, 200, 200]
    });

    croppieInstance = croppie;
  }

  imageCropper.style.display = 'block';
});

profileForm.addEventListener('submit', async (event) => {
  event.preventDefault();
  const formData = new FormData(profileForm);
  const newPfp = await croppieInstance.result({
    type: 'blob',
    size: 'viewport',
    format: 'jpeg',
    quality: 1,
    circle: false
  });
  formData.append('profile_picture', newPfp, 'profile.jpg');

  try {
    const response = await fetch('api/post-profile-edits.php', {
      method: 'POST',
      body: formData
    });

    if (response.ok) {
      window.location.reload();
    }
  } catch (error) {
    console.error('Error updating profile:', error);
  }
});
