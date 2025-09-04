<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Profil Saya
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <h2 class="mb-4 text-center page-header">Profil Saya</h2>

            <div class="card profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <?= esc(substr($user['full_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <h4 class="user-name"><?= esc($user['full_name']) ?></h4>
                        <p class="user-email"><?= esc($user['email']) ?></p>
                    </div>
                </div>

                <div class="list-group list-group-flush">
                    <a href="/order/history" class="profile-nav-item">
                        <i class="bi bi-receipt profile-nav-icon"></i>
                        <div class="nav-text">
                            <strong>Riwayat Pesanan</strong>
                            <small>Lihat dan lacak pesanan Anda</small>
                        </div>
                        <i class="bi bi-chevron-right nav-arrow"></i>
                    </a>
                    
                    <a href="/addresses" class="profile-nav-item">
                        <i class="bi bi-geo-alt-fill profile-nav-icon"></i>
                        <div class="nav-text">
                            <strong>Alamat Pengiriman</strong>
                            <small>Kelola daftar alamat Anda</small>
                        </div>
                        <i class="bi bi-chevron-right nav-arrow"></i>
                    </a>

                    <a href="/settings" class="profile-nav-item">
                        <i class="bi bi-gear-fill profile-nav-icon"></i>
                        <div class="nav-text">
                            <strong>Pengaturan Akun</strong>
                            <small>Ubah detail profil dan kata sandi</small>
                        </div>
                        <i class="bi bi-chevron-right nav-arrow"></i>
                    </a>
                    
                    <?php if(!session()->get('is_seller')): ?>
                    <a href="<?= route_to('seller.activate') ?>" class="profile-nav-item" style="background-color: #fef8e7;">
                        <i class="bi bi-shop profile-nav-icon"></i>
                        <div class="nav-text">
                            <strong>Buka Toko Gratis</strong>
                            <small>Mulai jual produk anyaman purun Anda!</small>
                        </div>
                        <i class="bi bi-chevron-right nav-arrow"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>