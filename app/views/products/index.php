<?php require __DIR__ . '/../helpers.php'; ?>
<section class="products-hero">
    <div class="hero-bg-wrapper">
        <div class="hero-gradient"></div>
        <div class="hero-pattern"></div>
        <div class="hero-shine"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <span class="hero-badge">Premium Collection</span>
                <h1 class="hero-title">
                    <span class="hero-title-main">Discover Your Style</span>
                    <span class="hero-title-sub">Premium Decor & Furniture</span>
                </h1>
                <p class="hero-subtitle">Transform your space with our curated collection of beautiful home essentials
                    designed for modern living.</p>
                <div class="hero-actions">
                    <a href="#products-grid" class="btn btn-primary btn-lg">
                        <span>Explore Collection</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 13l5 5 5-5M12 15V3m0 0l-4 4m4-4l4 4" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-icon">📦</div>
                    <div class="stat-info">
                        <span class="stat-number"><?= count($products) ?>+</span>
                        <span class="stat-label">Products</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">🏷️</div>
                    <div class="stat-info">
                        <span class="stat-number"><?= count($categories) ?>+</span>
                        <span class="stat-label">Categories</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">✨</div>
                    <div class="stat-info">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Quality</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="filters-section">
    <div class="container">
        <div class="filters-container">
            <div class="filters-header">
                <div class="filters-title-wrap">
                    <span class="filters-icon">🔍</span>
                    <h2 class="filters-title">Find Your Perfect Piece</h2>
                </div>
                <p class="filters-subtitle">Use filters to discover exactly what you're looking for</p>
            </div>

            <form method="get" action="<?= base_url('products') ?>" class="filters-form" id="filters-form">
                <div class="filters-row" style="flex-wrap: nowrap; gap: 0.75rem;">
                    <div class="filter-group search-group" style="flex: 2 1 280px;">
                        <label class="filter-label">Search</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>
                            <input type="text" name="q" placeholder="Search products..."
                                value="<?= e($filters['search'] ?? '') ?>" class="filter-input">
                        </div>
                    </div>
                    <div class="filter-group category-group">
                        <label class="filter-label">Category</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9,22 9,12 15,12 15,22" />
                            </svg>
                            <select name="category" class="filter-select">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"
                                    <?= (isset($filters['category']) && $filters['category'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= e($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="filter-group" style="flex: 0 0 0px; min-width: 100px;">
                        <select name="min_price" style="padding: 12px 16px;" id="min-price-select"
                            class="filter-select">
                            <option value="">Min</option>
                            <?php $minPriceOptions = [0, 50, 100, 200, 300, 500, 1000, 2000, 5000]; ?>
                            <?php foreach ($minPriceOptions as $opt): ?>
                            <option value="<?= $opt ?>"
                                <?= (isset($filters['min_price']) && $filters['min_price'] !== '' && $filters['min_price'] == $opt) ? 'selected' : '' ?>>
                                $<?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group" style="flex: 0 0 130px; min-width:0px;">
                        <select name="max_price" style="padding: 12px 16px;" id="max-price-select"
                            class="filter-select">
                            <option value="">Max</option>
                            <?php $maxPriceOptions = [100, 200, 300, 500, 1000, 2000, 5000, 10000, 50000]; ?>
                            <?php foreach ($maxPriceOptions as $opt): ?>
                            <option value="<?= $opt ?>"
                                <?= (isset($filters['max_price']) && $filters['max_price'] !== '' && $filters['max_price'] == $opt) ? 'selected' : '' ?>>
                                $<?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-actions" style="flex: 0 0 auto; margin-left: 0;">
                        <?php if (!empty($filters['search']) || !empty($filters['category']) || !empty($filters['min_price']) || !empty($filters['max_price'])): ?>
                        <a href="<?= base_url('products') ?>" style="padding: 12px 16px;" class="btn btn-ghost btn-sm">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18" />
                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                            </svg>
                            Clear
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="products-section" id="products-grid">
    <div class="container">
        <div class="products-header">
            <div class="section-info">
                <span class="section-badge">Handpicked Items</span>
                <h2 class="section-title">
                    <span class="title-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9,22 9,12 15,12 15,22" />
                        </svg>
                    </span>
                    Our Collection
                </h2>
                <p class="section-subtitle">Discover handpicked items for your home</p>
            </div>
            <div class="products-count">
                <span class="count-number"><?= count($products) ?></span>
                <span class="count-label"><?= count($products) === 1 ? 'product' : 'products' ?> found</span>
            </div>
        </div>

        <div class="products-grid">
            <?php foreach ($products as $p): ?>
            <article class="product-card" data-product-id="<?= $p['id'] ?>">
                <div class="product-image-wrapper">
                    <a href="<?= base_url('product/' . $p['slug']) ?>" class="product-image-link">
                        <img src="<?= $p['image'] ? asset('images/products/' . $p['image']) : placeholder_image() ?>"
                            alt="<?= e($p['name']) ?>" class="product-image"
                            onerror="this.src='<?= placeholder_image() ?>'">
                        <?php if ($p['stock'] < 1): ?>
                        <div class="stock-badge out-of-stock">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="15" x2="9" y1="9" y2="15" />
                                <line x1="9" x2="15" y1="9" y2="15" />
                            </svg>
                            <span>Out of Stock</span>
                        </div>
                        <?php elseif ($p['stock'] <= 5): ?>
                        <div class="stock-badge low-stock">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                <line x1="12" x2="12" y1="9" y2="13" />
                                <line x1="12" x2="12.01" y1="17" y2="17" />
                            </svg>
                            <span>Only <?= $p['stock'] ?> left</span>
                        </div>
                        <?php endif; ?>
                        <div class="product-overlay">
                            <button class="btn btn-primary btn-quick-view"
                                onclick="window.location.href='<?= base_url('product/' . $p['slug']) ?>'">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <span>Quick View</span>
                            </button>
                        </div>
                    </a>
                </div>

                <div class="product-info">
                    <div class="product-meta">
                        <span class="product-category">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                            </svg>
                            <?= e($p['category_name'] ?? '') ?>
                        </span>
                    </div>

                    <h3 class="product-name">
                        <a href="<?= base_url('product/' . $p['slug']) ?>"><?= e($p['name']) ?></a>
                    </h3>

                    <div class="product-price-section">
                        <span class="product-price">$<?= number_format($p['price'], 2) ?></span>
                    </div>

                    <div class="product-actions">
                        <?php if (!empty($_SESSION['user_id'])): ?>
                        <form action="<?= base_url('cart/add') ?>" method="post" class="add-to-cart-form">
                            <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-cart add-to-cart-btn"
                                <?= $p['stock'] < 1 ? 'disabled' : '' ?>>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1" />
                                    <circle cx="20" cy="21" r="1" />
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                                </svg>
                                <span><?= $p['stock'] < 1 ? 'Out of Stock' : 'Add to Cart' ?></span>
                            </button>
                        </form>
                        <?php else: ?>
                        <a href="<?= base_url('auth/login') ?>" class="btn btn-cart">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                <polyline points="10,17 15,12 10,7" />
                                <line x1="15" x2="3" y1="12" y2="12" />
                            </svg>
                            <span>Login to Add</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($products)): ?>
        <div class="no-results">
            <div class="no-results-content">
                <div class="no-results-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                        <path d="M8 8l6 6" />
                        <path d="M14 8l-6 6" />
                    </svg>
                </div>
                <h3 class="no-results-title">No products found</h3>
                <p class="no-results-text">Try adjusting your filters or search terms to find what you're looking for.
                </p>
                <div class="no-results-actions">
                    <a href="<?= base_url('products') ?>" class="btn nullItems btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <span>View All Products</span>
                    </a>
                    <a href="<?= base_url('contact') ?>" class="btn btn-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg>
                        <span>Contact Us</span>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<style>
.filters-section {
    padding: 0 0 2rem;
    margin-top: -2rem;
    position: relative;
    z-index: 2;
}

.nullItems {
    color: white !important;
}
</style>