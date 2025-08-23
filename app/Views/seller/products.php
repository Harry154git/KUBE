<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
My Products - <?= esc(session()->get('store_name')) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-4">
    <div class="row">
        <!-- Seller Navigation Menu -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?= route_to('seller.dashboard') ?>" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a href="<?= route_to('seller.products') ?>" class="list-group-item list-group-item-action active"><i class="bi bi-box-seam me-2"></i>My Products</a>
                <a href="<?= route_to('seller.orders') ?>" class="list-group-item list-group-item-action"><i class="bi bi-receipt me-2"></i>Incoming Orders</a>
                <a href="<?= route_to('seller.settings') ?>" class="list-group-item list-group-item-action"><i class="bi bi-gear me-2"></i>Store Settings</a>
            </div>
        </div>
        <!-- Products Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>My Products List</h3>
                <a href="/seller/products/add" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Add New Product</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $key => $product): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= esc($product['product_name']) ?></td>
                                    <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                                    <td><?= esc($product['stock']) ?></td>
                                    <td>
                                        <a href="/seller/products/edit/<?= $product['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="/seller/products/delete/<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')"><i class="bi bi-trash-fill"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">You do not have any products yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>