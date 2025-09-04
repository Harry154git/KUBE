<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Keranjang Belanja
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <h2 class="mb-4 cart-header">Keranjang Belanja Anda</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="text-center py-5 card cart-empty-card">
            <i class="bi bi-cart-x" style="font-size: 5rem; color: #6c757d;"></i>
            <h4 class="mt-3">Keranjang Anda Kosong</h4>
            <p class="text-muted">Ayo jelajahi produk kami dan temukan favoritmu!</p>
            <a href="/" class="btn btn-purun-secondary mt-3" style="max-width: 250px; margin: auto;">Mulai Belanja</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <form action="<?= route_to('cart.remove_batch') ?>" method="post" id="cartActionForm">
                    <?= csrf_field() ?>
                    <div class="card cart-list-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label" for="checkAll">Pilih Semua</label>
                            </div>
                            <button type="submit" class="btn btn-sm btn-outline-danger" id="deleteSelectedBtn" disabled>
                                <i class="bi bi-trash-fill me-1"></i> Hapus yang Dipilih
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle cart-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Produk</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-center" style="width: 15%;">Jumlah</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <tr class="cart-item-row">
                                            <td class="ps-4">
                                                <input class="form-check-input item-checkbox" type="checkbox" name="cart_items[]" value="<?= $item['cart_id'] ?>" data-price="<?= $item['price'] ?>">
                                            </td>
                                            <td>
                                                <div class="product-info-cart">
                                                    <img src="/uploads/products/<?= esc($item['product_image']) ?>" alt="<?= esc($item['product_name']) ?>" onerror="this.onerror=null;this.src='<?= base_url('images/produk-placeholder.jpg') ?>';">
                                                    <div>
                                                        <a href="/product/<?= $item['product_id'] ?>"><?= esc($item['product_name']) ?></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end item-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                            <td class="text-center">
                                                <div class="quantity-stepper mx-auto">
                                                    <button class="btn minus-btn" type="button" data-cart-id="<?= $item['cart_id'] ?>">-</button>
                                                    <input type="text" class="form-control text-center quantity-input" value="<?= $item['quantity'] ?>" min="1" data-cart-id="<?= $item['cart_id'] ?>" readonly>
                                                    <button class="btn plus-btn" type="button" data-cart-id="<?= $item['cart_id'] ?>">+</button>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold item-subtotal">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
                                            <td class="text-center">
                                                <a href="/cart/remove/<?= $item['cart_id'] ?>" class="btn-delete-cart" title="Hapus item"><i class="bi bi-trash-fill"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <form action="/checkout/initiate" method="post" id="checkoutForm">
                    <?= csrf_field() ?>
                    <div id="checkoutItemsContainer"></div>
                    <div class="card summary-card">
                        <div class="card-header"><h5 class="mb-0">Ringkasan Pesanan</h5></div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span>Subtotal (<span id="itemCount">0</span> produk)</span>
                                <span id="totalPrice">Rp 0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total</span>
                                <span id="grandTotal">Rp 0</span>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-purun-secondary btn-lg" id="checkoutBtn" disabled>
                                    Lanjut ke Checkout <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// (FIXED) Seluruh blok script diganti dengan versi yang sudah rapi dan berfungsi
document.addEventListener('DOMContentLoaded', function() {
    
    // === DEKLARASI ELEMEN & VARIABEL ===
    const checkAll = document.getElementById('checkAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const checkoutItemsContainer = document.getElementById('checkoutItemsContainer');
    const csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    // === DEKLARASI FUNGSI ===

    // Fungsi untuk format mata uang Rupiah
    function formatCurrency(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    // Fungsi untuk mengupdate ringkasan pesanan di card kanan dan status tombol
    function updateSummaryAndButtons() {
        let total = 0;
        let count = 0;
        let checkedItemsHTML = '';
        
        itemCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const row = checkbox.closest('tr');
                const quantityInput = row.querySelector('.quantity-input');
                const price = parseFloat(checkbox.dataset.price);
                const quantity = parseInt(quantityInput.value);
                total += price * quantity;
                count++;
                checkedItemsHTML += `<input type="hidden" name="cart_items[]" value="${checkbox.value}">`;
            }
        });

        document.getElementById('itemCount').textContent = count;
        document.getElementById('totalPrice').textContent = formatCurrency(total);
        document.getElementById('grandTotal').textContent = formatCurrency(total);
        
        checkoutBtn.disabled = count === 0;
        if(deleteSelectedBtn) {
            deleteSelectedBtn.disabled = count === 0;
        }
        
        checkoutItemsContainer.innerHTML = checkedItemsHTML;
    }
    
    // Fungsi untuk update kuantitas ke server via AJAX
    async function updateQuantityOnServer(cartId, newQuantity) {
        const formData = new FormData();
        formData.append(csrfName, csrfHash);
        formData.append('cart_id', cartId);
        formData.append('quantity', newQuantity);
        try {
            const response = await fetch('/cart/update', { 
                method: 'POST', 
                body: formData, 
                headers: { 'X-Requested-With': 'XMLHttpRequest' } 
            });
            const data = await response.json();
            if (data.success) { 
                csrfHash = data.csrf_hash; 
            } else { 
                console.error('Gagal update kuantitas di server.'); 
            }
        } catch (error) { 
            console.error('Error saat update keranjang:', error); 
        }
    }

    // Fungsi yang menangani perubahan kuantitas pada baris produk
    function handleQuantityChange(input, newQuantity) {
        const cartId = input.dataset.cartId;
        const row = input.closest('tr');
        const checkbox = row.querySelector('.item-checkbox');
        const price = parseFloat(checkbox.dataset.price);
        const subtotalEl = row.querySelector('.item-subtotal');
        
        input.value = newQuantity;
        
        const subtotalValue = price * newQuantity;
        subtotalEl.textContent = formatCurrency(subtotalValue);
        
        updateQuantityOnServer(cartId, newQuantity);
        
        // PANGGILAN KUNCI: Pastikan summary di kanan ikut terupdate!
        updateSummaryAndButtons();
    }

    // === EVENT LISTENERS ===

    // Listener untuk checkbox "Pilih Semua"
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            updateSummaryAndButtons();
        });
    }

    // Listener untuk setiap checkbox produk
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (checkAll) {
                checkAll.checked = Array.from(itemCheckboxes).every(cb => cb.checked);
            }
            updateSummaryAndButtons();
        });
    });

    // Listener untuk tombol + dan -
    document.querySelectorAll('.minus-btn, .plus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
            let currentQuantity = parseInt(input.value);
            let newQuantity = this.classList.contains('plus-btn') ? currentQuantity + 1 : currentQuantity - 1;
            
            if (newQuantity >= 1) {
                // Panggil fungsi utama yang akan mengurus semuanya
                handleQuantityChange(input, newQuantity);
            }
        });
    });

    // === INISIALISASI ===
    // Panggil sekali saat halaman pertama kali dimuat
    updateSummaryAndButtons();
});
</script>
<?= $this->endSection() ?>