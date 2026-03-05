<?php
require "../php/dbConnection.php";

$_SESSION['error']   = '';
$_SESSION['success'] = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_address'])) {
    $title       = trim($_POST['title']);
    $line1       = trim($_POST['address_line1']);
    $line2       = trim($_POST['address_line2']);
    $state       = trim($_POST['state']);
    $city        = trim($_POST['city']);
    $postal_code = trim($_POST['postal_code']);
    $country     = trim($_POST['country']);

    if ($title === '' || $line1 === '' || $state === '' || $country === '') {
        $_SESSION['error'] = "Adres başlığı, satır 1, şehir ve ülke alanları zorunludur.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO addresses (user_id, title, address_line1, address_line2, state, city, postal_code, country)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "isssssss",
             $_SESSION['user_id'], $title, $line1, $line2, $state, $city, $postal_code, $country
        );
        if ($stmt->execute()) {
            $_SESSION['success'] = "Adres başarıyla kaydedildi.";
        } else {
            $_SESSION['error'] = "Adres kaydedilirken hata oluştu.";
        }
        $stmt->close();
    }
}

$_SESSION['lastIndex'] = $conn -> insert_id;
header("location: ../adminPage/customerAdmin.php#address");

?>