<div class="admin-header">
    <h1><i class="fas fa-ad"></i> Ads</h1>
    <a href="<?= url('/admin/ads/create') ?>" class="btn-admin btn-admin-gold"><i class="fas fa-plus"></i> Create Ad</a>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr><th>Image</th><th>Title</th><th>Placement</th><th>Impressions</th><th>Clicks</th><th>Active</th><th>Dates</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($ads)): ?>
                    <tr><td colspan="8" style="text-align:center;color:#666;padding:2rem;">No ads yet.</td></tr>
                <?php else: foreach ($ads as $ad): ?>
                    <tr>
                        <td>
                            <?php if ($ad->image): ?>
                                <img src="<?= upload_url($ad->image) ?>" class="thumb" alt="">
                            <?php else: ?>
                                <div style="width:50px;height:50px;background:#1a1a2e;border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-ad" style="color:#333;"></i></div>
                            <?php endif; ?>
                        </td>
                        <td><strong style="color:#fff;"><?= e($ad->title) ?></strong></td>
                        <td><span style="text-transform:capitalize;"><?= e(str_replace('_', ' ', $ad->placement ?? '—')) ?></span></td>
                        <td><?= number_format($ad->impressions ?? 0) ?></td>
                        <td><?= number_format($ad->clicks ?? 0) ?></td>
                        <td>
                            <?php if ($ad->is_active): ?>
                                <span class="status-badge status-active">Active</span>
                            <?php else: ?>
                                <span class="status-badge status-inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td style="white-space:nowrap;font-size:0.75rem;">
                            <?= $ad->start_date ? formatDate($ad->start_date) : '—' ?> →
                            <?= $ad->end_date ? formatDate($ad->end_date) : '∞' ?>
                        </td>
                        <td>
                            <div style="display:flex;gap:4px;">
                                <a href="<?= url('/admin/ads/edit/' . $ad->id) ?>" class="btn-admin btn-admin-sm btn-admin-outline"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="<?= url('/admin/ads/delete/' . $ad->id) ?>" onsubmit="return confirm('Delete this ad?')">
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
