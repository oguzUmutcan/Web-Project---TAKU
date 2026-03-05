// Burada inputlara tıklandığında label'ların kaybolmasını sağladım
document.querySelectorAll(".input-box input").forEach((input) => {
  input.addEventListener("input", () => {
    const label = input.nextElementSibling;
    label.style.display = input.value.trim() ? "none" : "block";
  });
});

// Email doğrulama
const emailInput = document.getElementById("email");
const emailError = document.getElementById("email-error");

emailInput.addEventListener("input", () => {
  const emailValue = emailInput.value;
  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

  if (emailPattern.test(emailValue)) {
    emailError.textContent = "Geçerli";
    emailError.style.color = "green";
  } else {
    emailError.textContent = "Geçersiz email adresi";
    emailError.style.color = "red";
  }
});

// Şifre doğrulama
const passwordInput = document.getElementById("password");
const passwordError = document.getElementById("password-error");

passwordInput.addEventListener("input", () => {
  const passwordValue = passwordInput.value;

  if (passwordValue.length >= 6) {
    passwordError.textContent = "Geçerli";
    passwordError.style.color = "green";
  } else {
    passwordError.textContent = "Şifre en az 6 karakter olmalı";
    passwordError.style.color = "red";
  }
});

// Şifre tekrar doğrulama
const confirmPasswordInput = document.getElementById("confirm-password");
const confirmPasswordError = document.getElementById("confirm-password-error");

confirmPasswordInput.addEventListener("input", () => {
  const passwordValue = passwordInput.value;
  const confirmPasswordValue = confirmPasswordInput.value;

  if (confirmPasswordValue === passwordValue) {
    confirmPasswordError.textContent = "Geçerli";
    confirmPasswordError.style.color = "green";
  } else {
    confirmPasswordError.textContent = "Şifreler uyuşmuyor";
    confirmPasswordError.style.color = "red";
  }
});
