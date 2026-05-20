<?php
/**
 * Cart controller - add, update, remove, view cart
 */
namespace App\Controllers;

use Core\Controller;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    private Cart $cartModel;
    private Product $productModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }

    /** View cart - requires login */
    public function index(): void
    {
        $this->requireLogin();
        $userId = (int) $_SESSION['user_id'];
        $items = $this->cartModel->getItems($userId);
        $total = $this->cartModel->getTotal($userId);
        $this->view('cart.index', ['items' => $items, 'total' => $total, 'cartCount' => $this->cartModel->count($userId), 'csrf_token' => $this->csrfToken()]);
    }

    /** Add to cart (AJAX or form) - requires login */
    public function add(): void
    {
        $this->requireLogin();
        if (!$this->validateCsrf()) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Invalid request.']);
            }
            $this->redirect($this->baseUrl('cart'));
        }
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
        $product = $this->productModel->find($productId);
        if (!$product || !$product['is_active']) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Product not found.']);
            }
            $_SESSION['error'] = 'Product not found.';
            $this->redirect($this->baseUrl());
        }
        if ($product['stock'] < $quantity) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Not enough stock.']);
            }
            $_SESSION['error'] = 'Not enough stock.';
            $this->redirect($this->baseUrl('product/' . $product['slug']));
        }
        $this->cartModel->add((int) $_SESSION['user_id'], $productId, $quantity);
        if ($this->isAjax()) {
            $this->json([
                'success' => true,
                'message' => 'Added to cart.',
                'cart_count' => $this->cartModel->count((int) $_SESSION['user_id']),
            ]);
        }
        $_SESSION['success'] = 'Added to cart.';
        $redirect = $_POST['redirect'] ?? $this->baseUrl('cart');
        $this->redirect($redirect);
    }

    /** Update quantity */
    public function update(): void
    {
        $this->requireLogin();
        if (!$this->validateCsrf()) {
            $this->redirect($this->baseUrl('cart'));
        }
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 0);
        $this->cartModel->updateQuantity((int) $_SESSION['user_id'], $productId, $quantity);
        $_SESSION['success'] = 'Cart updated.';
        $this->redirect($this->baseUrl('cart'));
    }

    /** Remove item */
    public function remove(): void
    {
        $this->requireLogin();
        if (!$this->validateCsrf()) {
            $this->redirect($this->baseUrl('cart'));
        }
        $productId = (int) ($_POST['product_id'] ?? $_GET['product_id'] ?? 0);
        $this->cartModel->remove((int) $_SESSION['user_id'], $productId);
        $_SESSION['success'] = 'Item removed from cart.';
        $this->redirect($this->baseUrl('cart'));
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
