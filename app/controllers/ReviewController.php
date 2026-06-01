<?php
/**
 * Review Controller - Handle product reviews
 */
namespace App\Controllers;

use Core\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;

class ReviewController extends Controller
{
    private Review $reviewModel;
    private Product $productModel;

    public function __construct()
    {
        $this->reviewModel = new Review();
        $this->productModel = new Product();
    }

   /**
 * Show review form for a product (with order selection if multiple purchases)
 */
public function form(string $productSlug): void
{
    $this->requireLogin();
    
    $product = $this->productModel->findBySlug($productSlug);
    if (!$product) {
        $_SESSION['error'] = 'Product not found.';
        $this->redirect($this->baseUrl());
        return;
    }
    
    $userId = (int) $_SESSION['user_id'];
    
    // Get any eligible orders for this product (optional order association)
    $eligibleOrders = $this->reviewModel->getEligibleOrdersForReview($product['id'], $userId);
    
    // Get existing reviews for display
    $existingReviews = $this->reviewModel->getUserReviewsForProduct($product['id'], $userId);
    
    $this->view('products.review-form', [
        'product' => $product,
        'eligible_orders' => $eligibleOrders,
        'existing_reviews' => $existingReviews,
        'csrf_token' => $this->csrfToken()
    ]);
}

/**
 * Submit a review
 */
public function submit(string $productSlug): void
{
    $this->requireLogin();
    
    if (!$this->validateCsrf()) {
        $_SESSION['error'] = 'Invalid request.';
        $this->redirect($this->baseUrl('product/' . $productSlug));
        return;
    }
    
    $product = $this->productModel->findBySlug($productSlug);
    if (!$product) {
        $_SESSION['error'] = 'Product not found.';
        $this->redirect($this->baseUrl());
        return;
    }
    
    $rating = (int) ($_POST['rating'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
    
    if ($rating < 1 || $rating > 5) {
        $_SESSION['error'] = 'Please select a valid rating.';
        $this->redirect($this->baseUrl('product/' . $productSlug . '/review'));
        return;
    }
    
    if (empty($comment)) {
        $_SESSION['error'] = 'Please write your review.';
        $this->redirect($this->baseUrl('product/' . $productSlug . '/review'));
        return;
    }
    
    $userId = (int) $_SESSION['user_id'];
    
    if ($orderId <= 0) {
        $orderId = $this->reviewModel->getOrderIdForReview($product['id'], $userId);
    }

    if ($orderId <= 0) {
        $_SESSION['error'] = 'You can only submit a review for a delivered order containing this product.';
        $this->redirect($this->baseUrl('product/' . $productSlug . '/review'));
        return;
    }

    // Verify this order belongs to the user and contains this product
    $canReviewOrder = $this->reviewModel->canReview($product['id'], $userId, $orderId);
    if (!$canReviewOrder) {
        $_SESSION['error'] = 'You cannot review this order.';
        $this->redirect($this->baseUrl('product/' . $productSlug));
        return;
    }
    
    $this->reviewModel->create(
        $product['id'],
        $userId,
        $orderId,
        $rating,
        $title,
        $comment
    );

    
    $_SESSION['success'] = 'Thank you for your review! It will be published after admin approval.';
    $this->redirect($this->baseUrl('product/' . $productSlug));
}

    /**
     * Mark review as helpful (AJAX)
     */
    public function helpful(): void
    {
        $this->requireLogin();
        
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.']);
            return;
        }
        
        $reviewId = (int) ($_POST['review_id'] ?? 0);
        $result = $this->reviewModel->markHelpful($reviewId, (int) $_SESSION['user_id']);
        
        $this->json(['success' => $result]);
    }

    /**
     * Admin: Manage reviews
     */
    public function adminIndex(): void
    {
        $this->requireAdmin();
        
        $status = $_GET['status'] ?? 'pending';
        $reviews = $this->reviewModel->getAllReviews(['status' => $status]);
        $pendingCount = count($this->reviewModel->getPendingReviews());
        
        $this->view('admin.reviews.index', [
            'reviews' => $reviews,
            'status' => $status,
            'pendingCount' => $pendingCount,
            'csrf_token' => $this->csrfToken()
        ], 'layouts.admin');
    }

    /**
     * Admin: Approve a review
     */
    public function adminApprove(string $id): void
    {
        $this->requireAdmin();
        
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/reviews'));
            return;
        }
        
        if ($this->reviewModel->approve((int) $id)) {
            $_SESSION['success'] = 'Review approved successfully.';
        } else {
            $_SESSION['error'] = 'Failed to approve review.';
        }
        
        $this->redirect($this->baseUrl('admin/reviews'));
    }

    /**
     * Admin: Delete a review
     */
    public function adminDelete(string $id): void
    {
        $this->requireAdmin();
        
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/reviews'));
            return;
        }
        
        if ($this->reviewModel->delete((int) $id)) {
            $_SESSION['success'] = 'Review deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete review.';
        }
        
        $this->redirect($this->baseUrl('admin/reviews'));
    }
}