<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger">
        <p class="mb-0">Terdapat kesalahan pada input Anda:</p>
        <ul>
            <?php foreach (session('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<div class="mb-3">
    <label for="label" class="form-label">Label Alamat (Contoh: Rumah, Kantor)</label>
    <input type="text" class="form-control" name="label" value="<?= old('label', $address['label'] ?? '') ?>" required>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="recipient_name" class="form-label">Nama Penerima</label>
        <input type="text" class="form-control" name="recipient_name" value="<?= old('recipient_name', $address['recipient_name'] ?? '') ?>" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="phone_number" class="form-label">Nomor Telepon</label>
        <input type="text" class="form-control" name="phone_number" value="<?= old('phone_number', $address['phone_number'] ?? '') ?>" required>
    </div>
</div>
<div class="mb-3">
    <label for="address" class="form-label">Alamat Lengkap</label>
    <textarea class="form-control" name="address" rows="3" required><?= old('address', $address['address'] ?? '') ?></textarea>
</div>
<div class="row">
     <div class="col-md-6 mb-3">
        <label for="province" class="form-label">Provinsi</label>
        <input type="text" class="form-control" name="province" value="<?= old('province', $address['province'] ?? '') ?>" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="city" class="form-label">Kota/Kabupaten</label>
        <input type="text" class="form-control" name="city" value="<?= old('city', $address['city'] ?? '') ?>" required>
    </div>
</div>
 <div class="mb-3">
    <label for="postal_code" class="form-label">Kode Pos</label>
    <input type="text" class="form-control" name="postal_code" value="<?= old('postal_code', $address['postal_code'] ?? '') ?>" required>
</div>
