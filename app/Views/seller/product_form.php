<?= $this->extend('layouts/seller_layout') ?>

<?= $this->section('title') ?>
    <?= isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' ?>
<?= $this->endSection() ?>

<?php $this->setVar('activePage', 'products'); ?>

<?= $this->section('seller_content') ?>
    <h2 class="mb-4 seller-header"><?= isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' ?></h2>
    
    <div class="card seller-card">
        <div class="card-body">
            <?php if (session()->has('validation')): ?>
                <div class="alert alert-danger"><?= session('validation')->listErrors() ?></div>
            <?php endif; ?>

            <form action="<?= isset($product) ? site_url('seller/products/update/' . $product['id']) : site_url('seller/products/create') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="product_name" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?= old('product_name', $product['product_name'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?= old('description', $product['description'] ?? '') ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" id="price" name="price" value="<?= old('price', $product['price'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="<?= old('stock', $product['stock'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="product_image" class="form-label">Gambar Produk</label>
                    <input class="form-control" type="file" id="product_image" name="product_image" <?= isset($product) ? '' : 'required' ?>>
                    <?php if (isset($product['product_image'])): ?>
                        <div class="mt-2">
                            <img src="/uploads/products/<?= $product['product_image'] ?>" alt="Current Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                            <small class="form-text text-muted d-block">Gambar saat ini. Kosongkan jika tidak ingin mengubah.</small>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mt-4">
                    <a href="<?= route_to('seller.products') ?>" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-purun-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
<?= $this->endSection() ?>