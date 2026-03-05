<?php
require "../php/dbConnection.php";

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
} 