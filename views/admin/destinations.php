<div class="admin-header">
    <h1><i class="fas fa-map-marker-alt"></i> Destinations</h1>
    <a href="<?= url('/admin/destinations/create') ?>" class="btn-admin btn-admin-gold"><i class="fas fa-plus"></i> Add Destination</a>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Region</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($destinations)): ?>
                    <tr><td colspan="6" style="text-align:center;color:#666;padding:2rem;">No destinations yet.</td></tr>
                <?php else: foreach ($destinations as $d): ?>
                    <tr>
                        <td>
                            <?php if ($d->image): ?>
                                <img src="<?= upload_url($d->image) ?>" class="thumb" alt="">
                            <?php else: ?>
                                <div style="width:50px;height:50px;background:#1a1a2e;border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-image" style="color:#333;"></i></div>
                            <?php endif; ?>
                        </td>
                        <td><strong style="color:#fff;"><?= e($d->name) ?></strong></td>
                        <td><span style="text-transform:capitalize;"><?= e($d->region) ?></span></td>
                        <td><?= $d->is_featured ? '<i class="fas fa-star" style="color:#d4af37;"></i>' : '—' ?></td>
                        <td><span class="status-badge status-<?= $d->status ?>"><?= $d->status ?></span></td>
                        <td>
                            <div style="display:flex;gap:4px;">
                                <a href="<?= url('/admin/destinations/edit/' . $d->id) ?>" class="btn-admin btn-admin-sm btn-admin-outline"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="<?= url('/admin/destinations/delete/' . $d->id) ?>" onsubmit="return confirm('Delete this destination?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-admin btn-admin-sm btn-admin-danger"><i class="fas fa-trash"></i></button>
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
                    <a href="<?= url('/admin/destinations?page=' . $i) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
