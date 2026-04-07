const form = document.getElementById('register-form');
const submitButton = document.getElementById('submit-register');

submitButton.addEventListener('submit', async (event) => {
  event.preventDefault();
  const formData = new FormData(form);
  if (formData.get('password') == '' || formData.get('username') == '') {
    console.error('Username and password cannot be empty');
    return;
  }

  try {
    const response = await fetch('api/reg.php', {
      method: 'POST',
      body: formData
    })

    if (!response.ok) {
      console.error('Failed to register:', response.statusText);
      return;
    } else {
      const result = await response.json();
      window.location.href = 'login.php';
    }

  } catch (err) {
    console.error('Error occurred while registering:', err);
  }
});
