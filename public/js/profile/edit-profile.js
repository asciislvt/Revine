const editButton = document.getElementById('edit-profile-button');
const saveButton = document.getElementById('save-edit-button');
const cancelButton = document.getElementById('cancel-edit-button');
const imageInput = document.getElementById('profile-picture-input');
const imagePreview = document.getElementById('profile-picture-preview');
const profileForm = document.getElementById('edit-profile-form');

editButton.addEventListener('click', () => {
  console.log('Edit profile button clicked');
  profileForm.style.display = 'block';
});

cancelButton.addEventListener('click', () => {
  profileForm.style.display = 'none';
});

imageInput.addEventListener('change', () => {
  imagePreview.src = URL.createObjectURL(imageInput.files[0]);
});
