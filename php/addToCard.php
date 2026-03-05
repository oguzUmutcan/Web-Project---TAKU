<?php
require '../php/dbConnection.php';

if (isset($_GET['nowID'])) {
    $productId = $_GET['nowID'];

    $stmt2 = $conn->prepare("
    SELECT id FROM carts
    WHERE user_id = ?
");

    $stmt2->bind_param("i", $_SESSION['user_id']);

    $stmt2->execute();

    $result2 = $stmt2->get_result();

    while ($row2 = $result2->fetch_assoc()) {
        $cartId = $row2['id'];
    }

    $stmt2->close();

    if (!isset($cartId)) {
        $stmt = $conn->prepare("
    INSERT INTO carts (user_id)
    VALUES
        (?)
");

        $stmt->bind_param("i", $_SESSION['user_id']);

        $stmt->execute();

        $stmt->close();
    } else {
        $stmt2 = $conn->prepare("
    INSERT INTO cart_items (cart_id, product_id, quantity)
    VALUES
        (?, ?, ?)
");

        $quantity = 1;

        $stmt2->bind_param("iii", $cartId, $productId, $quantity);

        if ($stmt2->execute()) {
            header("location: ../adminPage/checkout.php");
        } else {
            echo "error";
        }

        $stmt2->close();
    }

    $conn->close();
} else {
    $productId = $_GET['id'];

    $stmt1 = $conn->prepare("
    SELECT id FROM carts
    WHERE user_id = ?
");

    $stmt1->bind_param("i", $_SESSION['user_id']);

    $stmt1->execute();

    $result = $stmt1->get_result();

    while ($row = $result->fetch_assoc()) {
        $cartId = $row['id'];
    }

    $stmt1->close();

    if (!isset($cartId)) {
        $stmt = $conn->prepare("
    INSERT INTO carts (user_id)
    VALUES
        (?)
");

        $stmt->bind_param("i", $_SESSION['user_id']);

        $stmt->execute();

        $stmt->close();
    } else {
        $stmt2 = $conn->prepare("
    INSERT INTO cart_items (cart_id, product_id, quantity)
    VALUES
        (?, ?, ?)
");

        $quantity = 1;

        $stmt2->bind_param("iii", $cartId, $productId, $quantity);

        if ($stmt2->execute()) {
            header("location: ../adminPage/cartAdmin.php");
        } else {
            echo "error";
        }

        $stmt2->close();
    }

    $conn->close();
}
