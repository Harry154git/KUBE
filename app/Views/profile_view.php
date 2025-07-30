<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Profil Saya
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Profil Saya</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-person-circle fs-1 me-4 text-secondary"></i>
                        <div>
                            <h5 class="card-title mb-0"><?= esc($user['nama_lengkap']) ?></h5>
                            <p class="card-text text-muted"><?= esc($user['email']) ?></p>
                        </div>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Riwayat Pesanan
                            <a href="/order-history" class="btn btn-outline-primary btn-sm">Lihat</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Alamat Pengiriman
                            <a href="/addresses" class="btn btn-outline-primary btn-sm">Kelola</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Pengaturan Akun
                            <a href="/settings" class="btn btn-outline-primary btn-sm">Ubah</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>