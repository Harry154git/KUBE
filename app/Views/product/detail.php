<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    <?= esc($product['product_name']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="/uploads/products/<?= esc($product['product_image']) ?>" class="img-fluid rounded-start" alt="<?= esc($product['product_name']) ?>" style="width: 100%; height: 400px; object-fit: cover;" onerror="this.onerror=null;this.src='https://placehold.co/400x400/CCCCCC/333333?text=Img';">
            </div>
            <div class="col-md-8">
                <div class="card-body d-flex flex-column h-100">
                    <div>
                        <h2 class="card-title"><?= esc($product['product_name']) ?></h2>
                        <div class="mb-3">
                            <small class="text-muted">Sold by <a href="#" class="text-decoration-none"><?= esc($store['store_name']) ?></a></small>
                        </div>
                        <h3 class="card-text text-danger fw-bold mb-3">Rp <?= number_format($product['price'], 0, ',', '.') ?></h3>
                        <p class="card-text"><small class="text-muted">Stock: <?= esc($product['stock']) ?></small></p>
                        <h5 class="mt-4">Product Description</h5>
                        <p class="card-text"><?= nl2br(esc($product['description'])) ?></p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-auto pt-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Add to Cart Form -->
                            <form action="/cart/add" method="post" class="d-flex align-items-center gap-2">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <div class="input-group" style="max-width: 120px;">
                                    <input type="number" name="quantity" id="quantity_input" class="form-control text-center" value="1" min="1" max="<?= $product['stock'] ?>">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-cart-plus-fill me-2"></i>Cart</button>
                            </form>
                            
                            <!-- Buy Now Form -->
                            <form action="/checkout/initiate" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" id="quantity_buy_now" value="1">
                                <button type="submit" class="btn btn-success"><i class="bi bi-bag-check-fill me-2"></i>Buy Now</button>
                            </form>

                            <!-- ===== NEW CHAT BUTTON ===== -->
                            <?php if(session()->get('user_id') && $store['user_id'] != session()->get('user_id')): // Don't show if the product belongs to the user ?>
                            <a href="<?= route_to('chat.start_with_seller', $store['id']) ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-chat-right-text-fill me-2"></i>Chat Seller
                            </a>
                            <?php endif; ?>
                            <!-- ===== END OF NEW CHAT BUTTON ===== -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('quantity_input').addEventListener('input', function() {
        document.getElementById('quantity_buy_now').value = this.value;
    });
</script>
<?= $this->endSection() ?>
