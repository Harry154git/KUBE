<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Home
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="container my-5">
        <div class="p-5 text-center bg-body-tertiary rounded-3">
            <h1 class="text-body-emphasis">Welcome Back!</h1>
            <p class="col-lg-8 mx-auto fs-5 text-muted">
                Hello, <strong><?= esc(session()->get('full_name')) ?></strong>! Explore our thousands of best products and find what you're looking for.
            </p>
        </div>

        <h2 class="mt-5 mb-4">Product List</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <a href="/product/<?= $product['id'] ?>" class="text-decoration-none text-dark">
                                <img src="/uploads/products/<?= esc($product['product_image']) ?>" class="card-img-top" alt="<?= esc($product['product_name']) ?>" style="height: 200px; object-fit: cover;" onerror="this.onerror=null;this.src='https://placehold.co/600x400/CCCCCC/333333?text=Image';">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($product['product_name']) ?></h5>
                                    <p class="card-text text-muted small"><?= esc(substr($product['description'], 0, 50)) ?>...</p>
                                    <h6 class="card-subtitle mb-2 text-primary fw-bold">Rp <?= number_format($product['price'], 0, ',', '.') ?></h6>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p>No products available yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?= $this->endSection() ?>
