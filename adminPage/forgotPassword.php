<?php

$adminOrUser = $_GET['adminOrUser'];

$message = isset($_GET['message']) ? $_GET['message'] : null;

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - TAKU - Mobilya & Dekor</title>
    <link rel="stylesheet" href="../Style/userLogin.css">
    <link rel="icon" href="../Images/TAKU (2).png"
        type="image/x-icon" />
</head>

<body>

    <div class="container">
        <div class="login-box">

            <form action=<?php
                            if ($adminOrUser == 'user') {
                                echo "../php/forgotPassword.php";
                            } else {
                                echo "../php/forgotPasswordAdmin.php";
                            }
                            ?>
                class="was-validated" method="post">
                <h2>Şifreni Değiştir</h2>
                <div class="input-box">
                    <input type="email" id="email" name="email" required>
                    <label>Email</label>
                    <span id="email-error" class="error-message" style="color: red;"><?= $message ?></span>
                </div>
                <div class="input-box">
                    <input type="password" id="password" name="password" required>
                    <label>Şifre</label>
                    <span id="password-error" class="error-message"></span>
                </div>
                <div class="input-box">
                    <input type="password" id="password1" name="password" required>
                    <label>Tekrar Şifreyi Giriniz</label>
                    <span id="password-error1" class="error-message"></span><br>
                    <span id="password-error2" class="error-message"></span>
                </div>
                <button type="submit" class="btn" id="button" disabled>
                    Şifreyi Değiştir
                </button>
            </form>
        </div>
    </div>

    <script src="../scripts/forgotPassword.js">
    </script>

</body>

</html>