<!-- Simpan sebagai app/Views/seller/products_add.php -->
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-4">
    <div class="row">
        <!-- Menu Navigasi Penjual -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?= route_to('seller.dashboard') ?>" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a href="<?= route_to('seller.products') ?>" class="list-group-item list-group-item-action active"><i class="bi bi-box-seam me-2"></i>Produk Saya</a>
                <a href="<?= route_to('seller.orders') ?>" class="list-group-item list-group-item-action"><i class="bi bi-receipt me-2"></i>Pesanan Masuk</a>
                <a href="<?= route_to('seller.settings') ?>" class="list-group-item list-group-item-action"><i class="bi bi-gear me-2"></i>Pengaturan Toko</a>
            </div>
        </div>
        <!-- Konten Form -->
        <div class="col-md-9">
            <h3>Tambah Produk Baru</h3>
            <div class="card">
                <div class="card-body">
                    <!-- Tampilkan error validasi -->
                    <?php if(session()->has('validation')): ?>
                        <div class="alert alert-danger">
                            <?= session('validation')->listErrors() ?>
                        </div>
                    <?php endif; ?>

                    <form action="/seller/products/create" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= old('nama_produk') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?= old('deskripsi') ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" id="harga" name="harga" value="<?= old('harga') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="stok" name="stok" value="<?= old('stok') ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="gambar_produk" class="form-label">Gambar Produk</label>
                            <input class="form-control" type="file" id="gambar_produk" name="gambar_produk" required>
                        </div>
                        <a href="<?= route_to('seller.products') ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Produk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

---

<!-- Simpan sebagai app/Views/seller/products_edit.php -->
<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-4">
    <div class="row">
        <!-- Menu Navigasi Penjual -->
        <div class="col-md-3">
             <div class="list-group">
                <a href="<?= route_to('seller.dashboard') ?>" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a href="<?= route_to('seller.products') ?>" class="list-group-item list-group-item-action active"><i class="bi bi-box-seam me-2"></i>Produk Saya</a>
                <a href="<?= route_to('seller.orders') ?>" class="list-group-item list-group-item-action"><i class="bi bi-receipt me-2"></i>Pesanan Masuk</a>
                <a href="<?= route_to('seller.settings') ?>" class="list-group-item list-group-item-action"><i class="bi bi-gear me-2"></i>Pengaturan Toko</a>
            </div>
        </div>
        <!-- Konten Form -->
        <div class="col-md-9">
            <h3>Edit Produk</h3>
            <div class="card">
                <div class="card-body">
                    <!-- Tampilkan error validasi -->
                    <?php if(session()->has('validation')): ?>
                        <div class="alert alert-danger">
                            <?= session('validation')->listErrors() ?>
                        </div>
                    <?php endif; ?>

                    <form action="/seller/products/update/<?= $product['id'] ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="gambar_lama" value="<?= esc($product['gambar_produk']) ?>">
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= old('nama_produk', $product['nama_produk']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?= old('deskripsi', $product['deskripsi']) ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" id="harga" name="harga" value="<?= old('harga', $product['harga']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="stok" name="stok" value="<?= old('stok', $product['stok']) ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="gambar_produk" class="form-label">Ganti Gambar Produk (Opsional)</label>
                            <br>
                            <img src="/uploads/products/<?= esc($product['gambar_produk']) ?>" alt="Gambar saat ini" class="img-thumbnail mb-2" width="150">
                            <input class="form-control" type="file" id="gambar_produk" name="gambar_produk">
                        </div>
                        <a href="<?= route_to('seller.products') ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Produk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
