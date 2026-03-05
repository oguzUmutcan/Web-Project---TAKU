<?php
require '../php/dbConnection.php';

$id = $_GET['id'];

$sql = "
  SELECT 
    p.id,
    p.name,
    p.price,
    d.width_cm, d.height_cm, d.depth_cm, d.description, d.color, d.material, d.warranty_months,
    img.image_path
  FROM products p
    JOIN product_details d ON d.product_id = p.id
    JOIN product_images img  ON img.product_id = p.id
  WHERE p.id = $id
";
$result = $conn->query($sql);
if (!$result) {
    die('Sorgu hatası: ' . $conn->error);
}

$stmt = $conn->prepare("
    SELECT product_id FROM favorites
    WHERE user_id = ? && product_id = ?
");

$stmt->bind_param("ii", $_SESSION['user_id'], $id);

$stmt->execute();

$result1 = $stmt->get_result();

$num_rows  = mysqli_num_rows($result1);

$isFav = false;

if ($num_rows > 0) {
    $isFav = true;
}


if ($isFav) {
    $colorHeart = "#ff0000";
} else {
    $colorHeart = "#a49999";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAKU - Mobilya & Dekor</title>
    <link rel="stylesheet" href="/WEB - 12/Style/productDetail.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="/WEB - 12/Images/TAKU (2).png"
        type="image/x-icon" />
</head>

<body>
    <header>
        <nav>
            <ul class="nav-links">
                <li><a href="/WEB - 12/index.php">Ana Sayfa</a></li>
                <li><a href="/WEB - 12/index/about.html">Hakkında</a></li>
                <li><a href="/WEB - 12/promotions/promotion.html">Kampanyalar</a></li>
            </ul>
            <div class="search-bar">
                <input type="text" placeholder="Ürün ara..." />
                <a href="">
                    <i class="fa-solid fa-magnifying-glass fa-rotate-90" style="color: #ffffff"></i>
                </a>
            </div>
            <div class="menu">
                <a href="/WEB - 12../adminPage/cartAdmin.php">
                    <i class="fa-solid fa-basket-shopping fa-xl" style="color: #ffffff"></i>
                </a>
                <a href="../index/favorites.php" style="margin-left: 15px;">
                    <i class="fa-solid fa-heart fa-xl" style="color: #ff0000;"></i>
                </a>
                <a href="">
                    <i class="fa-regular fa-user fa-xl profile-icon" style="color: #ffffff"></i>
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

    <main class="product-detail">
        <?php
        while ($row = $result->fetch_assoc()):
            $id = $row['id'];
            $name      = htmlspecialchars($row['name']);
            $price     = number_format($row['price'], 2, ',', '.');
            $imgPath   = htmlspecialchars($row['image_path']);
            $description   = htmlspecialchars($row['description']);
            $color   = htmlspecialchars($row['color']);
            $material   = htmlspecialchars($row['material']);
            $height_cm   = round(htmlspecialchars($row['height_cm']));
            $width_cm   = round(htmlspecialchars($row['width_cm']));
            $depth_cm   = round(htmlspecialchars($row['depth_cm']));
            $warranty_months = htmlspecialchars($row['warranty_months']);
        ?>
            <div class="product-image">
                <img src="<?= "../php/" . $imgPath ?>" alt="">
            </div>
            <div class="product-info">
                <h1><?= $name ?></h1>
                <p class="price">
                    <span class="new-price"><?= $price ?></span>
                </p>
                <p class="description">
                    <?= $description ?>
                </p>
                <ul class="features">
                    <li>Renk: <?= $color ?></li>
                    <li>Malzeme: <?= $material ?></li>
                    <li>Boyut: <?= ($height_cm . "x" . $width_cm) . ($depth_cm == 0 ? "" : ("x" . $depth_cm)) . "cm" ?></li>
                    <li><?= $warranty_months ?> Ay Garanti</li>
                </ul>
                <a href="../php/addToCard.php?nowID=<?= $id ?>">
                    <button class="buy-now">Hemen Satın Al</button>
                </a>

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
            </div>
        <?php endwhile; ?>
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
                <a href="/WEB - 12/index/about.html" id="about">Hakkımızda</a>
                <a href="/WEB - 12/index/privacy.html">Gizlilik Politikası</a>
                <a href="/WEB - 12/index/TermsOfUse.html">Kullanım Şartları</a>
            </div>
        </div>
        <br>
        <p class="small-text"><img src="" alt=""> © TAKU 2024 tüm hakları saklıdır.</p>
    </footer>

    <script src="../scripts/productDetails.js"></script>
</body>

</html>