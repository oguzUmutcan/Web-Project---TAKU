<?php
require "../php/dbConnection.php";

$stmt = $conn->prepare("
  SELECT
  p.id AS product_id,
  p.name AS product_name, 
  sc.name AS subcategory_name, 
  p.price AS price, 
  pi.image_path AS image 
  FROM favorites f 
  JOIN products p ON f.product_id = p.id 
  JOIN users u ON f.user_id = u.id
  JOIN subcategories sc ON p.subcategory_id = sc.id
  JOIN product_images pi ON p.id = pi.product_id
  WHERE user_id = ?
");

$stmt->bind_param("i", $_SESSION['user_id']);

$stmt->execute();

$result = $stmt->get_result();
//$length = count($row);

$row_number = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Favoriler</title>
  <link rel="stylesheet" href="../Style/categories.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="icon" href="../Images/TAKU (2).png" type="image/x-icon" />
  <link rel="stylesheet" href="../Style/favorites.css">
</head>

<body>
  <header>
    <div class="logo">
      <img src="../Images/TAKU (2).png" alt="">
    </div>
    <nav>
      <ul>
        <li><a href="../index.php">Ana Sayfa</a></li>
        <li><a href="../index/about.html">Hakkında</a></li>
        <li><a href="../promotions/promotion.html">Kampanyalar</a></li>
      </ul>
      <div class="search-bar">
        <input type="text" placeholder="Ürün ara..." />
        <a href="">
          <i class="fa-solid fa-magnifying-glass fa-rotate-90" style="color: #ffffff"></i>
        </a>
      </div>
      <div class="menu">
        <a href="../index/favorites.php" style="margin-right: 15px;">
          <i class="fa-solid fa-heart fa-xl" style="color: #ff0000;"></i>
        </a>
        <a href="../adminPage/cartAdmin.php">
          <i class="fa-solid fa-basket-shopping fa-xl" style="color: #ffffff"></i>
        </a>
        <a href="">
          <i class="fa-regular fa-user fa-xl profile-icon" style="color: #ffffff"></i>
        </a>
        <div class="dropdown-menu" id="profileDropdown">
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="../adminPage/customerAdmin.php">Ayarlar</a>
            <a href="../php/logout.php">Çıkış Yap</a>
          <?php else: ?>
            <a href="./userOperations/userLogin.html">Giriş Yap</a>
            <a href="./userOperations/userSignUp.php">Kayıt Ol</a>
            <a href="#logout" style="color: #ccc" id="inactive">Çıkış Yap</a>
          <?php endif; ?>
        </div>
      </div>
    </nav>
  </header>
  <main>
    <h1>Favoriler</h1>

    <?php if (!isset($_SESSION['user_id'])): ?>

      <div id="login-alert" class="alert alert-warning" style="padding: 1rem; border: 1px solid #f0ad4e; border-radius: 0.25rem; background-color: #fcf8e3; color: #8a6d3b; margin: 1rem 0;">
        Favorilere görüntülemek için lütfen <a href="../userOperations/userLogin.html" style="color: #8a6d3b; text-decoration: underline;">giriş yapın</a>.
      </div>

    <?php else: ?>

      <div class="favorites-summary">
        <div class="favorites-count"><?= $row_number ?> ürün</div>
        <button class="clear-favorites">
          <a href="../php/removeFavorites.php?id=-1"><i class="fa-solid fa-trash-can"></i> Tümünü Temizle</a>
        </button>
      </div>
      <?php if ($row_number > 0):?>
        <div class="favorites-container">
          <?php while ($row = $result->fetch_assoc()):
            $product_id = $row['product_id'];
            $productName = $row['product_name'];
            $subCategoryName = $row['subcategory_name'];
            $price = $row['price'];
            $image = $row['image']; ?>

            <?php
            echo "<div class='favorite-item'>";
            echo  "<div class='favorite-image'>";
            echo    "<img src='../php/$image' alt='Modern Koltuk'>";
            echo    "<div class='favorite-actions'>";
            echo      "<button class='remove-favorite'>";
            echo        "<a href='../php/removeFavoritesInPage.php?id=$product_id'><i class='fa-solid fa-heart' style='color: #ff0000;'></i></a>";
            echo        "</button>";
            echo      "<button class='quick-view'>";
            echo        "<a href='../products/product_detail.php?id=$product_id'><i class='fa-solid fa-eye'></i></a>";
            echo        "</button>";
            echo      "</div>";
            echo    "</div>";
            echo  "<div class='favorite-details'>";
            echo    "<h3 class='favorite-title'>$productName </h3>";
            echo    "<div class='favorite-category'>$subCategoryName </div>";
            echo    "<div class='favorite-price'>₺$price </div>";
            echo    "<div class='favorite-actions-bottom'>";
            echo      "<button class='add-to-cart'>
                        <a href='../php/addToCard.php?id=$product_id'>Sepete Ekle</a>
                      </button>";
            echo      "</div>";
            echo    "</div>";
            echo  "</div>" ?>
          <?php endwhile; ?>
        </div>
      <?php else:?>
        <div class="empty-favorites">
          <i class="fa-regular fa-heart"></i>
          <p>Henüz favori ürününüz bulunmamaktadır.</p>
          <a href="../index.php" class="browse-products">Ürünleri Keşfedin</a>
        </div>
      <?php endif;?>
    <?php endif; ?>
  </main>

  <footer>
    <div class="footer-container">
      <div class="footer-social">
        <a href="https://facebook.com" target="_blank">
          <i class="fa-brands fa-facebook fa-xl"></i>
        </a>
        <a href="https://twitter.com" target="_blank">
          <i class="fa-brands fa-x-twitter fa-xl"></i>
        </a>
        <a href="https://www.instagram.com/kadir.gndz13/" target="_blank">
          <i class="fa-brands fa-instagram fa-xl"></i>
        </a>
      </div>
      <div class="footer-contact">
        <p>İletişim: <a href="mailto:info@TAKU.com">info@Taku.com</a></p>
        <br>
        <p>Telefon: <a href="tel:+90 (531) 401 9965">+90 (531) 401 9965</a></p>
      </div>
      <div class="footer-links">
        <a href="/index/about.html" id="about">Hakkımızda</a>
        <a href="/index/privacy.html">Gizlilik Politikası</a>
        <a href="/index/TermsOfUse.html">Kullanım Şartları</a>
      </div>
    </div>
    <br>
    <p class="small-text"><img src="" alt=""> © TAKU 2024 tüm hakları saklıdır.</p>
  </footer>

  <script src="../scripts/dropdown.js">
  </script>
</body>

</html>