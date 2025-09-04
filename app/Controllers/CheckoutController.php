<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AddressModel;
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\OrderDetailModel;
use App\Models\ProductModel;
use App\Models\StoreModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class CheckoutController extends BaseController
{
    protected $userId;

    public function __construct()
    {
        $this->userId = session()->get('user_id');
        helper(['form', 'url', 'session', 'number']);
    }

    public function initiate()
    {
        // ... (Fungsi ini sudah benar, tidak perlu diubah)
        $cartModel = new CartModel();
        $productModel = new ProductModel();
        $items = [];
        $subtotal = 0;

        $selectedCartItems = $this->request->getPost('cart_items');
        if ($selectedCartItems) {
            foreach ($selectedCartItems as $cartId) {
                $item = $cartModel->getCartItems($this->userId, $cartId);
                if ($item) {
                    $items[] = [
                        'product_id' => $item['product_id'], 'product_name' => $item['product_name'],
                        'product_image' => $item['product_image'], 'price' => $item['price'],
                        'quantity' => $item['quantity'], 'store_id' => $item['store_id'],
                        'cart_id' => $cartId
                    ];
                    $subtotal += $item['price'] * $item['quantity'];
                }
            }
        }
        elseif ($this->request->getPost('product_id')) {
            $productId = $this->request->getPost('product_id');
            $quantity = $this->request->getPost('quantity');
            $product = $productModel->find($productId);
            if ($product && $quantity > 0) {
                $items[] = [
                    'product_id' => $product['id'], 'product_name' => $product['product_name'],
                    'product_image' => $product['product_image'], 'price' => $product['price'],
                    'quantity' => $quantity, 'store_id' => $product['store_id'],
                    'cart_id' => null
                ];
                $subtotal += $product['price'] * $quantity;
            }
        }

        if (empty($items)) {
            return redirect()->to('/cart')->with('error', 'Silakan pilih setidaknya satu produk untuk checkout.');
        }

        session()->set('checkout_data', ['items' => $items, 'subtotal' => $subtotal]);
        return redirect()->to('/checkout');
    }

    public function index()
    {
        // ... (Fungsi ini sudah benar, tidak perlu diubah)
        $checkoutData = session()->get('checkout_data');
        if (!$checkoutData) { return redirect()->to('/cart'); }
        $addressModel = new AddressModel();
        $storeModel = new StoreModel();
        $groupedItems = [];
        foreach ($checkoutData['items'] as $item) {
            $storeId = $item['store_id'];
            if (!isset($groupedItems[$storeId])) {
                $storeData = $storeModel->find($storeId);
                $groupedItems[$storeId] = [
                    'store_id' => $storeId,
                    'store_name' => $storeData['store_name'],
                    'items' => [],
                    'store_subtotal' => 0
                ];
            }
            $itemTotal = $item['price'] * $item['quantity'];
            $groupedItems[$storeId]['items'][] = $item;
            $groupedItems[$storeId]['store_subtotal'] += $itemTotal;
        }
        $data = [
            'groupedItems' => $groupedItems,
            'subtotal' => $checkoutData['subtotal'],
            'addresses' => $addressModel->where('user_id', $this->userId)->orderBy('is_primary', 'DESC')->findAll(),
        ];
        return view('checkout_view', $data);
    }
    
    /**
     * (DIROMBAK TOTAL) Memproses checkout untuk beberapa toko sekaligus.
     */
    public function process()
    {
        $checkoutData = session()->get('checkout_data');
        if (!$this->request->is('post') || !$checkoutData) {
            return redirect()->to('/cart');
        }

        // 1. Ambil data input tunggal dan array
        $addressId = $this->request->getPost('address_id');
        $paymentMethod = $this->request->getPost('payment_method');
        $shippingMethods = $this->request->getPost('shipping_method'); // Ini adalah array
        $sellerNotes = $this->request->getPost('seller_notes');     // Ini adalah array
        $terms = $this->request->getPost('terms');
        
        // 2. Validasi input
        if (empty($addressId) || empty($shippingMethods) || empty($paymentMethod) || !$terms) {
            return redirect()->back()->withInput()->with('error', 'Harap lengkapi semua data yang diperlukan.');
        }
        
        $addressModel = new AddressModel();
        $selectedAddress = $addressModel->where('id', $addressId)->where('user_id', $this->userId)->first();
        if (!$selectedAddress) {
            return redirect()->back()->withInput()->with('error', 'Alamat pengiriman yang dipilih tidak valid.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Siapkan semua model yang dibutuhkan
            $orderModel = new OrderModel();
            $orderDetailModel = new OrderDetailModel();
            $productModel = new ProductModel();
            $cartModel = new CartModel();

            // Kelompokkan item berdasarkan toko, sama seperti di method index()
            $groupedItems = [];
            foreach ($checkoutData['items'] as $item) {
                $groupedItems[$item['store_id']][] = $item;
            }

            // 3. Loop untuk setiap toko dan buat order terpisah
            foreach ($groupedItems as $storeId => $storeItems) {
                // Hitung subtotal hanya untuk toko ini
                $storeSubtotal = 0;
                foreach ($storeItems as $item) {
                    $storeSubtotal += $item['price'] * $item['quantity'];
                }

                // Ambil data spesifik untuk toko ini dari array POST
                $shippingForThisStore = $shippingMethods[$storeId] ?? 'Reguler'; // Default jika data tidak ada
                $notesForThisStore = $sellerNotes[$storeId] ?? '';
                
                $shippingCost = ($shippingForThisStore === 'Express') ? 25000 : 15000;
                $totalAmount = $storeSubtotal + $shippingCost;
                
                // Buat entri order utama untuk toko ini
                $invoiceNumber = 'INV-' . time() . '-' . $this->userId . '-' . $storeId;
                $orderData = [
                    'user_id' => $this->userId,
                    'store_id' => $storeId,
                    'shipping_address_id' => $selectedAddress['id'],
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => $totalAmount,
                    'shipping_cost' => $shippingCost,
                    'shipping_method' => $shippingForThisStore,
                    'payment_method' => $paymentMethod,
                    'seller_notes' => $notesForThisStore,
                    'status' => 'pending_payment',
                ];
                $orderId = $orderModel->insert($orderData);
                
                if (!$orderId) { throw new DatabaseException('Gagal menyimpan data pesanan.'); }

                // Simpan detail pesanan & kurangi stok untuk setiap item di toko ini
                foreach ($storeItems as $item) {
                    $orderDetailModel->insert([
                        'order_id' => $orderId,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price_at_purchase' => $item['price'],
                    ]);
                    
                    $productModel->where('id', $item['product_id'])->decrement('stock', (int)$item['quantity']);
                    
                    if ($item['cart_id']) {
                        $cartModel->delete($item['cart_id']);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new DatabaseException('Transaksi database gagal.');
            }

            // Hapus session dan arahkan ke halaman riwayat pesanan
            session()->remove('checkout_data');
            return redirect()->to('/order/history')->with('success', 'Pesanan Anda telah berhasil dibuat.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[CheckoutController] ' . $e->getMessage());
            return redirect()->to('/checkout')->with('error', 'Terjadi kesalahan internal. Silakan coba lagi.');
        }
    }

    public function success($orderId)
    {
        // Mengarahkan ke riwayat pesanan lebih informatif daripada halaman sukses tunggal
        return redirect()->to('/order/history')->with('success', 'Pesanan Anda telah berhasil dibuat!');
    }
}