<?= $this->extend('layouts/main_layout') ?> 

<?= $this->section('title') ?>
Seller Account Activation - My E-Commerce
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Open a Free Store</h4>
                </div>
                <div class="card-body">
                    <p>Complete the data below to activate your store and start selling.</p>
                    
                    <?php if (isset($validation)): ?>
                        <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                    <?php endif; ?>

                    <form action="<?= route_to('seller.activate') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="store_name" class="form-label">Store Name</label>
                            <input type="text" class="form-control" id="store_name" name="store_name" value="<?= set_value('store_name') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="store_description" class="form-label">Brief Store Description</label>
                            <textarea class="form-control" id="store_description" name="store_description" rows="3" required><?= set_value('store_description') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="store_address" class="form-label">Shipping Address (City)</label>
                            <input type="text" class="form-control" id="store_address" name="store_address" value="<?= set_value('store_address') ?>" placeholder="e.g., Central Jakarta" required>
                        </div>
                        <div class="mb-3">
                            <label for="bank_account" class="form-label">Bank Account for Payouts</label>
                            <input type="text" class="form-control" id="bank_account" name="bank_account" value="<?= set_value('bank_account') ?>" placeholder="e.g., BCA 123456789 a/n John Doe" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Activate My Store</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>