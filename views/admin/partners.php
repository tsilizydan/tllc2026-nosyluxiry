<div class="admin-header">
    <h1><i class="fas fa-handshake"></i> Partners</h1>
    <a href="<?= url('/admin/partners/create') ?>" class="btn-admin btn-admin-gold"><i class="fas fa-plus"></i> Add Partner</a>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr><th>Image</th><th>Name</th><th>Type</th><th>Location</th><th>Featured</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($partners)): ?>
                    <tr><td colspan="7" style="text-align:center;color:#666;padding:2rem;">No partners yet.</td></tr>
                <?php else: foreach ($partners as $p): ?>
                    <tr>
                        <td>
                            <?php if ($p->image): ?>
                                <img src="<?= upload_url($p->image) ?>" class="thumb" alt="">
                            <?php else: ?>
                                <div style="width:50px;height:50px;background:#1a1a2e;border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-building" style="color:#333;"></i></div>
                            <?php endif; ?>
                        </td>
                        <td><strong style="color:#fff;"><?= e($p->name) ?></strong></td>
                        <td><?= Partner::typeLabel($p->type) ?></td>
                        <td><?= e($p->location) ?></td>
                        <td><?= $p->is_featured ? '<i class="fas fa-star" style="color:#d4af37;"></i>' : '—' ?></td>
                        <td><span class="status-badge status-<?= $p->status ?>"><?= $p->status ?></span></td>
                        <td>
                            <div style="display:flex;gap:4px;">
                                <a href="<?= url('/admin/partners/edit/' . $p->id) ?>" class="btn-admin btn-admin-sm btn-admin-outline"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="<?= url('/admin/partners/delete/' . $p->id) ?>" onsubmit="return confirm('Delete this partner?')">
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
</div>
