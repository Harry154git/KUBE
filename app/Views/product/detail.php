<?= $this->extend('layouts/main_layout') ?> <!-- Sesuaikan dengan layout utama Anda -->

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-4">
                <!-- Gambar Produk -->
                <img src="/uploads/products/<?= esc($product['gambar_produk']) ?>" class="img-fluid rounded-start" alt="<?= esc($product['nama_produk']) ?>" style="width: 100%; height: 400px; object-fit: cover;">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <!-- Nama Produk -->
                    <h2 class="card-title"><?= esc($product['nama_produk']) ?></h2>
                    
                    <!-- Info Toko -->
                    <div class="mb-3">
                        <small class="text-muted">Dijual oleh <a href="#" class="text-decoration-none"><?= esc($toko['nama_toko']) ?></a></small>
                    </div>

                    <!-- Harga -->
                    <h3 class="card-text text-danger fw-bold mb-3">Rp <?= number_format($product['harga'], 0, ',', '.') ?></h3>
                    
                    <!-- Stok -->
                    <p class="card-text"><small class="text-muted">Stok: <?= esc($product['stok']) ?></small></p>

                    <!-- Deskripsi -->
                    <h5 class="mt-4">Deskripsi Produk</h5>
                    <p class="card-text"><?= nl2br(esc($product['deskripsi'])) ?></p> <!-- nl2br untuk menjaga format baris baru -->

                    <!-- Tombol Aksi -->
                    <div class="mt-4">
                        <form action="/cart/add" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <div class="input-group" style="max-width: 150px;">
                                <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?= $product['stok'] ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-2"><i class="bi bi-cart-plus-fill me-2"></i>Tambah ke Keranjang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
