<?php

namespace App\Controllers;

use App\Models\ProductModel;

class HomeController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $data['products'] = $productModel->findAll();
        
        // Memuat session untuk mendapatkan nama pengguna
        $session = \Config\Services::session();
        $data['nama_lengkap'] = $session->get('nama_lengkap');

        return view('home_view', $data);
    }
}