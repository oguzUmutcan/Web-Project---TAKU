<?php

require "./dbConnection.php";

$str = $_GET['str'];

$stmt = $conn -> prepare("
    SELECT name, id FROM products
    WHERE name LIKE '%$str%';
");

$stmt -> execute();

$result = $stmt -> get_result();

while($row = $result -> fetch_assoc()){
    echo "<a href='./products/product_detail.php?id={$row['id']}'>" . $row['name'] . "</a><hr>";
}