<?php
require "../php/dbConnection.php";


$userId     = $_SESSION['user_id'];
$cartID     = $_SESSION['cart_id'];
$addressID  = $_POST['selected_address_id'];
$grandTotal = $_SESSION['grand-total'] + 25;  // Kargo veya ek ücret vs.

// 1) Sepet verilerini çek ve associative array olarak sakla: [product_id => quantity]
$stmt = $conn->prepare("
    SELECT product_id, quantity
    FROM cart_items
    WHERE cart_id = ?
");
$stmt->bind_param("i", $cartID);
$stmt->execute();
$res = $stmt->get_result();

$arrayInCheckout = [];
while ($row = $res->fetch_assoc()) {
    $arrayInCheckout[(int)$row['product_id']] = (int)$row['quantity'];
}
$stmt->close();

// 2) Stoğu ve fiyatı tek sorguda çek
$productIds = array_keys($arrayInCheckout);
if (empty($productIds)) {
    exit('Sepetiniz boş.');
}
$placeholders = implode(',', array_fill(0, count($productIds), '?'));

$sql = "SELECT id, stock_quantity, price FROM products WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);

// Dinamik bind
$types = str_repeat('i', count($productIds));
$stmt->bind_param($types, ...$productIds);
$stmt->execute();
$res = $stmt->get_result();

$arrayInProducts = [];  // stok
$arrayUnitPrice   = [];  // fiyat
while ($r = $res->fetch_assoc()) {
    $pid = (int)$r['id'];
    $arrayInProducts[$pid] = (int)$r['stock_quantity'];
    $arrayUnitPrice[$pid]   = (float)$r['price'];
}
$stmt->close();

// 3) Stok kontrolü
foreach ($arrayInCheckout as $prodId => $qty) {
    if (!isset($arrayInProducts[$prodId]) || $qty > $arrayInProducts[$prodId]) {
        exit("Stok yetersiz: Ürün ID {$prodId}");
    }
}

// 4) Tek bir orders kaydı ekle
$stmt = $conn->prepare("
    INSERT INTO orders (user_id, address_id, total_amount, created_at)
    VALUES (?, ?, ?, NOW())
");
$stmt->bind_param("iid", $userId, $addressID, $grandTotal);
$stmt->execute();
$orderId = $stmt->insert_id;
$stmt->close();

// 5) order_items ekle, stok güncelle/sil
foreach ($arrayInCheckout as $prodId => $qty) {
    $unitPrice = $arrayUnitPrice[$prodId];

    // 5.a) order_items tablosuna ekle (unit_price ile)
    $stmt = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, unit_price)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iiid", $orderId, $prodId, $qty, $unitPrice);
    $stmt->execute();
    $_SESSION['last_id'] = $conn->insert_id;
    $stmt->close();

    // 5.b) Stok güncelle veya ürün sil
    if ($qty === $arrayInProducts[$prodId]) {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $prodId);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("
            UPDATE products
            SET stock_quantity = stock_quantity - ?
            WHERE id = ?
        ");
        $stmt->bind_param("ii", $qty, $prodId);
        $stmt->execute();
        $stmt->close();
    }
}

// 6) Sepeti temizle
$stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
$stmt->bind_param("i", $cartID);
$stmt->execute();
$stmt->close();

// 7) Yönlendir
header("Location: ../index/successfullCheckout.php");
exit;
