<?= $this->extend('layouts/seller_layout') ?>

<?= $this->section('title') ?>
    Dasbor Penjual - <?= esc(session()->get('store_name')) ?>
<?= $this->endSection() ?>

<?php $this->setVar('activePage', 'dashboard'); ?>

<?= $this->section('seller_content') ?>
    <h2 class="mb-4 seller-header">Dasbor</h2>

    <?php if(session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card stat-card-orders">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small">Pesanan Perlu Diproses</div>
                        <div class="stat-number"><?= esc($newOrders) ?></div> 
                    </div>
                    <i class="bi bi-receipt stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
             <div class="stat-card stat-card-products">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small">Total Produk Anda</div>
                        <div class="stat-number"><?= esc($totalProducts) ?></div>
                    </div>
                    <i class="bi bi-box-seam stat-icon"></i>
                </div>
            </div>
        </div>
        </div>

    <div class="card seller-card">
        <div class="card-body">
            <h5 class="card-title">Selamat datang, <?= esc(session()->get('full_name')) ?>!</h5>
            <p class="card-text text-muted">Anda berada di dasbor untuk toko <strong><?= esc(session()->get('store_name')) ?></strong>. Gunakan menu di samping untuk mengelola toko Anda.</p>
        </div>
    </div>
<?= $this->endSection() ?>