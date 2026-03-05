document.querySelectorAll(".btn-toggle").forEach((btn) => {
  btn.addEventListener("click", () => {
    const id = btn.dataset.target;
    const row = document.getElementById(id);
    row.style.display =
      row.style.display === "table-row" ? "none" : "table-row";
  });
});

function displayDiv() {
  let div = document.getElementById("addAddress");
  let button = document.getElementById("saveButton");
  let addressDiv = document.getElementById("addressDiv");
  let myAddresses = document.getElementById("myAddresses");

  myAddresses.innerText = "Adres Kaydet";
  addressDiv.style.display = "none";
  div.style.display = "block";
  button.style.display = "none";
}

function displayUpdate(button) {
  button.style.display = "none";

  // 1) Butona en yakın .address-item konteynerini bul
  const container = button.closest(".address-item");
  if (!container) return;

  // 2) O konteyner içindeki görüntüleme div ve formu seç
  const list = container.querySelector(".addressList");
  const form = container.querySelector(".updateAddress");

  // 3) Toggle / show-hide
  if (list && form) {
    list.style.display = "none";
    form.style.display = "block";
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const msgDiv = document.getElementById("message");
  if (!msgDiv) return; // div yoksa çık

  // 3 saniye göster, sonra gizle
  setTimeout(() => {
    msgDiv.style.display = "none";
  }, 3000);
});
