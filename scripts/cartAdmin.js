document.addEventListener("DOMContentLoaded", function () {
    // Tüm arttırma butonlarını seçelim
    const increaseButtons = document.querySelectorAll(".increase");
    // Tüm azaltma butonlarını seçelim
    const decreaseButtons = document.querySelectorAll(".decrease");
    // Tüm kaldır butonlarını seçelim
    const removeButtons = document.querySelectorAll(".remove");
    // Toplam fiyat
    const grandTotal = document.getElementById("grand-total");

    // Arttırma butonları için olay dinleyicisi
    increaseButtons.forEach((button, index) => {
        button.addEventListener("click", function () {
            // Ürün bilgilerini alalım
            const row = this.closest("tr");
            const quantityElement = row.querySelector(".quantity");
            const totalPriceElement = row.querySelector(".total-price");
            const priceElement = row.querySelector('td[data-label="Fiyat"] span');
            const productName = row.querySelector(
                'td[data-label="Ürün"]'
            ).textContent;

            let quantity = parseInt(quantityElement.textContent);
            const price = parseFloat(priceElement.textContent);

            // Miktarı arttıralım
            quantity++;

            // AJAX ile veritabanını güncelleyelim
            updateCartItem(productName, quantity, "increase", function (response) {
                if (response.success) {
                    // Başarılı olduğunda arayüzü güncelleyelim
                    quantityElement.textContent = quantity;
                    const newTotal = quantity * price;
                    totalPriceElement.textContent = newTotal.toFixed(2) + " ₺";

                    // Genel toplamı güncelle
                    updateGrandTotal();
                } else {
                    alert("Güncelleme başarısız: " + response.message);
                }
            });
            location.reload();
        });
    });

    // Azaltma butonları için olay dinleyicisi
    decreaseButtons.forEach((button, index) => {
        button.addEventListener("click", function () {
            // Ürün bilgilerini alalım
            const row = this.closest("tr");
            const quantityElement = row.querySelector(".quantity");
            const totalPriceElement = row.querySelector(".total-price");
            const priceElement = row.querySelector('td[data-label="Fiyat"] span');
            const productName = row.querySelector(
                'td[data-label="Ürün"]'
            ).textContent;

            let quantity = parseInt(quantityElement.textContent);
            const price = parseFloat(priceElement.textContent);

            // Miktar 1'den büyükse azaltalım
            if (quantity > 1) {
                quantity--;

                // AJAX ile veritabanını güncelleyelim
                updateCartItem(productName, quantity, "decrease", function (response) {
                    if (response.success) {
                        // Başarılı olduğunda arayüzü güncelleyelim
                        quantityElement.textContent = quantity;
                        const newTotal = quantity * price;
                        totalPriceElement.textContent = newTotal.toFixed(2) + " ₺";

                        // Genel toplamı güncelle
                        updateGrandTotal();
                    } else {
                        alert("Güncelleme başarısız: " + response.message);
                    }
                });
            }

            location.reload();
        });
    });

    // Kaldır butonları için olay dinleyicisi
    removeButtons.forEach((button, index) => {
        button.addEventListener("click", function () {
            const confirmation = confirm(
                "Bu ürünü sepetten kaldırmak istediğinize emin misiniz?"
            );

            if (confirmation) {
                // Ürün bilgilerini alalım
                const row = this.closest("tr");
                const productName = row.querySelector(
                    'td[data-label="Ürün"]'
                ).textContent;

                // AJAX ile sepetten kaldıralım
                removeCartItem(productName, function (response) {
                    if (response.success) {
                        // Başarılı olduğunda satırı kaldıralım
                        row.remove();

                        // Genel toplamı güncelle
                        updateGrandTotal();

                        // Kullanıcıya bilgi mesajı
                        alert("Ürün sepetten kaldırıldı.");
                    } else {
                        alert("Kaldırma başarısız: " + response.message);
                    }
                });
            }
        });
    });

    // Sepet güncelleme fonksiyonu
    function updateCartItem(productName, quantity, action, callback) {
        // Yükleniyor göstergesi
        const loadingIndicator = document.createElement("div");
        loadingIndicator.className = "loading-indicator";
        loadingIndicator.textContent = "Güncelleniyor...";
        document.body.appendChild(loadingIndicator);

        // FormData nesnesi oluştur
        const formData = new FormData();
        formData.append("product_name", productName);
        formData.append("quantity", quantity);
        formData.append("action", action);

        // AJAX isteği
        fetch("../php/updateCart.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                // Yükleniyor göstergesini kaldır
                document.body.removeChild(loadingIndicator);
                callback(data);
            })
            .catch((error) => {
                // Yükleniyor göstergesini kaldır
                document.body.removeChild(loadingIndicator);
                console.error("Hata:", error);
                callback({
                    success: false,
                    message: "Bir hata oluştu: " + error.message,
                });
            });
    }

    // Sepetten ürün kaldırma fonksiyonu
    function removeCartItem(productName, callback) {
        // Yükleniyor göstergesi
        const loadingIndicator = document.createElement("div");
        loadingIndicator.className = "loading-indicator";
        loadingIndicator.textContent = "Ürün kaldırılıyor...";
        document.body.appendChild(loadingIndicator);

        // FormData nesnesi oluştur
        const formData = new FormData();
        formData.append("product_name", productName);

        // AJAX isteği
        fetch("../php/removeCartItem.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                // Yükleniyor göstergesini kaldır
                document.body.removeChild(loadingIndicator);
                callback(data);
            })
            .catch((error) => {
                // Yükleniyor göstergesini kaldır
                document.body.removeChild(loadingIndicator);
                console.error("Hata:", error);
                callback({
                    success: false,
                    message: "Bir hata oluştu: " + error.message,
                });
            });
    }

    // Genel toplamı güncelleme fonksiyonu
    function updateGrandTotal() {
        const allTotalPrices = document.querySelectorAll(".total-price");
        let sum = 0;

        allTotalPrices.forEach((priceElement) => {
            const priceText = priceElement.textContent.replace(" ₺", "");
            sum += parseFloat(priceText);
        });

        grandTotal.textContent = sum.toFixed(2) + " ₺";

        console.log(allTotalPrices);
        // Eğer sepet boşsa bir mesaj göster
        if (allTotalPrices.length === 0) {
            const cartContainer = document.querySelector(".cart-container");
            const emptyCart = document.createElement("div");
            emptyCart.className = "empty-cart-message";
            emptyCart.innerHTML =
                '<p>Sepetinizde ürün bulunmamaktadır.</p><a href="../index.php" class="continue-shopping">Alışverişe Devam Et</a>';

            // Tabloyu gizle
            const table = document.querySelector("table");
            if (table) {
                table.style.display = "none";
            }

            // Özet bilgisini gizle
            const summary = document.querySelector(".cart-summary");
            if (summary) {
                summary.style.display = "none";
            }

            cartContainer.appendChild(emptyCart);
        }
    }

    updateGrandTotal();
});
