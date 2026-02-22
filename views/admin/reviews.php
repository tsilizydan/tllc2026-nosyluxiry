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
                <td><span class="badge <?= $r->status === 'approved' ? 'badge-success' : ($r->status === 'pending' ? 'badge-warning' : 'badge-danger') ?>"><?= ucfirst($r->status) ?></span></td>
                <td style="white-space:nowrap;"><?= timeAgo($r->created_at) ?></td>
                <td>
                    <form action="<?= url('/admin/reviews/approve') ?>" method="POST" style="display:flex;gap:var(--space-1);">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $r->id ?>">
                        <select name="status" class="form-control" style="padding:var(--space-1) var(--space-2);font-size:var(--text-xs);min-width:90px;">
                            <option value="pending" <?= $r->status === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= $r->status === 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= $r->status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm" style="padding:var(--space-1) var(--space-2);font-size:var(--text-xs);">Save</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="padding:var(--space-10);text-align:center;color:var(--color-gray-400);">No reviews.</div>
    <?php endif; ?>
</div>
