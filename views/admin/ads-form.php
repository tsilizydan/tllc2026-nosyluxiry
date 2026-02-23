<div class="admin-header">
    <h1><i class="fas fa-ad"></i> <?= $ad ? 'Edit Ad' : 'Create Ad' ?></h1>
    <a href="<?= url('/admin/ads') ?>" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="<?= url($ad ? '/admin/ads/edit/' . $ad->id : '/admin/ads/create') ?>" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>

    <div class="admin-card">
        <h3>Ad Details</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" value="<?= e($ad->title ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Link URL</label>
                <input type="url" name="link" value="<?= e($ad->link ?? '') ?>" placeholder="https://">
            </div>
        </div>
        <div class="form-group">
            <label>Placement *</label>
            <select name="placement" required>
                <option value="hero" <?= ($ad && $ad->placement === 'hero') ? 'selected' : '' ?>>Hero Banner</option>
                <option value="sidebar" <?= (!$ad || ($ad && $ad->placement === 'sidebar')) ? 'selected' : '' ?>>Sidebar</option>
                <option value="footer" <?= ($ad && $ad->placement === 'footer') ? 'selected' : '' ?>>Footer</option>
                <option value="inline" <?= ($ad && $ad->placement === 'inline') ? 'selected' : '' ?>>Inline Content</option>
                <option value="popup" <?= ($ad && $ad->placement === 'popup') ? 'selected' : '' ?>>Popup</option>
            </select>
        </div>
        <div class="form-group">
            <label>HTML Content (optional)</label>
            <textarea name="html_content" rows="4"><?= e($ad->html_content ?? '') ?></textarea>
            <div class="form-hint">Custom HTML for the ad. If provided, this takes priority over the image.</div>
        </div>
    </div>

    <div class="admin-card">
        <h3>Image & Schedule</h3>
        <div class="form-group">
            <label>Ad Image</label>
            <?php if ($ad && $ad->image): ?>
                <div style="margin-bottom:0.5rem;"><img src="<?= upload_url($ad->image) ?>" style="max-width:200px;border-radius:8px;"></div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" value="<?= e($ad->start_date ?? '') ?>">
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" value="<?= e($ad->end_date ?? '') ?>">
                <div class="form-hint">Leave empty for no expiry</div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h3>Publishing</h3>
        <div class="form-row">
            <div class="form-group">
                <div class="form-check" style="margin-top:0.5rem;">
                    <input type="checkbox" name="is_active" value="1" <?= (!$ad || ($ad && $ad->is_active)) ? 'checked' : '' ?>> <label>Active</label>
                </div>
            </div>
            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" value="<?= e($ad->sort_order ?? 0) ?>" min="0">
            </div>
        </div>
    </div>

    <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:1rem;">
        <a href="<?= url('/admin/ads') ?>" class="btn-admin btn-admin-outline">Cancel</a>
        <button type="submit" class="btn-admin btn-admin-gold"><i class="fas fa-save"></i> <?= $ad ? 'Update' : 'Create Ad' ?></button>
    </div>
</form>
