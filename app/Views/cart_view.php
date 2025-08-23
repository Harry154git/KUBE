<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Shopping Cart
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <h2 class="mb-4">Your Shopping Cart</h2>

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
        <div class="text-center py-5 card card-body">
            <i class="bi bi-cart-x" style="font-size: 5rem; color: #6c757d;"></i>
            <h4 class="mt-3">Your cart is empty</h4>
            <p class="text-muted">Let's explore our products and find your favorites!</p>
            <a href="/home" class="btn btn-primary mt-2">Start Shopping</a>
        </div>
    <?php else: ?>
        <form action="/checkout/initiate" method="post" id="cartForm">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label" for="checkAll">Select All</label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Product</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-center" style="width: 15%;">Quantity</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input item-checkbox" type="checkbox" name="cart_items[]" value="<?= $item['cart_id'] ?>" data-price="<?= $item['price'] ?>" data-quantity="<?= $item['quantity'] ?>">
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= esc($item['product_image']) ?>" width="60" class="me-3 rounded" onerror="this.onerror=null;this.src='https://placehold.co/60x60/CCCCCC/333333?text=Img';">
                                                        <div>
                                                            <a href="/product/<?= $item['product_id'] ?>" class="text-dark text-decoration-none fw-bold"><?= esc($item['product_name']) ?></a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">Rp <span class="item-price"><?= number_format($item['price'], 0, ',', '.') ?></span></td>
                                                <td class="text-center">
                                                    <div class="input-group input-group-sm">
                                                        <button class="btn btn-outline-secondary btn-sm minus-btn" type="button" data-cart-id="<?= $item['cart_id'] ?>">-</button>
                                                        <input type="number" name="quantity[<?= $item['cart_id'] ?>]" class="form-control form-control-sm text-center quantity-input" value="<?= $item['quantity'] ?>" min="1" data-cart-id="<?= $item['cart_id'] ?>">
                                                        <button class="btn btn-outline-secondary btn-sm plus-btn" type="button" data-cart-id="<?= $item['cart_id'] ?>">+</button>
                                                    </div>
                                                </td>
                                                <td class="text-end fw-bold">Rp <span class="item-subtotal"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span></td>
                                                <td class="text-center">
                                                    <a href="/cart/remove/<?= $item['cart_id'] ?>" class="btn btn-outline-danger btn-sm" title="Remove item"><i class="bi bi-trash-fill"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card shadow-sm">
                        <div class="card-header"><h5 class="mb-0">Order Summary</h5></div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span>Subtotal (<span id="itemCount">0</span> items)</span>
                                <span id="totalPrice">Rp 0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total</span>
                                <span id="grandTotal">Rp 0</span>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg" id="checkoutBtn" disabled>
                                    Proceed to Checkout <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const minusBtns = document.querySelectorAll('.minus-btn');
    const plusBtns = document.querySelectorAll('.plus-btn');
    const itemCountEl = document.getElementById('itemCount');
    const totalPriceEl = document.getElementById('totalPrice');
    const grandTotalEl = document.getElementById('grandTotal');
    const checkoutBtn = document.getElementById('checkoutBtn');

    const csrfToken = '<?= csrf_token() ?>';
    const csrfHash = '<?= csrf_hash() ?>';

    // Function to format number to IDR currency
    function formatCurrency(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    // Function to update the summary section
    function updateSummary() {
        let total = 0;
        let count = 0;
        itemCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const quantityInput = document.querySelector(`.quantity-input[data-cart-id="${checkbox.value}"]`);
                const price = parseFloat(checkbox.dataset.price);
                const quantity = parseInt(quantityInput.value);
                total += price * quantity;
                count++;
            }
        });

        itemCountEl.textContent = count;
        totalPriceEl.textContent = formatCurrency(total);
        grandTotalEl.textContent = formatCurrency(total);

        // Enable/disable checkout button based on item count
        checkoutBtn.disabled = count === 0;
    }
    
    // Function to update quantity via AJAX
    async function updateQuantityOnServer(cartId, newQuantity) {
        const formData = new FormData();
        formData.append(csrfToken, csrfHash);
        formData.append('cart_id', cartId);
        formData.append('quantity', newQuantity);

        try {
            const response = await fetch('/cart/update', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // For CodeIgniter's isAJAX()
                }
            });

            const data = await response.json();

            if (data.success) {
                // Update CSRF token on success
                document.querySelector('input[name="' + csrfToken + '"]').value = data.csrf_hash;
                console.log('Quantity updated on server.');
            } else {
                console.error('Failed to update quantity on server.');
            }
        } catch (error) {
            console.error('Error updating cart:', error);
        }
    }

    // Event listener for "Select All" checkbox
    checkAll.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSummary();
    });

    // Event listener for individual item checkboxes
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                checkAll.checked = false;
            } else {
                const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                if (allChecked) {
                    checkAll.checked = true;
                }
            }
            updateSummary();
        });
    });

    // Event listeners for quantity changes
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const cartId = this.dataset.cartId;
            const newQuantity = parseInt(this.value);

            // Get the corresponding checkbox
            const checkbox = document.querySelector(`.item-checkbox[value="${cartId}"]`);
            
            // Get the price and subtotal elements
            const row = this.closest('tr');
            const price = parseFloat(checkbox.dataset.price);
            const subtotalEl = row.querySelector('.item-subtotal');
            
            // Update the quantity in the dataset attribute
            checkbox.dataset.quantity = newQuantity;
            
            // Update the displayed subtotal
            subtotalEl.textContent = formatCurrency(price * newQuantity);

            // Call AJAX function to update quantity on the server
            updateQuantityOnServer(cartId, newQuantity);

            updateSummary(); // Recalculate summary
        });
    });

    // Event listeners for plus/minus buttons
    minusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
            let newQuantity = parseInt(input.value) - 1;
            if (newQuantity >= 1) {
                input.value = newQuantity;
                input.dispatchEvent(new Event('change')); // Trigger the change event
            }
        });
    });

    plusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
            let newQuantity = parseInt(input.value) + 1;
            input.value = newQuantity;
            input.dispatchEvent(new Event('change')); // Trigger the change event
        });
    });

    // Initial calculation when the page loads
    updateSummary();
});
</script>
<?= $this->endSection() ?>