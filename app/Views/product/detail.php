<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    <?= esc($product['product_name']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb breadcrumb-purun">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item"><a href="/#produk">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc(word_limiter($product['product_name'], 5)) ?></li>
        </ol>
    </nav>
    <div class="row g-5">
        <div class="col-lg-6">
            <img src="/uploads/products/<?= esc($product['product_image']) ?>" class="product-image-main" alt="<?= esc($product['product_name']) ?>" onerror="this.onerror=null;this.src='<?= base_url('images/produk-placeholder.jpg') ?>';">
        </div>
        <div class="col-lg-6 d-flex flex-column">
            <div>
                <h1 class="product-title"><?= esc($product['product_name']) ?></h1>
                <p class="product-price">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                <div class="product-meta-info">
                    <div class="meta-item">
                        <i class="bi bi-shop"></i>
                        <span>Dijual oleh <a href="#"><?= esc($store['store_name']) ?></a></span>
                    </div>
                    <?php $stockClass = ($product['stock'] <= 5) ? 'low-stock' : ''; ?>
                    <div class="meta-item product-stock <?= $stockClass ?>">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Stok: <?= esc($product['stock']) ?></span>
                    </div>
                </div>
                <h5 class="mt-4 mb-3" style="font-family: var(--font-brand);">Deskripsi Produk</h5>
                <p class="card-text text-muted" style="line-height: 1.8;"><?= nl2br(esc($product['description'])) ?></p>
            </div>
            <div class="mt-auto pt-4">
                <div class="product-actions">
                    <div class="input-group">
                        <input type="number" name="quantity" id="quantity_input" class="form-control text-center" value="1" min="1" max="<?= $product['stock'] ?>" aria-label="Jumlah">
                    </div>
                    <form action="/cart/add" method="post" class="ms-auto">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="quantity" id="quantity_cart" value="1"> 
                        <button type="submit" class="btn btn-purun-primary"><i class="bi bi-cart-plus-fill me-2"></i>Keranjang</button>
                    </form>
                    <form action="/checkout/initiate" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="quantity" id="quantity_buy_now" value="1"> 
                        <button type="submit" class="btn btn-purun-secondary"><i class="bi bi-bag-check-fill me-2"></i>Beli Sekarang</button>
                    </form>
                </div>
                
                <?php if(session()->get('user_id') && $store['user_id'] != session()->get('user_id')): ?>
                <div class="mt-3">
                    <a href="<?= route_to('chat.from_product', $store['user_id'], $product['id']) ?>" class="btn btn-purun-chat w-100" id="chatSellerBtn">
                        <i class="bi bi-chat-right-text-fill me-2"></i>Chat Penjual
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Script untuk menyamakan jumlah barang di semua form
    const quantityInput = document.getElementById('quantity_input');
    const quantityCart = document.getElementById('quantity_cart');
    const quantityBuyNow = document.getElementById('quantity_buy_now');
    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            if(quantityCart) { quantityCart.value = this.value; }
            if(quantityBuyNow) { quantityBuyNow.value = this.value; }
        });
        // Inisialisasi
        if(quantityCart) { quantityCart.value = quantityInput.value; }
        if(quantityBuyNow) { quantityBuyNow.value = quantityInput.value; }
    }
</script>

<script>
// (BARU) Script untuk menangani chat via AJAX tanpa pindah halaman
document.addEventListener('DOMContentLoaded', function() {
    const chatButton = document.getElementById('chatSellerBtn');

    if (chatButton) {
        chatButton.addEventListener('click', async function(event) {
            event.preventDefault(); 
            
            const url = this.href;
            const originalContent = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Membuka Chat...';
            this.disabled = true;

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await response.json();

                if (data.success) {
                    // Panggil fungsi global dari main_layout.php
                    if (window.KUBE_CHAT) {
                        window.KUBE_CHAT.openToConversation(data.conversation_id);
                    }
                } else {
                    alert(data.error || 'Terjadi kesalahan. Silakan coba lagi.');
                }
            } catch (error) {
                console.error('Error starting chat:', error);
                alert('Tidak dapat terhubung ke server chat.');
            } finally {
                this.innerHTML = originalContent;
                this.disabled = false;
            }
        });
    }
});
</script>
<?= $this->endSection() ?>