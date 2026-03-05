<?php
require '../php/dbConnection.php';

$firstName;
$lastName;
$birthDate;
$email;
$password;
$confirmPassword;


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $firstName = trim(htmlspecialchars($_POST["firstName"]));
    $lastName = trim(htmlspecialchars($_POST["lastName"]));
    $birthDate = trim(htmlspecialchars($_POST["birthDate"]));
    $email = trim(htmlspecialchars($_POST["email"]));
    $password = trim(htmlspecialchars($_POST["password"]));
    $confirmPassword = trim(htmlspecialchars($_POST["confirmPassword"]));

    $stmt1 = $conn -> prepare("SELECT email FROM users WHERE email = ?");

    $stmt1 -> bind_param("s", $email);

    $stmt1 -> execute();

    $result = $stmt1 -> get_result();

    while($row = $result -> fetch_assoc()){
        $email11 = $row['email'];
    }

    if(isset($email11)){
        $_SESSION['signup_error'] = "Bu e-posta zaten kayıtlı.";
        header("Location: ../userOperations/userSignup.php");
    } 

    else{
        $salt = "KSPKKOSROKGRENGOVL6197856";

        $cryptedPassword = hash('sha512', $password . $salt);
        
        $stmt = $conn -> prepare("INSERT INTO users (firstName, lastName, email, password_hash, birthDate) 
                VALUES (?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "sssss",
            $firstName,
            $lastName,
            $email,
            $cryptedPassword,
            $birthDate
        );

        if($stmt -> execute()){
            echo "Registered Successfully";
            header("location:../userOperations/userLogin.html");
        }

        else{
            echo "Something Wrong";
            header("location:../userOperations/userSignUp.php");
        }
    }

    $stmt -> close();
}