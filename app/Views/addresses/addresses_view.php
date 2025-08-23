<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Kelola Alamat
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Kelola Alamat Pengiriman</h3>
                
                <?php if (count($addresses) < 3): ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="bi bi-plus-circle"></i> Tambah Alamat Baru
                    </button>
                <?php else: ?>
                    <p class="text-muted mb-0">Anda telah mencapai batas maksimal 3 alamat.</p>
                <?php endif; ?>
            </div>

            <!-- Tampilkan pesan notifikasi -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($addresses)): ?>
                <div class="card card-body text-center">
                    <p class="mb-0">Anda belum memiliki alamat tersimpan. Silakan tambahkan alamat baru.</p>
                </div>
            <?php else: ?>
                <!-- Daftar Alamat -->
                <?php foreach ($addresses as $address): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div class="me-3">
                                <h5 class="card-title mb-1">
                                    <?= esc($address['label']) ?>
                                    <?php if ($address['is_primary']): ?>
                                        <span class="badge bg-success">Utama</span>
                                    <?php endif; ?>
                                </h5>
                                <p class="card-text mb-1"><strong><?= esc($address['recipient_name']) ?></strong> (<?= esc($address['phone_number']) ?>)</p>
                                <p class="card-text text-muted">
                                    <?= esc($address['address']) ?>, <?= esc($address['city']) ?>, <?= esc($address['province']) ?>, <?= esc($address['postal_code']) ?>
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                                <!-- Tombol Aksi -->
                                <button type="button" class="btn btn-outline-secondary btn-sm edit-address-btn" 
                                        data-bs-toggle="modal" data-bs-target="#editAddressModal"
                                        data-address='<?= json_encode($address) ?>'>
                                    Ubah
                                </button>
                                <?php if (!$address['is_primary']): ?>
                                    <a href="/addresses/set-primary/<?= $address['id'] ?>" class="btn btn-outline-success btn-sm">Jadikan Utama</a>
                                    <a href="/addresses/delete/<?= $address['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <a href="/profile" class="btn btn-link mt-3 p-0"><i class="bi bi-arrow-left"></i> Kembali ke Profil</a>
        </div>
    </div>
</div>

<!-- Modal Tambah Alamat -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAddressModalLabel">Tambah Alamat Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= site_url('/addresses/create') ?>" method="post">
        <?= csrf_field() ?>
        <div class="modal-body">
            <!-- Form fields for adding address -->
            <?= $this->include('addresses/partials/address_form_fields', ['address' => []]) ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Alamat</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Alamat -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAddressModalLabel">Ubah Alamat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editAddressForm" action="" method="post">
        <?= csrf_field() ?>
        <div class="modal-body">
            <!-- Form fields for editing address -->
            <?= $this->include('addresses/partials/address_form_fields', ['address' => []]) ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editAddressModal = document.getElementById('editAddressModal');
    if (editAddressModal) {
        editAddressModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;
            // Extract info from data-address attribute
            const address = JSON.parse(button.getAttribute('data-address'));
            
            // Update the modal's content.
            const modalTitle = editAddressModal.querySelector('.modal-title');
            const modalForm = editAddressModal.querySelector('#editAddressForm');

            modalTitle.textContent = 'Ubah Alamat: ' + address.label;
            modalForm.action = '<?= site_url('addresses/update/') ?>' + address.id;

            // Populate form fields
            modalForm.querySelector('[name="label"]').value = address.label;
            modalForm.querySelector('[name="recipient_name"]').value = address.recipient_name;
            modalForm.querySelector('[name="phone_number"]').value = address.phone_number;
            modalForm.querySelector('[name="address"]').value = address.address;
            modalForm.querySelector('[name="city"]').value = address.city;
            modalForm.querySelector('[name="province"]').value = address.province;
            modalForm.querySelector('[name="postal_code"]').value = address.postal_code;
        });
    }
});
</script>
<?= $this->endSection() ?>
