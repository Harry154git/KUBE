<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Keranjang Belanja
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <h2 class="mb-4">Keranjang Belanja Anda</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 5rem; color: #6c757d;"></i>
            <h4 class="mt-3">Keranjang Anda masih kosong</h4>
            <p class="text-muted">Ayo, jelajahi produk kami dan temukan barang favoritmu!</p>
            <a href="/home" class="btn btn-primary mt-2">Mulai Belanja</a>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Kolom Daftar Item -->
            <div class="col-lg-8">
                <?= form_open('/cart/update') ?>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-end">Harga</th>
                                            <th class="text-center" style="width: 15%;">Jumlah</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= esc($item['gambar_produk']) ?>" width="60" class="me-3 rounded" onerror="this.onerror=null;this.src='https://placehold.co/60x60/CCCCCC/333333?text=Img';">
                                                        <div>
                                                            <a href="/product/<?= $item['product_id'] ?>" class="text-dark text-decoration-none fw-bold"><?= esc($item['nama_produk']) ?></a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                                <td class="text-center">
                                                    <input type="number" name="cart[<?= $item['cart_id'] ?>][quantity]" class="form-control form-control-sm text-center" value="<?= $item['quantity'] ?>" min="1">
                                                </td>
                                                <td class="text-end fw-bold">Rp <?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></td>
                                                <td class="text-center">
                                                    <a href="/cart/remove/<?= $item['cart_id'] ?>" class="btn btn-outline-danger btn-sm" title="Hapus item">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                             <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat me-2"></i>Update Keranjang</button>
                        </div>
                    </div>
                <?= form_close() ?>
            </div>

            <!-- Kolom Ringkasan Belanja -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Ringkasan Belanja</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total</span>
                            <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>
                        <div class="d-grid mt-4">
                            <a href="/checkout" class="btn btn-success btn-lg">
                                Lanjut ke Pembayaran <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
