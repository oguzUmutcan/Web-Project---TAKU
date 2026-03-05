<?php
require '../php/dbConnection.php';

$res = $conn->query("SELECT id, name FROM categories ORDER BY name");
$categories = $res->fetch_all(MYSQLI_ASSOC);
$res->close();

$res1 = $conn->query("SELECT id, name FROM subcategories ORDER BY name");
$subcategories = $res1->fetch_all(MYSQLI_ASSOC);
$res1->close();

?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Paneli – Yeni Ürün Ekle</title>
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../Style/addProduct.css">
  <link rel="icon" href="../Images/TAKU (2).png"
      type="image/x-icon" />
</head>
<body>
    <div class="admin-dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="profile">
                <img src="../adminPage/Profil Photo/1747654588_WhatsApp Image 2024-11-22 at 00.28.58_3bb68e5f.jpg" alt="Yönetici Profil">
                <h2>Hoşgeldiniz, Yönetici!</h2>
            </div>
            <ul class="menu">
                <li><a href="../adminPage/managementAdmin.php#users"><i class="fa-solid fa-users"></i> Kullanıcı Yönetimi</a></li>
                <li><a href="../adminPage/managementAdmin.php#products"><i class="fa-solid fa-box"></i> Ürün Yönetimi</a></li>
                <li><a href="../adminPage/managementAdmin.php../adminPage/customerAdmin.php"><i class="fa-solid fa-cog"></i> Ayarlar</a></li>
                <li><a href="../adminPage/managementAdmin.php#logout" class="logout"><i class="fa-solid fa-sign-out-alt"></i> Çıkış Yap</a></li>
            </ul>
        </aside>
    
        <!-- Main Content -->
        <main class="main-content">
          <section>
            <h2>Ürün Yönetimi</h2>
            <p>Burada mevcut ürünleri yönetebilir veya yeni ürün ekleyebilirsiniz.</p>
    
            <!-- Güncellenmiş Form -->
            <form action="../php/addProduct.php" method="POST" enctype="multipart/form-data">
              <!-- @csrf -->
    
              <!-- 1. Kategori & Alt Kategori -->
              <div class="form-row">
                <div class="form-group">
                  <label for="category">Kategori <span style="color: red;">*</span></label>
                  <select id="category" name="category" required>
                    <option value="">Kategori seçin</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['id']) ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="subcategory">Alt Kategori</label>
                  <select id="subcategory" name="subcategory">
                    <option value="">Alt kategori seçin</option>
                    <?php foreach($subcategories as $cat1): ?>
                        <option value="<?= htmlspecialchars($cat1['id']) ?>">
                        <?= htmlspecialchars($cat1['name']) ?>
                        </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
    
              <!-- 2. Temel Ürün Bilgileri -->
              <div class="form-row">
                <div class="form-group">
                  <label for="name">Ürün Adı <span style="color: red;">*</span></label>
                  <input type="text" id="name" name="name" placeholder="Ürün adı" required>
                </div>
                <div class="form-group">
                  <label for="price">Fiyat (₺) <span style="color: red;">*</span></label>
                  <input type="number" step="0.01" id="price" name="price" placeholder="0.00" required>
                </div>
                <div class="form-group">
                  <label for="stock_quantity">Stok Adeti <span style="color: red;">*</span></label>
                  <input type="number" id="stock_quantity" name="stock_quantity" placeholder="0" required>
                </div>
              </div>
    
              <!-- 3. Detaylı Ürün Bilgileri (productDetails) -->
              <div class="form-group">
                <label for="description">Açıklama</label>
                <textarea id="description" name="description" rows="3" placeholder="Ürün açıklamasını girin"></textarea>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="color">Renk</label>
                  <input type="text" id="color" name="color" placeholder="Örn: Kırmızı">
                </div>
                <div class="form-group">
                  <label for="material">Malzeme</label>
                  <input type="text" id="material" name="material" placeholder="Örn: Ahşap">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="width_cm">Genişlik (cm)</label>
                  <input type="number" step="0.01" id="width_cm" name="width_cm" placeholder="Örn: 15.50">
                </div>
                <div class="form-group">
                  <label for="height_cm">Yükseklik (cm)</label>
                  <input type="number" step="0.01" id="height_cm" name="height_cm" placeholder="Örn: 20.00">
                </div>
                <div class="form-group">
                  <label for="depth_cm">Derinlik (cm)</label>
                  <input type="number" step="0.01" id="depth_cm" name="depth_cm" placeholder="Örn: 5.75">
                </div>
              </div>
              <div class="form-group">
                <label for="warranty_months">Garanti Süresi (ay)</label>
                <input type="number" id="warranty_months" name="warranty_months" placeholder="Örn: 12">
              </div>
    
              <!-- 4. Ürün Fotoğrafı -->
              <div class="form-group">
                <label for="product_image">Ürün Fotoğrafı</label>
                <input type="file" id="product_image" name="product_image" accept="image/*">
              </div>
    
              <!-- Kaydet Butonu -->
              <button type="submit">Yeni Ürün Kaydet</button>
            </form>
          </section>
        </main>
      </div>
</body>
</html>