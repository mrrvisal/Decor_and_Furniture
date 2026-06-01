<?php
/**
 * Admin controller - Dashboard, Products, Orders, Categories
 */
namespace App\Controllers;

use Core\Controller;
use Core\StockAlert;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Admin;
use App\Models\AuditLog;

class AdminController extends Controller
{
    private Product $productModel;
    private Category $categoryModel;
    private Order $orderModel;
    private User $userModel;
    private Admin $adminModel;
    private AuditLog $auditLog;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->orderModel = new Order();
        $this->userModel = new User();
        $this->adminModel = new Admin();
        $this->auditLog = new AuditLog();
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

    private function logAction(string $action, string $targetType, int $targetId, ?array $oldData = null, ?array $newData = null): bool
    {
        $adminId = (int) ($_SESSION['admin_id'] ?? 0);
        $adminName = $_SESSION['admin_name'] ?? 'Admin';
        return $this->auditLog->log($adminId, $adminName, $action, $targetType, $targetId, $oldData, $newData);
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
        $this->view('admin.profile', ['admin' => $admin, 'csrf_token' => $this->csrfToken()]);
    }

    /** Update current admin profile */
    public function profileUpdate(): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/profile'));
        }

        $adminId = (int) ($_SESSION['admin_id'] ?? 0);
        $admin = $this->adminModel->find($adminId);
        if (!$admin) {
            $_SESSION['error'] = 'Admin account not found.';
            $this->redirect($this->baseUrl('admin/profile'));
        }

        $username = trim($_POST['username'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($username === '' || strlen($username) < 3) {
            $_SESSION['error'] = 'Username must be at least 3 characters.';
            $this->redirect($this->baseUrl('admin/profile'));
        }
        if ($name === '' || strlen($name) < 2) {
            $_SESSION['error'] = 'Full name must be at least 2 characters.';
            $this->redirect($this->baseUrl('admin/profile'));
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Please enter a valid email address.';
            $this->redirect($this->baseUrl('admin/profile'));
        }

        $existingUsername = $this->adminModel->findByUsername($username);
        if ($existingUsername && (int) $existingUsername['id'] !== $adminId) {
            $_SESSION['error'] = 'Username is already in use.';
            $this->redirect($this->baseUrl('admin/profile'));
        }

        $existingEmail = $this->adminModel->findByEmail($email);
        if ($existingEmail && (int) $existingEmail['id'] !== $adminId) {
            $_SESSION['error'] = 'Email is already in use.';
            $this->redirect($this->baseUrl('admin/profile'));
        }

        $data = [
            'username' => $username,
            'name' => $name,
            'email' => $email,
        ];

        if ($currentPassword !== '' || $newPassword !== '' || $confirmPassword !== '') {
            if (!password_verify($currentPassword, $admin['password'])) {
                $_SESSION['error'] = 'Current password is incorrect.';
                $this->redirect($this->baseUrl('admin/profile'));
            }
            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = 'New password must be at least 6 characters.';
                $this->redirect($this->baseUrl('admin/profile'));
            }
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'New password confirmation does not match.';
                $this->redirect($this->baseUrl('admin/profile'));
            }
            $data['password'] = $newPassword;
        }

        if ($this->adminModel->update($adminId, $data)) {
            $_SESSION['admin_name'] = $name;
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_email'] = $email;
            $_SESSION['success'] = 'Admin profile updated successfully.';
        } else {
            $_SESSION['error'] = 'Could not update admin profile.';
        }
        $this->redirect($this->baseUrl('admin/profile'));
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
        $sql = "SELECT p.*, c.name AS category_name, s.name AS supplier_name FROM products p LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN suppliers s ON p.supplier_id = s.id WHERE 1=1";
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
        $db = \Core\Database::getInstance();
        $suppliers = $db->query("SELECT id, name, email FROM suppliers WHERE is_active = 1 ORDER BY name ASC")->fetchAll();

        $this->view('admin.products.form', [
            'product' => null,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'csrf_token' => $this->csrfToken(),
        ]);
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
            'supplier_id' => !empty($_POST['supplier_id']) ? (int) $_POST['supplier_id'] : null,
            'image' => $image,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
        if (!$data['name'] || !$data['category_id']) {
            $_SESSION['error'] = 'Name and category required.';
            $this->redirect($this->baseUrl('admin/product/add'));
        }
        $productId = $this->productModel->create($data);
        $this->logAction('create', 'product', $productId, null, $data);
        $createdProduct = $this->productModel->find($productId);
        if ($createdProduct) {
            $this->createStockAlertIfNeeded($productId, (int) $createdProduct['stock'], (int) $createdProduct['reorder_level']);
        }
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
        $db = \Core\Database::getInstance();
        $suppliers = $db->query("SELECT id, name, email FROM suppliers WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
        $this->view('admin.products.form', [
            'product' => $product,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'csrf_token' => $this->csrfToken(),
        ]);
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
            'supplier_id' => isset($_POST['supplier_id']) && $_POST['supplier_id'] !== '' ? (int) $_POST['supplier_id'] : null,
            'image' => $image ?: $product['image'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
        $this->productModel->update((int) $id, $data);
        $this->logAction('update', 'product', (int) $id, $product, $data);
        $updatedProduct = $this->productModel->find((int) $id);
        if ($updatedProduct) {
            $this->createStockAlertIfNeeded((int) $id, (int) $updatedProduct['stock'], (int) $updatedProduct['reorder_level']);
        }
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
                $this->logAction('delete', 'product', (int) $id, $product, null);
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
            $oldData = ['is_active' => $product['is_active']];
            $newData = ['is_active' => $product['is_active'] ? 0 : 1];
            $this->productModel->toggleStatus((int) $id);
            $this->logAction('toggle_status', 'product', (int) $id, $oldData, $newData);
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

    private function createStockAlertIfNeeded(int $productId, int $currentStock, int $reorderLevel): void
    {
        StockAlert::createIfNeeded($productId);
    }

    /** Orders list */
    public function orders(): void
    {
        $this->guard();
        $filters = [
            'status' => trim($_GET['status'] ?? ''),
            'delivery_status' => trim($_GET['delivery_status'] ?? ''),
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
            $this->logAction('update_status', 'order', (int) $id, ['status' => $order['status']], ['status' => $status]);
            $_SESSION['success'] = 'Order status updated.';
        } else {
            $_SESSION['error'] = 'Failed to update status.';
        }
        $this->redirect($this->baseUrl('admin/order/' . $id));
    }

    public function orderUpdateDelivery(string $id): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/orders'));
        }

        $order = $this->orderModel->find((int) $id);
        if (!$order) {
            $_SESSION['error'] = 'Order not found.';
            $this->redirect($this->baseUrl('admin/orders'));
        }

        $data = [
            'delivery_status' => trim($_POST['delivery_status'] ?? 'not_ready'),
            'courier_name' => trim($_POST['courier_name'] ?? ''),
            'tracking_number' => trim($_POST['tracking_number'] ?? ''),
            'estimated_delivery_date' => trim($_POST['estimated_delivery_date'] ?? ''),
        ];

        if ($this->orderModel->updateDelivery((int) $id, $data)) {
            if ($data['delivery_status'] === 'delivered' && $order['status'] !== 'delivered') {
                $this->orderModel->updateStatus((int) $id, 'delivered');
            } elseif ($data['delivery_status'] === 'out_for_delivery' && in_array($order['status'], ['paid', 'processing'], true)) {
                $this->orderModel->updateStatus((int) $id, 'shipped');
            }
            $this->logAction('update_status', 'order', (int) $id, [
                'delivery_status' => $order['delivery_status'] ?? 'not_ready',
                'courier_name' => $order['courier_name'] ?? null,
                'tracking_number' => $order['tracking_number'] ?? null,
                'estimated_delivery_date' => $order['estimated_delivery_date'] ?? null,
            ], $data);
            $_SESSION['success'] = 'Delivery details updated.';
        } else {
            $_SESSION['error'] = 'Could not update delivery details.';
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
        if ($userId) {
            $this->logAction('create', 'user', $userId, null, [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'is_active' => $isActive,
                'email_verified_at' => isset($_POST['email_verified']) ? date('Y-m-d H:i:s') : null,
            ]);
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
        $this->logAction('update', 'user', (int) $id, $user, $data);
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
            $oldData = ['is_active' => $user['is_active']];
            $newData = ['is_active' => $user['is_active'] ? 0 : 1];
            $this->userModel->toggleStatus((int) $id);
            $this->logAction('toggle_status', 'user', (int) $id, $oldData, $newData);
            $newStatus = $user['is_active'] ? 'deactivated' : 'activated';
            $_SESSION['success'] = 'User ' . $newStatus . '.';
        }
        $this->redirect($this->baseUrl('admin/users'));
    }

    public function analytics(): void
    {
        $this->guard();
        $db = \Core\Database::getInstance();

        $allowedStatuses = ['pending', 'waiting_payment', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'];
        $allowedPayments = ['qr_code', 'pay_later'];
        $allowedPeriods = ['all', 'last_7', 'last_30', 'last_90', 'last_6_months', 'this_year', 'custom'];
        $requestedPeriod = $_GET['period'] ?? 'all';

        $filters = [
            'period' => in_array($requestedPeriod, $allowedPeriods, true) ? $requestedPeriod : 'all',
            'date_from' => preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date_from'] ?? '') ? $_GET['date_from'] : '',
            'date_to' => preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date_to'] ?? '') ? $_GET['date_to'] : '',
            'status' => in_array(($_GET['status'] ?? ''), $allowedStatuses, true) ? $_GET['status'] : '',
            'payment_method' => in_array(($_GET['payment_method'] ?? ''), $allowedPayments, true) ? $_GET['payment_method'] : '',
            'category_id' => isset($_GET['category']) && $_GET['category'] !== '' ? max(0, (int) $_GET['category']) : null,
        ];

        if ($filters['period'] !== 'custom') {
            $filters['date_from'] = '';
            $filters['date_to'] = '';
        }

        $orderWhere = [];
        $orderParams = [];
        if ($filters['period'] === 'last_7') {
            $orderWhere[] = 'o.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)';
        } elseif ($filters['period'] === 'last_30') {
            $orderWhere[] = 'o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
        } elseif ($filters['period'] === 'last_90') {
            $orderWhere[] = 'o.created_at >= DATE_SUB(NOW(), INTERVAL 90 DAY)';
        } elseif ($filters['period'] === 'last_6_months') {
            $orderWhere[] = 'o.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)';
        } elseif ($filters['period'] === 'this_year') {
            $orderWhere[] = 'YEAR(o.created_at) = YEAR(CURDATE())';
        } elseif ($filters['period'] === 'custom') {
            if ($filters['date_from'] !== '') {
                $orderWhere[] = 'DATE(o.created_at) >= ?';
                $orderParams[] = $filters['date_from'];
            }
            if ($filters['date_to'] !== '') {
                $orderWhere[] = 'DATE(o.created_at) <= ?';
                $orderParams[] = $filters['date_to'];
            }
        }
        if ($filters['status'] !== '') {
            $orderWhere[] = 'o.status = ?';
            $orderParams[] = $filters['status'];
        }
        if ($filters['payment_method'] !== '') {
            $orderWhere[] = 'o.payment_method = ?';
            $orderParams[] = $filters['payment_method'];
        }

        $orderWhereSql = $orderWhere ? ' AND ' . implode(' AND ', $orderWhere) : '';
        $revenueWhereSql = $orderWhereSql;
        if ($filters['status'] === '') {
            $revenueWhereSql .= " AND o.status IN ('paid','processing','shipped','delivered')";
        }
        $categoryWhereSql = $filters['category_id'] ? ' AND p.category_id = ?' : '';
        $categoryParam = $filters['category_id'] ? [$filters['category_id']] : [];

        // Monthly revenue
        $stmt = $db->prepare("
            SELECT DATE_FORMAT(o.created_at, '%b %y') AS month,
                   YEAR(o.created_at) AS year_num,
                   MONTH(o.created_at) AS month_num,
                   COALESCE(SUM(" . ($filters['category_id'] ? 'oi.subtotal' : 'o.total_amount') . "), 0) AS revenue
            FROM orders o
            " . ($filters['category_id'] ? 'JOIN order_items oi ON oi.order_id = o.id JOIN products p ON oi.product_id = p.id' : '') . "
            WHERE 1=1 {$revenueWhereSql} {$categoryWhereSql}
            GROUP BY year_num, month_num, month
            ORDER BY year_num ASC, month_num ASC
        ");
        $stmt->execute(array_merge($orderParams, $categoryParam));
        $monthly = $stmt->fetchAll();

        // Order status breakdown
        $stmt = $db->prepare("
            SELECT o.status AS status, COUNT(" . ($filters['category_id'] ? 'DISTINCT o.id' : '*') . ") AS cnt
            FROM orders o
            " . ($filters['category_id'] ? 'JOIN order_items oi ON oi.order_id = o.id JOIN products p ON oi.product_id = p.id' : '') . "
            WHERE 1=1 {$orderWhereSql} {$categoryWhereSql}
            GROUP BY o.status
        ");
        $stmt->execute(array_merge($orderParams, $categoryParam));
        $statusBreakdown = $stmt->fetchAll();

        // Revenue by category
        $stmt = $db->prepare("
            SELECT c.name, SUM(oi.subtotal) AS revenue
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN categories c ON p.category_id = c.id
            JOIN orders o ON oi.order_id = o.id
            WHERE 1=1 {$revenueWhereSql} {$categoryWhereSql}
            GROUP BY c.id, c.name
            ORDER BY revenue DESC
        ");
        $stmt->execute(array_merge($orderParams, $categoryParam));
        $categoryRevenue = $stmt->fetchAll();

        // Payment method split
        $stmt = $db->prepare("
            SELECT o.payment_method, COUNT(" . ($filters['category_id'] ? 'DISTINCT o.id' : '*') . ") AS cnt
            FROM orders o
            " . ($filters['category_id'] ? 'JOIN order_items oi ON oi.order_id = o.id JOIN products p ON oi.product_id = p.id' : '') . "
            WHERE 1=1 {$orderWhereSql} {$categoryWhereSql}
            GROUP BY o.payment_method
        ");
        $stmt->execute(array_merge($orderParams, $categoryParam));
        $paymentSplit = $stmt->fetchAll();

        // Low stock products (stock < 10)
        $lowStock = $db->query("
            SELECT name, stock FROM products
            WHERE stock < 10 AND is_active = 1
            ORDER BY stock ASC LIMIT 10
        ")->fetchAll();

        // Top products by revenue
        $stmt = $db->prepare("
            SELECT p.name, SUM(oi.quantity) AS units, SUM(oi.subtotal) AS revenue
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN orders o ON oi.order_id = o.id
            WHERE 1=1 {$revenueWhereSql} {$categoryWhereSql}
            GROUP BY p.id, p.name
            ORDER BY revenue DESC LIMIT 5
        ");
        $stmt->execute(array_merge($orderParams, $categoryParam));
        $topProducts = $stmt->fetchAll();

        $statsSql = "
            SELECT
                COUNT(" . ($filters['category_id'] ? 'DISTINCT o.id' : '*') . ") AS total_orders,
                COALESCE(COUNT(" . ($filters['category_id'] ? 'DISTINCT' : '') . " CASE WHEN o.status = 'pending' OR o.status = 'waiting_payment' THEN o.id ELSE NULL END), 0) AS pending_orders,
                COALESCE(COUNT(" . ($filters['category_id'] ? 'DISTINCT' : '') . " CASE WHEN o.status = 'delivered' THEN o.id ELSE NULL END), 0) AS delivered_orders,
                COALESCE(SUM(" . ($filters['category_id'] ? 'oi.subtotal' : 'o.total_amount') . "), 0) AS total_revenue
            FROM orders o
            " . ($filters['category_id'] ? 'JOIN order_items oi ON oi.order_id = o.id JOIN products p ON oi.product_id = p.id' : '') . "
            WHERE 1=1 {$orderWhereSql} {$categoryWhereSql}
        ";
        $stmt = $db->prepare($statsSql);
        $stmt->execute(array_merge($orderParams, $categoryParam));
        $stats = $stmt->fetch();
        $userStats = $this->userModel->getStats();
        $productTotal = (int) $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $categories = $this->categoryModel->all();

        // Customer retention metrics
        $retention = $db->query("            SELECT 
                COUNT(DISTINCT CASE WHEN first_order >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN user_id END) AS new_customers,
                COUNT(DISTINCT CASE WHEN first_order >= DATE_SUB(NOW(), INTERVAL 90 DAY) 
                    AND first_order < DATE_SUB(NOW(), INTERVAL 30 DAY) THEN user_id END) AS returning_customers,
                COUNT(DISTINCT CASE WHEN order_count >= 2 THEN user_id END) AS repeat_customers
            FROM (
                SELECT user_id, MIN(created_at) AS first_order, COUNT(*) AS order_count
                FROM orders
                GROUP BY user_id
            ) AS customer_stats
        ")->fetch();

        // Abandoned carts
        $abandonedCarts = $db->query("            SELECT COUNT(DISTINCT user_id) AS abandoned_carts
            FROM carts
            WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)
            AND user_id NOT IN (SELECT user_id FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY))
        ")->fetch();

        // Sales by hour of day
        $salesByHour = $db->query("            SELECT HOUR(created_at) AS hour, COUNT(*) AS order_count, SUM(total_amount) AS revenue
            FROM orders
            WHERE status IN ('paid','processing','shipped','delivered')
            GROUP BY HOUR(created_at)
            ORDER BY hour ASC
        ")->fetchAll();

        $this->view('admin.analytics', [
            'filters'         => $filters,
            'categories'      => $categories,
            'monthly'         => $monthly,
            'statusBreakdown' => $statusBreakdown,
            'categoryRevenue' => $categoryRevenue,
            'paymentSplit'    => $paymentSplit,
            'lowStock'        => $lowStock,
            'topProducts'     => $topProducts,
            'stats'           => $stats,
            'userStats'       => $userStats,
            'productTotal'    => $productTotal,
            'retention'       => $retention,
            'abandonedCarts'  => $abandonedCarts,
            'salesByHour'     => $salesByHour,
        ]);
    }

    /** Audit logs list */
    public function auditLogs(): void
    {
        $this->guard();

        $logs = $this->auditLog->getRecent(100, 0);
        $summary = $this->auditLog->getSummary();

        $this->view('admin.audit-logs', [
            'logs' => $logs,
            'summary' => $summary,
        ]);
    }
    
    /** Suppliers list */
    public function suppliers(): void
    {
        $this->guard();
        $supplierModel = new \App\Models\Supplier();
        $suppliers = $supplierModel->all();
        $this->view('admin.suppliers.index', ['suppliers' => $suppliers, 'csrf_token' => $this->csrfToken()]);
    }

    public function supplierAdd(): void
    {
        $this->guard();
        $this->view('admin.suppliers.form', ['supplier' => null, 'csrf_token' => $this->csrfToken()]);
    }

    public function supplierStore(): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/suppliers'));
        }
        $supplierModel = new \App\Models\Supplier();
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'contact_name' => trim($_POST['contact_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
        if (!$data['name'] || !$data['email']) {
            $_SESSION['error'] = 'Name and email are required.';
            $this->redirect($this->baseUrl('admin/supplier/add'));
        }
        $id = $supplierModel->create($data);
        $this->logAction('create', 'supplier', $id, null, $data);
        $_SESSION['success'] = 'Supplier created.';
        $this->redirect($this->baseUrl('admin/suppliers'));
    }

    public function supplierEdit(string $id): void
    {
        $this->guard();
        $supplierModel = new \App\Models\Supplier();
        $supplier = $supplierModel->find((int) $id);
        if (!$supplier) {
            $_SESSION['error'] = 'Supplier not found.';
            $this->redirect($this->baseUrl('admin/suppliers'));
        }
        $this->view('admin.suppliers.form', ['supplier' => $supplier, 'csrf_token' => $this->csrfToken()]);
    }

    public function supplierUpdate(string $id): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/suppliers'));
        }
        $supplierModel = new \App\Models\Supplier();
        $supplier = $supplierModel->find((int) $id);
        if (!$supplier) {
            $_SESSION['error'] = 'Supplier not found.';
            $this->redirect($this->baseUrl('admin/suppliers'));
        }
        $data = [
            'name' => trim($_POST['name'] ?? $supplier['name']),
            'contact_name' => trim($_POST['contact_name'] ?? $supplier['contact_name']),
            'email' => trim($_POST['email'] ?? $supplier['email']),
            'phone' => trim($_POST['phone'] ?? $supplier['phone']),
            'address' => trim($_POST['address'] ?? $supplier['address']),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
        $supplierModel->update((int) $id, $data);
        $this->logAction('update', 'supplier', (int) $id, $supplier, $data);
        $_SESSION['success'] = 'Supplier updated.';
        $this->redirect($this->baseUrl('admin/suppliers'));
    }

    public function supplierDelete(string $id): void
    {
        $this->guard();
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/suppliers'));
        }
        $supplierModel = new \App\Models\Supplier();
        $supplier = $supplierModel->find((int) $id);
        if ($supplier) {
            $supplierModel->delete((int) $id);
            $this->logAction('delete', 'supplier', (int) $id, $supplier, null);
            $_SESSION['success'] = 'Supplier deleted.';
        }
        $this->redirect($this->baseUrl('admin/suppliers'));
    }
    public function stockAlerts(): void
{
    $this->guard();
    $db = \Core\Database::getInstance();

    $pendingAlerts = $db->query("
        SELECT product_id
        FROM stock_alerts
        WHERE status = 'pending'
    ")->fetchAll();

    foreach ($pendingAlerts as $pendingAlert) {
        StockAlert::createIfNeeded((int) $pendingAlert['product_id']);
    }
    
    $alerts = $db->query("
        SELECT a.*, p.name as product_name, p.image, 
               s.name as supplier_name, s.email as supplier_email,
               p.reorder_quantity
        FROM stock_alerts a
        JOIN products p ON a.product_id = p.id
        LEFT JOIN suppliers s ON p.supplier_id = s.id
        WHERE a.status = 'pending' OR a.status = 'sent'
        ORDER BY a.created_at DESC
    ")->fetchAll();
    
    $this->view('admin.stock-alerts', [
        'alerts' => $alerts,
        'csrf_token' => $this->csrfToken()
    ]);
}

public function resolveStockAlert(string $id): void
{
    $this->guard();
    $db = \Core\Database::getInstance();
    $stmt = $db->prepare("SELECT a.id, a.status, a.product_id, p.stock AS current_stock, p.reorder_level AS current_reorder_level
        FROM stock_alerts a
        JOIN products p ON a.product_id = p.id
        WHERE a.id = ?");
    $stmt->execute([(int) $id]);
    $alert = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($alert) {
        $oldData = ['status' => $alert['status']];
        $newData = ['status' => 'resolved'];

        $update = $db->prepare("UPDATE stock_alerts SET status = 'resolved', resolved_at = NOW() WHERE id = ?");
        if ($update->execute([(int) $id])) {
            // Add 100 units to existing stock and set reorder level to 100.
            $newStock = (int) $alert['current_stock'] + 100;
            $newReorderLevel = 100;
            $productUpdate = $db->prepare("UPDATE products SET stock = ?, reorder_level = ? WHERE id = ?");
            $productUpdate->execute([$newStock, $newReorderLevel, (int) $alert['product_id']]);
            $oldData['product_stock'] = $alert['current_stock'];
            $newData['product_stock'] = $newStock;
            $oldData['reorder_level'] = $alert['current_reorder_level'];
            $newData['reorder_level'] = $newReorderLevel;

            $this->logAction('resolve_stock_alert', 'stock_alert', (int) $id, $oldData, $newData);
            $_SESSION['success'] = 'Stock alert resolved and product stock updated.';
            $this->redirect($this->baseUrl('admin/stock-alerts'));
            return;
        }
    }

    $_SESSION['error'] = 'Stock alert not found or could not be resolved.';
    $this->redirect($this->baseUrl('admin/stock-alerts'));
}
}
