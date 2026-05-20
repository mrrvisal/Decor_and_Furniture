<?php require __DIR__ . '/../helpers.php'; ?>

<!-- Hero Section -->
<section class="home-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Luxery</h1>
            <p class="hero-subtitle">Find your perfect piece for every room</p>
            <a href="<?= base_url('products') ?>" class="btn btn-primary btn-lg">Shop Now</a>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="section categories-section">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
            <a href="<?= base_url('products?category=' . $cat['id']) ?>" class="category-card">
                <div class="category-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-tag"
                        viewBox="0 0 16 16">
                        <path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0" />
                        <path
                            d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1m0 5.586 7 7L13.586 9l-7-7H2z" />
                    </svg>
                </div>
                <h3 class="category-name"><?= e($cat['name']) ?></h3>
                <span class="category-count"><?= e($cat['product_count'] ?? 'View') ?> items</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if (!empty($featuredProducts)): ?>
<section class="section featured-section">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div class="products-grid">
            <?php foreach ($featuredProducts as $p): ?>
            <article class="product-card" data-product-id="<?= $p['id'] ?>">
                <a href="<?= base_url('product/' . $p['slug']) ?>" class="product-image-link">
                    <img src="<?= $p['image'] ? asset('images/products/' . $p['image']) : placeholder_image() ?>"
                        alt="<?= e($p['name']) ?>" class="product-image"
                        onerror="this.src='<?= placeholder_image() ?>'">
                </a>
                <div class="product-info">
                    <span class="product-category"><?= e($p['category_name'] ?? '') ?></span>
                    <h3 class="product-name"><a href="<?= base_url('product/' . $p['slug']) ?>"><?= e($p['name']) ?></a>
                    </h3>
                    <p class="product-price">$<?= number_format($p['price'], 2) ?></p>
                    <?php if (!empty($_SESSION['user_id'])): ?>
                    <form action="<?= base_url('cart/add') ?>" method="post" class="add-to-cart-form">
                        <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">
                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-cart add-to-cart-btn"
                            <?= $p['stock'] < 1 ? 'disabled' : '' ?>>
                            <?= $p['stock'] < 1 ? 'Out of stock' : 'Add to Cart' ?>
                        </button>
                    </form>
                    <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>" class="btn btn-cart">Login to Add to Cart</a>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= base_url('products') ?>" class="btn btn-ghost btn-lg">View All Products →</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Why Choose Us -->
<section class="section features-section">
    <div class="container">
        <h2 class="section-title">Why Choose Decor & Furniture</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="3" width="15" height="13"></rect>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                    </svg>
                </div>
                <h3>Free Delivery</h3>
                <p>Free shipping on orders over $500</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon
                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                        </polygon>
                    </svg>
                </div>
                <h3>Quality Products</h3>
                <p>Premium furniture & decor items</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                    </svg>
                </div>
                <h3>Easy Returns</h3>
                <p>30-day return policy</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <h3>24/7 Support</h3>
                <p>Dedicated customer service</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="container text-center">
        <h2>Ready to Transform Your Home?</h2>
        <p>Browse our collection of premium furniture and decor pieces</p>
        <a href="<?= base_url('products') ?>" class="btn btn-primary btn-lg">Explore Collection</a>
    </div>
</section>