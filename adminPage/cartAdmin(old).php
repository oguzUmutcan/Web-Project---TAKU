<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alışveriş Sepeti</title>
    <link rel="stylesheet" href="../Style/cartAdmin.css">
    <link rel="icon" href="../Images/TAKU (2).png" type="image/x-icon">
</head>
<body>
    <div class="cart-container">
        <h1>Alışveriş Sepetiniz</h1>
        <table>
            <thead>
                <tr>
                    <th>Ürün</th>
                    <th>Eski Fiyat</th>
                    <th>İndirimli Fiyat</th>
                    <th>Miktar</th>
                    <th>Toplam</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="Ürün">Eva Sabit Masa</td>
                    <td data-label="Eski Fiyat"><span class="old-price">5.433,00 TL</span></td>
                    <td data-label="İndirimli Fiyat"><span class="discounted-price">4.890,00 TL</span></td>
                    <td data-label="Miktar">
                        <button class="decrease">-</button>
                        <span class="quantity">3</span>
                        <button class="increase">+</button>
                    </td>
                    <td data-label="Toplam" class="total-price">14.670,00 TL</td>
                    <td data-label="İşlemler">
                        <button class="remove">Kaldır</button>
                    </td>
                </tr>
                <tr>
                    <td data-label="Ürün">Eva 6503 2'li Sandalye</td>
                    <td data-label="Eski Fiyat"><span class="old-price">6.341,00 TL</span></td>
                    <td data-label="İndirimli Fiyat"><span class="discounted-price">5.707,00 TL</span></td>
                    <td data-label="Miktar">
                        <button class="decrease">-</button>
                        <span class="quantity">4</span>
                        <button class="increase">+</button>
                    </td>
                    <td data-label="Toplam" class="total-price">22.828,00 TL</td>
                    <td data-label="İşlemler">
                        <button class="remove">Kaldır</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="cart-summary">
            <p>Toplam Tutar: <span id="grand-total">37.498,00 TL</span></p>
            <button class="checkout">Satın Al</button>
        </div>
    </div>

    <script >
        document.querySelectorAll('.increase').forEach(button => {
        button.addEventListener('click', (e) => {
        const quantityElement = e.target.parentNode.querySelector('.quantity');
        let quantity = parseInt(quantityElement.textContent);
        quantity++;
        quantityElement.textContent = quantity;
        updateTotal(e.target, quantity);
       });
    });

        document.querySelectorAll('.decrease').forEach(button => {
        button.addEventListener('click', (e) => {
        const quantityElement = e.target.parentNode.querySelector('.quantity');
        let quantity = parseInt(quantityElement.textContent);
        if (quantity > 1) {
            quantity--;
            quantityElement.textContent = quantity;

            updateTotal(e.target, quantity);}
        });
    });

        document.querySelectorAll('.remove').forEach(button => {
        button.addEventListener('click', (e) => {
        const row = e.target.parentNode.parentNode;
        row.remove();
        updateGrandTotal();
        });
    });

    // Her ürün için toplam fiyatı güncelle
    function updateTotal(button, quantity) {
        const row = button.parentNode.parentNode;
        const priceText = row.querySelector('.discounted-price').textContent;
        const price = parseFloat(priceText.replace(' TL', '').replace(',', '')); // Fiyatı formatla
        const total = price * quantity;
        row.querySelector('.total-price').textContent = `${total.toLocaleString('tr-TR')} TL`; // Formatlı toplam
        updateGrandTotal();
    }

    // Sepet genel toplamını güncelle
    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.total-price').forEach(total => {
            const totalText = total.textContent;
            const totalValue = parseFloat(totalText.replace(' TL', '').replace(',', '')); // Formatla
            grandTotal += totalValue;
        });
        document.getElementById('grand-total').textContent = `${grandTotal.toLocaleString('tr-TR')} TL`; // Formatlı toplam
    }
    </script>
</body>
</html>
