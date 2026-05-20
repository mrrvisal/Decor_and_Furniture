<?php
/**
 * Order controller - checkout, payment (QR / pay later), order history
 */
namespace App\Controllers;

use Core\Controller;
use Core\Database;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;

class OrderController extends Controller
{
    private Order $orderModel;
    private Cart $cartModel;
    private Product $productModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }

    /** Checkout form (shipping + payment method) */
    public function checkout(): void
    {
        $this->requireLogin();
        $userId = (int) $_SESSION['user_id'];
        $items = $this->cartModel->getItems($userId);
        if (empty($items)) {
            $_SESSION['error'] = 'Your cart is empty.';
            $this->redirect($this->baseUrl('cart'));
        }
        $total = $this->cartModel->getTotal($userId);
        $this->view('cart.checkout', [
            'items' => $items,
            'total' => $total,
            'csrf_token' => $this->csrfToken(),
            'cartCount' => $this->cartModel->count($userId),
        ]);
    }

    /** Place order */
    public function place(): void
    {
        $this->requireLogin();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('cart/checkout'));
        }
        $userId = (int) $_SESSION['user_id'];
        $items = $this->cartModel->getItems($userId);
        if (empty($items)) {
            $_SESSION['error'] = 'Your cart is empty.';
            $this->redirect($this->baseUrl('cart'));
        }
        $total = $this->cartModel->getTotal($userId);
        $paymentMethod = $_POST['payment_method'] ?? '';
        if (!in_array($paymentMethod, ['qr_code', 'pay_later'])) {
            $_SESSION['error'] = 'Please select a payment method.';
            $this->redirect($this->baseUrl('cart/checkout'));
        }
        $name = trim($_POST['shipping_name'] ?? '');
        $email = trim($_POST['shipping_email'] ?? '');
        $phone = trim($_POST['shipping_phone'] ?? '');
        $address = trim($_POST['shipping_address'] ?? '');
        if (!$name || !$email || !$phone || !$address) {
            $_SESSION['error'] = 'Please fill all shipping fields.';
            $this->redirect($this->baseUrl('cart/checkout'));
        }
        $db = Database::getInstance();
        $db->beginTransaction();
        try {
            $orderId = $this->orderModel->create($userId, [
                'total_amount' => $total,
                'payment_method' => $paymentMethod,
                'shipping_name' => $name,
                'shipping_email' => $email,
                'shipping_phone' => $phone,
                'shipping_address' => $address,
                'shipping_city' => trim($_POST['shipping_city'] ?? ''),
                'shipping_postcode' => trim($_POST['shipping_postcode'] ?? ''),
                'notes' => trim($_POST['notes'] ?? ''),
            ]);
            foreach ($items as $item) {
                $qty = min((int) $item['quantity'], (int) $item['stock']);
                $this->orderModel->addItem($orderId, $item['product_id'], $item['name'], (float) $item['price'], $qty);
                $this->productModel->decrementStock($item['product_id'], $qty);
            }
            $this->cartModel->clear($userId);
            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            $_SESSION['error'] = 'Order failed. Please try again.';
            $this->redirect($this->baseUrl('cart/checkout'));
        }
        $order = $this->orderModel->find($orderId);
        if ($paymentMethod === 'qr_code') {
            $this->redirect($this->baseUrl('order/qr/' . $order['order_number']));
        }
        $_SESSION['success'] = 'Order placed. You can pay later.';
        $this->redirect($this->baseUrl('order/my-orders'));
    }

    /** Show QR payment page for order */
    public function qr(string $orderNumber): void
    {
        $this->requireLogin();
        $order = $this->orderModel->findByOrderNumber($orderNumber);
        if (!$order || (int) $order['user_id'] !== (int) $_SESSION['user_id']) {
            $_SESSION['error'] = 'Order not found.';
            $this->redirect($this->baseUrl('order/my-orders'));
        }
        if ($order['status'] === 'paid' || $order['status'] === 'delivered') {
            $_SESSION['success'] = 'Order already paid.';
            $this->redirect($this->baseUrl('order/my-orders'));
        }
        $this->view('cart.qr-payment', [
            'order' => $order,
            'qr_data' => $this->baseUrl() . ' | Order: ' . $order['order_number'] . ' | Amount: ' . number_format($order['total_amount'], 2),
            'cartCount' => $this->cartModel->count((int) $_SESSION['user_id']),
        ]);
    }

    /** My orders list */
    public function myOrders(): void
    {
        $this->requireLogin();
        $orders = $this->orderModel->getByUserId((int) $_SESSION['user_id']);
        $this->view('cart.my-orders', ['orders' => $orders, 'cartCount' => $this->cartModel->count((int) $_SESSION['user_id'])]);
    }

    /** Order detail */
    public function show(string $orderNumber): void
    {
        $this->requireLogin();
        $order = $this->orderModel->findByOrderNumber($orderNumber);
        if (!$order || (int) $order['user_id'] !== (int) $_SESSION['user_id']) {
            $_SESSION['error'] = 'Order not found.';
            $this->redirect($this->baseUrl('order/my-orders'));
        }
        $items = $this->orderModel->getItems($order['id']);
        $this->view('cart.order-detail', ['order' => $order, 'items' => $items, 'cartCount' => $this->cartModel->count((int) $_SESSION['user_id'])]);
    }
}
