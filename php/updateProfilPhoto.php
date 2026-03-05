<?php

require "../php/dbConnection.php";
// updateProfilPhoto.php içinde

if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $tmpPath  = $_FILES['profile_photo']['tmp_name'];
    $origName = basename($_FILES['profile_photo']['name']);
    // dilersen benzersizleştir:
    $uniqueName = time() . '_' . $origName;

    // Hedef klasör: ../adminPage/Profil Photo/
    $uploadDir = __DIR__
        . DIRECTORY_SEPARATOR . '..'
        . DIRECTORY_SEPARATOR . 'adminPage'
        . DIRECTORY_SEPARATOR . 'Profil Photo'
        . DIRECTORY_SEPARATOR;

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        die('Profil Photo klasörü oluşturulamadı.');
    }

    $destPath = $uploadDir . $uniqueName;
    if (!move_uploaded_file($tmpPath, $destPath)) {
        die('Dosya taşınırken bir hata oluştu.');
    }

    // Veritabanına yazdırmak için kullanılacak yol
    $profilePhotoPath = 'adminPage/Profil Photo/' . $uniqueName;

    // 4) UPDATE sorgusunu hazırla ve çalıştır
    $sql  = "UPDATE admins SET profilPhoto = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Sorgu hazırlama hatası: ' . $conn->error);
    }

    // İki parametre: 's' = string, 'i' = integer
    $stmt->bind_param('si', $profilePhotoPath, $_SESSION['admin_id']);
    $stmt->execute();
    // 5) Sonucu kontrol et
    if ($stmt->affected_rows > 0) {
        echo 'Profil fotoğrafı başarıyla güncellendi: ' . htmlspecialchars($profilePhotoPath);

        header("location: ../adminPage/managementAdmin.php");
    } else {
        echo 'Değişiklik yapılmadı (aynı resim olabilir veya kullanıcı bulunamadı).';
    }

    $stmt->close();
} else {
    echo 'Dosya yüklenmedi ya da bir hata oluştu.';
}

$conn->close();
