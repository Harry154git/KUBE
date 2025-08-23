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

    // Metode initiate() dan index() tidak perlu diubah karena sudah benar
    public function initiate()
    {
        $cartModel = new CartModel();
        $productModel = new ProductModel();
        $items = [];
        $subtotal = 0;

        // Scenario 1: Checkout from Cart (with selected items)
        $selectedCartItems = $this->request->getPost('cart_items');
        if ($selectedCartItems) {
            foreach ($selectedCartItems as $cartId) {
                $item = $cartModel->getCartItems($this->userId, $cartId);
                if ($item) {
                    $items[] = [
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'product_image' => $item['product_image'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'store_id' => $item['store_id'],
                        'cart_id' => $cartId
                    ];
                    $subtotal += $item['price'] * $item['quantity'];
                }
            }
        }
        // Scenario 2: Checkout from "Buy Now" button
        elseif ($this->request->getPost('product_id')) {
            $productId = $this->request->getPost('product_id');
            $quantity = $this->request->getPost('quantity');
            $product = $productModel->find($productId);

            if ($product && $quantity > 0) {
                $items[] = [
                    'product_id' => $product['id'],
                    'product_name' => $product['product_name'],
                    'product_image' => $product['product_image'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'store_id' => $product['store_id'],
                    'cart_id' => null // Not from cart
                ];
                $subtotal += $product['price'] * $quantity;
            }
        }

        if (empty($items)) {
            return redirect()->to('/cart')->with('error', 'Please select at least one product to checkout.');
        }

        // Store checkout details in the session
        session()->set('checkout_data', [
            'items' => $items,
            'subtotal' => $subtotal
        ]);

        return redirect()->to('/checkout');
    }

    public function index()
    {
        $checkoutData = session()->get('checkout_data');
        if (!$checkoutData) {
            return redirect()->to('/cart');
        }

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
    
    // Perbaikan utama ada di metode ini
    public function process()
    {
        $checkoutData = session()->get('checkout_data');
        if (!$this->request->is('post') || !$checkoutData) {
            return redirect()->to('/cart');
        }

        // 1. Ambil data input
        $addressId = $this->request->getPost('address_id');
        $shippingMethod = $this->request->getPost('shipping_method');
        $paymentMethod = $this->request->getPost('payment_method');
        $terms = $this->request->getPost('terms');
        
        // 2. Lakukan validasi input secara manual
        if (empty($addressId) || empty($shippingMethod) || empty($paymentMethod) || !$terms) {
            return redirect()->back()->withInput()->with('error', 'Please complete all required data.');
        }

        $addressModel = new AddressModel();
        $selectedAddress = $addressModel->where('id', $addressId)->where('user_id', $this->userId)->first();

        // VALIDASI KRITIS: Pastikan alamat yang dipilih valid dan milik pengguna
        if (!$selectedAddress) {
            return redirect()->back()->withInput()->with('error', 'Invalid shipping address selected. Please choose a valid address.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Group items by store
            $itemsByStore = [];
            foreach ($checkoutData['items'] as $item) {
                $storeId = $item['store_id'];
                if (!isset($itemsByStore[$storeId])) {
                    $itemsByStore[$storeId] = [];
                }
                $itemsByStore[$storeId][] = $item;
            }

            $orderModel = new OrderModel();
            $orderDetailModel = new OrderDetailModel();
            $productModel = new ProductModel();
            $cartModel = new CartModel();
            $invoiceNumbers = [];

            // 2. Process each store's order separately
            foreach ($itemsByStore as $storeId => $storeItems) {
                $storeSubtotal = 0;
                foreach ($storeItems as $item) {
                    $storeSubtotal += $item['price'] * $item['quantity'];
                }

                $shippingCost = ($shippingMethod === 'Express') ? 25000 : 15000;
                $totalAmount = $storeSubtotal + $shippingCost;
                
                // Create the main order entry for this store
                $invoiceNumber = 'INV-' . time() . '-' . $this->userId . '-' . $storeId;
                $orderData = [
                    'user_id' => $this->userId,
                    'store_id' => $storeId,
                    'shipping_address_id' => $selectedAddress['id'], // Gunakan ID alamat yang sudah divalidasi
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => $totalAmount,
                    'shipping_cost' => $shippingCost,
                    'shipping_method' => $shippingMethod,
                    'payment_method' => $paymentMethod,
                    'seller_notes' => $this->request->getPost('seller_notes'),
                    'status' => 'pending_payment',
                ];
                $orderId = $orderModel->insert($orderData);
                
                // Check if order was inserted successfully
                if (!$orderId) {
                     throw new DatabaseException('Failed to insert order data.');
                }

                $invoiceNumbers[] = $invoiceNumber;

                // Save order details & reduce stock for this store's items
                foreach ($storeItems as $item) {
                    $orderDetailModel->insert([
                        'order_id' => $orderId,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price_at_purchase' => $item['price'],
                    ]);
                    
                    // Reduce product stock
                    $productModel->where('id', $item['product_id'])->decrement('stock', (int)$item['quantity']);
                    
                    // Delete item from cart if it came from there
                    if ($item['cart_id']) {
                        $cartModel->delete($item['cart_id']);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new DatabaseException('Transaction failed.');
            }

            // Clear session and redirect to history page
            session()->remove('checkout_data');
            return redirect()->to('/order/history')->with('message', 'Your orders have been successfully created.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[CheckoutController] ' . $e->getMessage());
            return redirect()->to('/checkout')->with('error', 'An internal error occurred. Please try again.');
        }
    }

    public function success($orderId)
    {
        return redirect()->to('/order/history')->with('message', 'Your orders have been successfully created!');
    }
}