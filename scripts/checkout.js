// Yeni adres ekleme formu gösterme/gizleme
document
  .getElementById("add-address-btn")
  .addEventListener("click", function () {
    document.getElementById("new-address-form").style.display = "block";
    this.style.display = "none";
  });

document
  .getElementById("cancel-address")
  .addEventListener("click", function () {
    document.getElementById("new-address-form").style.display = "none";
    document.getElementById("add-address-btn").style.display = "block";
  });

// Adres kartları arasında seçim yapma
const addressCards = document.querySelectorAll(".address-card");
addressCards.forEach((card) => {
  card.addEventListener("click", function () {
    // Tüm kartlardan selected class'ını kaldır
    addressCards.forEach((c) => c.classList.remove("selected"));
    // Tıklanan karta selected class'ı ekle
    this.classList.add("selected");
    // İlgili radio butonunu seç
    const radio = this.querySelector('input[type="radio"]');
    radio.checked = true;

    const addressId = card.dataset.addressId;
    console.log(addressId);

    const idInput = document.getElementById("selected-address-id");
    idInput.setAttribute("value", addressId);
  });
});

// Sipariş tamamlama işlemi
document
  .getElementById("complete-order")
  .addEventListener("click", function () {
    // Form validasyonu ve sipariş gönderme işlemleri
    const paymentForm = document.getElementById("payment-form");

    if (paymentForm.checkValidity()) {
      alert("Siparişiniz başarıyla alındı!");
      // Burada gerçek bir sipariş işleme kodu olacak
      // window.location.href = 'siparis-onay.php';
    } else {
      alert("Lütfen ödeme bilgilerinizi kontrol ediniz!");
    }
  });
