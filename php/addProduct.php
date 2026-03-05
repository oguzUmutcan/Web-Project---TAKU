<?php
$category;
$subcategory;
$name;
$price;
$stock_quantity;
$description;
$color;
$material;
$width_cm;
$height_cm;
$depth_cm;
$warranty_months;
$product_image;

if($_SERVER['REQUEST_METHOD'] == "POST"){
    require '../php/dbConnection.php';

    $category         = isset($_POST['category'])         ? trim($_POST['category'])         : '';
    $subcategory      = isset($_POST['subcategory'])      ? trim($_POST['subcategory'])      : '';
    $name             = isset($_POST['name'])             ? trim($_POST['name'])             : '';
    $price            = isset($_POST['price'])            ? floatval($_POST['price'])        : 0.0;
    $stock_quantity   = isset($_POST['stock_quantity'])   ? intval($_POST['stock_quantity']) : 0;
    $description      = isset($_POST['description'])      ? trim($_POST['description'])      : '';
    $color            = isset($_POST['color'])            ? trim($_POST['color'])            : '';
    $material         = isset($_POST['material'])         ? trim($_POST['material'])         : '';
    $width_cm         = isset($_POST['width_cm'])         ? floatval($_POST['width_cm'])     : 0.0;
    $height_cm        = isset($_POST['height_cm'])        ? floatval($_POST['height_cm'])    : 0.0;
    $depth_cm         = isset($_POST['depth_cm'])         ? floatval($_POST['depth_cm'])     : 0.0;
    $warranty_months  = isset($_POST['warranty_months'])  ? intval($_POST['warranty_months']): 0;

    /* if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['product_image']['tmp_name'];
        $fileName      = $_FILES['product_image']['name'];
        $fileSize      = $_FILES['product_image']['size'];
        $fileType      = $_FILES['product_image']['type'];

        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'Images' . DIRECTORY_SEPARATOR;

        // Eğer Images klasörü yoksa oluştur
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                die('Images klasörü oluşturulamadı.');
            }
        }

        // Ardından taşıma işlemi
        $fileTmpPath = $_FILES['product_image']['tmp_name'];
        $fileName    = basename($_FILES['product_image']['name']);
        $destPath    = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $product_image = 'Images/' . $fileName;
        } else {
            die('Dosya taşınırken bir hata oluştu.');
        }
    } else {
        $product_image = '';
    } */

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['product_image']['tmp_name'];
        $fileName      = $_FILES['product_image']['name'];
        $fileSize      = $_FILES['product_image']['size'];
        $fileType      = $_FILES['product_image']['type'];

        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'Images' . DIRECTORY_SEPARATOR;

        // Eğer Images klasörü yoksa oluştur
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                die('Images klasörü oluşturulamadı.');
            }
        }

        // Ardından taşıma işlemi
        $fileTmpPath = $_FILES['product_image']['tmp_name'];
        $fileName    = basename($_FILES['product_image']['name']);
        $destPath    = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $product_image = 'Images/' . $fileName;
        } else {
            die('Dosya taşınırken bir hata oluştu.');
        }
    } else {
        $product_image = '';
    }

    $stmt = $conn->prepare("INSERT INTO products (category_id, subcategory_id, name, price, stock_quantity) 
    VALUES 
        (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        'iisdi',
        $category,         
        $subcategory,      
        $name,             
        $price,            
        $stock_quantity
    );

    if ($stmt->execute()) {
        echo "Kayıt başarıyla eklendi. Son ID: " . $stmt->insert_id;
    } else {
        echo "Execute hatası: " . $stmt->error;
    }

    $lastID = $stmt -> insert_id;

    $stmt->close();

    $stmtDet = $conn->prepare("INSERT INTO product_details (product_id, description, color, material,width_cm, height_cm, depth_cm, warranty_months) 
    VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmtDet->bind_param(
        'isssdddi',
        $lastID,  // int
        $description,    // string
        $color,          // string
        $material,       // string
        $width_cm,       // double
        $height_cm,      // double
        $depth_cm,       // double
        $warranty_months // int
    );

    $stmtDet->execute();
    $stmtDet->close();

    $stmtImg = $conn->prepare("INSERT INTO product_images (product_id, image_path) 
    VALUES 
        (?, ?)
    ");
    $stmtImg->bind_param(
        'is',
        $lastID,      // int
        $product_image   // string
    );
    $stmtImg->execute();
    $stmtImg->close();

    // 6) Commit
    $conn->commit();

    echo "Ürün, detay ve resim başarıyla eklendi. Ürün ID: {$lastID}";

    $conn -> close();
}
header("location:../adminPage/addProductPage.php");
?>