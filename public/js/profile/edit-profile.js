const editButton = document.getElementById('edit-profile-button');
const saveButton = document.getElementById('save-edit-button');
const cancelButton = document.getElementById('cancel-edit-button');
const imageInput = document.getElementById('profile-picture-input');
const imageCropper = document.getElementById('img-cropper');
const profileForm = document.getElementById('edit-profile-form');
const editProfileContainer = document.getElementById('edit-profile-container');

let croppieInstance;

editButton.addEventListener('click', () => {
  console.log('Edit profile button clicked');
  editProfileContainer.style.display = 'flex';
  editProfileContainer.scrollIntoView({ behavior: 'smooth' });
  editProfileContainer.classList.add('slide-in-top');
});

cancelButton.addEventListener('click', () => {
  editProfileContainer.classList.remove('slide-in-top');
  editProfileContainer.classList.add('slide-out-top');
  editProfileContainer.addEventListener('animationend', () => {
    editProfileContainer.style.display = 'none';
    editProfileContainer.classList.remove('slide-out-top');
  }, { once: true });
  croppieInstance?.destroy();
  croppieInstance = null;
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
  let newPfp;
  if (croppieInstance) {
    newPfp = await croppieInstance.result({
      type: 'blob',
      size: 'viewport',
      format: 'jpeg',
      quality: 1,
      circle: false
    });
  } else {
    newPfp = null;
  }

  if (newPfp) {
    formData.append('profile_picture', newPfp, 'profile.jpg');
  }

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
