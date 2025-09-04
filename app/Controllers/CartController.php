<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CartModel;
use CodeIgniter\API\ResponseTrait; // Use this for API responses

class CartController extends BaseController
{
    use ResponseTrait; // Trait to easily send JSON responses

    protected $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        helper(['form', 'url', 'session']);
    }

    /**
     * Displays the shopping cart page.
     */
    public function index()
    {
        $userId = session()->get('user_id');
        $items = $this->cartModel->getCartItems($userId);
        
        return view('cart_view', [
            'items' => $items,
        ]);
    }

    /**
     * Adds a product to the cart.
     */
    public function add()
    {
        $userId = session()->get('user_id');
        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity');

        // Check if the product already exists in the user's cart
        $existingItem = $this->cartModel
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingItem) {
            // If it exists, update the quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            $this->cartModel->update($existingItem['id'], ['quantity' => $newQuantity]);
        } else {
            // If it doesn't exist, add it as a new item
            $this->cartModel->save([
                'user_id'    => $userId,
                'product_id' => $productId,
                'quantity'   => $quantity,
            ]);
        }

        session()->setFlashdata('success', 'Product has been successfully added to cart!');
        return redirect()->to('/cart');
    }

    /**
     * Updates the quantity of an item in the cart via AJAX.
     */
    public function update()
    {
        $cartId = $this->request->getPost('cart_id');
        $quantity = $this->request->getPost('quantity');

        // Validate the request
        if (!$this->request->isAJAX() || empty($cartId) || empty($quantity)) {
            return $this->failUnauthorized('Unauthorized access or invalid request.');
        }

        // Check if the user is authorized to update this cart item
        $userId = session()->get('user_id');
        $item = $this->cartModel->find($cartId);

        if (!$item || $item['user_id'] != $userId) {
            return $this->failUnauthorized('You are not authorized to update this item.');
        }

        // Update the quantity
        $this->cartModel->update($cartId, ['quantity' => $quantity]);

        // Get the new CSRF hash
        $newCsrfHash = csrf_hash();

        // Send a JSON response with the new CSRF hash
        return $this->respond(['success' => true, 'message' => 'Quantity updated successfully.', 'csrf_hash' => $newCsrfHash]);
    }

    /**
     * Removes an item from the cart.
     */
    public function remove($cartId = null)
    {
        $userId = session()->get('user_id');
        $item = $this->cartModel->where('id', $cartId)->where('user_id', $userId)->first();

        if ($item) {
            $this->cartModel->delete($cartId);
            session()->setFlashdata('success', 'Item successfully removed from cart.');
        }

        return redirect()->to('/cart');
    }

    /**
     * Removes multiple items from the cart based on selection.
     */
    public function removeBatch()
    {
        $userId = session()->get('user_id');
        $cartIds = $this->request->getPost('cart_items');

        if (empty($cartIds) || !is_array($cartIds)) {
            session()->setFlashdata('error', 'Tidak ada produk yang dipilih untuk dihapus.');
            return redirect()->to('/cart');
        }

        // Ensure the user owns these cart items before deleting
        $this->cartModel
            ->where('user_id', $userId)
            ->whereIn('id', $cartIds)
            ->delete();

        session()->setFlashdata('success', 'Produk yang dipilih telah berhasil dihapus dari keranjang.');
        return redirect()->to('/cart');
    }
}