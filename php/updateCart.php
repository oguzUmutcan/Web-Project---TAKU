<?php
// update_cart.php - Sepet güncelleme için API
header('Content-Type: application/json');

// Veritabanı bağlantısını dışarıdan al
require '../php/dbConnection.php';

// Güvenlik kontrolü
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Oturum açmalısınız']);
    exit;
}

// POST verilerini al
$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Veri doğrulama
if (empty($product_name) || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz ürün veya miktar']);
    exit;
}

// Kullanıcının sepet ID'sini al
$sql = "SELECT id FROM carts WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $cart_id = $row['id'];
} else {
    echo json_encode(['success' => false, 'message' => 'Sepet bulunamadı']);
    exit;
}

// Ürün ID'sini al
$sql = "SELECT id FROM products WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $product_name);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $product_id = $row['id'];
} else {
    echo json_encode(['success' => false, 'message' => 'Ürün bulunamadı']);
    exit;
}

// Sepetteki ürünü güncelle
$sql = "UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $quantity, $cart_id, $product_id);

if ($stmt->execute()) {
    // Başarıyla güncellendi - kullanıcıya JSON yanıtı döndür
    echo json_encode([
        'success' => true, 
        'message' => 'Sepet güncellendi',
        'data' => [
            'product_name' => $product_name,
            'quantity' => $quantity,
            'action' => $action
        ]
    ]);
} else {
    // Hata durumunda JSON yanıtı
    echo json_encode([
        'success' => false, 
        'message' => 'Sepet güncellenirken hata oluştu: ' . $conn->error
    ]);
}

// Bağlantıyı kapat
$conn->close();
?>