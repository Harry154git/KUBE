<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    KUBE - Anyaman Purun Asli Desa Pulantani
<?= $this->endSection() ?>

<?php // Memberi tahu layout bahwa ini adalah homepage agar tidak memakai container ?>
<?php $this->setData(['isHomepage' => true]); ?>

<?= $this->section('content') ?>

    <div class="hero-section">
        <div class="container">
            <h1 class="display-4">Karya Tangan, Warisan Budaya</h1>
            <p class="lead">
                Temukan keindahan anyaman purun asli dari tangan-tangan terampil pengrajin Desa Pulantani. Setiap helai menceritakan sebuah kisah.
            </p>
            <?php if (session()->get('isLoggedIn')): ?>
                <p class="greeting mb-4">
                    Selamat datang kembali, <strong><?= esc(session()->get('full_name')) ?></strong>!
                </p>
            <?php endif; ?>
            <a href="#produk" class="cta-button">Jelajahi Koleksi</a>
        </div>
    </div>

    <div class="mission-section">
        <div class="container">
            <h2>Dari Desa Pulantani Untuk Dunia</h2>
            <p class="subtitle">KUBE hadir untuk memberdayakan para pengrajin lokal, membawa karya mereka ke panggung yang lebih luas, dan memastikan warisan ini tetap hidup.</p>
            
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="<?= base_url('/assets/img/purun-pengrajin.jpg') ?>" alt="Pengrajin Purun Desa Pulantani" class="img-fluid">
                </div>
                <div class="col-md-6 story-text">
                    <h4>Cerita di Balik Helai Purun</h4>
                    <p>
                        <strong>KUBE (Kelompok Usaha Bersama)</strong> bukan sekadar toko online. Kami adalah jembatan antara Anda dan para ibu pengrajin di Desa Pulantani, Kalimantan Selatan. 
                    </p>
                    <p>
                        Setiap produk yang Anda beli adalah dukungan langsung bagi ekonomi keluarga mereka, membantu melestarikan kerajinan tradisional yang diwariskan turun-temurun, dan menjaga ekosistem lahan gambut tempat purun tumbuh subur.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="product-section" id="produk">
        <div class="container">
            <h2 class="section-title">Koleksi Kami</h2>
            
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="product-card">
                                <a href="/product/<?= $product['id'] ?>" class="text-decoration-none">
                                    <img src="/uploads/products/<?= esc($product['product_image']) ?>" class="card-img-top" alt="<?= esc($product['product_name']) ?>" onerror="this.onerror=null;this.src='https://placehold.co/600x400/D4A048/FFFFFF?text=Produk+KUBE';">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= esc($product['product_name']) ?></h5>
                                        <p class="card-text text-muted"><?= esc(substr($product['description'], 0, 50)) ?>...</p>
                                        <h6 class="card-price mt-3">Rp <?= number_format($product['price'], 0, ',', '.') ?></h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>Produk terbaru akan segera hadir. Nantikan koleksi kami!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>