<?= $this->extend('layouts/seller_layout') ?>

<?= $this->section('title') ?>
    Pesanan Masuk - <?= esc(session()->get('store_name')) ?>
<?= $this->endSection() ?>

<?php $this->setVar('activePage', 'orders'); ?>

<?= $this->section('seller_content') ?>
    <h2 class="mb-4 seller-header">Pesanan Masuk</h2>
    
    <?php if(session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card seller-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="fw-bold"><?= esc($order['invoice_number']) ?></td>
                                <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                                <td><?= esc($order['customer_name']) ?></td>
                                <td>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                        $statusClass = 'status-' . strtolower($order['status']);
                                        $statusText = ucwords(str_replace('_', ' ', $order['status']));
                                    ?>
                                    <span class="status-badge <?= $statusClass ?>"><?= esc($statusText) ?></span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= route_to('seller.orders.detail', $order['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada pesanan yang masuk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>