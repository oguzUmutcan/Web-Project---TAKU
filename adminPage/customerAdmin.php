<?php
require "../php/dbConnection.php";

$successUpdateAddress = isset($_SESSION['successUpdate']) ? $_SESSION['successUpdate'] : null;
$errorUpdateAddress = isset($_SESSION['errorUpdate']) ? $_SESSION['errorUpdate'] : null;

$lastIndex = isset($_SESSION['lastIndex']) ? $_SESSION['lastIndex'] : null;

$stmt = $conn->prepare("
SELECT firstName, lastName, email FROM users
WHERE id = ?
");

$stmt->bind_param("s", $_SESSION["user_id"]);

$stmt->execute();

$result = $stmt->get_result();


while ($row = $result->fetch_assoc()) {
    $name = htmlspecialchars($row['firstName']);
    $lastName = htmlspecialchars($row['lastName']);
    $email = htmlspecialchars($row['email']);
}


$stmt->close();

$id = null;
$title = "";
$address_line1 = "";
$address_line2 = "";
$state = "";
$city = "";
$country = "";

// Kullanıcının mevcut adreslerini çek
$stmt1 = $conn->prepare("SELECT id, title, address_line1, address_line2, state, city, postal_code, country FROM addresses WHERE user_id = ?");
$stmt1->bind_param("i", $_SESSION['user_id']);
$stmt1->execute();

/* $resultForId = $stmt1 -> get_result();

while ($rowForID = $resultForId->fetch_assoc()) {
    $id = $rowForID['id'];
} */

$result = $stmt1->get_result();

$stmt1->close();


// 1) Kullanıcının siparişlerini al
$sql = "
  SELECT 
    o.order_id          AS order_id,
    o.created_at  AS order_date,
    o.total_amount,
    a.address_line1,
    a.address_line2,
    a.city,
    a.state
  FROM orders o
  JOIN addresses a ON o.address_id = a.id
  WHERE o.user_id = ?
  ORDER BY o.created_at DESC
";
$stmt2 = $conn->prepare($sql);
$stmt2->bind_param("i", $_SESSION['user_id']);
$stmt2->execute();
$result1 = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Paneli</title>
    <link rel="stylesheet" href="../Style/customerAdmin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <link rel="icon" href="../Images/TAKU (2).png"
        type="image/x-icon" />

</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="profile">
                <h2>Merhaba, <?= $name ?>!</h2>
            </div>
            <ul class="menu">
                <li><a href="../index.php"><i class="fa-solid fa-house"></i> Ana Sayfa</a></li>
                <li><a href="#orders"><i class="fa-solid fa-box"></i> Siparişlerim</a></li>
                <li><a href="#addresses"><i class="fa-solid fa-map-marker-alt"></i> Adreslerim</a></li>
                <li><a href="#settings"><i class="fa-solid fa-cog"></i> Hesap Ayarları</a></li>
                <li><a href="../php/logout.php" class="logout"><i class="fa-solid fa-sign-out-alt"></i> Çıkış Yap</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <section id="orders">
                <h2>Siparişlerim</h2>

                <?php if ($result1->num_rows === 0): ?>
                    <p>Henüz bir alışveriş yapmadınız.</p>
                <?php else: ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Sipariş No</th>
                                <th>Tarih</th>
                                <th>Toplam Tutar</th>
                                <th>Adres</th>
                                <th>Detay</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result1->fetch_assoc()): $oid = htmlspecialchars($row['order_id']); ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                                    <td><?= date('d.m.Y H:i', strtotime($row['order_date'])) ?></td>
                                    <td>₺<?= number_format($row['total_amount'], 2, ',', '.') ?></td>
                                    <td>
                                        <?= htmlspecialchars($row['address_line1']) ?>
                                        <?php if ($row['address_line2']): ?>
                                            , <?= htmlspecialchars($row['address_line2']) ?>
                                        <?php endif; ?>
                                        <br>
                                        <?= htmlspecialchars($row['city']) ?> / <?= htmlspecialchars($row['state']) ?>
                                    </td>
                                    <td>
                                        <button class="btn-toggle" data-target="details-<?= $oid ?>">Detayları Gör</button>
                                    </td>
                                </tr>
                                <!-- Detay satırı (başta gizli) -->
                                <tr id="details-<?= $oid ?>" class="order-details-row" style="display: none;">
                                    <td colspan="5">
                                        <div class="order-details">
                                            <?php
                                            // 2) Her sipariş için ürünleri çek
                                            $stmt3 = $conn->prepare("
                    SELECT p.name, oi.quantity, oi.unit_price, image_path
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.id
                    JOIN product_images pi ON p.id = pi.product_id
                    WHERE oi.order_id = ?
                  ");
                                            $stmt3->bind_param("i", $oid);
                                            $stmt3->execute();
                                            $items = $stmt3->get_result();
                                            $stmt3->close();
                                            ?>
                                            <?php if ($items->num_rows === 0): ?>
                                                <p>Ürün bilgisi bulunamadı.</p>
                                            <?php else: ?>
                                                <table class="items-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Ürün Resmi</th>
                                                            <th>Ürün Adı</th>
                                                            <th>Miktar</th>
                                                            <th>Birim Fiyat</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($it = $items->fetch_assoc()): ?>
                                                            <tr>
                                                                <td style="width: 30%">
                                                                    <img style="width: 100%; border-radius: 10%" src="../php/<?=$it['image_path']?>" alt="">
                                                                </td>
                                                                <td><?= htmlspecialchars($it['name']) ?></td>
                                                                <td><?= (int)$it['quantity'] ?></td>
                                                                <td>₺<?= number_format($it['unit_price'], 2, ',', '.') ?></td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php endif; $stmt2 -> close();?>
            </section>
            <section id="addresses">
                <h2 id="myAddresses">Adreslerim</h2>
                <div id="addressDiv">
                    <h3>Mevcut Adresler</h3>
                    <div>
                        <?php $count = 1;
                        while ($row = $result->fetch_assoc()):
                            $id = $row['id'];
                            $title = $row['title'];
                            $address_line1 = $row['address_line1'];
                            $address_line2 = $row['address_line2'];
                            $state = $row['state'];
                            $city = $row['city'];
                            $country = $row['country'];
                            $postal_code = $row['postal_code'];
                        ?>
                            <div class="address-item">
                                <!-- 1. Adres Görüntüleme -->
                                <div class="addressList">
                                    <h4>Adres <?= $count ?></h4>
                                    <button class="btn-edit" onclick="displayUpdate(this)">
                                        <i class="fa fa-solid fa-pencil"></i> <strong>Düzenle</strong>
                                    </button>
                                    <button class="btn-remove">
                                        <a href="../php/removeAddress.php?id=<?= $id ?>" style="text-decoration: none; color: inherit">
                                            <i class="fa fa-solid fa-trash"></i> <strong>Adresi Kaldır</strong>
                                        </a>
                                    </button>
                                    <p><strong><?= $title ?></strong></p>
                                    <p><?= $address_line1 ?> <?= $address_line2 ?></p>
                                    <p><?= $city ?> / <?= $state ?> / <?= $postal_code ?> / <?= $country ?></p>
                                </div>
                                <?php if ($id == $lastIndex): ?>
                                    <div id="message">
                                        <p style="color: red;"><?= $errorUpdateAddress ?></p>
                                        <p style="color: green;"><?= $successUpdateAddress ?></p>
                                        <p style="color: green;"><?= $_SESSION['success'] ?></p>
                                        <p style="color: red;"><?= $_SESSION['error'] ?></p>
                                    </div>
                                <?php unset($_SESSION['lastIndex']);
                                    unset($_SESSION['error']);
                                    unset($_SESSION['success']);
                                    unset($_SESSION['errorUpdate']);
                                    unset($_SESSION['successUpdate']);
                                endif; ?>
                                <hr>
                                <!-- 2. Güncelleme Formu (başlangıçta gizli) -->
                                <form class="updateAddress"
                                    method="post"
                                    action="../php/updateAddress.php?id=<?= $id ?>"
                                    style="display: none;">
                                    <label>Adres Başlığı <span style="color: red;">*</span>:</label>
                                    <input type="text" name="title" value="<?= $title ?>" required><br><br>
                                    <label>Adres Satırı 1 <span style="color: red;">*</span>:</label>
                                    <input type="text" name="address_line1" value="<?= $address_line1 ?>" required><br><br>
                                    <label>Adres Satırı 2:</label>
                                    <input type="text" name="address_line2" value="<?= $address_line2 ?>"><br><br>
                                    <label>Şehir <span style="color: red;">*</span>:</label>
                                    <input type="text" name="city" value="<?= $city ?>" required><br><br>
                                    <label>İlçe:</label>
                                    <input type="text" name="state" value="<?= $state ?>"><br><br>
                                    <label>Posta Kodu:</label>
                                    <input type="text" name="postal_code" value="<?= $postal_code ?>"><br><br>
                                    <label>Ülke <span style="color: red;">*</span>:</label>
                                    <input type="text" name="country" value="<?= $country ?>" required><br><br>
                                    <button type="submit" name="save_address">Güncelle</button>
                                    <hr>
                                </form>
                            </div>
                        <?php $count++;
                        endwhile; ?>
                        <?php if ($id == null): ?>
                            <?php echo "<p>Henüz kayıtlı adresiniz yok.</p>" ?>
                        <?php endif; ?>
                    </div>
                </div>

                <button id="saveButton" name="save_address" onclick="displayDiv()">Yeni Kayıt</button>

                <div id="addAddress">
                    <form method="post" action="../php/saveAdress.php">
                        <label>Adres Başlığı <span style="color: red;">*</span>:</label>
                        <input type="text" name="title" required>
                        <br><br>
                        <label>Adres Satırı 1 <span style="color: red;">*</span>:</label>
                        <input type="text" name="address_line1" required>
                        <br><br>
                        <label>Adres Satırı 2:</label>
                        <input type="text" name="address_line2">
                        <br><br>
                        <label>Şehir <span style="color: red;">*</span>:</label>
                        <input type="text" name="state" required>
                        <br><br>
                        <label>İlçe:</label>
                        <input type="text" name="city">
                        <br><br>
                        <label>Posta Kodu:</label>
                        <input type="text" name="postal_code">
                        <br><br>
                        <label>Ülke <span style="color: red;">*</span>:</label>
                        <input type="text" name="country" required>
                        <br><br>
                        <button type="submit" name="save_address">Kaydet</button>
                    </form>
                </div>
            </section>
            <section id="settings">
                <h2>Hesap Ayarları</h2>
                <form action="../php/updateInformations.php" method="post">
                    <label>Ad:</label>
                    <input type="text" name="firstName" value="<?= $name ?>" required>

                    <label>Soyad:</label>
                    <input type="text" name="lastName" value="<?= $lastName ?>" required>

                    <label>E-posta:</label>
                    <input type="email" name="email" value="<?= $email ?>" required>
                    <p style="color:red"><?php if (isset($_SESSION['update-error'])) echo $_SESSION['update-error'];
                                            unset($_SESSION['update-error']); ?></p>

                    <button type="submit" name="update">Güncelle</button>
                </form>
                <?php if (!empty($_SESSION['message'])) echo "<p class='success'>{$_SESSION['message']}</p>";
                unset($_SESSION['message']); ?>
                <br>
                <hr>
                <form method="post" action="../php/passwordUpdate.php">
                    <label for="old_password">Eski Şifre:</label>
                    <input type="password" id="old_password" name="old_password" placeholder="Eski şifrenizi giriniz..." required>

                    <a href="forgotPassword.php?adminOrUser='user'">Şifremi unuttum?</a>
                    <p style="color:red"><?php if (isset($_SESSION['oldPassError'])) echo $_SESSION['oldPassError'];
                                            unset($_SESSION['oldPassError']); ?></p>
                    <br><br>

                    <label for="new_password">Yeni Şifre:</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Yeni şifrenizi giriniz..." required>

                    <label for="confirm_password">Yeni Şifre (Tekrar):</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Yeni şifrenizi tekrar giriniz..." required>
                    <p style="color:red"><?php if (isset($_SESSION['passError'])) echo $_SESSION['passError'];
                                            unset($_SESSION['passError']); ?></p>
                    <br><br>
                    <button type="submit" name="change_password">Şifreyi Güncelle</button>
                </form>
                <?php if (!empty($_SESSION['passSuccess'])) echo "<p class='success'>{$_SESSION['passSuccess']}</p>";
                unset($_SESSION['passSuccess']); ?>
                <br><br>
                <hr><br><br>
                <form action="../php/deleteUser.php" method="post">
                    <label for="">Şifrenizi Girin: </label>
                    <input type="password" name="password">
                    <button type="submit">Hesabı Sil</button>
                </form>
                <?php if (!empty($_SESSION['passErrorDelete'])) echo "<p class='success'>{$_SESSION['passErrorDelete']}</p>";
                unset($_SESSION['passErrorDelete']); ?>
                <?php if (!empty($_SESSION['passSuccessDelete'])) echo "<p class='success'>{$_SESSION['passSuccessDelete']}</p>";
                unset($_SESSION['passSuccessDelete']); ?>
            </section>
        </main>
    </div>
</body>

</html>

<script src="../scripts/customerAdmin.js"></script>