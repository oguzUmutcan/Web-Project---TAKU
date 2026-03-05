<?php
require '../php/dbConnection.php';

$email;
$password;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = trim(htmlspecialchars($_POST["email"]));
    $password = trim(htmlspecialchars($_POST["password"]));

    $salt = "KSPKKOSROKGRENGOVL6197856";

    $cryptedPassword = hash('sha512', $password . $salt);
    
    $stmt = $conn -> prepare("SELECT id, email, password_hash FROM users 
            WHERE email = ? AND password_hash = ?");

    $stmt -> bind_param("ss", $email, $cryptedPassword);

    $stmt -> execute();

    $result = $stmt -> get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_email'] = $row['email'];

        echo "Kullanıcı bulundu: " . $row['email'];
        header("location:../index.php");
    } else {
        echo "yanlış";
        $_SESSION['login_error'] = "Geçersiz e-posta veya şifre.";
        header("location:../userOperations/userLogin.html");
    }

    $stmt -> close();
    
}
?>