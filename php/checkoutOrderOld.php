<?php
require "../php/dbConnection.php";

$addressID = $_POST['selected_address_id'];
$cartID = $_SESSION['cart_id'];

$stmt1 = $conn->prepare("
    SELECT product_id, quantity FROM cart_items
    WHERE cart_id = ?
");

$stmt1->bind_param("i", $cartID);

$stmt1->execute();

$result = $stmt1->get_result();

$arrayInCheckout = array(array());
$arrayInProdcuts = array(array());

$count = 0;
while ($row = $result->fetch_assoc()) {
    $count1 = 0;
    $arrayInCheckout[$count][$count1] = $row['product_id'];
    $count1++;
    $arrayInCheckout[$count][$count1] = $row['quantity'];
    $count++;
}

$stmt2 = $conn->prepare("
        SELECT id, stock_quantity FROM products
        WHERE id = ?
    ");

//id çekme
for ($i = 0; $i < count($arrayInCheckout); $i++) {
    for ($j = 0; $j <= count($arrayInCheckout); $j++) {
        if ($i == $j) {
            $stmt2->bind_param("i", $arrayInCheckout[$i][$j]);

            $stmt2->execute();

            $result1 = $stmt2->get_result();

            $count2 = 0;
            while ($row1 = $result1->fetch_assoc()) {
                $count3 = 0;
                $arrayInProdcuts[$count2][$count3] = $row1['id'];
                $count3++;
                $arrayInProdcuts[$count2][$count3] = $row1['stock_quantity'];
                $count2++;
            }
        }
    }
}


/* for ($i = 0; $i < count($arrayInCheckout); $i++) {
    for ($j = 0; $j <= count($arrayInCheckout); $j++) {
        echo $arrayInCheckout[$i][$j] . " " . $arrayInProdcuts[$i][$j] . "<br>";
    }
}
 */



$stmt = $conn->prepare("
    INSERT INTO orders (user_id, address_id, total_amount, product_id, created_at)
    VALUES
        (?, ?, ?, ?, NOW())
");

$flag = true;

$grandTotal = $_SESSION['grand-total'] + 25;

for ($i = 0; $i < count($arrayInCheckout); $i++) {
    for ($j = $i + 1; $j <= count($arrayInCheckout); $j++) {
        if ($arrayInCheckout[$i][$j] > $arrayInProdcuts[$i][$j]) {
            $flag = false;
        } else {
            $stmt->bind_param("iiii", $_SESSION['user_id'], $addressID, $grandTotal, $arrayInProdcuts[$i][$j - 1]);
            if ($stmt->execute()) {
                if ($arrayInCheckout[$i][$j] == $arrayInProdcuts[$i][$j]) {
                    $stmt3 = $conn->prepare("
                    DELETE FROM products
                    WHERE id = ?
                ");

                    $stmt3->bind_param("i", $arrayInProdcuts[$i][$j - 1]);

                    if ($stmt3->execute()) {
                        header("location: ../index/successfullCheckout.php");
                    }
                } else if ($arrayInCheckout[$i][$j] < $arrayInProdcuts[$i][$j]) {
                    $stmt4 = $conn->prepare("
                    UPDATE products SET stock_quantity = stock_quantity - ?
                    WHERE id = ?
                ");

                    $stmt4->bind_param("ii", $arrayInCheckout[$i][$j], $arrayInProdcuts[$i][$j - 1]);

                    if ($stmt4->execute()) {
                        header("location: ../index/successfullCheckout.php");
                    }
                }
            }
        }
    }
}


//$_SESSION['last_id'] = $
