<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderDetailModel;
use App\Models\ProductModel;
use App\Models\AddressModel;

class OrderController extends BaseController
{
    /**
     * Displays the user's order history page.
     */
    public function history()
    {
        $orderModel = new OrderModel();
        $userId = session()->get('user_id');

        $data = [
            'title' => 'Order History',
            'orders' => $orderModel->where('user_id', $userId)
                                   ->orderBy('created_at', 'DESC')
                                   ->findAll(),
        ];

        return view('orders/history', $data);
    }

    /**
     * Displays the detail and tracking page for an order.
     */
    public function track($invoiceNumber)
    {
        $orderModel = new OrderModel();
        $orderDetailModel = new OrderDetailModel();
        $addressModel = new AddressModel();
        $productModel = new ProductModel(); // Load ProductModel
        $userId = session()->get('user_id');

        $order = $orderModel
                    ->where('invoice_number', $invoiceNumber)
                    ->where('user_id', $userId) // Security check
                    ->first();

        if (!$order) {
            return redirect()->to('/order/history')->with('error', 'Order not found.');
        }

        // The query is now simpler as 'order_details' no longer has 'store_id'
        $orderDetails = $orderDetailModel
            ->select('order_details.*, products.product_name, products.product_image, products.store_id') // Corrected names
            ->join('products', 'products.id = order_details.product_id')
            ->where('order_details.order_id', $order['id'])
            ->findAll();
        
        $shippingAddress = $addressModel->find($order['shipping_address_id']);

        $data = [
            'title' => 'Track Order ' . $invoiceNumber,
            'order' => $order,
            'orderDetails' => $orderDetails,
            'shippingAddress' => $shippingAddress,
        ];

        return view('orders/track', $data);
    }
}
