<!-- Account Profile Edit -->
<div class="page-header">
    <div class="container">
        <h1>Edit <span class="text-gold">Profile</span></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><a href="<?= url('/account') ?>"><?= __('nav.account') ?></a><span>/</span><span>Profile</span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <div style="display:grid;grid-template-columns:250px 1fr;gap:var(--space-8);">
            <!-- Sidebar -->
            <?php include VIEWS_PATH . '/account/_sidebar.php'; ?>

            <!-- Main Content -->
            <div>
                <?php if (Session::hasFlash('success')): ?>
                <div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);border-radius:var(--radius-md);padding:var(--space-4);margin-bottom:var(--space-6);color:#34d399;">
                    <i class="fas fa-check-circle"></i> <?= Session::getFlash('success') ?>
                </div>
                <?php endif; ?>
                <?php if (Session::hasFlash('error')): ?>
                <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:var(--radius-md);padding:var(--space-4);margin-bottom:var(--space-6);color:#f87171;">
                    <i class="fas fa-exclamation-circle"></i> <?= Session::getFlash('error') ?>
                </div>
                <?php endif; ?>

                <!-- Profile Form -->
                <form method="POST" action="<?= url('/account/profile') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);margin-bottom:var(--space-6);">
                        <h3 style="margin-bottom:var(--space-5);"><i class="fas fa-user text-gold"></i> Personal Information</h3>

                        <!-- Avatar -->
                        <div style="display:flex;align-items:center;gap:var(--space-4);margin-bottom:var(--space-6);">
                            <div style="width:80px;height:80px;border-radius:var(--radius-full);overflow:hidden;background:var(--color-gold-muted);display:flex;align-items:center;justify-content:center;font-size:var(--text-3xl);color:var(--color-gold);font-weight:700;font-family:var(--font-heading);">
                                <?php if (!empty($user->avatar)): ?>
                                    <img src="<?= upload_url($user->avatar) ?>" style="width:100%;height:100%;object-fit:cover;" alt="Avatar">
                                <?php else: ?>
                                    <?= strtoupper(substr($user->first_name ?? 'U', 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="btn btn-outline btn-sm" style="cursor:pointer;">
                                    <i class="fas fa-camera"></i> Change Photo
                                    <input type="file" name="avatar" accept="image/*" style="display:none;">
                                </label>
                                <p style="font-size:var(--text-xs);color:var(--color-gray-500);margin-top:var(--space-1);">JPEG, PNG or WebP. Max 10MB.</p>
                            </div>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);">
                            <div class="form-group">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" class="form-control" value="<?= e($user->first_name ?? '') ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control" value="<?= e($user->last_name ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="<?= e($user->email ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control" value="<?= e($user->phone ?? '') ?>" placeholder="+261 34 00 000 00">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="3" placeholder="Tell us about yourself..."><?= e($user->bio ?? '') ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                    </div>
                </form>

                <!-- Password Change -->
                <form method="POST" action="<?= url('/account/password') ?>">
                    <?= csrf_field() ?>
                    <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);">
                        <h3 style="margin-bottom:var(--space-5);"><i class="fas fa-lock text-gold"></i> Change Password</h3>

                        <div class="form-group">
                            <label class="form-label">Current Password *</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);">
                            <div class="form-group">
                                <label class="form-label">New Password *</label>
                                <input type="password" name="new_password" class="form-control" required minlength="8">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm New Password *</label>
                                <input type="password" name="confirm_password" class="form-control" required minlength="8">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline"><i class="fas fa-key"></i> Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
