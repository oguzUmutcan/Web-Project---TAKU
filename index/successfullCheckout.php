<?php
require "../php/dbConnection.php";

$stmt = $conn->prepare("
        SELECT address_line1, address_line2, city, state, total_amount FROM orders o JOIN addresses a
        ON o.address_id = a.id
        ORDER BY order_id DESC
        LIMIT 1
    ");

$stmt->execute();
$result = $stmt->get_result();

//echo $_SESSION['last_id'] . " hello";

?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Başarıyla Alındı</title>
    <link rel="stylesheet" href="../Style/successfullOrder.css">
</head>

<body>
    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"></path>
                </svg>
            </div>
            <h1>Siparişiniz Başarıyla Alındı!</h1>
            <p class="order-number">Sipariş Numarası: #SP78945612</p>

            <p class="message">
                Siparişiniz için teşekkür ederiz! Sipariş detaylarınızı içeren bir e-posta belirttiğiniz adrese gönderilmiştir.
                Siparişiniz en kısa sürede hazırlanıp kargoya verilecektir.
            </p>

            <div class="details">
                <?php while ($row = $result->fetch_assoc()):
                    $address_line1 = $row['address_line1'];
                    $address_line2 = $row['address_line2'];
                    $city = $row['city'];
                    $state = $row['state'];
                    $total_amount = $row['total_amount'];
                ?>
                    <div class="detail-row">
                        <span class="detail-label">Teslimat Adresi:</span>
                        <span class="detail-value"><?php echo $address_line1 . " " . $address_line2 . " , " . $state . "/" . $city ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tahmini Teslimat:</span>
                        <span class="detail-value">22-24 Mayıs 2025</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Toplam Tutar:</span>
                        <span class="detail-value"><?= $total_amount ?> ₺</span>
                    </div>
            </div>
        <?php endwhile; ?>

        <div class="buttons">
            <a href="../adminPage/customerAdmin.php#orders" class="btn primary-btn">Siparişlerim</a>
            <a href="../index.php" class="btn secondary-btn">Alışverişe Devam Et</a>
        </div>

        <div class="next-steps">
            <h3>Bundan Sonra Ne Olacak?</h3>
            <ul>
                <li>Siparişiniz onaylandıktan sonra hazırlanma sürecine alınacaktır.</li>
                <li>Siparişiniz hazırlandıktan sonra kargoya verilecek ve size bir kargo takip numarası gönderilecektir.</li>
                <li>Teslimat sırasında herhangi bir sorun yaşarsanız müşteri hizmetlerimizle iletişime geçebilirsiniz.</li>
            </ul>
        </div>
        </div>

        <footer>
            <p>Bir sorunuz mu var? <a href="#" style="color: #4CAF50; text-decoration: none;">Müşteri Hizmetleri</a> ile iletişime geçin</p>
            <p style="margin-top: 8px;">© 2025 Mağazamız. Tüm hakları saklıdır.</p>
        </footer>
    </div>

    <script src="../scripts/successfullCheckout.js">
    </script>
</body>

</html>