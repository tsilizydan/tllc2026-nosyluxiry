<div class="admin-header">
    <h1><i class="fas fa-newspaper"></i> <?= $post ? 'Edit Post' : 'New Blog Post' ?></h1>
    <a href="<?= url('/admin/blog') ?>" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="<?= url($post ? '/admin/blog/edit/' . $post->id : '/admin/blog/create') ?>" enctype="multipart/form-data" class="admin-form">
    <?= csrf_field() ?>

    <div class="admin-card">
        <h3>Content</h3>
        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" value="<?= e($post->title ?? '') ?>" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Category</label>
                <select name="category_id">
                    <option value="">— No Category —</option>
                    <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id ?>" <?= ($post && $post->category_id == $cat->id) ? 'selected' : '' ?>><?= e($cat->name) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tags</label>
                <input type="text" name="tags" value="<?= e($post->tags ?? '') ?>" placeholder="wildlife, nature, travel">
                <div class="form-hint">Comma-separated</div>
            </div>
        </div>
        <div class="form-group">
            <label>Excerpt</label>
            <textarea name="excerpt" rows="2"><?= e($post->excerpt ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Content *</label>
            <textarea name="content" rows="12" required><?= e($post->content ?? '') ?></textarea>
        </div>
    </div>

    <div class="admin-card">
        <h3>Image & SEO</h3>
        <div class="form-group">
            <label>Featured Image</label>
            <?php if ($post && $post->image): ?>
                <div style="margin-bottom:0.5rem;"><img src="<?= upload_url($post->image) ?>" style="max-width:200px;border-radius:8px;"></div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Meta Title</label>
                <input type="text" name="meta_title" value="<?= e($post->meta_title ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Meta Description</label>
                <input type="text" name="meta_description" value="<?= e($post->meta_description ?? '') ?>">
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h3>Publishing</h3>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="draft" <?= ($post && $post->status === 'draft') ? 'selected' : '' ?>>Draft</option>
                <option value="published" <?= ($post && $post->status === 'published') ? 'selected' : '' ?>>Published</option>
            </select>
        </div>
    </div>

    <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:1rem;">
        <a href="<?= url('/admin/blog') ?>" class="btn-admin btn-admin-outline">Cancel</a>
        <button type="submit" class="btn-admin btn-admin-gold"><i class="fas fa-save"></i> <?= $post ? 'Update Post' : 'Publish Post' ?></button>
    </div>
</form>
