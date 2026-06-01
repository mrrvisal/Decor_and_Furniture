<?php require __DIR__ . '/../helpers.php'; ?>
<section class="admin-reviews-section">
    <div class="admin-section-header">
        <div>
            <h1>Manage Reviews</h1>
            <p class="muted">Approve or delete customer reviews</p>
        </div>
        <?php if ($pendingCount > 0): ?>
        <span class="pending-badge"><?= $pendingCount ?> Pending</span>
        <?php endif; ?>
    </div>

    <!-- Status Tabs -->
    <div class="status-tabs">
        <a href="<?= base_url('admin/reviews?status=pending') ?>" 
           class="tab <?= $status === 'pending' ? 'active' : '' ?>">
            Pending <?= $pendingCount > 0 ? "($pendingCount)" : '' ?>
        </a>
        <a href="<?= base_url('admin/reviews?status=approved') ?>" 
           class="tab <?= $status === 'approved' ? 'active' : '' ?>">
            Approved
        </a>
        <a href="<?= base_url('admin/reviews?status=all') ?>" 
           class="tab <?= $status === 'all' ? 'active' : '' ?>">
            All Reviews
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                <tr>
                    <td>
                        <a href="<?= base_url('admin/product/edit/' . $review['product_id']) ?>" class="product-link">
                            <?= e($review['product_name']) ?>
                        </a>
                    </td>
                    <td class="customer-cell">
                        <strong><?= e($review['user_name']) ?></strong>
                    </td>
                    <td>
                        <div class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?= $i <= $review['rating'] ? 'filled' : '' ?>">★</span>
                            <?php endfor; ?>
                        </div>
                    </td>
                    <td class="review-content-cell">
                        <?php if (!empty($review['title'])): ?>
                        <strong><?= e($review['title']) ?></strong><br>
                        <?php endif; ?>
                        <span class="review-excerpt"><?= e(substr($review['comment'], 0, 100)) ?>...</span>
                    </td>
                    <td><?= date('M j, Y', strtotime($review['created_at'])) ?></td>
                    <td>
                        <?php if ($review['is_approved']): ?>
                        <span class="status-badge approved">✓ Approved</span>
                        <?php else: ?>
                        <span class="status-badge pending">⏳ Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <?php if (!$review['is_approved']): ?>
                            <form method="post" action="<?= base_url('admin/review/approve/' . $review['id']) ?>" 
                                  style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                                <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                    ✓ Approve
                                </button>
                            </form>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-danger delete-review"
                                    data-review-id="<?= $review['id'] ?>"
                                    data-review-title="<?= e($review['title'] ?: substr($review['comment'], 0, 50)) ?>"
                                    data-csrf-token="<?= e($csrf_token) ?>">
                                🗑️ Delete
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($reviews)): ?>
                <tr>
                    <td colspan="7" class="text-center">
                        <div class="empty-state">
                            <p>No reviews found.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteReviewModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Delete Review</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this review?</p>
            <p class="review-quote" id="reviewQuote"></p>
            <p class="warning-text">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost modal-cancel">Cancel</button>
            <form method="post" id="deleteReviewForm">
                <input type="hidden" name="csrf_token" id="deleteCsrfToken">
                <button type="submit" class="btn btn-danger">Delete Review</button>
            </form>
        </div>
    </div>
</div>

<style>
.admin-reviews-section {
    padding: 0;
}
.admin-section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.pending-badge {
    background: var(--warning-bg);
    color: #92400e;
    padding: 0.5rem 1rem;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 600;
}
.status-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--border);
}
.tab {
    padding: 0.75rem 1.25rem;
    text-decoration: none;
    color: var(--text-muted);
    font-weight: 600;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
}
.tab:hover {
    color: var(--accent);
}
.tab.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
}
.rating-stars .star {
    font-size: 0.9rem;
    color: #ddd;
}
.rating-stars .star.filled {
    color: #ffc107;
}
.review-content-cell {
    max-width: 300px;
}
.review-excerpt {
    font-size: 0.85rem;
    color: var(--text-muted);
}
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.6rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
}
.status-badge.approved {
    background: var(--success-bg);
    color: #0f766e;
}
.status-badge.pending {
    background: var(--warning-bg);
    color: #92400e;
}
.product-link {
    color: var(--accent);
    text-decoration: none;
}
.product-link:hover {
    text-decoration: underline;
}
.review-quote {
    margin: 0.75rem 0;
    padding: 0.5rem;
    background: var(--bg);
    border-left: 3px solid var(--accent);
    font-style: italic;
    font-size: 0.9rem;
}
.warning-text {
    color: var(--error);
    font-size: 0.85rem;
    margin-top: 0.5rem;
}
.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--text-muted);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteReviewModal');
    const deleteForm = document.getElementById('deleteReviewForm');
    const csrfInput = document.getElementById('deleteCsrfToken');
    const reviewQuote = document.getElementById('reviewQuote');
    
    function closeModal() {
        modal.classList.remove('active');
    }
    
    document.querySelectorAll('.delete-review').forEach(btn => {
        btn.addEventListener('click', function() {
            const reviewId = this.dataset.reviewId;
            const reviewTitle = this.dataset.reviewTitle;
            const csrfToken = this.dataset.csrfToken;
            
            reviewQuote.textContent = reviewTitle;
            csrfInput.value = csrfToken;
            deleteForm.action = '<?= base_url('admin/review/delete/') ?>' + reviewId;
            modal.classList.add('active');
        });
    });
    
    modal.querySelector('.modal-close')?.addEventListener('click', closeModal);
    modal.querySelector('.modal-cancel')?.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
});
</script>