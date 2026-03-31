<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    <?= esc($product['product_name']) ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <!-- Anda bisa memuat CSS spesifik halaman di sini jika perlu -->
    <link rel="stylesheet" href="<?= base_url('css/pages/_product-detail.css') ?>">
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<!-- (BARU) Menggunakan struktur dan class dari desain AI -->
<div class="product-detail-container">
    <div class="breadcrumb-bar">
        <h3 class="breadcrumb-text">/ <a href="/home">Home</a> / <a href="/#produk">Produk</a> / <?= esc(word_limiter($product['product_name'], 5)) ?></h3>
    </div>

    <div class="product-main-info">
        <!-- Kolom Gambar Produk -->
        <div class="product-image-wrapper">
             <img class="product-image" loading="lazy" alt="<?= esc($product['product_name']) ?>" src="/uploads/products/<?= esc($product['product_image']) ?>" onerror="this.onerror=null;this.src='<?= base_url('images/produk-placeholder.jpg') ?>';" />
        </div>

        <!-- Kolom Detail Teks Produk -->
        <section class="product-text-details">
            <h1 class="product-name"><?= esc($product['product_name']) ?></h1>
            
            <div class="product-rating-sold">
                <div class="rating">
                    <h3 class="rating-text">5</h3>
                    <img class="star-icon" loading="lazy" alt="star" src="https://api.iconify.design/material-symbols/star-rounded.svg?color=%23e09f3e" />
                </div>
                <div class="sold-info">
                    <h3 class="sold-text">Terjual 20+</h3>
                </div>
            </div>

            <div class="price-wrapper">
                <h2 class="product-price">Rp <?= number_format($product['price'], 0, ',', '.') ?></h2>
            </div>
            
            <div class="details-divider"></div>

            <div class="description-section">
                <h3 class="section-title">Detail Produk</h3>
                <div class="description-text">
                    <?= nl2br(esc($product['description'])) ?>
                </div>
            </div>

            <div class="details-divider"></div>
            
            <div class="store-info">
                <img class="store-icon" loading="lazy" alt="store" src="https://api.iconify.design/bxs/store.svg?color=%23335c67" />
                <h3 class="store-name"><a href="#"><?= esc($store['store_name']) ?></a></h3>
            </div>
        </section>

        <!-- Kolom Aksi (Beli, Keranjang, dll) -->
        <section class="product-actions-card">
            <div class="actions-content">
                <div class="quantity-section">
                    <div class="quantity-title">Atur jumlah pembelian</div>
                </div>

                <div class="quantity-controls-group">
                    <div class="quantity-stepper">
                        <button class="stepper-btn" id="btn-minus">-</button>
                        <input type="number" id="quantity_input" class="quantity-input" value="1" min="1" max="<?= $product['stock'] ?>">
                        <button class="stepper-btn" id="btn-plus">+</button>
                    </div>
                    <div class="stock-info">Stok : <?= esc($product['stock']) ?></div>
                </div>
                
                <!-- Form Add to Cart -->
                <form action="/cart/add" method="post" class="action-form">
                     <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="quantity" id="quantity_cart" value="1"> 
                    <button type="submit" class="btn-action btn-add-cart requires-auth">
                        + Keranjang
                    </button>
                </form>

                <!-- Form Buy Now -->
                <form action="/checkout/initiate" method="post" class="action-form">
                     <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="quantity" id="quantity_buy_now" value="1">
                    <button type="submit" class="btn-action btn-buy-now requires-auth">
                       Beli Sekarang
                    </button>
                </form>
                
                 <?php if(session()->get('user_id') && $store['user_id'] != session()->get('user_id')): ?>
                    <a href="<?= route_to('chat.from_product', $store['user_id'], $product['id']) ?>" class="btn-action btn-chat" id="chatSellerBtn">
                        <img class="chat-icon" loading="lazy" alt="chat" src="https://api.iconify.design/material-symbols/chat.svg?color=%23000000" />
                        <span>Tanya Penjual</span>
                    </a>
                 <?php endif; ?>
            </div>
        </section>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity_input');
    const quantityCart = document.getElementById('quantity_cart');
    const quantityBuyNow = document.getElementById('quantity_buy_now');
    const btnPlus = document.getElementById('btn-plus');
    const btnMinus = document.getElementById('btn-minus');
    const maxStock = <?= (int)$product['stock'] ?>;

    function updateFormQuantities(value) {
        if (quantityCart) quantityCart.value = value;
        if (quantityBuyNow) quantityBuyNow.value = value;
    }

    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            let val = parseInt(this.value);
            if (isNaN(val) || val < 1) val = 1;
            if (val > maxStock) val = maxStock;
            this.value = val;
            updateFormQuantities(val);
        });

        btnPlus.addEventListener('click', function() {
            let currentVal = parseInt(quantityInput.value);
            if (currentVal < maxStock) {
                quantityInput.value = currentVal + 1;
                updateFormQuantities(currentVal + 1);
            }
        });

        btnMinus.addEventListener('click', function() {
            let currentVal = parseInt(quantityInput.value);
            if (currentVal > 1) {
                quantityInput.value = currentVal - 1;
                updateFormQuantities(currentVal - 1);
            }
        });
        
        // Inisialisasi
        updateFormQuantities(quantityInput.value);
    }
    
    // Script chat AJAX Anda yang sudah ada bisa diletakkan di sini
});
</script>
<?= $this->endSection() ?>
