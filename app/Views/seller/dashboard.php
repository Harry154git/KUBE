<!-- Simpan sebagai app/Views/seller/dashboard.php -->
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Dashboard Penjual - <?= esc(session()->get('nama_toko')) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-4">
    <h2 class="mb-4">Dashboard Penjual</h2>

    <?php if(session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Menu Navigasi Penjual -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?= route_to('seller.dashboard') ?>" class="list-group-item list-group-item-action active"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a href="<?= route_to('seller.products') ?>" class="list-group-item list-group-item-action"><i class="bi bi-box-seam me-2"></i>Produk Saya</a>
                <a href="<?= route_to('seller.orders') ?>" class="list-group-item list-group-item-action"><i class="bi bi-receipt me-2"></i>Pesanan Masuk</a>
                <a href="<?= route_to('seller.settings') ?>" class="list-group-item list-group-item-action"><i class="bi bi-gear me-2"></i>Pengaturan Toko</a>
            </div>
        </div>
        <!-- Konten Utama Dashboard -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Selamat Datang, <?= esc(session()->get('nama_lengkap')) ?>!</h5>
                    <p class="card-text">Anda sekarang berada di dashboard toko <strong><?= esc(session()->get('nama_toko')) ?></strong>. Gunakan menu di samping untuk mengelola toko Anda.</p>
                    <!-- Anda bisa menambahkan statistik penjualan, pesanan baru, dll di sini -->
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
