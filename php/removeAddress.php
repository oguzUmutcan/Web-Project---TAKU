<?php
require '../php/dbConnection.php';

$id = $_GET['id'];

$stmt = $conn -> prepare("
    DELETE FROM addresses WHERE id = ?
");

$stmt -> bind_param("i", $id);

if($stmt -> execute()){
    header("location: ../adminPage/customerAdmin.php#addresses");
}

else{
    echo "error";
}

$stmt -> close();
$conn -> close();