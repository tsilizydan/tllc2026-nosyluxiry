<div class="admin-header">
    <h1><i class="fas fa-handshake"></i> <?= $partner ? 'Edit Partner' : 'Add Partner' ?></h1>
    <a href="<?= url('/admin/partners') ?>" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="<?= url($partner ? '/admin/partners/edit/' . $partner->id : '/admin/partners/create') ?>" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>

    <div class="admin-card">
        <h3>Partner Details</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Partner Name *</label>
                <input type="text" name="name" value="<?= e($partner->name ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Type *</label>
                <select name="type" required>
                    <option value="hotel" <?= ($partner && $partner->type === 'hotel') ? 'selected' : '' ?>>Hotel / Lodge</option>
                    <option value="car_rental" <?= ($partner && $partner->type === 'car_rental') ? 'selected' : '' ?>>Car Rental</option>
                    <option value="currency_exchange" <?= ($partner && $partner->type === 'currency_exchange') ? 'selected' : '' ?>>Currency Exchange</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Short Description</label>
            <textarea name="short_description" rows="2"><?= e($partner->short_description ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Full Description</label>
            <textarea name="description" rows="5"><?= e($partner->description ?? '') ?></textarea>
        </div>
    </div>

    <div class="admin-card">
        <h3>Contact & Location</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" value="<?= e($partner->location ?? '') ?>" placeholder="Nosy Be, Madagascar">
            </div>
            <div class="form-group">
                <label>Website</label>
                <input type="url" name="website" value="<?= e($partner->website ?? '') ?>" placeholder="https://">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="contact_email" value="<?= e($partner->contact_email ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="contact_phone" value="<?= e($partner->contact_phone ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Rating (1-5)</label>
            <input type="number" name="rating" value="<?= e($partner->rating ?? '') ?>" min="1" max="5" step="0.1">
        </div>
    </div>

    <div class="admin-card">
        <h3>Image & Publishing</h3>
        <div class="form-group">
            <label>Partner Image</label>
            <?php if ($partner && $partner->image): ?>
                <div style="margin-bottom:0.5rem;"><img src="<?= upload_url($partner->image) ?>" style="max-width:200px;border-radius:8px;"></div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?= ($partner && $partner->status === 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($partner && $partner->status === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" value="<?= e($partner->sort_order ?? 0) ?>" min="0">
            </div>
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_featured" value="1" <?= ($partner && $partner->is_featured) ? 'checked' : '' ?>> <label>Featured Partner</label>
        </div>
    </div>

    <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:1rem;">
        <a href="<?= url('/admin/partners') ?>" class="btn-admin btn-admin-outline">Cancel</a>
        <button type="submit" class="btn-admin btn-admin-gold"><i class="fas fa-save"></i> <?= $partner ? 'Update' : 'Add Partner' ?></button>
    </div>
</form>
