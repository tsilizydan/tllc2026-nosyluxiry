<!-- Login Page -->
<div class="page-header">
    <div class="container">
        <h1><?= __('auth.login') ?></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><span><?= __('auth.login') ?></span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container container-narrow" style="max-width:480px;">
        <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-10);">
            <div style="text-align:center;margin-bottom:var(--space-8);">
                <div class="brand-icon" style="width:60px;height:60px;font-size:var(--text-2xl);margin:0 auto var(--space-4);border-radius:var(--radius-lg);background:linear-gradient(135deg,var(--color-gold),var(--color-gold-dark));display:flex;align-items:center;justify-content:center;color:var(--color-black);">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Welcome <span class="text-gold">Back</span></h3>
                <p style="color:var(--color-gray-400);font-size:var(--text-sm);margin-top:var(--space-2);">Sign in to access your bookings and profile</p>
            </div>

            <form action="<?= url('/login') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label class="form-label"><?= __('auth.email') ?></label>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= __('auth.password') ?></label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;"><?= __('auth.login_btn') ?></button>
            </form>

            <p style="text-align:center;margin-top:var(--space-6);color:var(--color-gray-400);font-size:var(--text-sm);">
                <?= __('auth.no_account') ?> <a href="<?= url('/register') ?>"><?= __('auth.register') ?></a>
            </p>
        </div>
    </div>
</section>
