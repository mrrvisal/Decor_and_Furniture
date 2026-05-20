<?php
/**
 * Admin controller - Dashboard, Products, Orders, Categories
 */
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Admin;

class AdminController extends Controller
{
    private Product $productModel;
    private Category $categoryModel;
    private Order $orderModel;
    private User $userModel;
    private Admin $adminModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->orderModel = new Order();
        $this->userModel = new User();
        $this->adminModel = new Admin();
    }

    /** Override view to use admin layout by default */
    protected function view(string $view, array $data = [], ?string $layout = 'layouts.admin'): void
    {
        parent::view($view, $data, $layout);
    }

    /** Middleware: require admin */
    private function guard(): void
    {
        $this->requireAdmin();
    }

    /** Dashboard */
    public function index(): void
    {
        $this->guard();
        $stats = $this->orderModel->getStats();
        $recentOrders = $this->orderModel->getAll(['status' => '']);
        $recentOrders = array_slice($recentOrders, 0, 10);
        $productCount = count($this->productModel->getList([]));
        $userStats = $this->userModel->getStats();
        $this->view('admin.dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'productCount' => $productCount,
            'userCount' => $userStats['total'],
            'newUsersCount' => $userStats['new_this_week'],
        ]);
    }

    /** Admin Profile */
    public function profile(): void
    {
        $this->guard();
        $adminId = (int) ($_SESSION['admin_id'] ?? 0);
        $admin = $this->adminModel->find($adminId);
        $this->view('admin.profile', ['admin' => $admin]);
    }

    /** Products list with search/filter */
    public function products(): void
    {
        $this->guard();
        $filters = [
            'search' => trim($_GET['q'] ?? ''),
            'category_id' => isset($_GET['category']) && $_GET['category'] !== '' ? (int) $_GET['category'] : null,
            'min_price' => isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float) $_GET['min_price'] : null,
            'max_price' => isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float) $_GET['max_price'] : null,
            'in_stock' => isset($_GET['in_stock']) ? (bool) $_GET['in_stock'] : null,
        ];
        // Admin sees all products (including inactive) - use raw query for admin
        $products = $this->getAdminProducts($filters);
        $categories = $this->categoryModel->all();
        $this->view('admin.products.index', ['products' => $products, 'categories' => $categories, 'filters' => $filters, 'csrf_token' => $this->csrfToken()]);
    }

    private function getAdminProducts(array $filters): array
    {
        $db = \Core\Database::getInstance();
        $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
        $params = [];
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category_id'];
        }
        if (isset($filters['min_price']) && $filters['min_price'] !== null && $filters['min_price'] !== '') {
            $sql .= " AND p.price >= ?";
            $params[] = $filters['min_price'];
        }
        if (isset($filters['max_price']) && $filters['max_price'] !== null && $filters['max_price'] !== '') {
            $sql .= " AND p.price <= ?";
            $params[] = $filters['max_price'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $term = '%' . $filters['search'] . '%';
            $params[] = $term;
            $params[] = $term;
        }
        if (isset($filters['in_stock']) && $filters['in_stock']) {
            $sql .= " AND p.stock > 0";
        }
        $sql .= " ORDER BY p.created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Add product form */
    public function productAdd(): void
    {
        $this->guard();
        $categories = $this->categoryModel->all();
        $this->view('admin.products.form', ['product' => null, 'categories' => $categories, 'csrf_token' => $this->csrfToken()]);
    }

    /** Add product submit */
    public function productStore(): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/products'));
        }
        $image = $this->handleImageUpload();
        $data = [
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float) ($_POST['price'] ?? 0),
            'stock' => (int) ($_POST['stock'] ?? 0),
            'image' => $image,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
        if (!$data['name'] || !$data['category_id']) {
            $_SESSION['error'] = 'Name and category required.';
            $this->redirect($this->baseUrl('admin/product/add'));
        }
        $this->productModel->create($data);
        $_SESSION['success'] = 'Product added.';
        $this->redirect($this->baseUrl('admin/products'));
    }

    /** Edit product form */
    public function productEdit(string $id): void
    {
        $this->guard();
        $product = $this->productModel->find((int) $id);
        if (!$product) {
            $_SESSION['error'] = 'Product not found.';
            $this->redirect($this->baseUrl('admin/products'));
        }
        $categories = $this->categoryModel->all();
        $this->view('admin.products.form', ['product' => $product, 'categories' => $categories, 'csrf_token' => $this->csrfToken()]);
    }

    /** Update product */
    public function productUpdate(string $id): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/products'));
        }
        $product = $this->productModel->find((int) $id);
        if (!$product) {
            $_SESSION['error'] = 'Product not found.';
            $this->redirect($this->baseUrl('admin/products'));
        }
        $image = $this->handleImageUpload();
        $data = [
            'category_id' => (int) ($_POST['category_id'] ?? $product['category_id']),
            'name' => trim($_POST['name'] ?? $product['name']),
            'description' => trim($_POST['description'] ?? $product['description']),
            'price' => (float) ($_POST['price'] ?? $product['price']),
            'stock' => (int) ($_POST['stock'] ?? $product['stock']),
            'image' => $image ?: $product['image'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
        $this->productModel->update((int) $id, $data);
        $_SESSION['success'] = 'Product updated.';
        $this->redirect($this->baseUrl('admin/products'));
    }

    /** Delete product */
    public function productDelete(string $id): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/products'));
        }
        $product = $this->productModel->find((int) $id);
        if ($product) {
            if ($this->productModel->delete((int) $id)) {
                $_SESSION['success'] = 'Product deleted.';
            } else {
                $_SESSION['error'] = 'Cannot delete product that has been ordered. Deactivate it instead.';
            }
        }
        $this->redirect($this->baseUrl('admin/products'));
    }

    /** Toggle product active status */
    public function productToggleStatus(string $id): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/products'));
        }
        $product = $this->productModel->find((int) $id);
        if ($product) {
            $this->productModel->toggleStatus((int) $id);
            $newStatus = $product['is_active'] ? 'deactivated' : 'activated';
            $_SESSION['success'] = 'Product ' . $newStatus . '.';
        }
        $this->redirect($this->baseUrl('admin/products'));
    }

    private function handleImageUpload(): ?string
    {
        if (empty($_FILES['image']['name']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowed)) {
            $_SESSION['error'] = 'Invalid image format. Allowed: JPEG, PNG, GIF, WebP';
            return null;
        }
        $baseDir = dirname(__DIR__, 2);
        $dir = $baseDir . '/public/assets/images/products';
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                $_SESSION['error'] = 'Failed to create images directory. Check folder permissions.';
                error_log("Failed to create directory: " . $dir);
                return null;
            }
        }
        // Check if directory is writable
        if (!is_writable($dir)) {
            $_SESSION['error'] = 'Images directory is not writable. Set permissions to 755.';
            error_log("Directory not writable: " . $dir);
            return null;
        }
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION)) ?: 'jpg';
        $filename = uniqid('prod_') . '.' . $ext;
        $targetPath = $dir . '/' . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            return $filename;
        }
        $error = $_FILES['image']['error'];
        switch ($error) {
            case UPLOAD_ERR_INI_SIZE:
                $errorMsg = 'File exceeds upload_max_filesize';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $errorMsg = 'File exceeds MAX_FILE_SIZE';
                break;
            case UPLOAD_ERR_PARTIAL:
                $errorMsg = 'File was only partially uploaded';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $errorMsg = 'Missing temporary folder';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $errorMsg = 'Failed to write file to disk';
                break;
            default:
                $errorMsg = 'Unknown upload error: ' . $error;
        }
        $_SESSION['error'] = 'Upload failed: ' . $errorMsg;
        error_log("Upload failed - Error: " . $errorMsg . ", Tmp: " . $_FILES['image']['tmp_name'] . ", Target: " . $targetPath);
        return null;
    }

    /** Orders list */
    public function orders(): void
    {
        $this->guard();
        $filters = [
            'status' => trim($_GET['status'] ?? ''),
            'search' => trim($_GET['q'] ?? ''),
        ];
        $orders = $this->orderModel->getAll($filters);
        $this->view('admin.orders.index', ['orders' => $orders, 'filters' => $filters]);
    }

    /** Order detail + customer */
    public function orderShow(string $id): void
    {
        $this->guard();
        $order = $this->orderModel->find((int) $id);
        if (!$order) {
            $_SESSION['error'] = 'Order not found.';
            $this->redirect($this->baseUrl('admin/orders'));
        }
        $items = $this->orderModel->getItems($order['id']);
        $user = $this->userModel->find($order['user_id']);
        $this->view('admin.orders.show', ['order' => $order, 'items' => $items, 'user' => $user, 'csrf_token' => $this->csrfToken()]);
    }

    /** Update order status */
    public function orderUpdateStatus(string $id): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/orders'));
        }
        $status = trim($_POST['status'] ?? '');
        $order = $this->orderModel->find((int) $id);
        if ($order && $this->orderModel->updateStatus($order['id'], $status)) {
            $_SESSION['success'] = 'Order status updated.';
        } else {
            $_SESSION['error'] = 'Failed to update status.';
        }
        $this->redirect($this->baseUrl('admin/order/' . $id));
    }

    /** Users list with search/filter */
    public function users(): void
    {
        $this->guard();
        $filters = [
            'search' => trim($_GET['q'] ?? ''),
            'status' => isset($_GET['status']) && $_GET['status'] !== '' ? (int) $_GET['status'] : '',
            'date_from' => trim($_GET['date_from'] ?? ''),
            'date_to' => trim($_GET['date_to'] ?? ''),
            'order_by' => $_GET['sort'] ?? 'created_at',
            'order_dir' => $_GET['order'] ?? 'DESC',
            'page' => (int) ($_GET['page'] ?? 1),
            'per_page' => 20,
        ];
        $users = $this->userModel->getAll($filters);
        $totalCount = $this->userModel->getCount($filters);
        $totalPages = ceil($totalCount / $filters['per_page']);
        $this->view('admin.users.index', [
            'users' => $users,
            'filters' => $filters,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'csrf_token' => $this->csrfToken(),
        ]);
    }

    /** User detail + order history */
    public function userShow(string $id): void
    {
        $this->guard();
        $user = $this->userModel->find((int) $id);
        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            $this->redirect($this->baseUrl('admin/users'));
        }
        // Get user's orders
        $userOrders = $this->orderModel->getByUserId((int) $id);
        $this->view('admin.users.show', ['user' => $user, 'orders' => $userOrders, 'csrf_token' => $this->csrfToken()]);
    }

    /** Add user form */
    public function userAdd(): void
    {
        $this->guard();
        $this->view('admin.users.form', ['user' => null, 'csrf_token' => $this->csrfToken()]);
    }

    /** Create user */
    public function userStore(): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/users'));
        }
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        // Validation
        if (!$name || !$email || !$password) {
            $_SESSION['error'] = 'Name, email and password are required.';
            $this->redirect($this->baseUrl('admin/user/add'));
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email format.';
            $this->redirect($this->baseUrl('admin/user/add'));
        }
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters.';
            $this->redirect($this->baseUrl('admin/user/add'));
        }

        // Check if email exists
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = 'Email already registered.';
            $this->redirect($this->baseUrl('admin/user/add'));
        }

        $userId = $this->userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'email_verified_at' => isset($_POST['email_verified']) ? date('Y-m-d H:i:s') : null,
        ]);
        // Update is_active separately since create() doesn't handle it
        if ($userId && !$isActive) {
            $this->userModel->update($userId, ['is_active' => 0]);
        }
        $_SESSION['success'] = 'User created.';
        $this->redirect($this->baseUrl('admin/users'));
    }

    /** Edit user form */
    public function userEdit(string $id): void
    {
        $this->guard();
        $user = $this->userModel->find((int) $id);
        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            $this->redirect($this->baseUrl('admin/users'));
        }
        $this->view('admin.users.form', ['user' => $user, 'csrf_token' => $this->csrfToken()]);
    }

    /** Update user */
    public function userUpdate(string $id): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/users'));
        }
        $user = $this->userModel->find((int) $id);
        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            $this->redirect($this->baseUrl('admin/users'));
        }

        $name = trim($_POST['name'] ?? $user['name']);
        $email = trim($_POST['email'] ?? $user['email']);
        $phone = trim($_POST['phone'] ?? $user['phone']);
        $password = $_POST['password'] ?? '';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $emailVerified = isset($_POST['email_verified']) ? date('Y-m-d H:i:s') : null;

        if (!$name || !$email) {
            $_SESSION['error'] = 'Name and email are required.';
            $this->redirect($this->baseUrl('admin/user/edit/' . $id));
        }

        // Check email uniqueness (skip if same email)
        $existing = $this->userModel->findByEmail($email);
        if ($existing && $existing['id'] != $id) {
            $_SESSION['error'] = 'Email already registered by another user.';
            $this->redirect($this->baseUrl('admin/user/edit/' . $id));
        }

        $data = [
            'name' => $name,
            'phone' => $phone,
            'is_active' => $isActive,
        ];

        // Only update email if it changed (can't change email due to UNIQUE constraint issues)
        if ($email !== $user['email']) {
            // For simplicity, we'll treat email as not changeable in admin
            unset($data['name']);
            $data['name'] = $name;
        }

        if ($emailVerified) {
            $data['email_verified_at'] = $emailVerified;
        }

        if (!empty($password) && strlen($password) >= 6) {
            $data['password'] = $password;
        }

        $this->userModel->update((int) $id, $data);
        $_SESSION['success'] = 'User updated.';
        $this->redirect($this->baseUrl('admin/users'));
    }

    /** Delete user */
    public function userDelete(string $id): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/users'));
        }
        $user = $this->userModel->find((int) $id);
        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            $this->redirect($this->baseUrl('admin/users'));
        }

        // Check if user has orders - if so, prevent deletion due to foreign key constraint
        $userOrders = $this->orderModel->getByUserId((int) $id);
    }
    /** Toggle user active status */
    public function userToggleStatus(string $id): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/users'));
        }
        $user = $this->userModel->find((int) $id);
        if ($user) {
            $this->userModel->toggleStatus((int) $id);
            $newStatus = $user['is_active'] ? 'deactivated' : 'activated';
            $_SESSION['success'] = 'User ' . $newStatus . '.';
        }
        $this->redirect($this->baseUrl('admin/users'));
    }
}