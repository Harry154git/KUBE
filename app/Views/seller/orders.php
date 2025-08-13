<!-- Simpan sebagai app/Views/seller/orders.php -->
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Pesanan Masuk - <?= esc(session()->get('nama_toko')) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-4">
    <div class="row">
        <!-- Menu Navigasi Penjual -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?= route_to('seller.dashboard') ?>" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a href="<?= route_to('seller.products') ?>" class="list-group-item list-group-item-action"><i class="bi bi-box-seam me-2"></i>Produk Saya</a>
                <a href="<?= route_to('seller.orders') ?>" class="list-group-item list-group-item-action active"><i class="bi bi-receipt me-2"></i>Pesanan Masuk</a>
                <a href="<?= route_to('seller.settings') ?>" class="list-group-item list-group-item-action"><i class="bi bi-gear me-2"></i>Pengaturan Toko</a>
            </div>
        </div>
        <!-- Konten Pesanan -->
        <div class="col-md-9">
            <h3>Pesanan Masuk</h3>
            <?php if(session()->getFlashdata('message')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tgl Pesan</th>
                                    <th>Produk</th>
                                    <th>Pembeli</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Status Saat Ini</th>
                                    <th>Update Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= date('d M Y H:i', strtotime($order['tanggal_pesan'])) ?></td>
                                        <td><?= esc($order['nama_produk']) ?></td>
                                        <td><?= esc($order['nama_pembeli']) ?></td>
                                        <td><?= esc($order['jumlah']) ?></td>
                                        <td>Rp <?= number_format($order['harga_saat_beli'] * $order['jumlah'], 0, ',', '.') ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark"><?= ucfirst($order['status_pesanan_penjual']) ?></span>
                                        </td>
                                        <td>
                                            <form action="<?= route_to('seller.updateOrderStatus') ?>" method="post" class="d-flex">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="order_detail_id" value="<?= $order['id'] ?>">
                                                <select name="status" class="form-select form-select-sm me-2">
                                                    <option value="diproses" <?= $order['status_pesanan_penjual'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                                    <option value="dikirim" <?= $order['status_pesanan_penjual'] == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                                    <option value="selesai" <?= $order['status_pesanan_penjual'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                                    <option value="dibatalkan" <?= $order['status_pesanan_penjual'] == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada pesanan masuk.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

---