<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Detail Pesanan
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="d-flex align-items-center mb-4">
        <a href="/order/history" class="btn btn-link p-0 me-3 text-decoration-none" style="color: var(--purun-dark-green);"><i class="bi bi-arrow-left fs-3"></i></a>
        <h2 class="mb-0 checkout-header">Detail Pesanan</h2>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
     <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card checkout-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Invoice: <?= esc($order['invoice_number']) ?></h5>
                </div>
                <div class="card-body">
                    <p><strong>Tanggal Pesanan:</strong> <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
                    <hr>
                    <h6 class="mb-3">Produk yang Dipesan:</h6>
                    <?php foreach ($orderDetails as $item): ?>
                    <div class="d-flex mb-3">
                        <img src="/uploads/products/<?= esc($item['product_image']) ?>" class="rounded" style="width: 80px; height: 80px; object-fit: cover;" alt="<?= esc($item['product_name']) ?>">
                        <div class="ms-3">
                            <h6 class="mb-1"><?= esc($item['product_name']) ?></h6>
                            <p class="mb-0 text-muted"><?= esc($item['quantity']) ?> x Rp <?= number_format($item['price_at_purchase'], 0, ',', '.') ?></p>
                        </div>
                        <div class="ms-auto fw-bold">Rp <?= number_format($item['quantity'] * $item['price_at_purchase'], 0, ',', '.') ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card checkout-card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-truck me-2"></i>Status Pengiriman</h5></div>
                <div class="card-body">
                    <?php
                        $statusClass = 'status-' . strtolower($order['status']);
                        $statusText = ucwords(str_replace('_', ' ', $order['status']));
                    ?>
                    <h6 class="fw-bold status-badge <?= $statusClass ?>"><?= esc($statusText) ?></h6>
                    </div>
            </div>

            <div class="card checkout-card">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Info Pengiriman & Pembayaran</h5></div>
                <div class="card-body">
                    <strong>Alamat Pengiriman:</strong>
                    <?php if($shippingAddress): ?>
                        <p class="mb-2"><?= esc($shippingAddress['recipient_name']) ?><br><?= esc($shippingAddress['phone_number']) ?><br><?= esc($shippingAddress['address']) ?>, <?= esc($shippingAddress['city']) ?>, <?= esc($shippingAddress['province']) ?> <?= esc($shippingAddress['postal_code']) ?></p>
                    <?php else: ?>
                        <p class="text-muted mb-2">Alamat tidak tersedia.</p>
                    <?php endif; ?>
                    <strong>Metode Pengiriman:</strong>
                    <p class="mb-2"><?= esc($order['shipping_method']) ?></p>
                    <hr>
                    <strong>Metode Pembayaran:</strong>
                    <p class="mb-0"><?= esc($order['payment_method']) ?></p>
                </div>
                <div class="card-footer">
                    <strong>Total: <span class="float-end fw-bold fs-5" style="color: var(--purun-maroon);">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span></strong>
                </div>
            </div>

            <div class="d-grid mt-3">
                <?php if($order['status'] == 'pending_payment'): ?>
                    <button type="button" class="btn btn-purun-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="bi bi-credit-card-fill me-2"></i> Bayar Sekarang
                    </button>
                <?php elseif($order['status'] == 'shipped'): ?>
                    <form action="<?= route_to('order.receive') ?>" method="post" onsubmit="return confirm('Apakah Anda yakin sudah menerima pesanan ini dengan baik? Aksi ini tidak dapat dibatalkan.');">
                        <?= csrf_field() ?>
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-purun-primary btn-lg w-100">
                            <i class="bi bi-check-circle-fill me-2"></i> Pesanan Diterima
                        </button>
                    </form>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>

<?php if($order['status'] == 'pending_payment'): ?>
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Pembayaran QRIS</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body qris-modal-body">
        <p>Silakan pindai (scan) kode QR di bawah ini dengan aplikasi pembayaran Anda.</p>
        
        <img src="https://placehold.co/250x250/FFFFFF/333333?text=KODE+QRIS" alt="QRIS Code">
        <h5 class="mt-3">Total Pembayaran</h5>
        <h3 class="fw-bold" style="color: var(--purun-maroon);">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></h3>
        <p class="text-muted small mt-3">Setelah melakukan pembayaran, klik tombol di bawah ini untuk mengonfirmasi.</p>
      </div>
      <div class="modal-footer d-grid">
        <form action="<?= route_to('order.confirm_payment') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <button type="submit" class="btn btn-success w-100">Saya Sudah Membayar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>