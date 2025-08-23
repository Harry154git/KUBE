<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Incoming Orders - <?= esc(session()->get('store_name')) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-4">
    <div class="row">
        <!-- Seller Navigation Menu -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?= route_to('seller.dashboard') ?>" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a href="<?= route_to('seller.products') ?>" class="list-group-item list-group-item-action"><i class="bi bi-box-seam me-2"></i>My Products</a>
                <a href="<?= route_to('seller.orders') ?>" class="list-group-item list-group-item-action active"><i class="bi bi-receipt me-2"></i>Incoming Orders</a>
                <a href="<?= route_to('seller.settings') ?>" class="list-group-item list-group-item-action"><i class="bi bi-gear me-2"></i>Store Settings</a>
            </div>
        </div>
        <!-- Order Content -->
        <div class="col-md-9">
            <h3>Incoming Orders</h3>
            <?php if(session()->getFlashdata('message')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Invoice No.</th>
                                    <th>Order Date</th>
                                    <th>Customer Name</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Update Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= esc($order['invoice_number']) ?></td>
                                        <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                                        <td>
                                            <!-- Logic to get customer name -->
                                            <!-- You will need to join with the users table in your controller to get this data -->
                                            User ID: <?= esc($order['user_id']) ?>
                                        </td>
                                        <td>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark"><?= ucfirst($order['status']) ?></span>
                                        </td>
                                        <td>
                                            <form action="<?= route_to('seller.updateOrderStatus') ?>" method="post" class="d-flex">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                <select name="status" class="form-select form-select-sm me-2">
                                                    <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                                    <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                                    <option value="canceled" <?= $order['status'] == 'canceled' ? 'selected' : '' ?>>Canceled</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No incoming orders.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>