<?php
require "../php/dbConnection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old      = trim($_POST['old_password']);
    $new      = trim($_POST['new_password']);
    $confirm  = trim($_POST['confirm_password']);

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt -> get_result();

    while ($row = $result->fetch_assoc()){
        $password = $row['password_hash'];
    }

    //echo $password;

    $salt = "KSPKKOSROKGRENGOVL6197856";

    $cryptedOldPassword = hash('sha512', $old . $salt);
    $cryptedNewPassword = hash('sha512', $new . $salt);

    $_SESSION['passError'] = "";
    $_SESSION['oldPassError'] = "";

    $flag = true;

    if ($cryptedOldPassword !== $password) {
        $_SESSION['oldPassError'] .= "Eski şifre hatalı.";
        $flag = false;
    } if ($new !== $confirm) {
        $_SESSION['passError'] .= "Yeni şifreler eşleşmiyor. <br>";
        $flag = false;
    } if (strlen($new) < 6) {
        $_SESSION['passError'] .= "Yeni şifre en az 6 karakter olmalı. <br>";
        $flag = false;
    } if($flag === true) {
        $upd = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $upd->bind_param("si", $cryptedNewPassword, $_SESSION['user_id']);
        if ($upd->execute()) {
            $_SESSION['passSuccess'] = "Şifreniz başarıyla değiştirildi.";
        }
        $upd->close();
    }
}

header("location: ../adminPage/customerAdmin.php#settings");
?>