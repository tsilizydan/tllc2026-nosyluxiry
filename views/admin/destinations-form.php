<div class="admin-header">
    <h1><i class="fas fa-map-marker-alt"></i> <?= $destination ? 'Edit Destination' : 'Create Destination' ?></h1>
    <a href="<?= url('/admin/destinations') ?>" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="<?= url($destination ? '/admin/destinations/edit/' . $destination->id : '/admin/destinations/create') ?>" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>

    <div class="admin-card">
        <h3>Basic Information</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Destination Name *</label>
                <input type="text" name="name" value="<?= e($destination->name ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Region *</label>
                <select name="region" required>
                    <?php $regions = ['north','south','east','west','tsingy','central'];
                    foreach ($regions as $r): ?>
                        <option value="<?= $r ?>" <?= ($destination && $destination->region === $r) ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Short Description</label>
            <textarea name="short_description" rows="2"><?= e($destination->short_description ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Full Description *</label>
            <textarea name="description" rows="6" required><?= e($destination->description ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Best Time to Visit</label>
            <input type="text" name="best_time" value="<?= e($destination->best_time ?? '') ?>" placeholder="e.g. April - November">
        </div>
    </div>

    <div class="admin-card">
        <h3>Location</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Latitude</label>
                <input type="text" name="map_lat" value="<?= e($destination->map_lat ?? '') ?>" placeholder="-18.9333">
            </div>
            <div class="form-group">
                <label>Longitude</label>
                <input type="text" name="map_lng" value="<?= e($destination->map_lng ?? '') ?>" placeholder="47.5167">
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h3>Image & SEO</h3>
        <div class="form-group">
            <label>Featured Image</label>
            <?php if ($destination && $destination->image): ?>
                <div style="margin-bottom:0.5rem;"><img src="<?= upload_url($destination->image) ?>" style="max-width:200px;border-radius:8px;"></div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Meta Title</label>
                <input type="text" name="meta_title" value="<?= e($destination->meta_title ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Meta Description</label>
                <input type="text" name="meta_description" value="<?= e($destination->meta_description ?? '') ?>">
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h3>Publishing</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="draft" <?= ($destination && $destination->status === 'draft') ? 'selected' : '' ?>>Draft</option>
                    <option value="active" <?= ($destination && $destination->status === 'active') ? 'selected' : '' ?>>Active</option>
                </select>
            </div>
            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" value="<?= e($destination->sort_order ?? 0) ?>" min="0">
            </div>
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_featured" value="1" <?= ($destination && $destination->is_featured) ? 'checked' : '' ?>> <label>Featured Destination</label>
        </div>
    </div>

    <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:1rem;">
        <a href="<?= url('/admin/destinations') ?>" class="btn-admin btn-admin-outline">Cancel</a>
        <button type="submit" class="btn-admin btn-admin-gold"><i class="fas fa-save"></i> <?= $destination ? 'Update' : 'Create' ?></button>
    </div>
</form>
