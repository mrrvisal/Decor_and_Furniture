<?php
/**
 * Product controller - listing, search, filter, product detail
 */
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;

class ProductController extends Controller
{
    private Product $productModel;
    private Category $categoryModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    private function getCartCount(): int
    {
        if (empty($_SESSION['user_id']))
            return 0;
        return (new Cart())->count((int) $_SESSION['user_id']);
    }

    /** Home / product listing with optional filters */
    public function index(): void
    {
        $filters = [
            'search' => trim($_GET['q'] ?? ''),
            'category' => isset($_GET['category']) && $_GET['category'] !== '' ? (int) $_GET['category'] : null,
            'min_price' => isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float) $_GET['min_price'] : null,
            'max_price' => isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float) $_GET['max_price'] : null,
        ];
        $products = $this->productModel->getList($filters);
        $categories = $this->categoryModel->all();
        $this->view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $filters,
            'csrf_token' => $this->csrfToken(),
            'cartCount' => $this->getCartCount(),
        ]);
    }

    /** Single product detail */
    public function show(string $slug): void
    {
        $product = $this->productModel->findBySlug($slug);
        if (!$product) {
            $_SESSION['error'] = 'Product not found.';
            $this->redirect($this->baseUrl());
        }
        $this->view('products.show', ['product' => $product, 'csrf_token' => $this->csrfToken(), 'cartCount' => $this->getCartCount()]);
    }
}