<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TokoModel;
use App\Models\UserModel; // Pastikan Anda sudah punya model ini
use App\Models\ProductModel; // Pastikan Anda sudah punya model ini
use App\Models\OrderDetailModel;

class SellerController extends BaseController
{
    protected $tokoModel;
    protected $userModel;
    protected $productModel;
    protected $orderDetailModel;

    public function __construct()
    {
        $this->tokoModel = new TokoModel();
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
        $this->orderDetailModel = new OrderDetailModel();
        // Load helper yang dibutuhkan
        helper(['form', 'url']);
    }

    /**
     * Halaman untuk mendaftar/mengaktifkan akun penjual.
     */
    public function activate()
    {
        // Jika sudah jadi penjual, redirect ke dashboard
        if (session()->get('is_seller')) {
            return redirect()->to(route_to('seller.dashboard'));
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'nama_toko' => 'required|min_length[3]|max_length[100]',
                'deskripsi_toko' => 'required|min_length[10]',
                'alamat_toko' => 'required',
                'rekening_bank' => 'required',
            ];

            if ($this->validate($rules)) {
                $userId = session()->get('user_id');
                
                // 1. Simpan data toko baru
                $tokoData = [
                    'user_id' => $userId,
                    'nama_toko' => $this->request->getPost('nama_toko'),
                    'deskripsi_toko' => $this->request->getPost('deskripsi_toko'),
                    'alamat_toko' => $this->request->getPost('alamat_toko'),
                    'rekening_bank' => $this->request->getPost('rekening_bank'),
                ];
                $this->tokoModel->insert($tokoData);
                $tokoId = $this->tokoModel->getInsertID();

                // 2. Update status pengguna menjadi penjual
                $this->userModel->update($userId, [
                    'is_seller' => 1,
                    'toko_id' => $tokoId
                ]);

                // 3. Update session
                $sessionData = [
                    'is_seller' => 1,
                    'toko_id' => $tokoId,
                    'nama_toko' => $tokoData['nama_toko'],
                ];
                session()->set($sessionData);

                return redirect()->to(route_to('seller.dashboard'))->with('message', 'Selamat! Toko Anda berhasil diaktifkan.');
            } else {
                return view('seller/activate', [
                    'validation' => $this->validator
                ]);
            }
        }

        return view('seller/activate');
    }

    /**
     * Halaman dashboard penjual.
     */
    public function dashboard()
    {
        // Pastikan hanya penjual yang bisa akses
        if (!session()->get('is_seller')) {
            return redirect()->to('/home')->with('error', 'Anda bukan penjual.');
        }
        
        // Anda bisa menambahkan logika untuk statistik di sini
        $data = [
            'title' => 'Dashboard Penjual',
        ];
        return view('seller/dashboard', $data);
    }

    /**
     * Halaman untuk melihat produk yang dijual.
     */
    public function products()
    {
        if (!session()->get('is_seller')) return redirect()->to('/home');
        
        $tokoId = session()->get('toko_id');
        $data = [
            'title' => 'Produk Saya',
            'products' => $this->productModel->where('toko_id', $tokoId)->findAll()
        ];
        return view('seller/products', $data);
    }

    /**
     * Halaman untuk melihat dan mengelola pesanan masuk.
     */
    public function orders()
    {
        if (!session()->get('is_seller')) return redirect()->to('/home');
        
        $tokoId = session()->get('toko_id');
        $data = [
            'title' => 'Pesanan Masuk',
            'orders' => $this->orderDetailModel->getOrdersByTokoId($tokoId)
        ];
        return view('seller/orders', $data);
    }

    /**
     * Method untuk mengupdate status pesanan.
     */
    public function updateOrderStatus()
    {
        if (!session()->get('is_seller')) return redirect()->to('/home');

        $orderDetailId = $this->request->getPost('order_detail_id');
        $newStatus = $this->request->getPost('status');
        $tokoId = session()->get('toko_id');

        // Validasi bahwa pesanan ini benar-benar milik toko ini
        $orderDetail = $this->orderDetailModel->where('id', $orderDetailId)->where('toko_id', $tokoId)->first();

        if ($orderDetail) {
            $this->orderDetailModel->update($orderDetailId, ['status_pesanan_penjual' => $newStatus]);
            return redirect()->to(route_to('seller.orders'))->with('message', 'Status pesanan berhasil diperbarui.');
        }

        return redirect()->to(route_to('seller.orders'))->with('error', 'Gagal memperbarui status pesanan.');
    }

    /**
     * Halaman pengaturan toko.
     */
    public function settings()
    {
        if (!session()->get('is_seller')) return redirect()->to('/home');

        $tokoId = session()->get('toko_id');
        $toko = $this->tokoModel->find($tokoId);

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'nama_toko' => 'required|min_length[3]|max_length[100]',
                'deskripsi_toko' => 'required|min_length[10]',
                'alamat_toko' => 'required',
                'rekening_bank' => 'required',
            ];
            
            if ($this->validate($rules)) {
                $dataUpdate = [
                    'nama_toko' => $this->request->getPost('nama_toko'),
                    'deskripsi_toko' => $this->request->getPost('deskripsi_toko'),
                    'alamat_toko' => $this->request->getPost('alamat_toko'),
                    'rekening_bank' => $this->request->getPost('rekening_bank'),
                ];
                $this->tokoModel->update($tokoId, $dataUpdate);

                // Update session jika nama toko berubah
                if($dataUpdate['nama_toko'] !== session()->get('nama_toko')) {
                    session()->set('nama_toko', $dataUpdate['nama_toko']);
                }

                return redirect()->to(route_to('seller.settings'))->with('message', 'Pengaturan toko berhasil diperbarui.');
            } else {
                return view('seller/settings', [
                    'title' => 'Pengaturan Toko',
                    'toko' => $toko,
                    'validation' => $this->validator
                ]);
            }
        }

        $data = [
            'title' => 'Pengaturan Toko',
            'toko' => $toko
        ];
        return view('seller/settings', $data);
    }
}
