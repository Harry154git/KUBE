<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Aktivasi Akun Penjual
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card seller-card">
                <div class="card-header text-center">
                    <h3 class="mb-0 seller-header">Buka Toko Gratis Anda</h3>
                </div>
                <div class="card-body p-4">
                    <p class="text-center text-muted">Lengkapi data di bawah ini untuk mengaktifkan toko Anda dan mulai berjualan produk anyaman purun ke seluruh Indonesia.</p>
                    
                    <?php if (isset($validation)): ?>
                        <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                    <?php endif; ?>

                    <form action="<?= route_to('seller.activate') ?>" method="post" class="mt-4">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="store_name" class="form-label">Nama Toko</label>
                            <input type="text" class="form-control" id="store_name" name="store_name" value="<?= set_value('store_name') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="store_description" class="form-label">Deskripsi Singkat Toko</label>
                            <textarea class="form-control" id="store_description" name="store_description" rows="3" required><?= set_value('store_description') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="store_address" class="form-label">Alamat Pengiriman (Kota)</label>
                            <input type="text" class="form-control" id="store_address" name="store_address" value="<?= set_value('store_address') ?>" placeholder="cth: Banjarmasin" required>
                        </div>
                        <div class="mb-3">
                            <label for="bank_account" class="form-label">Rekening Bank untuk Pencairan Dana</label>
                            <input type="text" class="form-control" id="bank_account" name="bank_account" value="<?= set_value('bank_account') ?>" placeholder="cth: BCA 123456789 a/n Pengrajin Kreatif" required>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-purun-secondary btn-lg">Aktifkan Toko Saya</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>