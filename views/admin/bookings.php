<div class="admin-header">
    <h1><i class="fas fa-ticket"></i> Bookings</h1>
    <div style="display:flex;gap:0.5rem;">
        <a href="<?= url('/admin/bookings') ?>" class="btn-admin btn-admin-sm <?= empty($filterStatus) ? 'btn-admin-gold' : 'btn-admin-outline' ?>">All</a>
        <a href="<?= url('/admin/bookings?status=pending') ?>" class="btn-admin btn-admin-sm <?= ($filterStatus ?? '') === 'pending' ? 'btn-admin-gold' : 'btn-admin-outline' ?>">Pending</a>
        <a href="<?= url('/admin/bookings?status=confirmed') ?>" class="btn-admin btn-admin-sm <?= ($filterStatus ?? '') === 'confirmed' ? 'btn-admin-gold' : 'btn-admin-outline' ?>">Confirmed</a>
        <a href="<?= url('/admin/bookings?status=completed') ?>" class="btn-admin btn-admin-sm <?= ($filterStatus ?? '') === 'completed' ? 'btn-admin-gold' : 'btn-admin-outline' ?>">Completed</a>
    </div>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Guest</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr><td colspan="7" style="text-align:center;color:#666;padding:2rem;">No bookings found.</td></tr>
                <?php else: foreach ($bookings as $b): ?>
                <tr>
                    <td><code style="color:#d4af37;"><?= e($b->reference) ?></code></td>
                    <td>
                        <strong style="color:#fff;"><?= e($b->guest_name) ?></strong><br>
                        <small style="color:#666;"><?= e($b->guest_email) ?></small>
                    </td>
                    <td style="font-weight:700;color:#d4af37;"><?= formatPrice($b->total_price ?? $b->total ?? 0) ?></td>
                    <td><span class="status-badge status-<?= $b->payment_status ?? 'pending' ?>"><?= ucfirst($b->payment_status ?? 'pending') ?></span></td>
                    <td><span class="status-badge status-<?= $b->status ?>"><?= ucfirst($b->status) ?></span></td>
                    <td style="white-space:nowrap;"><?= formatDate($b->created_at) ?></td>
                    <td>
                        <div style="display:flex;gap:4px;align-items:center;">
                            <a href="<?= url('/admin/bookings/view/' . $b->id) ?>" class="btn-admin btn-admin-sm btn-admin-outline" title="View details"><i class="fas fa-eye"></i></a>
                            <form action="<?= url('/admin/bookings/status') ?>" method="POST" style="display:flex;gap:4px;align-items:center;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $b->id ?>">
                                <select name="status" style="padding:4px 8px;background:#0d0d1a;border:1px solid rgba(212,175,55,0.15);border-radius:6px;color:#eee;font-size:0.72rem;min-width:90px;">
                                    <?php foreach (['pending','confirmed','paid','completed','cancelled','refunded'] as $s): ?>
                                        <option value="<?= $s ?>" <?= $b->status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn-admin btn-admin-sm btn-admin-gold"><i class="fas fa-sync"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
        <div class="admin-pagination">
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <?php if ($i == $pagination['page']): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= url('/admin/bookings?page=' . $i . '&status=' . e($filterStatus ?? '')) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
