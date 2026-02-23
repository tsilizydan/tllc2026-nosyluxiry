<div class="admin-header">
    <h1><i class="fas fa-credit-card"></i> Payments & Transactions</h1>
</div>

<!-- Stats -->
<?php if (!empty($gatewayStats)): ?>
<div class="stats-grid">
    <?php foreach ($gatewayStats as $gs): ?>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-wallet"></i></div>
            <div class="stat-value"><?= formatPrice($gs->total) ?></div>
            <div class="stat-label"><?= e(ucfirst($gs->gateway)) ?> (<?= $gs->count ?>)</div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="admin-card" style="padding:1rem 1.5rem;">
    <form method="GET" action="<?= url('/admin/payments') ?>" style="display:flex;gap:0.75rem;align-items:end;flex-wrap:wrap;">
        <div>
            <label style="display:block;font-size:0.7rem;color:#888;margin-bottom:2px;">Status</label>
            <select name="status" style="padding:0.4rem;background:#0d0d1a;border:1px solid rgba(212,175,55,0.15);border-radius:6px;color:#eee;font-size:0.8rem;">
                <option value="">All</option>
                <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="failed" <?= ($filters['status'] ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
                <option value="refunded" <?= ($filters['status'] ?? '') === 'refunded' ? 'selected' : '' ?>>Refunded</option>
            </select>
        </div>
        <div>
            <label style="display:block;font-size:0.7rem;color:#888;margin-bottom:2px;">Gateway</label>
            <select name="gateway" style="padding:0.4rem;background:#0d0d1a;border:1px solid rgba(212,175,55,0.15);border-radius:6px;color:#eee;font-size:0.8rem;">
                <option value="">All</option>
                <option value="stripe" <?= ($filters['gateway'] ?? '') === 'stripe' ? 'selected' : '' ?>>Stripe</option>
                <option value="paypal" <?= ($filters['gateway'] ?? '') === 'paypal' ? 'selected' : '' ?>>PayPal</option>
                <option value="bank_transfer" <?= ($filters['gateway'] ?? '') === 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                <option value="cash" <?= ($filters['gateway'] ?? '') === 'cash' ? 'selected' : '' ?>>Cash</option>
            </select>
        </div>
        <div>
            <label style="display:block;font-size:0.7rem;color:#888;margin-bottom:2px;">From</label>
            <input type="date" name="date_from" value="<?= e($filters['date_from'] ?? '') ?>" style="padding:0.4rem;background:#0d0d1a;border:1px solid rgba(212,175,55,0.15);border-radius:6px;color:#eee;font-size:0.8rem;">
        </div>
        <div>
            <label style="display:block;font-size:0.7rem;color:#888;margin-bottom:2px;">To</label>
            <input type="date" name="date_to" value="<?= e($filters['date_to'] ?? '') ?>" style="padding:0.4rem;background:#0d0d1a;border:1px solid rgba(212,175,55,0.15);border-radius:6px;color:#eee;font-size:0.8rem;">
        </div>
        <button type="submit" class="btn-admin btn-admin-sm btn-admin-gold"><i class="fas fa-filter"></i> Filter</button>
        <a href="<?= url('/admin/payments') ?>" class="btn-admin btn-admin-sm btn-admin-outline">Reset</a>
    </form>
</div>

<!-- Table -->
<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr><th>Reference</th><th>Guest</th><th>Tour</th><th>Gateway</th><th>Amount</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($payments)): ?>
                    <tr><td colspan="8" style="text-align:center;color:#666;padding:2rem;">No transactions found.</td></tr>
                <?php else: foreach ($payments as $p): ?>
                    <tr>
                        <td><code style="color:#d4af37;"><?= e($p->reference ?? '—') ?></code></td>
                        <td>
                            <strong style="color:#fff;"><?= e($p->guest_name) ?></strong><br>
                            <small style="color:#666;"><?= e($p->guest_email) ?></small>
                        </td>
                        <td><?= e($p->tour_name ?? '—') ?></td>
                        <td><span style="text-transform:capitalize;"><?= e(str_replace('_', ' ', $p->gateway ?? '—')) ?></span></td>
                        <td style="font-weight:600;color:#d4af37;"><?= formatPrice($p->amount) ?></td>
                        <td><span class="status-badge status-<?= $p->status ?>"><?= $p->status ?></span></td>
                        <td style="white-space:nowrap;"><?= formatDate($p->created_at) ?></td>
                        <td>
                            <?php if ($p->status === 'pending'): ?>
                                <form method="POST" action="<?= url('/admin/payments/validate/' . $p->id) ?>" onsubmit="return confirm('Validate this payment?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-admin btn-admin-sm btn-admin-success"><i class="fas fa-check"></i> Validate</button>
                                </form>
                            <?php else: ?>
                                <span style="color:#555;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
        <div class="admin-pagination">
            <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                <?php if ($i == $pagination['page']): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= url('/admin/payments?page=' . $i . '&status=' . e($filters['status'] ?? '') . '&gateway=' . e($filters['gateway'] ?? '')) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
