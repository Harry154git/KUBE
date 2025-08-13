<?= $this->extend('layouts/main_layout') ?> 

<?= $this->section('title') ?>
Aktivasi Akun Penjual - My E-Commerce
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Buka Toko Gratis</h4>
                </div>
                <div class="card-body">
                    <p>Lengkapi data di bawah ini untuk mengaktifkan tokomu dan mulai berjualan.</p>
                    
                    <?php if (isset($validation)): ?>
                        <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                    <?php endif; ?>

                    <form action="<?= route_to('seller.activate') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="nama_toko" class="form-label">Nama Toko</label>
                            <input type="text" class="form-control" id="nama_toko" name="nama_toko" value="<?= set_value('nama_toko') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi_toko" class="form-label">Deskripsi Singkat Toko</label>
                            <textarea class="form-control" id="deskripsi_toko" name="deskripsi_toko" rows="3" required><?= set_value('deskripsi_toko') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="alamat_toko" class="form-label">Alamat Pengiriman (Kota)</label>
                            <input type="text" class="form-control" id="alamat_toko" name="alamat_toko" value="<?= set_value('alamat_toko') ?>" placeholder="Contoh: Jakarta Pusat" required>
                        </div>
                        <div class="mb-3">
                            <label for="rekening_bank" class="form-label">Rekening Bank untuk Pencairan Dana</label>
                            <input type="text" class="form-control" id="rekening_bank" name="rekening_bank" value="<?= set_value('rekening_bank') ?>" placeholder="Contoh: BCA 123456789 a/n John Doe" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Aktifkan Toko Saya</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>