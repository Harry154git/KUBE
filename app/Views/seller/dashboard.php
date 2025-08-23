<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Seller Dashboard - <?= esc(session()->get('store_name')) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-4">
    <h2 class="mb-4">Seller Dashboard</h2>

    <?php if(session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Seller Navigation Menu -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?= route_to('seller.dashboard') ?>" class="list-group-item list-group-item-action active"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a href="<?= route_to('seller.products') ?>" class="list-group-item list-group-item-action"><i class="bi bi-box-seam me-2"></i>My Products</a>
                <a href="<?= route_to('seller.orders') ?>" class="list-group-item list-group-item-action"><i class="bi bi-receipt me-2"></i>Incoming Orders</a>
                <a href="<?= route_to('seller.settings') ?>" class="list-group-item list-group-item-action"><i class="bi bi-gear me-2"></i>Store Settings</a>
            </div>
        </div>
        <!-- Main Dashboard Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Welcome, <?= esc(session()->get('full_name')) ?>!</h5>
                    <p class="card-text">You are now in the dashboard for store <strong><?= esc(session()->get('store_name')) ?></strong>. Use the side menu to manage your store.</p>
                    <!-- You can add sales statistics, new orders, etc. here -->
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>