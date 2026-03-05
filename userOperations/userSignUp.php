<?php 
session_start();
$error = isset($_SESSION['signup_error']) ? $_SESSION['signup_error'] : null;

unset($_SESSION['signup_error']);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - TAKU - Mobilya & Dekor</title>
    <link rel="stylesheet" href="../Style/userSignUp.css">
    <link rel="icon" href="../Images/TAKU (2).png"
        type="image/x-icon" />
</head>

<body>

    <div class="container">
        <div class="login-box">

            <form action="../php/signUp.php" method="post">
                <h2>Kayıt Ol</h2>
                <div class="input-box">
                    <input type="text" id="first-name" name="firstName" required>
                    <label>Ad</label>
                </div>
                <div class="input-box">
                    <input type="text" id="last-name" name="lastName" required>
                    <label>Soyad</label>
                </div>
                <div class="input-box">
                    <input type="date" id="dob" name="birthDate" required>
                </div>
                <div class="input-box">
                    <input type="email" id="email" name="email" required>
                    <label>Email</label>
                    <span id="email-error" class="error-message" style="color: red"><?php echo $error ?></span>
                </div>
                <div class="input-box">
                    <input type="password" id="password" name="password" required>
                    <label>Şifre</label>
                    <span id="password-error" class="error-message"></span>
                </div>
                <div class="input-box">
                    <input type="password" id="confirm-password" name="confirmPassword" required>
                    <label>Tekrar Şifre</label>
                    <span id="confirm-password-error" class="error-message"></span>
                </div>

                <button type="submit" class="btn">
                    Kayıt ol
                </button>

                <div class="forgot-sign">
                    Zaten hesabın var mı? <a href="../userOperations/userLogin.html">Giriş yap</a>
                </div>

            </form>
        </div>
    </div>

    <script src="../scripts/userSignUp.js">
    </script>

</body>

</html>