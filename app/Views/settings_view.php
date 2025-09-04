<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Pengaturan Akun
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="d-flex align-items-center mb-4">
         <a href="/profile" class="btn btn-link p-0 me-3 text-decoration-none" style="color: var(--purun-dark-green);"><i class="bi bi-arrow-left fs-3"></i></a>
        <h2 class="mb-0 page-header">Pengaturan Akun</h2>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session('errors')): ?>
        <div class="alert alert-danger">
            <strong>Gagal memvalidasi data:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="nav flex-column nav-pills settings-nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab">Ubah Profil</button>
                <button class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-password" type="button" role="tab">Ubah Kata Sandi</button>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
                
                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel">
                    <div class="card seller-card">
                        <div class="card-header">
                            <h5>Informasi Profil</h5>
                        </div>
                        <div class="card-body">
                            <?= form_open('/settings') ?>
                                <input type="hidden" name="form_type" value="update_profile">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="full_name" id="full_name" value="<?= esc($user['full_name']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="<?= esc($user['email']) ?>" readonly disabled>
                                    <div class="form-text">Email tidak dapat diubah.</div>
                                </div>
                                <button type="submit" class="btn btn-purun-primary">Simpan Perubahan</button>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="v-pills-password" role="tabpanel">
                     <div class="card seller-card">
                        <div class="card-header">
                            <h5>Ubah Kata Sandi</h5>
                        </div>
                        <div class="card-body">
                            <?= form_open('/settings') ?>
                                <input type="hidden" name="form_type" value="update_password">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                                    <input type="password" class="form-control" name="current_password" id="current_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Kata Sandi Baru</label>
                                    <input type="password" class="form-control" name="new_password" id="new_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-purun-primary">Ubah Kata Sandi</button>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>