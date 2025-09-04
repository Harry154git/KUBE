<?= $this->extend('layouts/seller_layout') ?>

<?= $this->section('title') ?>
    Pengaturan Toko - <?= esc(session()->get('store_name')) ?>
<?= $this->endSection() ?>

<?php $this->setVar('activePage', 'settings'); ?>

<?= $this->section('seller_content') ?>
    <h2 class="mb-4 seller-header">Pengaturan Toko</h2>

    <?php if(session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>
    <?php if (isset($validation)): ?>
        <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
    <?php endif; ?>

    <div class="card seller-card">
        <div class="card-body">
            <form action="<?= route_to('seller.settings') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="store_name" class="form-label">Nama Toko</label>
                    <input type="text" class="form-control" id="store_name" name="store_name" value="<?= set_value('store_name', $store['store_name'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="store_description" class="form-label">Deskripsi Singkat Toko</label>
                    <textarea class="form-control" id="store_description" name="store_description" rows="3" required><?= set_value('store_description', $store['store_description'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="store_address" class="form-label">Alamat Pengiriman (Kota)</label>
                    <input type="text" class="form-control" id="store_address" name="store_address" value="<?= set_value('store_address', $store['store_address'] ?? '') ?>" placeholder="cth: Banjarmasin" required>
                </div>
                <div class="mb-3">
                    <label for="bank_account" class="form-label">Rekening Bank untuk Pencairan Dana</label>
                    <input type="text" class="form-control" id="bank_account" name="bank_account" value="<?= set_value('bank_account', $store['bank_account'] ?? '') ?>" placeholder="cth: BCA 123456789 a/n Pengrajin Kreatif" required>
                </div>
                <button type="submit" class="btn btn-purun-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
<?= $this->endSection() ?>