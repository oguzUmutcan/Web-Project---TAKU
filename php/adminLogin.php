<?php 

require "./dbConnection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $salt = "KSPKKOSROKGRENGOVL6197856";

    $cryptedPassword = hash('sha512', $password . $salt);

    $stmt = $conn -> prepare("
        SELECT id, userName, password FROM admins
        WHERE userName = ? AND  password = ?
    ");

    $stmt -> bind_param("ss", $username, $cryptedPassword);

    $stmt -> execute();

    $result = $stmt -> get_result();

    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
        while($row = $result -> fetch_assoc()){
            $id = $row['id'];
        }
        $_SESSION['admin_id'] = $id;
        header("location: ../adminPage/managementAdmin.php");
    }
    else{
        $_SESSION['errorMessageAdmin'] = "Kullanıcı adı veya şifre hatalı!";
        header("location: ../adminPage/admin.php");
    }
}

