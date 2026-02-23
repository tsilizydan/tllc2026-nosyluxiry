<div class="admin-header">
    <h1><i class="fas fa-compass"></i> <?= $tour ? 'Edit Tour' : 'Create Tour' ?></h1>
    <a href="<?= url('/admin/tours') ?>" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="<?= url($tour ? '/admin/tours/edit/' . $tour->id : '/admin/tours/create') ?>" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>

    <div class="admin-card">
        <h3>Basic Information</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Tour Name *</label>
                <input type="text" name="name" value="<?= e($tour->name ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Destination</label>
                <select name="destination_id">
                    <option value="">— Select Destination —</option>
                    <?php foreach ($destinations as $d): ?>
                        <option value="<?= $d->id ?>" <?= ($tour && $tour->destination_id == $d->id) ? 'selected' : '' ?>><?= e($d->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Subtitle</label>
            <input type="text" name="subtitle" value="<?= e($tour->subtitle ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Short Description</label>
            <textarea name="short_description" rows="2"><?= e($tour->short_description ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Full Description *</label>
            <textarea name="description" rows="8" required><?= e($tour->description ?? '') ?></textarea>
        </div>
    </div>

    <div class="admin-card">
        <h3>Details</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Tour Type *</label>
                <select name="type" required>
                    <?php $types = ['adventure','wildlife','cultural','beach','luxury','photography','trekking'];
                    foreach ($types as $t): ?>
                        <option value="<?= $t ?>" <?= ($tour && $tour->type === $t) ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Difficulty</label>
                <select name="difficulty">
                    <option value="easy" <?= ($tour && $tour->difficulty === 'easy') ? 'selected' : '' ?>>Easy</option>
                    <option value="moderate" <?= (!$tour || ($tour && $tour->difficulty === 'moderate')) ? 'selected' : '' ?>>Moderate</option>
                    <option value="challenging" <?= ($tour && $tour->difficulty === 'challenging') ? 'selected' : '' ?>>Challenging</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Duration (Days) *</label>
                <input type="number" name="duration_days" value="<?= e($tour->duration_days ?? 1) ?>" min="1" required>
            </div>
            <div class="form-group">
                <label>Departure Location</label>
                <input type="text" name="departure_location" value="<?= e($tour->departure_location ?? '') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Min Group Size</label>
                <input type="number" name="group_size_min" value="<?= e($tour->group_size_min ?? 1) ?>" min="1">
            </div>
            <div class="form-group">
                <label>Max Group Size</label>
                <input type="number" name="group_size_max" value="<?= e($tour->group_size_max ?? 12) ?>" min="1">
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h3>Pricing</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Price (EUR) *</label>
                <input type="number" name="price" value="<?= e($tour->price ?? '') ?>" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label>Sale Price (EUR)</label>
                <input type="number" name="sale_price" value="<?= e($tour->sale_price ?? '') ?>" step="0.01" min="0">
                <div class="form-hint">Leave empty if no discount</div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h3>Lists <small style="color:#666;">(one item per line)</small></h3>
        <div class="form-group">
            <label>What's Included</label>
            <textarea name="included" rows="5"><?php if ($tour && $tour->included): $items = json_decode($tour->included, true); if (is_array($items)) echo implode("\n", $items); endif; ?></textarea>
        </div>
        <div class="form-group">
            <label>What's Excluded</label>
            <textarea name="excluded" rows="4"><?php if ($tour && $tour->excluded): $items = json_decode($tour->excluded, true); if (is_array($items)) echo implode("\n", $items); endif; ?></textarea>
        </div>
        <div class="form-group">
            <label>Highlights</label>
            <textarea name="highlights" rows="4"><?php if ($tour && $tour->highlights): $items = json_decode($tour->highlights, true); if (is_array($items)) echo implode("\n", $items); endif; ?></textarea>
        </div>
    </div>

    <div class="admin-card">
        <h3>Image & SEO</h3>
        <div class="form-group">
            <label>Featured Image</label>
            <?php if ($tour && $tour->image): ?>
                <div style="margin-bottom:0.5rem;"><img src="<?= upload_url($tour->image) ?>" style="max-width:200px;border-radius:8px;"></div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Meta Title</label>
                <input type="text" name="meta_title" value="<?= e($tour->meta_title ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Meta Description</label>
                <input type="text" name="meta_description" value="<?= e($tour->meta_description ?? '') ?>">
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h3>Publishing</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="draft" <?= ($tour && $tour->status === 'draft') ? 'selected' : '' ?>>Draft</option>
                    <option value="active" <?= ($tour && $tour->status === 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="archived" <?= ($tour && $tour->status === 'archived') ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>
            <div class="form-group">
                <div class="form-check" style="margin-top:1.5rem;">
                    <input type="checkbox" name="is_featured" value="1" <?= ($tour && $tour->is_featured) ? 'checked' : '' ?>> <label>Featured</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="is_bestseller" value="1" <?= ($tour && $tour->is_bestseller) ? 'checked' : '' ?>> <label>Bestseller</label>
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:1rem;">
        <a href="<?= url('/admin/tours') ?>" class="btn-admin btn-admin-outline">Cancel</a>
        <button type="submit" class="btn-admin btn-admin-gold"><i class="fas fa-save"></i> <?= $tour ? 'Update Tour' : 'Create Tour' ?></button>
    </div>
</form>
