<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Checkout
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="d-flex align-items-center mb-4">
        <a href="/cart" class="btn btn-link p-0 me-3"><i class="bi bi-arrow-left fs-4"></i></a>
        <h2 class="mb-0">Checkout</h2>
    </div>

    <!-- Error Notification -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="/checkout/process" method="post">
        <?= csrf_field() ?>
        <div class="row">
            <!-- Left Column: Address, Shipping, Notes -->
            <div class="col-lg-7">
                <!-- 1. Shipping Address -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($addresses)): ?>
                            <p>You don't have an address yet. <a href="/addresses">Add an Address</a></p>
                        <?php else: ?>
                            <?php foreach ($addresses as $address): ?>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="address_id" id="address<?= $address['id'] ?>" value="<?= $address['id'] ?>" <?= $address['is_primary'] ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="address<?= $address['id'] ?>">
                                        <strong><?= esc($address['label']) ?></strong> (<?= esc($address['recipient_name']) ?>)
                                        <p class="mb-0 text-muted"><?= esc($address['address']) ?>, <?= esc($address['city']) ?>, <?= esc($address['province']) ?>, <?= esc($address['postal_code']) ?> | <?= esc($address['phone_number']) ?></p>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <a href="/addresses" class="btn btn-outline-primary btn-sm mt-2">Manage Addresses</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 2. Shipping Options -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="bi bi-truck me-2"></i>Shipping Options</h5></div>
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input shipping-option" type="radio" name="shipping_method" id="shippingReguler" value="Reguler" data-cost="15000" checked required>
                            <label class="form-check-label d-flex justify-content-between" for="shippingReguler">
                                <span>Reguler (Est. 3-5 days)</span>
                                <span>Rp 15.000</span>
                            </label>
                        </div>
                        <hr>
                        <div class="form-check">
                            <input class="form-check-input shipping-option" type="radio" name="shipping_method" id="shippingExpress" value="Express" data-cost="25000" required>
                            <label class="form-check-label d-flex justify-content-between" for="shippingExpress">
                                <span>Express (Est. 1-2 days)</span>
                                <span>Rp 25.000</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 3. Message for Seller -->
                <div class="card shadow-sm">
                    <div class="card-header"><h5 class="mb-0"><i class="bi bi-chat-left-text-fill me-2"></i>Message (Optional)</h5></div>
                    <div class="card-body">
                        <textarea name="seller_notes" class="form-control" rows="2" placeholder="Leave a message for the seller..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary & Payment -->
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="card shadow-sm sticky-top" style="top: 1rem;">
                    <div class="card-header"><h5 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i>Order Summary</h5></div>
                    <div class="card-body">
                        <!-- Item list grouped by store -->
                        <?php foreach($groupedItems as $store): ?>
                            <div class="mb-3">
                                <strong>From: <a href="#" class="text-decoration-none"><?= esc($store['store_name']) ?? 'Unnamed Store' ?></a></strong>
                                <?php foreach($store['items'] as $item): ?>
                                <div class="d-flex justify-content-between align-items-center my-2">
                                    <div class="d-flex align-items-center">
                                        <img src="/uploads/products/<?= esc($item['product_image']) ?>" width="40" class="me-2 rounded" onerror="this.onerror=null;this.src='https://placehold.co/40x40/CCCCCC/333333?text=Img';">
                                        <span><?= esc($item['product_name']) ?> <small>(x<?= $item['quantity'] ?>)</small></span>
                                    </div>
                                    <span>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                        
                        <hr>
                        <!-- Cost Breakdown -->
                        <div class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Shipping Cost</span>
                            <span id="shippingCostText">Rp 15.000</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total Payment</span>
                            <span id="grandTotalText">Rp <?= number_format($subtotal + 15000, 0, ',', '.') ?></span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <h6 class="mb-2">Payment Method</h6>
                        <select name="payment_method" class="form-select" required>
                            <option value="QRIS" selected>QRIS (Scan QR Code)</option>
                            <option value="COD" disabled>Cash on Delivery (Coming Soon)</option>
                        </select>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#">Terms & Conditions</a>.
                            </label>
                        </div>
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-shield-check-fill me-2"></i>Create Order
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

    shippingOptions.forEach(option => {
        option.addEventListener('change', function() {
            const shippingCost = parseFloat(this.dataset.cost);
            const grandTotal = subtotal + shippingCost;

            shippingCostText.textContent = formatCurrency(shippingCost);
            grandTotalText.textContent = formatCurrency(grandTotal);
        });
    });
});
</script>
<?= $this->endSection() ?>