<!-- Admin Reviews -->
<div class="admin-header">
    <h1>Reviews</h1>
</div>

<div class="table-container">
    <?php if (!empty($reviews)): ?>
    <table class="admin-table">
        <thead><tr><th>Guest</th><th>Rating</th><th>Title</th><th>Comment</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
            <?php foreach ($reviews as $r): ?>
            <tr>
                <td><strong style="color:var(--color-white);"><?= e($r->reviewer_name) ?></strong></td>
                <td><?= starRating($r->rating) ?></td>
                <td><?= e($r->title ?? '-') ?></td>
                <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($r->comment) ?></td>
                <td>
                    <?php if ($r->is_approved): ?>
                        <span class="badge badge-success">Approved</span>
                    <?php else: ?>
                        <span class="badge badge-warning">Pending</span>
                    <?php endif; ?>
                </td>
                <td style="white-space:nowrap;"><?= timeAgo($r->created_at) ?></td>
                <td>
                    <?php if (!$r->is_approved): ?>
                    <form action="<?= url('/admin/reviews/approve') ?>" method="POST" style="display:inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $r->id ?>">
                        <button type="submit" class="btn btn-primary btn-sm" style="padding:var(--space-1) var(--space-3);font-size:var(--text-xs);"><i class="fas fa-check"></i> Approve</button>
                    </form>
                    <?php else: ?>
                        <span style="color:var(--color-gray-500);font-size:var(--text-xs);">✓ Approved</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="padding:var(--space-10);text-align:center;color:var(--color-gray-400);">No reviews.</div>
    <?php endif; ?>
</div>

