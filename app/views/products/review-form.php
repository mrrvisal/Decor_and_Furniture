<?php require __DIR__ . '/../helpers.php'; ?>
<section class="review-form-section container">
    <div class="review-form-container">
        <div class="review-header">
            <a href="<?= base_url('product/' . $product['slug']) ?>" class="back-link">
                ← Back to Product
            </a>
            <div class="review-header-icon">✍️</div>
            <h1>Write a Review</h1>
            <p class="review-subtitle">Share your experience with <strong><?= e($product['name']) ?></strong></p>
        </div>

        <form method="post" action="<?= base_url('product/' . $product['slug'] . '/review') ?>" 
              class="review-form" id="reviewForm">
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
            <?php if (!empty($eligible_orders)): ?>
                <div class="form-group">
                    <label for="order_id">Order</label>
                    <select id="order_id" name="order_id" class="form-input" required>
                        <option value="">Select order for this review</option>
                        <?php foreach ($eligible_orders as $order): ?>
                            <option value="<?= e($order['id']) ?>">Order #<?= e($order['order_number']) ?> — <?= date('M j, Y', strtotime($order['created_at'])) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <input type="hidden" name="order_id" value="<?= e($order_id ?? '') ?>">
            <?php endif; ?>

            <!-- Rating Stars -->
            <div class="form-group">
                <label class="rating-label">Your Rating <span class="required">*</span></label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required>
                    <label for="star5" title="5 stars">★</label>
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4" title="4 stars">★</label>
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3" title="3 stars">★</label>
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2" title="2 stars">★</label>
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1" title="1 star">★</label>
                </div>
                <div class="invalid-feedback">Please select a rating</div>
            </div>

            <!-- Review Title -->
            <div class="form-group">
                <label for="title">Review Title <span class="optional">(optional)</span></label>
                <input type="text" id="title" name="title" 
                       placeholder="Summarize your experience" 
                       value="<?= e($_POST['title'] ?? '') ?>"
                       class="form-input">
            </div>

            <!-- Review Comment -->
            <div class="form-group">
                <label for="comment">Your Review <span class="required">*</span></label>
                <textarea id="comment" name="comment" rows="6" 
                          placeholder="What did you like or dislike? What about quality, design, and value?"
                          required class="form-textarea"><?= e($_POST['comment'] ?? '') ?></textarea>
                <div class="invalid-feedback">Please write your review</div>
            </div>

            <!-- Tips -->
            <div class="review-tips">
                <div class="tips-icon">💡</div>
                <div class="tips-content">
                    <strong>Review Tips:</strong>
                    <ul>
                        <li>Be honest and specific about your experience</li>
                        <li>Mention product quality, design, and value</li>
                        <li>Share photos if possible (coming soon)</li>
                    </ul>
                </div>
            </div>

            <!-- Submit -->
            <div class="form-actions">
                <a href="<?= base_url('product/' . $product['slug']) ?>" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </div>
        </form>
    </div>
</section>

<style>
.review-form-section {
    padding: 2rem 0;
    min-height: calc(100vh - 200px);
}
.review-form-container {
    max-width: 700px;
    margin: 0 auto;
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 2rem;
}
.review-header {
    text-align: center;
    margin-bottom: 2rem;
}
.back-link {
    display: inline-block;
    margin-bottom: 1rem;
    color: var(--text-muted);
    text-decoration: none;
}
.back-link:hover {
    color: var(--accent);
}
.review-header-icon {
    font-size: 3rem;
    margin-bottom: 0.5rem;
}
.review-header h1 {
    margin: 0 0 0.5rem;
    font-size: 1.75rem;
    font-weight: 700;
}
.review-subtitle {
    color: var(--text-muted);
    margin: 0;
}

/* Star Rating */
.rating-label {
    font-weight: 600;
    margin-bottom: 0.75rem;
    display: block;
}
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 0.25rem;
}
.star-rating input {
    display: none;
}
.star-rating label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s ease;
}
.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #ffc107;
}
.required {
    color: var(--error);
}
.optional {
    color: var(--text-muted);
    font-weight: normal;
    font-size: 0.85rem;
}
.form-input, .form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 0.95rem;
    transition: all 0.2s;
}
.form-input:focus, .form-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-soft);
}
.review-tips {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: var(--accent-soft);
    border-radius: var(--radius-sm);
    margin: 1.5rem 0;
}
.tips-icon {
    font-size: 1.5rem;
}
.tips-content ul {
    margin: 0.5rem 0 0 1rem;
    padding: 0;
}
.tips-content li {
    font-size: 0.85rem;
    color: var(--text-muted);
}
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
}
@media (max-width: 768px) {
    .review-form-container {
        margin: 0 1rem;
        padding: 1.5rem;
    }
    .form-actions {
        flex-direction: column;
    }
    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    const rating = document.querySelector('input[name="rating"]:checked');
    const comment = document.getElementById('comment').value.trim();
    
    if (!rating) {
        e.preventDefault();
        alert('Please select a rating');
        return false;
    }
    if (!comment) {
        e.preventDefault();
        alert('Please write your review');
        return false;
    }
});
</script>