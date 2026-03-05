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
  emailInput.innerHTML = "";
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
const passwordInput1 = document.getElementById("password1");
const passwordError = document.getElementById("password-error");
const passwordError1 = document.getElementById("password-error1");
const passwordError2 = document.getElementById("password-error2");
let button = document.getElementById("button");

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
passwordInput1.addEventListener("input", () => {
  const passwordValue = passwordInput.value;
  const passwordValue1 = passwordInput1.value;
  if (passwordValue1 !== passwordValue) {
    passwordError2.textContent = "Şifreler eşleşmiyor";
    passwordError2.style.color = "red";
  }
  if (passwordValue1.length <= 6) {
    passwordError1.textContent = "Şifre en az 6 karakter olmalı";
    passwordError1.style.color = "red";
  }
  if (passwordValue1.length >= 6) {
    passwordError1.textContent = "Geçerli";
    passwordError1.style.color = "green";
  }
  if (passwordValue1 === passwordValue) {
    passwordError2.textContent = "";
  }
  if (passwordValue1 === passwordValue && passwordValue1.length >= 6) {
    button.removeAttribute("disabled");
    button.setAttribute("active", "");
  }
});
