<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row">
        <div class="col-lg-3">
            <div class="seller-sidebar">
                <div class="list-group">
                    <a href="<?= route_to('seller.dashboard') ?>" class="list-group-item list-group-item-action <?= ($activePage == 'dashboard') ? 'active' : '' ?>">
                        <i class="bi bi-speedometer2 me-2"></i>Dasbor
                    </a>
                    <a href="<?= route_to('seller.products') ?>" class="list-group-item list-group-item-action <?= ($activePage == 'products') ? 'active' : '' ?>">
                        <i class="bi bi-box-seam me-2"></i>Produk Saya
                    </a>
                    <a href="<?= route_to('seller.orders') ?>" class="list-group-item list-group-item-action <?= ($activePage == 'orders') ? 'active' : '' ?>">
                        <i class="bi bi-receipt me-2"></i>Pesanan Masuk
                    </a>
                    <a href="<?= route_to('seller.settings') ?>" class="list-group-item list-group-item-action <?= ($activePage == 'settings') ? 'active' : '' ?>">
                        <i class="bi bi-gear me-2"></i>Pengaturan Toko
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <?= $this->renderSection('seller_content') ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>