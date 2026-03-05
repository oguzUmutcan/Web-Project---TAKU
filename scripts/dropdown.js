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

const input = document.getElementById("searchInput");
const link = document.getElementById("searchLink");

link.addEventListener("click", function (e) {
  e.preventDefault(); // normal <a> davranışını iptal et
  const query = encodeURIComponent(input.value.trim());
  if (!query) return; // boşsa hiçbir şey yapma
  window.location.href = `./index/search_result.php?search=${query}`;
});

function displayProducts(str) {
  document.getElementById("products").style.display = "block";
  if(str.length === 0){
    document.getElementById("products").style.display = "none";
  }
  else{
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
      if(xhr.readyState === 4 && xhr.status === 200){
        console.log(typeof(xhr.responseText));
        document.getElementById("products").innerHTML = xhr.responseText;
      }
    }
    xhr.open("GET", "./php/displayProducts.php?str=" + str, true);
    xhr.send();
  }
}
