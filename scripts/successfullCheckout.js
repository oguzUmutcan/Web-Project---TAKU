// Rastgele sipariş numarası oluşturma
window.onload = function () {
  const orderNumber = "SP" + Math.floor(10000000 + Math.random() * 90000000);
  document.querySelector(".order-number").textContent =
    "Sipariş Numarası: #" + orderNumber;

  // Bugünden 3-5 gün sonra için tarih hesaplama
  const today = new Date();
  const deliveryStart = new Date(today);
  deliveryStart.setDate(today.getDate() + 4);
  const deliveryEnd = new Date(today);
  deliveryEnd.setDate(today.getDate() + 6);

  // Tarih formatını ayarlama
  const options = {
    day: "numeric",
    month: "long",
  };
  const startDate = deliveryStart.toLocaleDateString("tr-TR", options);
  const endDate = deliveryEnd.toLocaleDateString("tr-TR", options);

  document.querySelector(".detail-row:nth-child(2) .detail-value").textContent =
    startDate + " - " + endDate;
};
