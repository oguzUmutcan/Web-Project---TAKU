<<!-- ?php
require '../php/dbConnection.php';


/*$count = 91;
$count1 = 1;
while(true){
    $sql = "INSERT INTO product_images (product_id, image_path)
    VALUES
        ($count, 'Images/yapaycicek{$count1}.jpg')
        ";

    if($conn -> query($sql)){
        echo "Succeed";
    }
    $count++;
    $count1++;

    if($count > 94){
        break;
    }
}*/

$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (73, 'sepet, kavak, 50x27 cm')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}

$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (74, 'sepet, kavak, 2 adet')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (75, 'seramik vazo, açık turkuaz, 22 cm')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (76, 'dekoratif aksesuar, krom kaplama, 35 cm, Disko topu')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (77, 'sepet, rattan')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (78, 'dekoratif aksesuar, çok renkli')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (79, 'boy aynası, şeffaf, 20x120 cm')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (80, 'masa aynası, siyah, 27x43 cm')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (81, 'boy aynası, oval, 150x70 cm')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (82, 'masa aynası, altın rengi, 17 cm')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (83, 'boy aynası, siyah, 30x115 cm')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (84, 'masa aynası, paslanmaz çelik, 17 cm')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (85, 'toprak saksı, beyaz')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (86, 'toprak saksı, 4 adet')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$char = "'";
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (87, 'toprak saksı, sarı-kahverengi, 2`li set')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (88, 'toprak saksı, mavi, 2 adet')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (89, 'toprak saksı, yeşil, 2 adet')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (90, 'seramik saksı, çok renkli')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (91, 'yapay çiçek, söğüt/kıvrımlı')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (92, 'yapay çiçek, karanfil')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (93, 'saksılı yapay bitki, kaktüs')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}
$sql = "INSERT INTO product_details (product_id, description)
VALUES
    (94, 'yapay çelenk, okaliptüs')
    ";

if($conn -> query($sql)){
    echo "Succeed";
}


$count = 149;

while($count <= 153){
    $sql = "DELETE FROM product_images
    WHERE id = $count";

    if($conn -> query($sql)) echo "Succeed";

    $count++;
    
}
? -->>
<!--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html> -->