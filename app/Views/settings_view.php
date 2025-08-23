<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Account Settings
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <h2>Account Settings</h2>
    <hr>

    <!-- Notifications -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session('errors')): ?>
        <div class="alert alert-danger">
            <strong>Failed to validate data:</strong>
            <ul>
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>


    <div class="row">
        <div class="col-md-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab">Edit Profile</button>
                <button class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-password" type="button" role="tab">Change Password</button>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
                <!-- Edit Profile Tab -->
                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5>Profile Information</h5>
                        </div>
                        <div class="card-body">
                            <?= form_open('/settings') ?>
                                <input type="hidden" name="form_type" value="update_profile">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" id="full_name" value="<?= esc($user['full_name']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="<?= esc($user['email']) ?>" readonly disabled>
                                    <div class="form-text">Email cannot be changed.</div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
                <!-- Change Password Tab -->
                <div class="tab-pane fade" id="v-pills-password" role="tabpanel">
                     <div class="card">
                        <div class="card-header">
                            <h5>Change Password</h5>
                        </div>
                        <div class="card-body">
                            <?= form_open('/settings') ?>
                                <input type="hidden" name="form_type" value="update_password">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" name="current_password" id="current_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="new_password" id="new_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>