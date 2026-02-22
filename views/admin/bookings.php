<!-- Admin Bookings -->
<div class="admin-header">
    <h1>Bookings</h1>
</div>

<div class="table-container">
    <?php if (!empty($bookings)): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Guest</th>
                <th>Contact</th>
                <th>Guests</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $b): ?>
            <tr>
                <td><strong class="text-gold"><?= e($b->reference) ?></strong></td>
                <td><?= e($b->guest_name) ?></td>
                <td>
                    <div><?= e($b->guest_email) ?></div>
                    <small style="color:var(--color-gray-500);"><?= e($b->guest_phone ?? '') ?></small>
                </td>
                <td><?= $b->num_guests ?></td>
                <td class="text-gold" style="font-weight:700;"><?= formatPrice($b->total) ?></td>
                <td><?= ucfirst(str_replace('_', ' ', $b->payment_method ?? '-')) ?></td>
                <td>
                    <span class="badge <?= $b->status === 'confirmed' ? 'badge-success' : ($b->status === 'pending' ? 'badge-warning' : 'badge-danger') ?>">
                        <?= ucfirst($b->status) ?>
                    </span>
                </td>
                <td style="white-space:nowrap;"><?= date('M d, Y', strtotime($b->created_at)) ?></td>
                <td>
                    <form action="<?= url('/admin/bookings/status') ?>" method="POST" style="display:flex;gap:var(--space-1);">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $b->id ?>">
                        <select name="status" class="form-control" style="padding:var(--space-1) var(--space-2);font-size:var(--text-xs);min-width:100px;">
                            <option value="pending" <?= $b->status === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="confirmed" <?= $b->status === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="completed" <?= $b->status === 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $b->status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm" style="padding:var(--space-1) var(--space-2);font-size:var(--text-xs);">Update</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="padding:var(--space-10);text-align:center;color:var(--color-gray-400);">No bookings found.</div>
    <?php endif; ?>
</div>
