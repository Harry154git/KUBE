<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Home
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="container my-5">
        <div class="p-5 text-center bg-body-tertiary rounded-3">
            <h1 class="text-body-emphasis">Selamat Datang Kembali!</h1>
            <p class="col-lg-8 mx-auto fs-5 text-muted">
                Halo, <strong><?= esc(session()->get('nama_lengkap')) ?></strong>! Jelajahi ribuan produk terbaik kami dan temukan apa yang Anda cari.
            </p>
        </div>

        <h2 class="mt-5 mb-4">Daftar Produk</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <a href="/product/<?= $product['id'] ?>" class="text-decoration-none text-dark">
                                <img src="<?= esc($product['gambar_produk']) ?>" class="card-img-top" alt="<?= esc($product['nama_produk']) ?>" style="height: 200px; object-fit: cover;" onerror="this.onerror=null;this.src='https://placehold.co/600x400/CCCCCC/333333?text=Image';">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($product['nama_produk']) ?></h5>
                                    <p class="card-text text-muted small"><?= esc(substr($product['deskripsi'], 0, 50)) ?>...</p>
                                    <h6 class="card-subtitle mb-2 text-primary fw-bold">Rp <?= number_format($product['harga'], 0, ',', '.') ?></h6>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p>Belum ada produk yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?= $this->endSection() ?>