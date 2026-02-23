<div class="admin-header">
    <h1><i class="fas fa-star"></i> Reviews</h1>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr><th>Guest</th><th>Tour</th><th>Rating</th><th>Review</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr><td colspan="7" style="text-align:center;color:#666;padding:2rem;">No reviews yet.</td></tr>
                <?php else: foreach ($reviews as $r): ?>
                <tr>
                    <td><strong style="color:#fff;"><?= e($r->reviewer_name ?? '—') ?></strong></td>
                    <td style="color:#888;"><?= e($r->tour_name ?? '—') ?></td>
                    <td>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fa<?= $i <= ($r->rating ?? 5) ? 's' : 'r' ?> fa-star" style="color:<?= $i <= ($r->rating ?? 5) ? '#d4af37' : '#333' ?>;font-size:0.75rem;"></i>
                        <?php endfor; ?>
                    </td>
                    <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($r->comment ?? '') ?></td>
                    <td>
                        <?php if ($r->is_approved): ?>
                            <span class="status-badge status-active">Approved</span>
                        <?php else: ?>
                            <span class="status-badge status-pending">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;"><?= formatDate($r->created_at) ?></td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <?php if (!$r->is_approved): ?>
                                <form method="POST" action="<?= url('/admin/reviews/approve') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $r->id ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn-admin btn-admin-sm btn-admin-success"><i class="fas fa-check"></i></button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" action="<?= url('/admin/reviews/approve') ?>" onsubmit="return confirm('Delete this review?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $r->id ?>">
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="btn-admin btn-admin-sm btn-admin-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
