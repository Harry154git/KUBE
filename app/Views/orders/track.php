<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    <?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order Details: <?= esc($order['invoice_number']) ?></h5>
                    
                    <!-- ===== NEW CHAT BUTTON ===== -->
                    <?php if(isset($order['store_id'])): ?>
                    <a href="<?= route_to('chat.start_with_seller', $order['store_id']) ?>" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-chat-right-text-fill me-1"></i> Contact Seller
                    </a>
                    <?php endif; ?>
                    <!-- ===== END OF NEW CHAT BUTTON ===== -->

                </div>
                <div class="card-body">
                    <p><strong>Order Date:</strong> <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
                    <hr>
                    <?php foreach ($orderDetails as $item): ?>
                    <div class="d-flex mb-3">
                        <img src="/uploads/products/<?= esc($item['product_image']) ?>" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;" alt="<?= esc($item['product_name']) ?>">
                        <div class="ms-3">
                            <h6 class="mb-1"><?= esc($item['product_name']) ?></h6>
                            <p class="mb-0 text-muted"><?= esc($item['quantity']) ?> x Rp <?= number_format($item['price_at_purchase'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Shipping Status -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Shipping Status</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-primary fw-bold"><?= esc(ucfirst($order['status'])) ?></h6>
                    <p class="text-muted">Your order is being processed.</p>
                </div>
            </div>

            <!-- Shipping & Payment Details -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Shipping Info</h5>
                </div>
                <div class="card-body">
                    <strong>Shipping Address:</strong>
                    <?php if($shippingAddress): ?>
                        <p class="mb-2"><?= esc($shippingAddress['address']) ?>, <?= esc($shippingAddress['city']) ?>, <?= esc($shippingAddress['province']) ?> <?= esc($shippingAddress['postal_code']) ?></p>
                    <?php else: ?>
                        <p class="text-muted mb-2">Address not available.</p>
                    <?php endif; ?>

                    <strong>Shipping Method:</strong>
                    <p class="mb-2"><?= esc($order['shipping_method']) ?></p>
                    <hr>
                    <strong>Payment Method:</strong>
                    <p class="mb-0"><?= esc($order['payment_method']) ?></p>
                </div>
                <div class="card-footer">
                    <strong>Total Payment: <span class="float-end text-danger fw-bold">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span></strong>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
