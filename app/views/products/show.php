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