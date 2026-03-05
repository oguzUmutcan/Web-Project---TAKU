<?php
/* require "../php/dbConnection.php";


if (!isset($_SESSION['user_id'])) {
    header("location: ../index/favorites.php");
}

$id = $_GET['id'];

$stmt = $conn -> prepare(
    "INSERT INTO favorites(user_id, product_id)
    VALUES
        (?, ?)                        
    ");
    
$stmt -> bind_param("ii", $_SESSION['user_id'], $id);

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
    // Önce ürünün gerçekten var olup olmadığını kontrol edelim
    $checkStmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Bu ürün veritabanında bulunamadı (ID: ' . $id . ')'
        ]);
        $checkStmt->close();
        $conn->close();
        exit;
    }
    $checkStmt->close();
    
    // Ürün zaten favorilerde mi kontrol et
    $checkFavStmt = $conn->prepare("SELECT product_id FROM favorites WHERE user_id = ? AND product_id = ?");
    $checkFavStmt->bind_param("ii", $_SESSION['user_id'], $id);
    $checkFavStmt->execute();
    $favResult = $checkFavStmt->get_result();
    
    if ($favResult->num_rows > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Bu ürün zaten favorilerinizde'
        ]);
        $checkFavStmt->close();
        $conn->close();
        exit;
    }
    $checkFavStmt->close();

    // Ürün mevcut ve henüz favorilerde değil, ekleyelim
    $stmt = $conn->prepare("
        INSERT INTO favorites(user_id, product_id)     
        VALUES (?, ?)
    ");

    $stmt->bind_param("ii", $_SESSION['user_id'], $id);

    // İşlem başarılı mı kontrolü
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Ürün favorilere eklendi'
        ]);
    } else {
        // Hata durumunda (genellikle benzersiz kısıtlama hatası olabilir)
        echo json_encode([
            'success' => false,
            'message' => 'Ürün favorilere eklenemedi: ' . $conn->error
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

