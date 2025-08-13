<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\TokoModel;

class ProductController extends BaseController
{
    protected $productModel;
    protected $tokoModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->tokoModel = new TokoModel();
        helper(['form', 'url']);
    }

    /**
     * Menampilkan halaman detail untuk satu produk.
     * Dapat diakses oleh semua pengguna yang login.
     */
    public function detail($id = null)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        // Ambil data toko untuk ditampilkan di halaman detail
        $toko = $this->tokoModel->find($product['toko_id']);

        $data = [
            'title'   => $product['nama_produk'],
            'product' => $product,
            'toko'    => $toko,
        ];

        return view('product/detail', $data); // Anda perlu membuat view ini
    }

    /**
     * Menampilkan form untuk menambah produk baru.
     * Hanya untuk penjual.
     */
    public function add()
    {
        // Pastikan hanya penjual yang bisa akses
        if (!session()->get('is_seller')) {
            return redirect()->to('/home')->with('error', 'Anda harus menjadi penjual untuk menambah produk.');
        }

        $data = [
            'title' => 'Tambah Produk Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('seller/products_add', $data);
    }

    /**
     * Memproses data dari form tambah produk.
     */
    public function create()
    {
        if (!session()->get('is_seller')) {
            return redirect()->to('/home');
        }

        // Aturan validasi
        $rules = [
            'nama_produk'   => 'required|min_length[3]|max_length[150]',
            'deskripsi'     => 'required',
            'harga'         => 'required|numeric',
            'stok'          => 'required|integer',
            'gambar_produk' => 'uploaded[gambar_produk]|max_size[gambar_produk,2048]|is_image[gambar_produk]|mime_in[gambar_produk,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Proses upload gambar
        $imgFile = $this->request->getFile('gambar_produk');
        $imgName = $imgFile->getRandomName();
        $imgFile->move(ROOTPATH . 'public/uploads/products', $imgName);

        // Simpan data ke database
        $this->productModel->save([
            'toko_id'       => session()->get('toko_id'),
            'nama_produk'   => $this->request->getPost('nama_produk'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'harga'         => $this->request->getPost('harga'),
            'stok'          => $this->request->getPost('stok'),
            'gambar_produk' => $imgName,
        ]);

        return redirect()->to(route_to('seller.products'))->with('message', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit($id = null)
    {
        if (!session()->get('is_seller')) {
            return redirect()->to('/home');
        }

        $product = $this->productModel->find($id);

        // Keamanan: Pastikan produk ada dan dimiliki oleh toko yang sedang login
        if (!$product || $product['toko_id'] != session()->get('toko_id')) {
            return redirect()->to(route_to('seller.products'))->with('error', 'Produk tidak valid atau Anda tidak memiliki izin.');
        }

        $data = [
            'title'      => 'Edit Produk',
            'product'    => $product,
            'validation' => \Config\Services::validation()
        ];

        return view('seller/products_edit', $data);
    }

    /**
     * Memproses data dari form edit produk.
     */
    public function update($id = null)
    {
        if (!session()->get('is_seller')) {
            return redirect()->to('/home');
        }

        $product = $this->productModel->find($id);

        // Keamanan: Pastikan produk ada dan dimiliki oleh toko ini
        if (!$product || $product['toko_id'] != session()->get('toko_id')) {
            return redirect()->to(route_to('seller.products'))->with('error', 'Aksi tidak diizinkan.');
        }

        // Aturan validasi
        $rules = [
            'nama_produk' => 'required|min_length[3]|max_length[150]',
            'deskripsi'   => 'required',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
        ];
        
        // Validasi gambar hanya jika ada file baru yang diupload
        if ($this->request->getFile('gambar_produk')->isValid()) {
            $rules['gambar_produk'] = 'max_size[gambar_produk,2048]|is_image[gambar_produk]|mime_in[gambar_produk,image/jpg,image/jpeg,image/png]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $imgFile = $this->request->getFile('gambar_produk');
        
        // Cek apakah ada gambar baru yang diupload
        if ($imgFile->isValid() && !$imgFile->hasMoved()) {
            // Hapus gambar lama
            if ($product['gambar_produk'] && file_exists(ROOTPATH . 'public/uploads/products/' . $product['gambar_produk'])) {
                unlink(ROOTPATH . 'public/uploads/products/' . $product['gambar_produk']);
            }
            // Upload gambar baru
            $imgName = $imgFile->getRandomName();
            $imgFile->move(ROOTPATH . 'public/uploads/products', $imgName);
        } else {
            // Jika tidak ada gambar baru, gunakan nama gambar lama
            $imgName = $this->request->getPost('gambar_lama');
        }

        $this->productModel->update($id, [
            'nama_produk'   => $this->request->getPost('nama_produk'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'harga'         => $this->request->getPost('harga'),
            'stok'          => $this->request->getPost('stok'),
            'gambar_produk' => $imgName,
        ]);

        return redirect()->to(route_to('seller.products'))->with('message', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk.
     */
    public function delete($id = null)
    {
        if (!session()->get('is_seller')) {
            return redirect()->to('/home');
        }

        $product = $this->productModel->find($id);

        // Keamanan: Pastikan produk ada dan dimiliki oleh toko ini
        if (!$product || $product['toko_id'] != session()->get('toko_id')) {
            return redirect()->to(route_to('seller.products'))->with('error', 'Aksi tidak diizinkan.');
        }

        // Hapus file gambar dari server
        if ($product['gambar_produk'] && file_exists(ROOTPATH . 'public/uploads/products/' . $product['gambar_produk'])) {
            unlink(ROOTPATH . 'public/uploads/products/' . $product['gambar_produk']);
        }
        
        $this->productModel->delete($id);

        return redirect()->to(route_to('seller.products'))->with('message', 'Produk berhasil dihapus.');
    }
}