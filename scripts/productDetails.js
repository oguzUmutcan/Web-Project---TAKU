document.addEventListener("DOMContentLoaded", function () {
  const profileIcon = document.querySelector(".profile-icon");
  const dropdownMenu = document.getElementById("profileDropdown");

  profileIcon.addEventListener("click", function (e) {
    e.preventDefault();
    dropdownMenu.style.display =
      dropdownMenu.style.display === "block" ? "none" : "block";
  });

  // Menü dışında bir yere tıklanınca menüyü kapat
  document.addEventListener("click", function (e) {
    if (!profileIcon.contains(e.target) && !dropdownMenu.contains(e.target)) {
      dropdownMenu.style.display = "none";
    }
  });
});
  
document.addEventListener("DOMContentLoaded", function () {
  console.log("Favori script yüklendi");

  // Tüm favori butonlarını seç
  const favoriteButtons = document.querySelectorAll(".add-favorite-btn");
  console.log("Bulunan favori butonları:", favoriteButtons.length);

  // Her butona tıklama olayı ekle
  favoriteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault(); // Sayfanın yeniden yüklenmesini engelle

      const productId = this.getAttribute("data-product-id");
      console.log("Tıklanan ürün ID:", productId);

      if (!productId || productId === "0" || productId === 0) {
        console.error("Geçersiz ürün ID:", productId);
        alert("Ürün ID bilgisi geçersiz!");
        return;
      }

      const isFavorited = this.getAttribute("data-favorited") === "true";
      console.log("Favori durumu:", isFavorited);

      const heartIcon = this.querySelector("i.fa-heart");

      // AJAX isteği oluştur
      const xhr = new XMLHttpRequest();

      if (isFavorited) {
        // Favorilerden kaldır
        xhr.open("POST", "../php/removeFavorites.php", true);
      } else {
        // Favorilere ekle
        xhr.open("POST", "../php/addFavorites.php", true);
      }

      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onload = function () {
        if (xhr.status === 200) {
          try {
            // Dönen yanıtı konsola yazdır
            console.log("Raw response:", xhr.responseText);

            const response = JSON.parse(xhr.responseText);

            if (response.success) {
              if (isFavorited) {
                // Favorilerden kaldırıldı, kalbi gri yap
                heartIcon.style.color = "#a49999";
                button.setAttribute("data-favorited", "false");
                console.log("Favorilerden kaldırıldı");
              } else {
                // Favorilere eklendi, kalbi kırmızı yap
                heartIcon.style.color = "#ff0000";
                button.setAttribute("data-favorited", "true");
                console.log("Favorilere eklendi");
              }
              // İsteğe bağlı başarı mesajı
              console.log("İşlem başarılı:", response.message);
            } else {
              console.error("İşlem başarısız:", response.message);
              // Hata mesajını göster
              alert(response.message);
              // Kullanıcı giriş yapmadıysa veya başka bir hata varsa
              if (response.redirect) {
                window.location.href = response.redirect;
              }
            }
          } catch (e) {
            // JSON ayrıştırma hatası durumunda
            console.error("JSON ayrıştırma hatası:", e);
            console.error("Alınan ham yanıt:", xhr.responseText);
            alert("Bir hata oluştu. Lütfen konsolu kontrol edin.");
          }
        } else {
          console.error("HTTP Hata Kodu:", xhr.status);
        }
      };

      // Veriyi gönder
      xhr.send("product_id=" + encodeURIComponent(productId));
    });
  });

  const input = document.getElementById("searchInput");
  const link = document.getElementById("searchLink");

  link.addEventListener("click", function (e) {
    e.preventDefault(); // normal <a> davranışını iptal et
    const query = encodeURIComponent(input.value.trim());
    if (!query) return; // boşsa hiçbir şey yapma
    window.location.href = `../index/search_result.php?search=${query}`;
  });
});
