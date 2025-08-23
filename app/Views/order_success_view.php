<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Pesanan Berhasil Dibuat
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm">
                <div class="card-body py-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    <h2 class="mt-3">Terima Kasih!</h2>
                    <p class="lead">Pesanan Anda telah berhasil kami terima.</p>
                    <p class="mb-4">Nomor Invoice Anda adalah: <br><strong class="fs-5"><?= esc($order['invoice_number']) ?></strong></p>
                    <p>Silakan selesaikan pembayaran agar pesanan Anda dapat segera kami proses.</p>
                    
                    <div class="mt-4">
                        <a href="/order-history" class="btn btn-primary">Lihat Riwayat Pesanan</a>
                        <a href="/home" class="btn btn-outline-secondary">Kembali ke Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>