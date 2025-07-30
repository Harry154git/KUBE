<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CartModel;

class CartController extends BaseController
{
    protected $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        helper(['form', 'url', 'session']);
    }

    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        $userId = session()->get('user_id');
        $items = $this->cartModel->getCartItems($userId);
        
        $total = 0;
        foreach ($items as $item) {
            $total += $item['harga'] * $item['quantity'];
        }

        return view('cart_view', [
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Menambahkan produk ke keranjang.
     */
    public function add()
    {
        $userId = session()->get('user_id');
        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity');

        // Cek apakah produk sudah ada di keranjang user
        $existingItem = $this->cartModel
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingItem) {
            // Jika sudah ada, update quantity-nya
            $newQuantity = $existingItem['quantity'] + $quantity;
            $this->cartModel->update($existingItem['id'], ['quantity' => $newQuantity]);
        } else {
            // Jika belum ada, tambahkan sebagai item baru
            $this->cartModel->save([
                'user_id'    => $userId,
                'product_id' => $productId,
                'quantity'   => $quantity,
            ]);
        }

        session()->setFlashdata('success', 'Produk berhasil ditambahkan ke keranjang!');
        return redirect()->to('/cart');
    }

    /**
     * Mengupdate kuantitas item di keranjang.
     */
    public function update()
    {
        $cartItems = $this->request->getPost('cart');
        foreach ($cartItems as $cartId => $data) {
            $this->cartModel->update($cartId, ['quantity' => $data['quantity']]);
        }
        
        session()->setFlashdata('success', 'Keranjang berhasil diperbarui.');
        return redirect()->to('/cart');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove($cartId = null)
    {
        $userId = session()->get('user_id');
        $item = $this->cartModel->where('id', $cartId)->where('user_id', $userId)->first();

        if ($item) {
            $this->cartModel->delete($cartId);
            session()->setFlashdata('success', 'Item berhasil dihapus dari keranjang.');
        }

        return redirect()->to('/cart');
    }
}
