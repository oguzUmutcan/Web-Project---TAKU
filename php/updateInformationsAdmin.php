<?php
require "../php/dbConnection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basit validasyon
    $firstName = trim($_POST['firstName']);
    $lastName  = trim($_POST['lastName']);
    $email     = trim($_POST['email']);

    $stmt1 = $conn->prepare("SELECT email FROM admins WHERE email = ? AND NOT id = ?");

    $stmt1->bind_param("si", $email, $_SESSION['admin_id']);

    $stmt1->execute();

    $result = $stmt1->get_result();

    while($row4 = $result -> fetch_assoc()){
        $email1 = $row4['email'];
    }

    if (mysqli_num_rows($result) !== 0) {
        $_SESSION['update-error'] = "Bu e-posta zaten kayıtlı. ";
        header("Location: ../adminPage/managementAdmin.php#settings");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Geçerli bir e-posta giriniz.";
    } else {
        $upd = $conn->prepare("
      UPDATE admins 
      SET firstName = ?, lastName = ?, email = ?
      WHERE id = ?
    ");
        $upd->bind_param("sssi", $firstName, $lastName, $email, $_SESSION['admin_id']);
        if ($upd->execute()) {
            $_SESSION['message'] = "Bilgiler başarıyla güncellendi.";
            header("location: ../adminPage/managementAdmin.php");
        } else {
            $error = "Güncelleme sırasında hata oluştu.";
        }
        $upd->close();
    }
}
