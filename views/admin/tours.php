<!-- Admin Tours -->
<div class="admin-header">
    <h1>Tours</h1>
</div>

<div class="table-container">
    <?php if (!empty($tours)): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Tour</th>
                <th>Duration</th>
                <th>Price</th>
                <th>Type</th>
                <th>Status</th>
                <th>Featured</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tours as $t): ?>
            <tr>
                <td>
                    <strong style="color:var(--color-white);"><?= e($t->name) ?></strong>
                    <div style="font-size:var(--text-xs);color:var(--color-gray-500);">/tours/<?= e($t->slug) ?></div>
                </td>
                <td><?= $t->duration_days ?> days</td>
                <td>
                    <span class="text-gold" style="font-weight:700;"><?= formatPrice($t->sale_price ?? $t->price) ?></span>
                    <?php if ($t->sale_price): ?><br><small style="text-decoration:line-through;color:var(--color-gray-500);"><?= formatPrice($t->price) ?></small><?php endif; ?>
                </td>
                <td><span class="badge badge-info"><?= ucfirst($t->type ?? 'general') ?></span></td>
                <td><span class="badge <?= $t->status === 'active' ? 'badge-success' : 'badge-danger' ?>"><?= ucfirst($t->status) ?></span></td>
                <td><?= $t->is_featured ? '<i class="fas fa-star text-gold"></i>' : '<i class="far fa-star" style="color:var(--color-gray-600);"></i>' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="padding:var(--space-10);text-align:center;color:var(--color-gray-400);">No tours found.</div>
    <?php endif; ?>
</div>
