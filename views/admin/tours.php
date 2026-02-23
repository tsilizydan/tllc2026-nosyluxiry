<div class="admin-header">
    <h1><i class="fas fa-compass"></i> Tours</h1>
    <a href="<?= url('/admin/tours/create') ?>" class="btn-admin btn-admin-gold"><i class="fas fa-plus"></i> Add Tour</a>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Tour</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tours)): ?>
                    <tr><td colspan="8" style="text-align:center;color:#666;padding:2rem;">No tours yet. <a href="<?= url('/admin/tours/create') ?>" style="color:#d4af37;">Create one</a>.</td></tr>
                <?php else: foreach ($tours as $t): ?>
                <tr>
                    <td>
                        <?php if ($t->image): ?>
                            <img src="<?= upload_url($t->image) ?>" class="thumb" alt="">
                        <?php else: ?>
                            <div style="width:50px;height:50px;background:#1a1a2e;border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-image" style="color:#333;"></i></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong style="color:#fff;"><?= e($t->name) ?></strong>
                        <div style="font-size:0.72rem;color:#555;">/tours/<?= e($t->slug) ?></div>
                    </td>
                    <td><?= $t->duration_days ?> days</td>
                    <td>
                        <span style="font-weight:700;color:#d4af37;"><?= formatPrice($t->sale_price ?? $t->price) ?></span>
                        <?php if ($t->sale_price): ?><br><small style="text-decoration:line-through;color:#555;"><?= formatPrice($t->price) ?></small><?php endif; ?>
                    </td>
                    <td><span style="text-transform:capitalize;"><?= e($t->type ?? 'general') ?></span></td>
                    <td><span class="status-badge status-<?= $t->status ?>"><?= ucfirst($t->status) ?></span></td>
                    <td><?= $t->is_featured ? '<i class="fas fa-star" style="color:#d4af37;"></i>' : '<i class="far fa-star" style="color:#333;"></i>' ?></td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="<?= url('/tours/' . $t->slug) ?>" target="_blank" class="btn-admin btn-admin-sm btn-admin-outline" title="View"><i class="fas fa-external-link-alt"></i></a>
                            <a href="<?= url('/admin/tours/edit/' . $t->id) ?>" class="btn-admin btn-admin-sm btn-admin-outline" title="Edit"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="<?= url('/admin/tours/delete/' . $t->id) ?>" onsubmit="return confirm('Delete this tour? This cannot be undone.')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-admin btn-admin-sm btn-admin-danger" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
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
                    <a href="<?= url('/admin/tours?page=' . $i) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
