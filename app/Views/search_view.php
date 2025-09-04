<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Hasil Pencarian untuk "<?= esc($keyword ?? '') ?>"
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="container my-5">
        <div class="text-center">
            <h1 class="page-header">
                <?php if (!empty($keyword)): ?>
                    Hasil Pencarian untuk "<strong><?= esc($keyword) ?></strong>"
                <?php else: ?>
                    Pencarian Produk
                <?php endif; ?>
            </h1>
            <?php if (!empty($products)): ?>
                <p class="search-results-count">Menampilkan <?= count($products) ?> produk yang cocok.</p>
            <?php endif; ?>
        </div>
        <hr class="mb-5">

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="product-card">
                            <a href="/product/<?= $product['id'] ?>" class="text-decoration-none">
                                <img src="/uploads/products/<?= esc($product['product_image']) ?>" class="card-img-top" alt="<?= esc($product['product_name']) ?>" onerror="this.onerror=null;this.src='<?= base_url('images/produk-placeholder.jpg') ?>';">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($product['product_name']) ?></h5>
                                    <p class="card-text text-muted"><?= esc(word_limiter($product['description'], 8)) ?></p>
                                    <h6 class="card-price mt-3">Rp <?= number_format($product['price'], 0, ',', '.') ?></h6>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php elseif (!empty($keyword)): ?>
                <div class="col-12">
                    <div class="search-no-results text-center">
                        <i class="bi bi-search-heartbreak" style="font-size: 4rem; color: var(--purun-brown);"></i>
                        <h4 class="alert-heading mt-3">Yah, Produk Tidak Ditemukan</h4>
                        <p class="text-muted">Kami tidak dapat menemukan produk dengan kata kunci "<?= esc($keyword) ?>".<br>Silakan coba kata kunci lain atau lihat koleksi lainnya.</p>
                        <a href="/" class="btn btn-purun-primary mt-2">Kembali ke Beranda</a>
                    </div>
                </div>
            <?php else: ?>
                 <div class="col-12">
                    <div class="search-no-results text-center">
                         <i class="bi bi-keyboard" style="font-size: 4rem; color: var(--purun-brown);"></i>
                        <h4 class="alert-heading mt-3">Mulai Mencari</h4>
                        <p class="text-muted">Silakan masukkan kata kunci di kolom pencarian di bagian atas halaman.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?= $this->endSection() ?>