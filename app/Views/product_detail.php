<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    <?= esc($product['nama_produk']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <!-- Kolom Gambar Produk -->
                <div class="col-md-6 text-center">
                    <img src="<?= esc($product['gambar_produk']) ?>" class="img-fluid rounded" alt="<?= esc($product['nama_produk']) ?>" style="max-height: 450px;" onerror="this.onerror=null;this.src='https://placehold.co/600x400/CCCCCC/333333?text=Image';">
                </div>

                <!-- Kolom Info & Aksi Produk -->
                <div class="col-md-6">
                    <h1 class="mb-3"><?= esc($product['nama_produk']) ?></h1>
                    
                    <h2 class="text-primary fw-bold mb-3">Rp <?= number_format($product['harga'], 0, ',', '.') ?></h2>
                    
                    <p class="lead"><?= esc($product['deskripsi']) ?></p>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="badge bg-success p-2">Stok Tersedia: <?= esc($product['stok']) ?></span>
                    </div>

                    <?= form_open('/cart/add') ?>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <div class="input-group mb-3" style="max-width: 300px;">
                            <span class="input-group-text">Jumlah</span>
                            <input type="number" name="quantity" id="quantity" class="form-control text-center" value="1" min="1" max="<?= esc($product['stok']) ?>" required>
                             <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cart-plus-fill me-2"></i>Tambah ke Keranjang
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>