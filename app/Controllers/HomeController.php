<?php

namespace App\Controllers;

use App\Models\ProductModel;

class HomeController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        
        $data = [
            'products' => $productModel->findAll(),
            'nama_lengkap' => session()->get('nama_lengkap'),
            'description' => 'KUBE Purun adalah pusat kerajinan anyaman purun berkualitas tinggi dari Kalimantan Selatan. Temukan tas, dompet, dan produk unik lainnya.'
        ];

        return view('home_view', $data);
    }
}