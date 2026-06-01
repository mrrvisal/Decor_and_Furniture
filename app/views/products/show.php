<?php require __DIR__ . '/../helpers.php'; ?>
<section class="product-detail container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb-nav">
        <a href="<?= base_url() ?>">Home</a>
        <span class="breadcrumb-sep">/</span>
        <a href="<?= base_url('products') ?>">Products</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current"><?= e($product['name']) ?></span>
    </nav>

    <div class="product-detail-wrapper">
        <div class="product-gallery">
            <div class="product-main-image">
                <img src="<?= $product['image'] ? asset('images/products/' . $product['image']) : placeholder_image() ?>"
                    alt="<?= e($product['name']) ?>" onerror="this.src='<?= placeholder_image() ?>'">
            </div>
        </div>

        <div class="product-info-panel">
            <div class="product-category-badge"><?= e($product['category_name'] ?? 'Uncategorized') ?></div>
            <h1 class="product-title"><?= e($product['name']) ?></h1>

            <div class="product-price-row">
                <span class="product-price">$<?= number_format($product['price'], 2) ?></span>
                <?php if ($product['stock'] > 0): ?>
                <span class="stock-badge in-stock">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    In Stock (<?= (int)$product['stock'] ?>)
                </span>
                <?php else: ?>
                <span class="stock-badge out-of-stock">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Out of Stock
                </span>
                <?php endif; ?>
            </div>

            <div class="product-description">
                <h3>Description</h3>
                <p><?= nl2br(e($product['description'] ?? 'No description available for this product.')) ?></p>
            </div>

            <div class="product-actions">
                <?php if (!empty($_SESSION['user_id'])): ?>
                <form action="<?= base_url('cart/add') ?>" method="post" class="add-to-cart-form">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <div class="qty-controls">
                            <button type="button" class="qty-btn qty-minus" aria-label="Decrease quantity">−</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1"
                                max="<?= max(1, $product['stock']) ?>" class="qty-input" readonly>
                            <button type="button" class="qty-btn qty-plus" aria-label="Increase quantity">+</button>
                        </div>
                    </div>
                    <input type="hidden" name="redirect" value="<?= base_url('cart') ?>">
                    <button type="submit"
                        class="btn btn-primary btn-add-cart <?= $product['stock'] < 1 ? 'disabled' : '' ?>"
                        <?= $product['stock'] < 1 ? 'disabled' : '' ?>>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <?= $product['stock'] < 1 ? 'Out of Stock' : 'Add to Cart' ?>
                    </button>
                </form>
                <?php else: ?>
                <div class="login-prompt">
                    <p>Sign in to add this item to your cart</p>
                    <a href="<?= base_url('auth/login') ?>" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                        Login to Add to Cart
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <div class="back-link">
                <a href="<?= base_url('products') ?>" class="btn btn-ghost">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Back to Products
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
<?php
$reviewModel = new \App\Models\Review();
$ratingSummary = $reviewModel->getRatingSummary($product['id']);
$reviews = $reviewModel->getForProduct($product['id'], 5, 0);
$canReview = !empty($_SESSION['user_id']);
?>

<section class="reviews-section">
    <div class="reviews-header">
        <h2>Customer Reviews</h2>
        <?php if ($canReview): ?>
        <a href="<?= base_url('product/' . $product['slug'] . '/review') ?>" class="btn btn-primary btn-sm">
            Write a Review
        </a>
        <?php endif; ?>
    </div>

    <!-- Rating Summary -->
    <div class="rating-summary">
        <div class="rating-average">
            <div class="average-score"><?= number_format($ratingSummary['average_rating'], 1) ?></div>
            <div class="stars-display">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="star <?= $i <= round($ratingSummary['average_rating']) ? 'filled' : '' ?>">★</span>
                <?php endfor; ?>
            </div>
            <div class="total-reviews">Based on <?= $ratingSummary['total_reviews'] ?> reviews</div>
        </div>
        
        <div class="rating-bars">
            <?php 
            $levels = [
                'five' => '5 star',
                'four' => '4 star', 
                'three' => '3 star',
                'two' => '2 star',
                'one' => '1 star'
            ];
            foreach ($levels as $key => $label):
                $percent = $ratingSummary[$key . '_percent'] ?? 0;
            ?>
            <div class="rating-bar-item">
                <span class="rating-label"><?= $label ?></span>
                <div class="bar-container">
                    <div class="bar-fill" style="width: <?= $percent ?>%"></div>
                </div>
                <span class="rating-percent"><?= $percent ?>%</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Reviews List -->
    <?php if (empty($reviews)): ?>
    <div class="no-reviews">
        <div class="no-reviews-icon">📝</div>
        <p>No reviews yet. Be the first to review this product!</p>
        <?php if ($canReview): ?>
        <a href="<?= base_url('product/' . $product['slug'] . '/review') ?>" class="btn btn-primary">
            Write a Review
        </a>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="reviews-list">
        <?php foreach ($reviews as $review): ?>
        <div class="review-card" data-review-id="<?= $review['id'] ?>">
            <div class="review-header-card">
                <div class="reviewer-info">
                    <span class="reviewer-initials">
                        <?= strtoupper(substr($review['user_name'], 0, 2)) ?>
                    </span>
                    <div>
                        <div class="reviewer-name"><?= e($review['user_name']) ?></div>
                        <div class="review-date"><?= date('M j, Y', strtotime($review['created_at'])) ?></div>
                    </div>
                </div>
                <div class="review-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star <?= $i <= $review['rating'] ? 'filled' : '' ?>">★</span>
                    <?php endfor; ?>
                </div>
            </div>
            <?php if (!empty($review['title'])): ?>
            <h4 class="review-title"><?= e($review['title']) ?></h4>
            <?php endif; ?>
            <p class="review-comment"><?= nl2br(e($review['comment'])) ?></p>
            <?php if ($review['is_verified_purchase']): ?>
            <div class="verified-badge">✓ Verified Purchase</div>
            <?php endif; ?>
            <div class="review-footer">
                <button class="btn-helpful" data-review-id="<?= $review['id'] ?>">
                    👍 Helpful (<span class="helpful-count"><?= $review['helpful_count'] ?></span>)
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<style>
.reviews-section {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border);
}
.reviews-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.reviews-header h2 {
    margin: 0;
    font-size: 1.35rem;
    font-weight: 600;
}
.rating-summary {
    display: flex;
    gap: 2rem;
    padding: 1.5rem;
    background: var(--bg);
    border-radius: var(--radius);
    margin-bottom: 2rem;
    flex-wrap: wrap;
}
.rating-average {
    text-align: center;
    min-width: 150px;
}
.average-score {
    font-size: 3rem;
    font-weight: 700;
    color: var(--text);
}
.stars-display .star {
    font-size: 1.25rem;
    color: #ddd;
}
.stars-display .star.filled {
    color: #ffc107;
}
.total-reviews {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}
.rating-bars {
    flex: 1;
    min-width: 200px;
}
.rating-bar-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}
.rating-label {
    width: 60px;
    font-size: 0.85rem;
    color: var(--text-muted);
}
.bar-container {
    flex: 1;
    height: 8px;
    background: var(--border);
    border-radius: 4px;
    overflow: hidden;
}
.bar-fill {
    height: 100%;
    background: var(--accent);
    border-radius: 4px;
}
.rating-percent {
    width: 45px;
    font-size: 0.85rem;
    color: var(--text-muted);
    text-align: right;
}
.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.review-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    padding: 1.25rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}
.review-header-card {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.reviewer-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.reviewer-initials {
    width: 40px;
    height: 40px;
    background: var(--accent-soft);
    color: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}
.reviewer-name {
    font-weight: 600;
    color: var(--text);
}
.review-date {
    font-size: 0.75rem;
    color: var(--text-muted);
}
.review-rating .star {
    font-size: 1rem;
    color: #ddd;
}
.review-rating .star.filled {
    color: #ffc107;
}
.review-title {
    margin: 0 0 0.5rem;
    font-size: 1rem;
    font-weight: 600;
}
.review-comment {
    color: var(--text);
    line-height: 1.6;
    margin: 0.5rem 0;
}
.verified-badge {
    display: inline-block;
    font-size: 0.75rem;
    color: var(--success);
    background: var(--success-bg);
    padding: 0.2rem 0.6rem;
    border-radius: 999px;
    margin: 0.5rem 0;
}
.review-footer {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border);
}
.btn-helpful {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-size: 0.85rem;
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    transition: all 0.2s;
}
.btn-helpful:hover {
    background: var(--accent-soft);
    color: var(--accent);
}
.no-reviews {
    text-align: center;
    padding: 3rem;
    background: var(--bg);
    border-radius: var(--radius);
}
.no-reviews-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}
.no-reviews p {
    color: var(--text-muted);
    margin-bottom: 1rem;
}
@media (max-width: 768px) {
    .rating-summary {
        flex-direction: column;
        align-items: center;
    }
    .rating-bars {
        width: 100%;
    }
}
</style>

<script>
document.querySelectorAll('.btn-helpful').forEach(btn => {
    btn.addEventListener('click', async function() {
        const reviewId = this.dataset.reviewId;
        const csrfToken = '<?= e($csrf_token ?? '') ?>';
        
        try {
            const response = await fetch('<?= base_url('review/helpful') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `csrf_token=${encodeURIComponent(csrfToken)}&review_id=${reviewId}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                const countSpan = this.querySelector('.helpful-count');
                countSpan.textContent = parseInt(countSpan.textContent) + 1;
                this.disabled = true;
                this.style.opacity = '0.6';
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});
</script>
