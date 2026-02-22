<!-- Admin Settings -->
<div class="admin-header">
    <h1>Settings</h1>
</div>

<div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-8);">
    <?php if (!empty($settings)): ?>
    <form action="<?= url('/admin/settings') ?>" method="POST">
        <?= csrf_field() ?>
        <?php foreach ($settings as $s): ?>
        <div class="form-group">
            <label class="form-label"><?= ucwords(str_replace('_', ' ', $s->setting_key)) ?></label>
            <?php if (strlen($s->setting_value ?? '') > 100): ?>
            <textarea name="settings[<?= e($s->setting_key) ?>]" class="form-control" rows="3"><?= e($s->setting_value) ?></textarea>
            <?php else: ?>
            <input type="text" name="settings[<?= e($s->setting_key) ?>]" class="form-control" value="<?= e($s->setting_value) ?>">
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
    </form>
    <?php else: ?>
    <p style="color:var(--color-gray-400);">No settings configured.</p>
    <?php endif; ?>
</div>
