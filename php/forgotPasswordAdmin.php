<?php

require "../php/dbConnection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password']);
    $email    = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT email FROM admins");

    $stmt->execute();

    $result = $stmt->get_result();

    $flag = false;

    while ($row = $result->fetch_assoc()) {
        if ($row['email'] == $email) {
            $flag = true;
            break;
        }
    }

    $message = "";

    if ($flag) {
        $salt = "KSPKKOSROKGRENGOVL6197856";

        $cryptedPassword = hash('sha512', $password . $salt);
        $upd = $conn->prepare("UPDATE admins SET password = ? WHERE email = ?");
        $upd->bind_param("ss", $cryptedPassword, $email);

        if ($upd->execute()) {
            $_SESSION['passSuccess'] = "Şifreniz başarıyla değiştirildi.";
        }
        header("location: ../php/logoutAdmin.php"); 
        $upd->close();
    } else {
        $message = "Email bulunamadı!";
        header("location: ../adminPage/forgotPassword.php?message=$message");
    }
}
