<?php
session_start();

$errorMessage = isset($_SESSION['errorMessageAdmin']) ? $_SESSION['errorMessageAdmin'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../Style/admin.css">

    <link rel="icon" href="../Images/TAKU (2).png"
        type="image/x-icon" />
</head>

<body>
    <div class="admin-container">
        <h1>Admin Panel</h1>

        <form action="../php/adminLogin.php" method="post">
            <label for="username">Kullanıcı Adı</label>
            <input type="text" id="username" placeholder="Kullanıcı Adı" name="username" required>

            <label for="password">Şifre</label>
            <input type="password" id="password" placeholder="Şifre" name="password" required>

            <p style="color: red;"><?= $errorMessage ?></p>

            <label for="role">Yetki</label>
            <select id="role">
                <option value="Yetkili">Yetkili</option>
            </select>

            <button type="submit">Giriş Yap</button>

            <p class="redirect">
                <a href="../adminPage/forgotPassword.php?adminOrUser='admin'">Şifreni mi unuttun?</a>
            </p>
        </form>
    </div>
</body>

</html>