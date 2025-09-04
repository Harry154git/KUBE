<?= $this->extend('layouts/seller_layout') ?>

<?= $this->section('title') ?>
    Produk Saya - <?= esc(session()->get('store_name')) ?>
<?= $this->endSection() ?>

<?php $this->setVar('activePage', 'products'); ?>

<?= $this->section('seller_content') ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="seller-header mb-0">Daftar Produk Saya</h2>
        <a href="/seller/products/add" class="btn btn-purun-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru</a>
    </div>

    <div class="card seller-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
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
                                <td class="fw-bold"><?= esc($product['product_name']) ?></td>
                                <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                                <td><?= esc($product['stock']) ?></td>
                                <td>
                                    <a href="/seller/products/edit/<?= $product['id'] ?>" class="btn btn-sm btn-action-edit"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="/seller/products/delete/<?= $product['id'] ?>" class="btn btn-sm btn-action-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Anda belum memiliki produk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>