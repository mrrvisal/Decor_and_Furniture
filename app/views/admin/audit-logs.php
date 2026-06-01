<?php require __DIR__ . '/helpers.php'; ?>
<section class="audit-logs-section">
    <div class="admin-section-header">
        <div>
            <h1>Admin Audit Trail</h1>
            <p class="muted">Complete history of all administrative actions</p>
        </div>
        <span class="audit-badge">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            Immutable Log
        </span>
    </div>

    <!-- Summary Cards -->
    <div class="audit-summary-grid">
        <?php 
        $actionCounts = [];
        foreach ($summary as $s) {
            $key = $s['action'] . '_' . $s['target_type'];
            $actionCounts[$key] = ($actionCounts[$key] ?? 0) + $s['count'];
        }
        ?>
        <div class="summary-card">
            <div class="summary-icon" style="background: rgba(29,158,117,0.12); color: #1D9E75;">✏️</div>
            <div class="summary-info">
                <span class="summary-number"><?= $actionCounts['update_product'] ?? 0 ?></span>
                <span class="summary-label">Product Updates</span>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon" style="background: rgba(55,138,221,0.12); color: #378ADD;">📦</div>
            <div class="summary-info">
                <span class="summary-number"><?= $actionCounts['update_status_order'] ?? 0 ?></span>
                <span class="summary-label">Order Status Changes</span>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon" style="background: rgba(245,158,11,0.12); color: #f59e0b;">👥</div>
            <div class="summary-info">
                <span class="summary-number"><?= $actionCounts['update_user'] ?? 0 ?></span>
                <span class="summary-label">User Modifications</span>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon" style="background: rgba(212,83,126,0.12); color: #D4537E;">⚙️</div>
            <div class="summary-info">
                <span class="summary-number"><?= $actionCounts['toggle_status_product'] ?? 0 ?></span>
                <span class="summary-label">Product Toggles</span>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon" style="background: rgba(14,165,233,0.12); color: #0369A1;">🚨</div>
            <div class="summary-info">
                <span class="summary-number"><?= $actionCounts['resolve_stock_alert_stock_alert'] ?? 0 ?></span>
                <span class="summary-label">Stock Alert Resolutions</span>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="audit-table-container">
        <div class="table-responsive">
            <table class="audit-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Admin</th>
                        <th>Action</th>
                        <th>Target</th>
                        <th>Changes</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td class="log-time">
                            <span class="timestamp"><?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?></span>
                        </td>
                        <td>
                            <div class="admin-info">
                                <span class="admin-initials"><?= strtoupper(substr($log['admin_name'], 0, 2)) ?></span>
                                <span><?= e($log['admin_name']) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="action-badge action-<?= e($log['action']) ?>">
                                <?= ucfirst(e(str_replace('_', ' ', $log['action']))) ?>
                            </span>
                        </td>
                        <td>
                            <span class="target-badge target-<?= e($log['target_type']) ?>">
                                <?= ucfirst(e(str_replace('_', ' ', $log['target_type']))) ?> #<?= $log['target_id'] ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                            $old = json_decode($log['old_data'] ?? 'null', true);
                            $new = json_decode($log['new_data'] ?? 'null', true);
                            if (!is_array($old)) {
                                $old = [];
                            }
                            if (!is_array($new)) {
                                $new = [];
                            }
                            $changes = [];
                            $keys = array_unique(array_merge(array_keys($old), array_keys($new)));

                            $formatValue = function ($value) {
                                if (is_bool($value)) {
                                    return $value ? 'true' : 'false';
                                }
                                if (is_null($value)) {
                                    return 'null';
                                }
                                if (is_array($value)) {
                                    return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                }
                                return e((string) $value);
                            };

                            foreach ($keys as $key) {
                                $oldExists = array_key_exists($key, $old);
                                $newExists = array_key_exists($key, $new);

                                if ($oldExists && !$newExists) {
                                    $changes[] = "<strong>" . ucfirst($key) . "</strong>: removed (" . $formatValue($old[$key]) . ")";
                                    continue;
                                }

                                if (!$oldExists && $newExists) {
                                    $changes[] = "<strong>" . ucfirst($key) . "</strong>: added (" . $formatValue($new[$key]) . ")";
                                    continue;
                                }

                                if ($old[$key] != $new[$key]) {
                                    $changes[] = "<strong>" . ucfirst($key) . "</strong>: " . $formatValue($old[$key]) . " → " . $formatValue($new[$key]);
                                }
                            }

                            if (!empty($changes)) {
                                echo '<div class="changes-list">' . implode('<br>', $changes) . '</div>';
                            } else {
                                echo '<span class="no-changes">—</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <span class="ip-address"><?= e($log['ip_address'] ?? 'N/A') ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="empty-audit">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <p>No audit logs recorded yet</p>
                                <small>Admin actions will appear here</small>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<style>
.audit-logs-section { padding: 0; }
.audit-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--success-bg);
    color: var(--success);
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
}
.audit-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.summary-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--shadow);
}
.summary-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.summary-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text);
    display: block;
    line-height: 1.2;
}
.summary-label {
    font-size: 0.75rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.audit-table-container {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.audit-table {
    width: 100%;
    border-collapse: collapse;
}
.audit-table th,
.audit-table td {
    padding: 1rem 1.25rem;
    text-align: left;
    border-bottom: 1px solid var(--border);
}
.audit-table th {
    background: var(--bg);
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.log-time .timestamp {
    font-family: monospace;
    font-size: 0.85rem;
    color: var(--text-muted);
}
.admin-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.admin-initials {
    width: 28px;
    height: 28px;
    background: var(--accent-soft);
    color: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
}
.action-badge {
    display: inline-block;
    padding: 0.25rem 0.6rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}
.action-update { background: #dbeafe; color: #1e40af; }
.action-create { background: var(--success-bg); color: #0f766e; }
.action-delete { background: var(--error-bg); color: #b91c1c; }
.action-update_status { background: var(--warning-bg); color: #92400e; }
.action-toggle_status { background: #f3e8ff; color: #6b21a5; }
.action-resolve_stock_alert { background: rgba(14,165,233,0.12); color: #0369A1; }
.target-badge {
    display: inline-block;
    padding: 0.25rem 0.6rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
}
.target-product { background: rgba(29,158,117,0.12); color: #1D9E75; }
.target-order { background: rgba(55,138,221,0.12); color: #378ADD; }
.target-user { background: rgba(245,158,11,0.12); color: #f59e0b; }
.target-stock_alert { background: rgba(14,165,233,0.12); color: #0369A1; }
.changes-list {
    font-size: 0.8rem;
    color: var(--text);
    line-height: 1.4;
    max-width: 300px;
}
.no-changes { color: var(--text-muted); }
.ip-address {
    font-family: monospace;
    font-size: 0.8rem;
    color: var(--text-muted);
}
.empty-audit {
    text-align: center;
    padding: 3rem;
    color: var(--text-muted);
}
.empty-audit svg { margin-bottom: 1rem; opacity: 0.4; }
.empty-audit p { margin: 0.5rem 0; }
@media (max-width: 768px) {
    .audit-table th:nth-child(5),
    .audit-table td:nth-child(5),
    .audit-table th:nth-child(6),
    .audit-table td:nth-child(6) { display: none; }
}
</style>