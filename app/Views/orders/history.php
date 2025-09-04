<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Riwayat Pesanan
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <h2 class="mb-4 checkout-header">Riwayat Pesanan Saya</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
            <div class="card order-history-card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <strong class="me-3">No. Invoice: <?= esc($order['invoice_number']) ?></strong>
                        <small class="text-muted">Tanggal: <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></small>
                    </div>
                    <?php
                        $statusClass = 'status-' . strtolower($order['status']);
                        $statusText = ucwords(str_replace('_', ' ', $order['status']));
                    ?>
                    <span class="status-badge <?= $statusClass ?>"><?= esc($statusText) ?></span>
                </div>
                <div class="card-body">
                    <?php if (!empty($order['details'])): $item = $order['details'][0]; ?>
                    <div class="d-flex align-items-center">
                        <div class="order-item-preview me-3">
                            <img src="/uploads/products/<?= esc($item['product_image']) ?>" alt="<?= esc($item['product_name']) ?>">
                        </div>
                        <div>
                            <div class="fw-bold"><?= esc($item['product_name']) ?></div>
                            <small class="text-muted"><?= esc($item['quantity']) ?> barang</small>
                            <?php if(count($order['details']) > 1): ?>
                                <small class="text-muted">dan <?= count($order['details']) - 1 ?> produk lainnya.</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">Total Pembayaran:</span>
                        <strong class="fs-5" style="color: var(--purun-maroon);">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong>
                    </div>
                    <div>
                        <?php if($order['status'] == 'pending_payment'): ?>
                            <a href="<?= site_url('order/track/' . $order['invoice_number']) ?>" class="btn btn-sm btn-purun-secondary">
                                <i class="bi bi-credit-card-fill me-1"></i> Bayar Sekarang
                            </a>
                        <?php else: ?>
                            <a href="<?= site_url('order/track/' . $order['invoice_number']) ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye-fill me-1"></i> Lihat Detail
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5 card cart-empty-card">
            <h4 class="mt-3">Anda Belum Memiliki Riwayat Pesanan</h4>
            <a href="/" class="btn btn-purun-primary mt-2">Mulai Belanja</a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>