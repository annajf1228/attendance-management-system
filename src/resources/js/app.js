import './bootstrap';

const togglePassword = (inputId, imgElement) => {
  const passwordField = document.getElementById(inputId);
  const openEyeSrc = '/images/open-eye.svg';
  const closeEyeSrc = '/images/close-eye.svg';

  if (passwordField.type === "password") {
      passwordField.type = "text";
      imgElement.src = openEyeSrc;
  } else {
      passwordField.type = "password";
      imgElement.src = closeEyeSrc;
  }
}
window.togglePassword = togglePassword;
