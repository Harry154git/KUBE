<!-- Simpan sebagai app/Views/seller/settings.php -->
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Pengaturan Toko - <?= esc(session()->get('nama_toko')) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-4">
    <div class="row">
        <!-- Menu Navigasi Penjual -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?= route_to('seller.dashboard') ?>" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a href="<?= route_to('seller.products') ?>" class="list-group-item list-group-item-action"><i class="bi bi-box-seam me-2"></i>Produk Saya</a>
                <a href="<?= route_to('seller.orders') ?>" class="list-group-item list-group-item-action"><i class="bi bi-receipt me-2"></i>Pesanan Masuk</a>
                <a href="<?= route_to('seller.settings') ?>" class="list-group-item list-group-item-action active"><i class="bi bi-gear me-2"></i>Pengaturan Toko</a>
            </div>
        </div>
        <!-- Konten Pengaturan -->
        <div class="col-md-9">
            <h3>Pengaturan Toko</h3>
            
            <?php if(session()->getFlashdata('message')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
            <?php endif; ?>
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?= route_to('seller.settings') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="nama_toko" class="form-label">Nama Toko</label>
                            <input type="text" class="form-control" id="nama_toko" name="nama_toko" value="<?= set_value('nama_toko', $toko['nama_toko'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi_toko" class="form-label">Deskripsi Singkat Toko</label>
                            <textarea class="form-control" id="deskripsi_toko" name="deskripsi_toko" rows="3" required><?= set_value('deskripsi_toko', $toko['deskripsi_toko'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="alamat_toko" class="form-label">Alamat Pengiriman (Kota)</label>
                            <input type="text" class="form-control" id="alamat_toko" name="alamat_toko" value="<?= set_value('alamat_toko', $toko['alamat_toko'] ?? '') ?>" placeholder="Contoh: Jakarta Pusat" required>
                        </div>
                        <div class="mb-3">
                            <label for="rekening_bank" class="form-label">Rekening Bank untuk Pencairan Dana</label>
                            <input type="text" class="form-control" id="rekening_bank" name="rekening_bank" value="<?= set_value('rekening_bank', $toko['rekening_bank'] ?? '') ?>" placeholder="Contoh: BCA 123456789 a/n John Doe" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>