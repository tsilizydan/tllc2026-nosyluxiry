<?php $tab = $_GET['tab'] ?? 'general'; ?>

<div class="admin-header">
    <h1><i class="fas fa-cog"></i> Settings</h1>
</div>

<!-- Tabs -->
<div class="admin-tabs">
    <a href="<?= url('/admin/settings?tab=general') ?>" class="<?= $tab === 'general' ? 'active' : '' ?>"><i class="fas fa-globe"></i> General</a>
    <a href="<?= url('/admin/settings?tab=contact') ?>" class="<?= $tab === 'contact' ? 'active' : '' ?>"><i class="fas fa-phone"></i> Contact</a>
    <a href="<?= url('/admin/settings?tab=social') ?>" class="<?= $tab === 'social' ? 'active' : '' ?>"><i class="fas fa-share-alt"></i> Social</a>
    <a href="<?= url('/admin/settings?tab=booking') ?>" class="<?= $tab === 'booking' ? 'active' : '' ?>"><i class="fas fa-calendar-check"></i> Booking</a>
    <a href="<?= url('/admin/settings?tab=seo') ?>" class="<?= $tab === 'seo' ? 'active' : '' ?>"><i class="fas fa-search"></i> SEO</a>
</div>

<form method="POST" action="<?= url('/admin/settings') ?>" class="admin-form">
    <?= csrf_field() ?>
    <input type="hidden" name="group" value="<?= e($tab) ?>">

    <?php if ($tab === 'general'): ?>
    <div class="admin-card">
        <h3>General Settings</h3>
        <div class="form-group">
            <label>Site Name</label>
            <input type="text" name="setting_site_name" value="<?= e($settings['site_name'] ?? APP_NAME) ?>">
        </div>
        <div class="form-group">
            <label>Tagline</label>
            <input type="text" name="setting_tagline" value="<?= e($settings['tagline'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Site Description</label>
            <textarea name="setting_site_description" rows="3"><?= e($settings['site_description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Default Currency</label>
            <select name="setting_currency">
                <option value="EUR" <?= ($settings['currency'] ?? 'EUR') === 'EUR' ? 'selected' : '' ?>>EUR (€)</option>
                <option value="USD" <?= ($settings['currency'] ?? '') === 'USD' ? 'selected' : '' ?>>USD ($)</option>
                <option value="GBP" <?= ($settings['currency'] ?? '') === 'GBP' ? 'selected' : '' ?>>GBP (£)</option>
                <option value="MGA" <?= ($settings['currency'] ?? '') === 'MGA' ? 'selected' : '' ?>>MGA (Ar)</option>
            </select>
        </div>
        <div class="form-group">
            <label>Timezone</label>
            <input type="text" name="setting_timezone" value="<?= e($settings['timezone'] ?? 'Indian/Antananarivo') ?>">
        </div>
    </div>

    <?php elseif ($tab === 'contact'): ?>
    <div class="admin-card">
        <h3>Contact Information</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Contact Email</label>
                <input type="email" name="setting_contact_email" value="<?= e($settings['contact_email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Contact Phone</label>
                <input type="text" name="setting_contact_phone" value="<?= e($settings['contact_phone'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label>WhatsApp Number</label>
            <input type="text" name="setting_whatsapp" value="<?= e($settings['whatsapp'] ?? '') ?>" placeholder="+261...">
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="setting_address" rows="2"><?= e($settings['address'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Business Hours</label>
            <input type="text" name="setting_business_hours" value="<?= e($settings['business_hours'] ?? '') ?>" placeholder="Mon-Sat: 8AM - 6PM">
        </div>
    </div>

    <?php elseif ($tab === 'social'): ?>
    <div class="admin-card">
        <h3>Social Media Links</h3>
        <div class="form-group">
            <label><i class="fab fa-facebook" style="color:#1877f2;"></i> Facebook</label>
            <input type="url" name="setting_facebook_url" value="<?= e($settings['facebook_url'] ?? '') ?>" placeholder="https://facebook.com/...">
        </div>
        <div class="form-group">
            <label><i class="fab fa-instagram" style="color:#e1306c;"></i> Instagram</label>
            <input type="url" name="setting_instagram_url" value="<?= e($settings['instagram_url'] ?? '') ?>" placeholder="https://instagram.com/...">
        </div>
        <div class="form-group">
            <label><i class="fab fa-twitter" style="color:#1da1f2;"></i> Twitter / X</label>
            <input type="url" name="setting_twitter_url" value="<?= e($settings['twitter_url'] ?? '') ?>" placeholder="https://x.com/...">
        </div>
        <div class="form-group">
            <label><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube</label>
            <input type="url" name="setting_youtube_url" value="<?= e($settings['youtube_url'] ?? '') ?>" placeholder="https://youtube.com/...">
        </div>
        <div class="form-group">
            <label><i class="fab fa-tiktok"></i> TikTok</label>
            <input type="url" name="setting_tiktok_url" value="<?= e($settings['tiktok_url'] ?? '') ?>" placeholder="https://tiktok.com/...">
        </div>
        <div class="form-group">
            <label><i class="fab fa-tripadvisor" style="color:#00af87;"></i> TripAdvisor</label>
            <input type="url" name="setting_tripadvisor_url" value="<?= e($settings['tripadvisor_url'] ?? '') ?>" placeholder="https://tripadvisor.com/...">
        </div>
    </div>

    <?php elseif ($tab === 'booking'): ?>
    <div class="admin-card">
        <h3>Booking Settings</h3>
        <div class="form-group">
            <label>Minimum Advance Booking (days)</label>
            <input type="number" name="setting_min_booking_days" value="<?= e($settings['min_booking_days'] ?? '2') ?>" min="0">
        </div>
        <div class="form-group">
            <label>Deposit Percentage (%)</label>
            <input type="number" name="setting_deposit_percentage" value="<?= e($settings['deposit_percentage'] ?? '30') ?>" min="0" max="100">
        </div>
        <div class="form-group">
            <label>Cancellation Policy</label>
            <textarea name="setting_cancellation_policy" rows="4"><?= e($settings['cancellation_policy'] ?? '') ?></textarea>
        </div>
    </div>
    <div class="admin-card">
        <h3>Payment Methods</h3>
        <p style="color:#888;font-size:0.8rem;margin-bottom:1rem;">Enable or disable available payment methods.</p>
        <?php if (!empty($paymentMethods)): foreach ($paymentMethods as $pm): ?>
            <div style="display:flex;align-items:center;gap:12px;padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.04);">
                <input type="checkbox" name="active_payment_methods[]" value="<?= e($pm->code) ?>" <?= $pm->is_active ? 'checked' : '' ?>>
                <i class="<?= e($pm->icon) ?>" style="width:20px;text-align:center;color:#d4af37;"></i>
                <div>
                    <strong style="color:#fff;font-size:0.85rem;"><?= e($pm->name) ?></strong>
                    <div style="color:#666;font-size:0.72rem;"><?= e($pm->description) ?></div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>

    <?php elseif ($tab === 'seo'): ?>
    <div class="admin-card">
        <h3>SEO Settings</h3>
        <div class="form-group">
            <label>Meta Title (Homepage)</label>
            <input type="text" name="setting_meta_title" value="<?= e($settings['meta_title'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Meta Description (Homepage)</label>
            <textarea name="setting_meta_description" rows="3"><?= e($settings['meta_description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Google Analytics ID</label>
            <input type="text" name="setting_google_analytics_id" value="<?= e($settings['google_analytics_id'] ?? '') ?>" placeholder="G-XXXXXXXXXX">
        </div>
        <div class="form-group">
            <label>Google Tag Manager ID</label>
            <input type="text" name="setting_gtm_id" value="<?= e($settings['gtm_id'] ?? '') ?>" placeholder="GTM-XXXXXXX">
        </div>
    </div>
    <?php endif; ?>

    <div style="display:flex;justify-content:flex-end;margin-top:1rem;">
        <button type="submit" class="btn-admin btn-admin-gold"><i class="fas fa-save"></i> Save Settings</button>
    </div>
</form>
