<!-- Simpan sebagai app/Views/seller/products.php -->
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Produk Saya - <?= esc(session()->get('nama_toko')) ?>
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
        <!-- Konten Produk -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Daftar Produk Saya</h3>
                <a href="/seller/products/add" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $key => $product): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= esc($product['nama']) ?></td>
                                    <td>Rp <?= number_format($product['harga'], 0, ',', '.') ?></td>
                                    <td><?= esc($product['stok']) ?></td>
                                    <td>
                                        <a href="/seller/products/edit/<?= $product['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="/seller/products/delete/<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')"><i class="bi bi-trash-fill"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Anda belum memiliki produk.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>