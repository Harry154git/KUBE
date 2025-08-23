<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    My Profile
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">My Profile</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-person-circle fs-1 me-4 text-secondary"></i>
                        <div>
                            <h5 class="card-title mb-0"><?= esc($user['full_name']) ?></h5>
                            <p class="card-text text-muted"><?= esc($user['email']) ?></p>
                        </div>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Order History
                            <a href="/order/history" class="btn btn-outline-primary btn-sm">View</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Shipping Addresses
                            <a href="/addresses" class="btn btn-outline-primary btn-sm">Manage</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Account Settings
                            <a href="/settings" class="btn btn-outline-primary btn-sm">Edit</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>