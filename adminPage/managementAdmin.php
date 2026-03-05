<?php
require '../php/dbConnection.php';

$stmt = $conn->prepare("
    SELECT * FROM admins
    WHERE id = ?
");

$stmt->bind_param("i", $_SESSION['admin_id']);

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $firstNameLoggedIn = $row['firstName'];
    $lastNameLoggedIn = $row['lastName'];
    $profilePhotoLoggedIn = $row['profilPhoto'];
    $titleLoggedIn = $row['title'];
    $emailLoggedIn = $row['email'];
}

$stmt->close();

$stmt1 = $conn->prepare("
    SELECT * FROM admins
");

$stmt1->execute();

$result1 = $stmt1->get_result();

$stmt2 = $conn->prepare("
    SELECT * FROM orders
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Paneli</title>
    <link rel="stylesheet" href="../Style/manegamentAdmin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <link rel="icon" href="../Images/TAKU (2).png"
        type="image/x-icon" />
</head>

<body>
    <div class="admin-dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="profile">
                <img src="../<?= $profilePhotoLoggedIn ?>" alt="Yönetici Profil">
                <h2>Hoşgeldiniz, <?= $titleLoggedIn ?>!</h2>
                <h2><?= $firstNameLoggedIn ?> <?= $lastNameLoggedIn ?></h2>
            </div>
            <ul class="menu">
                <li><a href="#users"><i class="fa-solid fa-users"></i> Kullanıcı Listesi</a></li>
                <li><a href="#products"><i class="fa-solid fa-box"></i> Ürün Yönetimi</a></li>
                <li><a href="../adminPage/managementAdmin.php#settings"><i class="fa-solid fa-cog"></i> Ayarlar</a></li>
                <li><a href="../php/logoutAdmin.php" class="logout"><i class="fa-solid fa-sign-out-alt"></i> Çıkış Yap</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <section id="users">
                <h2>Kullanıcı Listesi</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad</th>
                            <th>Email</th>
                            <th>Yetki</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row1 = $result1->fetch_assoc()): ?>
                            <tr>
                                <?php if ($row1['id'] == $_SESSION['admin_id']) continue; ?>
                                <td><?= $row1['id'] ?></td>
                                <td><?= $row1['firstName'] ?> <?= $row1['lastName'] ?></td>
                                <td><?= $row1['email'] ?></td>
                                <td><?= $row1['title'] ?></td>
                            </tr>
                        <?php endwhile;
                        $stmt1->close(); ?>
                    </tbody>
                </table>
            </section>
            <section id="products">
                <h2>Ürün Yönetimi</h2>
                <a href="addProductPage.php">Yeni Ürün Ekle</a>
                <p>Burada mevcut ürünleri yönetebilirsiniz.</p>
            </section>
            <section id="settings">
                <h2>Hesap Ayarları</h2>
                <form action="../php/updateInformationsAdmin.php" method="post">
                    <label>Ad:</label>
                    <input type="text" name="firstName" value="<?= $firstNameLoggedIn ?>" required>

                    <label>Soyad:</label>
                    <input type="text" name="lastName" value="<?= $lastNameLoggedIn ?>" required>

                    <label>E-posta:</label>
                    <input type="email" name="email" value="<?= $emailLoggedIn ?>" required>
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

                    <a href="./forgotPassword.php?adminOrUser='admin'">Şifremi unuttum?</a>
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
                <form action="../php/updateProfilPhoto.php" method="post" enctype="multipart/form-data">
                    <label for="image">Profil Fotoğrafı Güncelle</label><br><br>
                    <input type="file" name="profile_photo">
                    <button type="submit">Yükle</button>
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