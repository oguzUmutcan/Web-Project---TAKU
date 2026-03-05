<?php
//header('Content-Type: application/json');

require '../php/dbConnection.php';

// Örnek: user_id = 1 olan kullanıcının sepeti (geliştirilebilir)

$sql = "
SELECT 
    p.name,
    p.price,
    ci.quantity,
    img.image_path
FROM cart_items ci
JOIN carts c ON ci.cart_id = c.id 
JOIN products p ON ci.product_id = p.id
JOIN product_images img ON p.id = img.product_id
WHERE c.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

$result = $stmt->get_result();

$conn->close();

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alışveriş Sepeti</title>
    <link rel="stylesheet" href="../Style/cartAdmin.css">
    <link rel="icon" href="../Images/TAKU (2).png" type="image/x-icon">
</head>

<body>
    <div class="cart-container">
        <h1>Alışveriş Sepetiniz</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <table>
                <thead>
                    <tr>
                        <th style='width:25%'>Resim</th>
                        <th>Ürün</th>
                        <th>Fiyat</th>
                        <th>Miktar</th>
                        <th>Toplam</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        $count = 0;

                        while ($row = $result->fetch_assoc()):
                            $name = $row['name'];
                            $price = $row['price'];
                            $quantity = $row['quantity'];
                            $image = "../php/" . $row['image_path'];

                            $prices[$count] = $price * $quantity;
                        ?>
                            <td><img src="<?= $image ?>" alt="" style='width:100%; float:left; border-radius:10px'></td>
                            <td data-label="Ürün" style="width: 30%;"><?= $name ?></td>
                            <td data-label="Fiyat"><span><?= $price ?> ₺</span></td>
                            <td data-label="Miktar">
                                <button class="decrease">-</button>
                                <span class="quantity"><?= $quantity ?></span>
                                <button class="increase">+</button>
                            </td>
                            <td data-label="Toplam" class="total-price"><?= $prices[$count] ?> ₺</td>
                            <td data-label="İşlemler">
                                <button class="remove">Kaldır</button>
                            </td>
                    </tr>
                <?php $count++;
                        endwhile; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <p>Toplam Tutar: <span id="grand-total"><?php if (isset($prices)) echo array_sum($prices); ?> ₺</span></p>
                <a href="../adminPage/checkout.php">
                    <button class="checkout">Satın Al</button>
                </a>
            </div>
    </div>
<?php else: ?>
    <div id="login-alert" class="alert alert-warning" style="padding: 1rem; border: 1px solid #f0ad4e; border-radius: 0.25rem; background-color: #fcf8e3; color: #8a6d3b; margin: 1rem 0;">
        Sepetinizi görüntülemek için lütfen <a href="../userOperations/userLogin.html" style="color: #8a6d3b; text-decoration: underline;">giriş yapın</a>.
    </div>
<?php endif; ?>

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
            <a href="index/about.html" id="about">Hakkımızda</a>
            <a href="index/privacy.html">Gizlilik Politikası</a>
            <a href="index/termsOfUse.html">Kullanım Şartları</a>
        </div>
    </div>
    <br>
    <p class="small-text"><img src="" alt=""> © TAKU 2024 tüm hakları saklıdır.</p>
</footer>

<script src="../scripts/cartAdmin.js">

</script>

<?php if (isset($prices)) $_SESSION['grand-total'] = array_sum($prices) ?>
</body>

</html>