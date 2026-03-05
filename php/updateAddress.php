<?php
require "../php/dbConnection.php";

$_SESSION['error']   = '';
$_SESSION['success'] = '';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            "UPDATE addresses SET 
            title = ?,
            address_line1 = ?, 
            address_line2 = ?, 
            city = ?,
            state = ?,
            postal_code = ?,
            country = ?
            WHERE id = ?"
        );
        $stmt->bind_param(
            "sssssssi",
            $title,
            $line1,
            $line2,
            $state,
            $city,
            $postal_code,
            $country,
            $id
        );
        if ($stmt->execute()) {
            $_SESSION['successUpdate'] = "Adres başarıyla güncellendi.";
        } else {
            $_SESSION['errorUpdate'] = "Adres güncellenirken hata oluştu.";
        }
        $stmt->close();
    }
}

$_SESSION['lastIndex'] = $id;

header("location: ../adminPage/customerAdmin.php#address");
