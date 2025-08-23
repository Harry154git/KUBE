<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\StoreModel; // Mengubah dari TokoModel

class ProductController extends BaseController
{
    protected $productModel;
    protected $storeModel; // Mengubah dari $tokoModel

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->storeModel = new StoreModel(); // Mengubah dari TokoModel()
        helper(['form', 'url']);
    }

    /**
     * Displays the detail page for a single product.
     * Accessible by all logged-in users.
     */
    public function detail($id = null)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Product not found.');
        }

        // Retrieve store data to display on the detail page
        $store = $this->storeModel->find($product['store_id']); // Mengubah 'toko_id' menjadi 'store_id'

        $data = [
            'title'   => $product['product_name'], // Mengubah 'nama_produk'
            'product' => $product,
            'store'   => $store, // Mengubah 'toko' menjadi 'store'
        ];

        return view('product/detail', $data); // You need to create this view
    }

    /**
     * Displays the form to add a new product.
     * For sellers only.
     */
    public function add()
    {
        // Ensure only sellers can access
        if (!session()->get('is_seller')) {
            return redirect()->to('/home')->with('error', 'You must be a seller to add products.');
        }

        $data = [
            'title' => 'Add New Product',
            'validation' => \Config\Services::validation()
        ];
        return view('seller/products_add', $data);
    }

    /**
     * Processes data from the add product form.
     */
    public function create()
    {
        if (!session()->get('is_seller')) {
            return redirect()->to('/home');
        }

        // Validation rules
        $rules = [
            'product_name'   => 'required|min_length[3]|max_length[150]', // Mengubah 'nama_produk'
            'description'    => 'required', // Mengubah 'deskripsi'
            'price'          => 'required|numeric', // Mengubah 'harga'
            'stock'          => 'required|integer', // Mengubah 'stok'
            'product_image'  => 'uploaded[product_image]|max_size[product_image,2048]|is_image[product_image]|mime_in[product_image,image/jpg,image/jpeg,image/png]' // Mengubah 'gambar_produk'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Process image upload
        $imgFile = $this->request->getFile('product_image'); // Mengubah 'gambar_produk'
        $imgName = $imgFile->getRandomName();
        $imgFile->move(ROOTPATH . 'public/uploads/products', $imgName);

        // Save data to the database
        $this->productModel->save([
            'store_id'      => session()->get('store_id'), // Mengubah 'toko_id'
            'product_name'  => $this->request->getPost('product_name'), // Mengubah 'nama_produk'
            'description'   => $this->request->getPost('description'), // Mengubah 'deskripsi'
            'price'         => $this->request->getPost('price'), // Mengubah 'harga'
            'stock'         => $this->request->getPost('stock'), // Mengubah 'stok'
            'product_image' => $imgName, // Mengubah 'gambar_produk'
        ]);

        return redirect()->to(route_to('seller.products'))->with('message', 'Product successfully added.');
    }

    /**
     * Displays the form to edit a product.
     */
    public function edit($id = null)
    {
        if (!session()->get('is_seller')) {
            return redirect()->to('/home');
        }

        $product = $this->productModel->find($id);

        // Security: Ensure the product exists and is owned by the logged-in store
        if (!$product || $product['store_id'] != session()->get('store_id')) { // Mengubah 'toko_id'
            return redirect()->to(route_to('seller.products'))->with('error', 'Invalid product or you do not have permission.');
        }

        $data = [
            'title'      => 'Edit Product',
            'product'    => $product,
            'validation' => \Config\Services::validation()
        ];

        return view('seller/products_edit', $data);
    }

    /**
     * Processes data from the edit product form.
     */
    public function update($id = null)
    {
        if (!session()->get('is_seller')) {
            return redirect()->to('/home');
        }

        $product = $this->productModel->find($id);

        // Security: Ensure the product exists and is owned by this store
        if (!$product || $product['store_id'] != session()->get('store_id')) { // Mengubah 'toko_id'
            return redirect()->to(route_to('seller.products'))->with('error', 'Action not allowed.');
        }

        // Validation rules
        $rules = [
            'product_name'  => 'required|min_length[3]|max_length[150]',
            'description'   => 'required',
            'price'         => 'required|numeric',
            'stock'         => 'required|integer',
        ];
        
        // Validate image only if a new file is uploaded
        if ($this->request->getFile('product_image')->isValid()) {
            $rules['product_image'] = 'max_size[product_image,2048]|is_image[product_image]|mime_in[product_image,image/jpg,image/jpeg,image/png]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $imgFile = $this->request->getFile('product_image');
        
        // Check if a new image was uploaded
        if ($imgFile->isValid() && !$imgFile->hasMoved()) {
            // Delete old image
            if ($product['product_image'] && file_exists(ROOTPATH . 'public/uploads/products/' . $product['product_image'])) {
                unlink(ROOTPATH . 'public/uploads/products/' . $product['product_image']);
            }
            // Upload new image
            $imgName = $imgFile->getRandomName();
            $imgFile->move(ROOTPATH . 'public/uploads/products', $imgName);
        } else {
            // If no new image, use the old image name
            $imgName = $this->request->getPost('old_image'); // Mengubah 'gambar_lama'
        }

        $this->productModel->update($id, [
            'product_name'  => $this->request->getPost('product_name'),
            'description'   => $this->request->getPost('description'),
            'price'         => $this->request->getPost('price'),
            'stock'         => $this->request->getPost('stock'),
            'product_image' => $imgName,
        ]);

        return redirect()->to(route_to('seller.products'))->with('message', 'Product successfully updated.');
    }

    /**
     * Deletes a product.
     */
    public function delete($id = null)
    {
        if (!session()->get('is_seller')) {
            return redirect()->to('/home');
        }

        $product = $this->productModel->find($id);

        // Security: Ensure the product exists and is owned by this store
        if (!$product || $product['store_id'] != session()->get('store_id')) { // Mengubah 'toko_id'
            return redirect()->to(route_to('seller.products'))->with('error', 'Action not allowed.');
        }

        // Delete the image file from the server
        if ($product['product_image'] && file_exists(ROOTPATH . 'public/uploads/products/' . $product['product_image'])) {
            unlink(ROOTPATH . 'public/uploads/products/' . $product['product_image']);
        }
        
        $this->productModel->delete($id);

        return redirect()->to(route_to('seller.products'))->with('message', 'Product successfully deleted.');
    }
}
