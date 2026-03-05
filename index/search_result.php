<?php
require '../php/dbConnection.php';

$term = $_GET['search'];

$sql = "
  SELECT 
    p.id,
    p.name,
    p.price,
    d.width_cm, d.height_cm, d.depth_cm,  /* gerekirse detaylar */
    img.image_path
  FROM products p
  LEFT JOIN product_details d ON d.product_id = p.id
  LEFT JOIN product_images img  ON img.product_id = p.id
  WHERE p.name LIKE '%$term%'
  ORDER BY p.id ASC
";
$result = $conn->query($sql);
if (!$result) {
    die('Sorgu hatası: ' . $conn->error);
}

$stmt = $conn->prepare("
    SELECT product_id FROM favorites
    WHERE user_id = ?
");

$stmt->bind_param("i", $_SESSION['user_id']);

$stmt->execute();

$result1 = $stmt->get_result();

$num_rows  = mysqli_num_rows($result1);
$isFav = false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAKU - Ev & Dekor</title>

    <link rel="stylesheet" href="../Style/categories.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" href="../Images/TAKU (2).png"
        type="image/x-icon" />

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
          <input type="text" placeholder="Ürün ara..." id="searchInput"/>
          <a href="#" id="searchLink">
            <i
              class="fa-solid fa-magnifying-glass fa-rotate-90"
              style="color: #ffffff"></i>
          </a>
        </div>

            <div class="menu">
                <a href="../index/favorites.php" style="margin-right: 15px;">
                    <i class="fa-solid fa-heart fa-xl" style="color: #ff0000;"></i>
                </a>
                <a href="../adminPage/cartAdmin.php">
                    <i
                        class="fa-solid fa-basket-shopping fa-xl"
                        style="color: #ffffff"></i>
                </a>

                <a href="">
                    <i
                        class="fa-regular fa-user fa-xl profile-icon"
                        style="color: #ffffff"></i>
                </a>
                <div class="dropdown-menu" id="profileDropdown">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="../adminPage/customerAdmin.php">Ayarlar</a>
                        <a href="../php/logout.php">Çıkış Yap</a>
                    <?php else: ?>
                        <a href="../userOperations/userLogin.html">Giriş Yap</a>
                        <a href="../userOperations/userSignUp.php">Kayıt Ol</a>
                        <a href="#logout" style="color: #ccc" id="inactive">Çıkış Yap</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="content">
            <?php

            $count = 0;
            while ($row = $result->fetch_assoc()): ?>
                <?php
                // Kısa değişkenler
                $id        = $row['id'];
                $name      = htmlspecialchars($row['name']);
                $price     = number_format($row['price'], 2, ',', '.');
                $imgPath   = htmlspecialchars($row['image_path']);
                // Ürün detayı sayfasına link
                $detailUrl = "../products/product_detail.php?id={$id}";
                ?>

                <?php
                if ($count % 4 == 0) {
                    echo "<div class='products'>";
                }
                ?>

                <div>
                    <a href="<?= $detailUrl ?>">
                        <img src="<?= "../php/" . $imgPath ?>" alt="<?= $name ?>">
                        <p class="product-name"><?= $name ?></p>
                    </a>
                    <p>
                        <span><?= $price ?> ₺</span>
                    </p>
                    <p>
                        <a href="../php/addToCard.php?id=<?= $id ?>">
                            <i class="fa-solid fa-basket-shopping fa-xl" style="color: #BA9160"></i>
                        </a>
                        <?php if ($num_rows > 0): ?>
                            <?php $isFav = false;
                            $result1->data_seek(0); ?>
                            <?php while ($row1 = $result1->fetch_assoc()) {
                                $idInFavs = $row1['product_id'];

                                if ($idInFavs == $id) {
                                    $isFav = true;
                                    break;
                                }
                            }
                            ?>
                            <?php if ($isFav): ?>
                                <a href="#"
                                    class="add-favorite-btn"
                                    data-product-id="<?= $id ?>"
                                    data-favorited="true">
                                    <i class="fa-solid fa-heart fa-xl" style="color: #ff0000;"></i>
                                </a>
                            <?php else: ?>
                                <a href="#"
                                    class="add-favorite-btn"
                                    data-product-id="<?= $id ?>"
                                    data-favorited="false">
                                    <i class="fa-solid fa-heart fa-xl" style="color: #a49999;"></i>
                                </a>
                            <?php endif; ?>

                        <?php else: ?>
                            <a href="#"
                                class="add-favorite-btn"
                                data-product-id="<?= $id ?>"
                                data-favorited="false">
                                <i class="fa-solid fa-heart fa-xl" style="color: #a49999;"></i>
                            </a>
                        <?php endif; ?>
                    </p>
                </div>
                <?php
                $count++;
                if ($count % 4 == 0) {
                    echo "</div>";
                };
                ?>
            <?php endwhile; ?>
        </div>
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
                <a href="../index/about.html" id="about">Hakkımızda</a>
                <a href="../index/privacy.html">Gizlilik Politikası</a>
                <a href="../index/TermsOfUse.html">Kullanım Şartları</a>
            </div>
        </div>
        <br>
        <p class="small-text"><img src="" alt=""> © TAKU 2024 tüm hakları saklıdır.</p>
    </footer>

    <script src="../scripts/products.js">
    </script>
</body>

</html>