<?php
/* require "../php/dbConnection.php";

$id = $_GET['id'];

if($id == -1){
    $stmt = $conn -> prepare(
    "DELETE FROM favorites
     WHERE user_id = ?                   
    ");

    $stmt -> bind_param("i", $_SESSION['user_id']);
}

else{
    $stmt = $conn -> prepare(
    "DELETE FROM favorites
     WHERE user_id = ? && product_id = ?                        
    ");

    $stmt -> bind_param("ii", $_SESSION['user_id'], $id);
}

if($stmt->execute()){
    header("location: ../index/favorites.php");
} */

// Hata raporlamayı kapatarak JSON çıktısını koruyalım
error_reporting(0);
ini_set('display_errors', 0);

// JSON başlığını ekleyelim
header('Content-Type: application/json');

// Veritabanı bağlantısı
require_once '../php/dbConnection.php';

// Oturum kontrolü
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Kullanıcı giriş yapmamış',
        'redirect' => '../userOperations/userLogin.html'
    ]);
    exit;
}

// POST verisi kontrolü
if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Ürün ID bilgisi eksik'
    ]);
    exit;
}

// AJAX ile gelen product_id
$id = (int)$_POST['product_id'];

try {
    // SQL injection koruması için hazırlık
    $stmt = $conn->prepare("
        DELETE FROM favorites 
        WHERE user_id = ? AND product_id = ?
    ");

    $stmt->bind_param("ii", $_SESSION['user_id'], $id);

    // İşlem başarılı mı kontrolü
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Ürün favorilerden kaldırıldı'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Ürün favorilerden kaldırılamadı: ' . $conn->error
        ]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu: ' . $e->getMessage()
    ]);
}

$conn->close();
