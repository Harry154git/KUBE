<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class SearchController extends BaseController
{
    /**
     * Load required helpers here.
     */
    public function __construct()
    {
        helper(['form', 'url']); 
    }

    /**
     * Handles product search logic.
     */
    public function search()
    {
        $keyword = $this->request->getGet('q'); // Get keyword from URL (?q=...)
        $model = new ProductModel();
        $data = [
            'keyword' => $keyword,
            'products' => [] // Default products is an empty array
        ];

        if ($keyword) {
            // If there's a keyword, search products by name or description
            $data['products'] = $model->like('product_name', $keyword) // Mengubah 'nama_produk'
                                     ->orLike('description', $keyword) // Mengubah 'deskripsi'
                                     ->findAll();
        }

        // Display the search results view with relevant data
        return view('search_view', $data);
    }
}
