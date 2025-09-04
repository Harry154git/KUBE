<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel; // Mengubah dari TokoModel
use App\Models\UserModel; 
use App\Models\ProductModel; 
use App\Models\OrderModel; // Gunakan OrderModel, bukan OrderDetailModel
use App\Models\OrderDetailModel;
use App\Models\AddressModel;

class SellerController extends BaseController
{
    protected $storeModel;
    protected $userModel;
    protected $productModel;
    protected $orderModel;
    protected $orderDetailModel;

    public function __construct()
    {
        $this->storeModel = new StoreModel(); // Mengubah dari TokoModel()
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
        $this->orderDetailModel = new OrderDetailModel();
        // Load required helper
        helper(['form', 'url']);
    }

    /**
     * Page to register/activate a seller account.
     */
    public function activate()
    {
        // If already a seller, redirect to dashboard
        if (session()->get('is_seller')) {
            return redirect()->to(route_to('seller.dashboard'));
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'store_name' => 'required|min_length[3]|max_length[100]', // Mengubah 'nama_toko'
                'store_description' => 'required|min_length[10]', // Mengubah 'deskripsi_toko'
                'store_address' => 'required', // Mengubah 'alamat_toko'
                'bank_account' => 'required', // Mengubah 'rekening_bank'
            ];

            if ($this->validate($rules)) {
                $userId = session()->get('user_id');
                
                // 1. Save new store data
                $storeData = [
                    'user_id' => $userId,
                    'store_name' => $this->request->getPost('store_name'), // Mengubah 'nama_toko'
                    'store_description' => $this->request->getPost('store_description'), // Mengubah 'deskripsi_toko'
                    'store_address' => $this->request->getPost('store_address'), // Mengubah 'alamat_toko'
                    'bank_account' => $this->request->getPost('bank_account'), // Mengubah 'rekening_bank'
                ];
                $this->storeModel->insert($storeData);
                $storeId = $this->storeModel->getInsertID();

                // 2. Update user status to seller
                $this->userModel->update($userId, [
                    'is_seller' => 1,
                    'store_id' => $storeId // Mengubah 'toko_id'
                ]);

                // 3. Update session
                $sessionData = [
                    'is_seller' => 1,
                    'store_id' => $storeId, // Mengubah 'toko_id'
                    'store_name' => $storeData['store_name'], // Mengubah 'nama_toko'
                ];
                session()->set($sessionData);

                return redirect()->to(route_to('seller.dashboard'))->with('message', 'Congratulations! Your store has been activated.');
            } else {
                return view('seller/activate', [
                    'validation' => $this->validator
                ]);
            }
        }

        return view('seller/activate');
    }

    /**
     * Seller dashboard page.
     */
    public function dashboard()
    {
        // Ensure only sellers can access
        if (!session()->get('is_seller')) {
            return redirect()->to('/home')->with('error', 'Anda bukan seorang penjual.');
        }
        
        $storeId = session()->get('store_id');

        // (BARU) Ambil data statistik dari database
        $totalProducts = $this->productModel->where('store_id', $storeId)->countAllResults();
        
        $newOrders = $this->orderModel->where('store_id', $storeId)
                                      ->where('status', 'processing') // Hanya hitung pesanan yang perlu diproses
                                      ->countAllResults();

        $data = [
            'title' => 'Dasbor Penjual',
            'totalProducts' => $totalProducts,
            'newOrders' => $newOrders,
        ];

        return view('seller/dashboard', $data);
    }

    /**
     * Page to view products for sale.
     */
    public function products()
    {
        if (!session()->get('is_seller')) return redirect()->to('/home');
        
        $storeId = session()->get('store_id'); // Mengubah 'toko_id'
        $data = [
            'title' => 'My Products',
            'products' => $this->productModel->where('store_id', $storeId)->findAll() // Mengubah 'toko_id'
        ];
        return view('seller/products', $data);
    }

    /**
     * Page to view and manage incoming orders.
     * With the new model, each order is from a single store.
     */
    public function orders()
    {
        if (!session()->get('is_seller')) return redirect()->to('/home');
        
        $storeId = session()->get('store_id');
        $orders = $this->orderModel
            ->select('orders.*, users.full_name as customer_name')
            ->join('users', 'users.id = orders.user_id')
            ->where('orders.store_id', $storeId)
            ->orderBy('orders.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Pesanan Masuk',
            'orders' => $orders
        ];
        return view('seller/orders', $data);
    }

    /**
     * (BARU) Menampilkan halaman detail untuk satu pesanan.
     */
    public function orderDetail($orderId)
    {
        if (!session()->get('is_seller')) return redirect()->to('/home');
        $storeId = session()->get('store_id');

        // Ambil data order utama dan pastikan milik toko ini
        $order = $this->orderModel
            ->select('orders.*, users.full_name as customer_name')
            ->join('users', 'users.id = orders.user_id')
            ->where('orders.id', $orderId)
            ->where('orders.store_id', $storeId)
            ->first();

        if (!$order) {
            return redirect()->to(route_to('seller.orders'))->with('error', 'Pesanan tidak ditemukan.');
        }

        // Ambil detail item dalam pesanan
        $orderDetails = $this->orderDetailModel
            ->select('order_details.*, p.product_name, p.product_image')
            ->join('products p', 'p.id = order_details.product_id')
            ->where('order_details.order_id', $orderId)
            ->findAll();

        // Ambil alamat pengiriman
        $addressModel = new AddressModel();
        $shippingAddress = $addressModel->find($order['shipping_address_id']);

        $data = [
            'title' => 'Detail Pesanan',
            'order' => $order,
            'orderDetails' => $orderDetails,
            'shippingAddress' => $shippingAddress,
        ];
        return view('seller/order_detail', $data);
    }

    /**
     * (BARU) Memproses aksi "Kirim Pesanan".
     */
    public function shipOrder()
    {
        if (!session()->get('is_seller')) return redirect()->to('/home');
        
        $orderId = $this->request->getPost('order_id');
        $storeId = session()->get('store_id');
        
        // Validasi: pastikan pesanan ada dan milik toko ini
        $order = $this->orderModel->where('id', $orderId)->where('store_id', $storeId)->first();
        if ($order) {
            $this->orderModel->update($orderId, ['status' => 'shipped']);
            return redirect()->to(route_to('seller.orders.detail', $orderId))
                             ->with('message', 'Status pesanan berhasil diubah menjadi "Dikirim".');
        }

        return redirect()->to(route_to('seller.orders'))->with('error', 'Gagal memperbarui status pesanan.');
    }

    /**
     * Method to update order status.
     * Logic is simplified with the new one-order-per-store model.
     */
    public function updateOrderStatus()
    {
        if (!session()->get('is_seller')) {
            return redirect()->to('/home');
        }

        $orderId = $this->request->getPost('order_id');
        $newStatus = $this->request->getPost('status');
        $storeId = session()->get('store_id');

        // Find the order by ID and ensure it belongs to the logged-in store
        $order = $this->orderModel->where('id', $orderId)->where('store_id', $storeId)->first();

        if ($order) {
            // Update the status
            $this->orderModel->update($orderId, ['status' => $newStatus]);
            return redirect()->to(route_to('seller.orders'))->with('message', 'Order status successfully updated.');
        }

        return redirect()->to(route_to('seller.orders'))->with('error', 'Failed to update order status. Order not found or not owned by your store.');
    }

    /**
     * Displays the form to add a new product.
     */
    public function add()
    {
        $data = [
            'title' => 'Tambah Produk Baru',
        ];
        // Panggil file form yang sama, tanpa data 'product'
        return view('seller/product_form', $data);
    }

    /**
     * Displays the form to edit an existing product.
     */
    public function edit($id)
    {
        $product = $this->productModel->find($id);

        // Security check: ensure the product belongs to the seller
        if (!$product || $product['store_id'] != session()->get('store_id')) {
            return redirect()->to(route_to('seller.products'))->with('error', 'Produk tidak ditemukan atau Anda tidak berhak mengaksesnya.');
        }

        $data = [
            'title' => 'Edit Produk',
            'product' => $product // Kirim data produk yang akan diedit
        ];
        
        // Panggil file form yang sama, dengan mengirimkan data 'product'
        return view('seller/product_form', $data);
    }

    /**
     * Store settings page.
     */
    public function settings()
    {
        if (!session()->get('is_seller')) return redirect()->to('/home');

        $storeId = session()->get('store_id'); // Mengubah 'toko_id'
        $store = $this->storeModel->find($storeId); // Mengubah $this->tokoModel->find($tokoId)

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'store_name' => 'required|min_length[3]|max_length[100]',
                'store_description' => 'required|min_length[10]',
                'store_address' => 'required',
                'bank_account' => 'required',
            ];
            
            if ($this->validate($rules)) {
                $dataUpdate = [
                    'store_name' => $this->request->getPost('store_name'),
                    'store_description' => $this->request->getPost('store_description'),
                    'store_address' => $this->request->getPost('store_address'),
                    'bank_account' => $this->request->getPost('bank_account'),
                ];
                $this->storeModel->update($storeId, $dataUpdate);

                // Update session if store name changes
                if($dataUpdate['store_name'] !== session()->get('store_name')) {
                    session()->set('store_name', $dataUpdate['store_name']);
                }

                return redirect()->to(route_to('seller.settings'))->with('message', 'Store settings successfully updated.');
            } else {
                return view('seller/settings', [
                    'title' => 'Store Settings',
                    'store' => $store,
                    'validation' => $this->validator
                ]);
            }
        }

        $data = [
            'title' => 'Store Settings',
            'store' => $store
        ];
        return view('seller/settings', $data);
    }

    public function cancelOrder()
    {
        $orderId = $this->request->getPost('order_id');
        $storeId = session()->get('store_id');

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Validasi: pastikan pesanan ada dan milik toko ini
            $order = $this->orderModel
                ->where('id', $orderId)
                ->where('store_id', $storeId)
                ->first();

            if (!$order) {
                throw new \Exception('Pesanan tidak valid untuk dibatalkan.');
            }

            // Ambil semua item di dalam pesanan
            $orderDetails = $this->orderDetailModel->where('order_id', $orderId)->findAll();

            // Kembalikan stok untuk setiap produk
            foreach ($orderDetails as $item) {
                $this->productModel->where('id', $item['product_id'])->increment('stock', (int)$item['quantity']);
            }

            // Ubah status pesanan menjadi 'canceled'
            $this->orderModel->update($orderId, ['status' => 'canceled']);

            $db->transComplete();

            if ($db->transStatus() === false) {
                 throw new \Exception('Transaksi database untuk pembatalan gagal.');
            }

            return redirect()->to(route_to('seller.orders.detail', $orderId))
                             ->with('message', 'Pesanan telah berhasil dibatalkan dan stok produk telah dikembalikan.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[SellerController] ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }
}
