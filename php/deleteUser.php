<?php
require "../php/dbConnection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password      = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt -> get_result();

    while ($row = $result->fetch_assoc()){
        $DBpassword = $row['password_hash'];
    }

    echo $DBpassword;

    $salt = "KSPKKOSROKGRENGOVL6197856";

    $cryptedPassword = hash('sha512', $password . $salt);

    $_SESSION['passErrorDelete'] = "";

    if ($cryptedPassword !== $DBpassword) {
        $_SESSION['passErrorDelete'] .= "Şifre hatalı.";
    } 
    else {
        $upd = $conn->prepare("DELETE FROM users WHERE id = ?");
        $upd->bind_param("i", $_SESSION['user_id']);
        if ($upd->execute()) {
            $_SESSION['passSuccessDelete'] = "Hesabınız kalıcı olarak başarılı ile silinmiştir. :(";
            require "../php/logout.php";
        }
        $upd->close();
    }
}

header("location: ../adminPage/customerAdmin.php#settings");
?>