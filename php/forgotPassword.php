<?php

require "../php/dbConnection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password']);
    $email    = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT email FROM users");

    $stmt->execute();

    $result = $stmt->get_result();

    $flag = false;

    while ($row = $result->fetch_assoc()) {
        if ($row['email'] == $email) {
            $flag = true;
            break;
        }
    }

    $message = "";

    if ($flag) {
        $salt = "KSPKKOSROKGRENGOVL6197856";

        $cryptedPassword = hash('sha512', $password . $salt);

        if (isset($_SESSION['user_id'])) {
            $upd = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $upd->bind_param("si", $cryptedPassword, $_SESSION['user_id']);
        }
        else{
            $upd = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
            $upd->bind_param("ss", $cryptedPassword, $email);
        }
        
        if ($upd->execute()) {
            $_SESSION['passSuccess'] = "Şifreniz başarıyla değiştirildi.";
        }
        require "../php/logout.php";
        $upd->close();
        header("location: ../userOperations/userLogin.html");  
    }


    else{
        $message = "Email bulunamadı!";
        header("location: ../adminPage/forgotPassword.php?message=$message");  
    }
}