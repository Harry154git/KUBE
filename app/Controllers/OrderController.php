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

    public function confirmPayment()
    {
        $orderId = $this->request->getPost('order_id');
        $userId = session()->get('user_id');

        $orderModel = new OrderModel();
        
        // Security check: Find the order, ensure it belongs to the user, and is pending payment
        $order = $orderModel->where('id', $orderId)
                            ->where('user_id', $userId)
                            ->where('status', 'pending_payment')
                            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak valid atau sudah dibayar.');
        }

        // Update status to 'processing'
        $orderModel->update($orderId, ['status' => 'processing']);

        return redirect()->to('order/track/' . $order['invoice_number'])
                         ->with('success', 'Pembayaran Anda sedang diverifikasi. Penjual akan segera memproses pesanan Anda.');
    }

    /**
     * (BARU) Menangani aksi pelanggan saat mengonfirmasi pesanan telah diterima.
     * Mengubah status pesanan dari 'shipped' menjadi 'completed'.
     */
    public function receiveOrder()
    {
        $orderId = $this->request->getPost('order_id');
        $userId = session()->get('user_id');

        $orderModel = new \App\Models\OrderModel();

        // Validasi: Cari pesanan, pastikan milik pengguna, dan statusnya 'shipped'
        $order = $orderModel->where('id', $orderId)
                            ->where('user_id', $userId)
                            ->where('status', 'shipped')
                            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak valid atau belum dikirim.');
        }

        // Jika valid, ubah status menjadi 'completed'
        $orderModel->update($orderId, ['status' => 'completed']);

        return redirect()->to('order/track/' . $order['invoice_number'])
                         ->with('success', 'Terima kasih telah berbelanja! Pesanan Anda telah selesai.');
    }
}
