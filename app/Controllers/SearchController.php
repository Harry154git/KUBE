<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class SearchController extends BaseController
{
    /**
    * Muat helper yang dibutuhkan di sini.
    */
    public function __construct()
    {
        // TAMBAHKAN BARIS INI
        helper(['form', 'url']); 
    }

    /**
    * Menangani logika pencarian produk.
    */
    public function search()
    {
        $keyword = $this->request->getGet('q'); // Mengambil keyword dari URL (?q=...)
        $model = new ProductModel();
        $data = [
            'keyword' => $keyword,
            'products' => [] // Default products adalah array kosong
        ];

        if ($keyword) {
            // Jika ada keyword, cari produk berdasarkan nama atau deskripsi
            $data['products'] = $model->like('nama_produk', $keyword)
                                      ->orLike('deskripsi', $keyword)
                                      ->findAll();
        }

        // Menampilkan view hasil pencarian dengan data yang relevan
        return view('search_view', $data);
    }

    public function detail($id = null)
    {
        $model = new ProductModel();
        $product = $model->find($id);

        if (!$product) {
            // Jika produk tidak ditemukan, tampilkan error 404
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan: ' . $id);
        }

        return view('product_detail', ['product' => $product]);
    }
}