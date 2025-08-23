<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Store Settings - <?= esc(session()->get('store_name')) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-4">
    <div class="row">
        <!-- Seller Navigation Menu -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?= route_to('seller.dashboard') ?>" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a href="<?= route_to('seller.products') ?>" class="list-group-item list-group-item-action"><i class="bi bi-box-seam me-2"></i>My Products</a>
                <a href="<?= route_to('seller.orders') ?>" class="list-group-item list-group-item-action"><i class="bi bi-receipt me-2"></i>Incoming Orders</a>
                <a href="<?= route_to('seller.settings') ?>" class="list-group-item list-group-item-action active"><i class="bi bi-gear me-2"></i>Store Settings</a>
            </div>
        </div>
        <!-- Settings Content -->
        <div class="col-md-9">
            <h3>Store Settings</h3>
            
            <?php if(session()->getFlashdata('message')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
            <?php endif; ?>
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?= route_to('seller.settings') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="store_name" class="form-label">Store Name</label>
                            <input type="text" class="form-control" id="store_name" name="store_name" value="<?= set_value('store_name', $store['store_name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="store_description" class="form-label">Brief Store Description</label>
                            <textarea class="form-control" id="store_description" name="store_description" rows="3" required><?= set_value('store_description', $store['store_description'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="store_address" class="form-label">Shipping Address (City)</label>
                            <input type="text" class="form-control" id="store_address" name="store_address" value="<?= set_value('store_address', $store['store_address'] ?? '') ?>" placeholder="e.g., Central Jakarta" required>
                        </div>
                        <div class="mb-3">
                            <label for="bank_account" class="form-label">Bank Account for Payouts</label>
                            <input type="text" class="form-control" id="bank_account" name="bank_account" value="<?= set_value('bank_account', $store['bank_account'] ?? '') ?>" placeholder="e.g., BCA 123456789 a/n John Doe" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>