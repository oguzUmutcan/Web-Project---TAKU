<?php
require "../php/dbConnection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basit validasyon
    $firstName = trim($_POST['firstName']);
    $lastName  = trim($_POST['lastName']);
    $email     = trim($_POST['email']);

    $stmt1 = $conn -> prepare("SELECT email FROM users WHERE email = ?");

    $stmt1 -> bind_param("s", $email);

    $stmt1 -> execute();

    $result = $stmt1 -> get_result();

    echo "hellooo";

    if($result -> fetch_assoc() !== 0){
        $_SESSION['update-error'] = "Bu e-posta zaten kayıtlı.";
        header("Location: ../adminPage/customerAdmin.php#settings");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Geçerli bir e-posta giriniz.";
    } else {
        $upd = $conn->prepare("
      UPDATE users 
      SET firstName = ?, lastName = ?, email = ?
      WHERE id = ?
    ");
        $upd->bind_param("sssi", $firstName, $lastName, $email, $_SESSION['user_id']);
        if ($upd->execute()) {
            $_SESSION['message'] = "Bilgiler başarıyla güncellendi.";
            header("location: ../adminPage/customerAdmin.php");
        } else {
            $error = "Güncelleme sırasında hata oluştu.";
        }
        $upd->close();
    }
}
