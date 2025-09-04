<?= $this->extend('layouts/seller_layout') ?>

<?= $this->section('title') ?>
    Detail Pesanan - <?= esc($order['invoice_number']) ?>
<?= $this->endSection() ?>

<?php $this->setVar('activePage', 'orders'); ?>

<?= $this->section('seller_content') ?>
    <div class="d-flex align-items-center mb-4">
        <a href="<?= route_to('seller.orders') ?>" class="btn btn-link p-0 me-3 text-decoration-none" style="color: var(--purun-dark-green);"><i class="bi bi-arrow-left fs-3"></i></a>
        <div>
            <h2 class="seller-header mb-0">Detail Pesanan</h2>
            <span class="text-muted"><?= esc($order['invoice_number']) ?></span>
        </div>
    </div>

    <?php if(session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card seller-card mb-4">
                <div class="card-header">Produk Dipesan</div>
                <div class="card-body">
                    <?php foreach ($orderDetails as $item): ?>
                    <div class="d-flex mb-3">
                        <img src="/uploads/products/<?= esc($item['product_image']) ?>" class="rounded" style="width: 70px; height: 70px; object-fit: cover;" alt="<?= esc($item['product_name']) ?>">
                        <div class="ms-3">
                            <h6 class="mb-1"><?= esc($item['product_name']) ?></h6>
                            <p class="mb-0 text-muted"><?= esc($item['quantity']) ?> barang x Rp <?= number_format($item['price_at_purchase'], 0, ',', '.') ?></p>
                        </div>
                        <div class="ms-auto fw-bold">Rp <?= number_format($item['quantity'] * $item['price_at_purchase'], 0, ',', '.') ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card seller-card">
                 <div class="card-header">Info Pelanggan & Pengiriman</div>
                 <div class="card-body">
                     <strong>Nama Pelanggan:</strong><p><?= esc($order['customer_name']) ?></p>
                     <strong>Alamat Pengiriman:</strong>
                     <?php if($shippingAddress): ?>
                        <p class="mb-0"><?= esc($shippingAddress['recipient_name']) ?> (<?= esc($shippingAddress['phone_number']) ?>)</p>
                        <p class="text-muted"><?= esc($shippingAddress['address']) ?>, <?= esc($shippingAddress['city']) ?>, <?= esc($shippingAddress['province']) ?> <?= esc($shippingAddress['postal_code']) ?></p>
                    <?php else: ?><p class="text-muted">Alamat tidak tersedia.</p><?php endif; ?>
                 </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card seller-card">
                <div class="card-header">Aksi Pesanan</div>
                <div class="card-body">
                    <?php if($order['status'] == 'processing'): ?>
                        <p class="text-muted small">Silakan periksa ketersediaan produk sebelum melanjutkan.</p>
                        
                        <form action="<?= route_to('seller.orders.ship') ?>" method="post" class="d-grid mb-3">
                            <?= csrf_field() ?>
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-purun-primary"><i class="bi bi-box-arrow-up me-2"></i>Kirim Pesanan</button>
                        </form>
                        
                        <hr>
                        <p class="text-muted small">Jika ingin membatalkan, hubungi pembeli terlebih dahulu.</p>
                        
                        <div class="d-grid mb-3">
                            <a href="<?= route_to('chat.from_order', $order['id']) ?>" class="btn btn-outline-secondary" id="contactBuyerBtn">
                                <i class="bi bi-chat-right-text-fill me-2"></i>Hubungi Pembeli
                            </a>
                        </div>
                        
                        <form action="<?= route_to('seller.orders.cancel') ?>" method="post" class="d-grid" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok akan dikembalikan.');">
                             <?= csrf_field() ?>
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-circle me-2"></i>Konfirmasi Pembatalan
                            </button>
                        </form>
                    <?php else: ?>
                        <p>Tidak ada aksi yang tersedia untuk status pesanan <span class="fw-bold">"<?= ucwords(str_replace('_', ' ', $order['status'])) ?>"</span>.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Script untuk menangani chat via AJAX tanpa pindah halaman
document.addEventListener('DOMContentLoaded', function() {
    const contactButton = document.getElementById('contactBuyerBtn');

    if (contactButton) {
        contactButton.addEventListener('click', async function(event) {
            event.preventDefault(); 
            
            const url = this.href;
            const originalContent = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghubungi...';
            this.disabled = true;

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                if (data.success) {
                    if (window.KUBE_CHAT) {
                        window.KUBE_CHAT.openToConversation(data.conversation_id);
                    }
                } else {
                    alert(data.error || 'Terjadi kesalahan.');
                }
            } catch (error) {
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