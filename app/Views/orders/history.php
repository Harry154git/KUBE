<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Order History
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">My Order History</h4>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            
            <?php if (!empty($orders)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice No.</th>
                                <th>Date</th>
                                <th>Total Payment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= esc($order['invoice_number']) ?></td>
                                    <td><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></td>
                                    <td>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                                    <td><span class="badge bg-primary"><?= esc(ucfirst($order['status'])) ?></span></td>
                                    <td>
                                        <a href="<?= site_url('order/track/' . $order['invoice_number']) ?>" class="btn btn-sm btn-info">
                                            <i class="bi bi-truck me-1"></i> Track
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <p class="text-muted">You do not have any order history yet.</p>
                    <a href="/home" class="btn btn-primary">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
