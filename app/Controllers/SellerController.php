<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel; // Mengubah dari TokoModel
use App\Models\UserModel; 
use App\Models\ProductModel; 
use App\Models\OrderModel; // Gunakan OrderModel, bukan OrderDetailModel
use App\Models\OrderDetailModel;

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
            return redirect()->to('/home')->with('error', 'You are not a seller.');
        }
        
        // You can add logic for statistics here
        $data = [
            'title' => 'Seller Dashboard',
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
        
        $storeId = session()->get('store_id'); // Mengubah 'toko_id'
        $data = [
            'title' => 'Incoming Orders',
            // Gunakan OrderModel untuk mengambil pesanan yang terkait dengan toko ini
            'orders' => $this->orderModel->where('store_id', $storeId)->findAll() // Mengubah dari OrderDetailModel
        ];
        return view('seller/orders', $data);
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
}
