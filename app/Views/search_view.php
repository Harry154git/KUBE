<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Search Results for "<?= esc($keyword ?? '') ?>"
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="container my-5">
        <h1 class="mb-4">
            <?php if (!empty($keyword)): ?>
                Search Results for "<strong><?= esc($keyword) ?></strong>"
            <?php else: ?>
                Please Enter a Keyword in the Search Bar
            <?php endif; ?>
        </h1>
        <hr>

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
            <?php elseif (!empty($keyword)): ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center" role="alert">
                        <h4 class="alert-heading">Oops!</h4>
                        <p>We couldn't find any products with the keyword "<?= esc($keyword) ?>". Please try another keyword.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?= $this->endSection() ?>
