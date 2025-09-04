<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Checkout
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="d-flex align-items-center mb-4">
        <a href="/cart" class="btn btn-link p-0 me-3 text-decoration-none" style="color: var(--purun-dark-green);"><i class="bi bi-arrow-left fs-3"></i></a>
        <h2 class="mb-0 checkout-header">Checkout</h2>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="/checkout/process" method="post">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-lg-7">
                <div class="checkout-card">
                    <div class="card-header"><h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Alamat Pengiriman</h5></div>
                    <div class="card-body">
                        <?php if (empty($addresses)): ?>
                            <p>Anda belum memiliki alamat. <a href="/addresses">Tambah Alamat</a></p>
                        <?php else: ?>
                            <?php foreach ($addresses as $address): ?>
                                <div class="choice-option mb-3">
                                    <input class="form-check-input" type="radio" name="address_id" id="address<?= $address['id'] ?>" value="<?= $address['id'] ?>" <?= $address['is_primary'] ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="address<?= $address['id'] ?>">
                                        <strong><?= esc($address['label']) ?></strong> (<?= esc($address['recipient_name']) ?>)
                                        <p class="mb-0 text-muted small"><?= esc($address['address']) ?>, <?= esc($address['city']) ?>, <?= esc($address['province']) ?> | <?= esc($address['phone_number']) ?></p>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <a href="/addresses" class="btn btn-outline-secondary btn-sm mt-2">Kelola Alamat</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php foreach($groupedItems as $storeId => $store): ?>
                <div class="checkout-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-shop me-2"></i>Pesanan dari <?= esc($store['store_name']) ?? 'Toko Tanpa Nama' ?></h5>
                    </div>
                    <div class="card-body">
                        <?php foreach($store['items'] as $item): ?>
                            <div class="checkout-product-item mb-3">
                                <img src="/uploads/products/<?= esc($item['product_image']) ?>" onerror="this.onerror=null;this.src='<?= base_url('images/produk-placeholder.jpg') ?>';">
                                <div>
                                    <div class="fw-bold"><?= esc($item['product_name']) ?></div>
                                    <div class="text-muted small"><?= $item['quantity'] ?> barang x Rp <?= number_format($item['price'], 0, ',', '.') ?></div>
                                </div>
                                <div class="ms-auto fw-bold">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></div>
                            </div>
                        <?php endforeach; ?>
                        
                        <hr>
                        <h6 class="mb-3">Opsi Pengiriman</h6>
                        <div class="choice-option mb-3">
                             <input class="form-check-input shipping-option" type="radio" name="shipping_method[<?= $storeId ?>]" id="shippingReguler<?= $storeId ?>" value="Reguler" data-cost="15000" checked required>
                             <label class="form-check-label d-flex justify-content-between" for="shippingReguler<?= $storeId ?>">
                                 <span>Reguler (Estimasi 3-5 hari)</span>
                                 <span>Rp 15.000</span>
                             </label>
                        </div>
                        <div class="choice-option">
                             <input class="form-check-input shipping-option" type="radio" name="shipping_method[<?= $storeId ?>]" id="shippingExpress<?= $storeId ?>" value="Express" data-cost="25000" required>
                             <label class="form-check-label d-flex justify-content-between" for="shippingExpress<?= $storeId ?>">
                                 <span>Express (Estimasi 1-2 hari)</span>
                                 <span>Rp 25.000</span>
                             </label>
                        </div>
                        
                        <hr>
                        <h6 class="mb-2">Catatan untuk Penjual (Opsional)</h6>
                        <textarea name="seller_notes[<?= $storeId ?>]" class="form-control" rows="2" placeholder="Tinggalkan pesan untuk penjual ini..."></textarea>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="card summary-card sticky-top">
                    <div class="card-header"><h5 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i>Ringkasan Pembayaran</h5></div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal Produk</span>
                            <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total Ongkos Kirim</span>
                            <span id="shippingCostText">Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total Pembayaran</span>
                            <span id="grandTotalText">Rp 0</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <h6 class="mb-3">Metode Pembayaran</h6>
                        <select name="payment_method" class="form-select" required>
                            <option value="QRIS" selected>QRIS (Scan QR Code)</option>
                            <option value="COD" disabled>Bayar di Tempat (Segera Hadir)</option>
                        </select>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                            <label class="form-check-label small" for="terms">
                                Saya menyetujui <a href="#">Syarat & Ketentuan</a> yang berlaku.
                            </label>
                        </div>
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-purun-secondary btn-lg">
                                <i class="bi bi-shield-check-fill me-2"></i>Buat Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subtotal = <?= $subtotal ?>;
    const shippingOptions = document.querySelectorAll('.shipping-option');
    const shippingCostText = document.getElementById('shippingCostText');
    const grandTotalText = document.getElementById('grandTotalText');

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

    function calculateTotal() {
        let totalShippingCost = 0;
        // Loop setiap grup opsi pengiriman
        document.querySelectorAll('.checkout-card').forEach(card => {
            const checkedShipping = card.querySelector('.shipping-option:checked');
            if (checkedShipping) {
                totalShippingCost += parseFloat(checkedShipping.dataset.cost);
            }
        });

        const grandTotal = subtotal + totalShippingCost;
        shippingCostText.textContent = formatCurrency(totalShippingCost);
        grandTotalText.textContent = formatCurrency(grandTotal);
    }

    shippingOptions.forEach(option => {
        option.addEventListener('change', calculateTotal);
    });

    // Kalkulasi awal saat halaman dimuat
    calculateTotal();
});
</script>
<?= $this->endSection() ?>