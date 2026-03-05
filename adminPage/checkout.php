<?php
require "../php/dbConnection.php";

$stmt = $conn->prepare("
    SELECT * FROM addresses
    WHERE user_id = ?
");

$stmt->bind_param("i", $_SESSION['user_id']);

$stmt->execute();

$result = $stmt->get_result();

$length = mysqli_num_rows($result);

$stmt1 = $conn->prepare("
    SELECT c.id AS cart_id, name, price * quantity AS total, quantity, image_path FROM carts c JOIN cart_items ci
    ON c.id = ci.cart_id JOIN products p
    ON ci.product_id = p.id JOIN product_images pi 
    ON pi.product_id = p.id 
    WHERE c.user_id = ?
");

$stmt1->bind_param("i", $_SESSION['user_id']);

$stmt1->execute();

$result1 = $stmt1->get_result();

$grand_total = $_SESSION['grand-total'];

$errorMessage = isset($_SESSION['errorMessageCheckout']) ? $_SESSION['errorMessageCheckout'] : null;

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Sayfası</title>
    <link rel="stylesheet" href="../style/checkout.css">
    <link rel="icon" href="../Images/TAKU (2).png" type="image/x-icon">
</head>

<body>
    <div class="cart-container">
        <h1>Ödeme Bilgileri</h1>

        <div class="checkout-container">
            <!-- Sol taraf - Adres ve Ödeme Bilgileri -->
            <div class="checkout-details">
                <!-- Adres Seçimi Bölümü -->
                <div class="checkout-section address-section">
                    <h2>Teslimat Adresi</h2>

                    <div class="saved-addresses">
                        <?php $count = 1;
                        while ($row = $result->fetch_assoc()):
                            $id = $row['id'];
                            $title = $row['title'];
                            $address_line1 = $row['address_line1'];
                            $address_line2 = $row['address_line2'];
                            $city = $row['city'];
                            $state = $row['state'];
                            $postal_code = $row['postal_code'];
                            $country = $row['country'];
                        ?>

                            <!-- Kayıtlı adresler burada listelenecek -->
                            <div class="address-card <?php if ($count == $length) echo "selected"; ?>" data-address-id="<?= $id ?>">
                                <div class="address-header">
                                    <input type="radio" name="address" id="address1" checked>
                                    <label for="address1"><?= $title ?></label>
                                </div>
                                <div class="address-content">
                                    <p><?= $address_line1 ?></p>
                                    <p><?= $address_line2 ?></p>
                                    <p><?= $state ?> / <?= $city ?> <?= $country ?></p>
                                    <p><?= $postal_code ?></p>
                                </div>
                            </div>

                            <!-- <div class="address-card">
                            <div class="address-header">
                                <input type="radio" name="address" id="address2">
                                <label for="address2">İş Adresim</label>
                            </div>
                            <div class="address-content">
                                <p>Ahmet Yılmaz</p>
                                <p>Merkez İş Merkezi Kat: 3 No: 301</p>
                                <p>Şişli / İstanbul</p>
                                <p>05XX XXX XX XX</p>
                            </div>
                        </div> -->
                        <?php $count++;
                        endwhile;
                        $stmt->close(); ?>
                    </div>

                    <button id="add-address-btn" class="secondary-button">Yeni Adres Ekle</button>

                    <!-- Yeni Adres Ekleme Formu (başlangıçta gizli) -->
                    <div id="new-address-form" style="display: none;">
                        <h3>Yeni Adres Ekle</h3>
                        <form>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="address-title">Adres Başlığı</label>
                                    <input type="text" id="address-title" name="title" placeholder="Ev, İş vb." required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address-line">Adres Satırı 1</label>
                                <textarea id="address-line" name="address_line1" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="address-line">Adres Satırı 2</label>
                                <textarea id="address-line" name="address_line2" rows="3" required></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">İl</label>
                                    <input id="city" name="city" required>
                                </div>
                                <div class="form-group">
                                    <label for="district">İlçe</label>
                                    <input id="district" name="state" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="country">Ülke</label>
                                    <input id="country" name="country" required>
                                </div>
                                <div class="form-group">
                                    <label for="zipCode">Posta Kodu</label>
                                    <input id="zipCode" name="postal_code" required>
                                </div>
                            </div>

                            <div class="form-buttons">
                                <button type="button" id="cancel-address" class="cancel-button">İptal</button>
                                <button type="submit" class="save-button">Kaydet</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Ödeme Bilgileri Bölümü -->
                <div class="checkout-section payment-section">
                    <h2>Ödeme Bilgileri</h2>

                    <form id="payment-form">
                        <div class="form-group">
                            <label for="card-holder">Kart Üzerindeki İsim</label>
                            <input type="text" id="card-holder" name="card-holder" required>
                        </div>

                        <div class="form-group">
                            <label for="card-number">Kart Numarası</label>
                            <input type="text" id="card-number" name="card-number" placeholder="XXXX XXXX XXXX XXXX" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiry-date">Son Kullanma Tarihi</label>
                                <input type="text" id="expiry-date" name="expiry-date" placeholder="AA/YY" required>
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV/CVC</label>
                                <input type="text" id="cvv" name="cvv" placeholder="XXX" required>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sağ taraf - Sipariş Özeti -->
            <div class="order-summary">
                <h2>Sipariş Özeti</h2>

                <div class="summary-products">
                    <?php while ($row1 = $result1->fetch_assoc()):
                        $cart_id = $row1['cart_id'];
                        $name = $row1['name'];
                        $total = $row1['total'];
                        $quantity = $row1['quantity'];
                        $image = $row1['image_path']
                    ?>
                        <!-- Sepetteki ürünlerin özeti -->
                        <div class="summary-product">
                            <div class="product-info">
                                <img src="../php/<?= $image ?>" alt="Ürün">
                                <div>
                                    <p class="product-name"><?= $name ?></p>
                                    <p class="product-quantity">Miktar: <?= $quantity ?> <span style="color: red"><?= $errorMessage ?></span></p>
                                    <?php unset($_SESSION['errorMessageCheckout']) ?>
                                </div>
                            </div>
                            <div class="product-price"><?= $total ?> ₺</div>
                        </div>
                    <?php endwhile;
                    $_SESSION['cart_id'] = $cart_id; ?>
                </div>

                <div class="summary-totals">
                    <div class="summary-row">
                        <span>Ara Toplam</span>
                        <span><?= $grand_total ?> ₺</span>
                    </div>
                    <div class="summary-row">
                        <span>Kargo</span>
                        <span>25 ₺</span>
                    </div>
                    <div class="summary-row total">
                        <span>Toplam</span>
                        <span><?= $grand_total + 25 ?> ₺</span>
                    </div>
                </div>

                <form action="../php/checkoutOrder.php" method="post">
                    <input type="hidden" id="selected-address-id" name="selected_address_id" value="<?= $id ?>">
                    <button class="checkout" id="complete-order">Siparişi Tamamla</button>
                </form>
                <a href="../adminPage/cartAdmin.php">
                    <button class="back-to-cart">Sepete Dön</button>
                </a>
            </div>
        </div>
    </div>

    <script src="../scripts/checkout.js">
        
    </script>
</body>

</html>