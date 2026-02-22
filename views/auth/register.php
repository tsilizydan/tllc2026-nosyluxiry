<!-- Register Page -->
<div class="page-header">
    <div class="container">
        <h1><?= __('auth.register') ?></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><span><?= __('auth.register') ?></span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container container-narrow" style="max-width:520px;">
        <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-10);">
            <div style="text-align:center;margin-bottom:var(--space-8);">
                <div class="brand-icon" style="width:60px;height:60px;font-size:var(--text-2xl);margin:0 auto var(--space-4);border-radius:var(--radius-lg);background:linear-gradient(135deg,var(--color-gold),var(--color-gold-dark));display:flex;align-items:center;justify-content:center;color:var(--color-black);">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Create Your <span class="text-gold">Account</span></h3>
                <p style="color:var(--color-gray-400);font-size:var(--text-sm);margin-top:var(--space-2);">Join Nosy Luxury and start planning your dream trip</p>
            </div>

            <form action="<?= url('/register') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="grid grid-2" style="gap:var(--space-4);">
                    <div class="form-group">
                        <label class="form-label"><?= __('auth.first_name') ?></label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?= __('auth.last_name') ?></label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= __('auth.email') ?></label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= __('auth.phone') ?></label>
                    <input type="tel" name="phone" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label"><?= __('auth.password') ?></label>
                    <input type="password" name="password" class="form-control" minlength="8" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;"><?= __('auth.register_btn') ?></button>
            </form>

            <p style="text-align:center;margin-top:var(--space-6);color:var(--color-gray-400);font-size:var(--text-sm);">
                <?= __('auth.have_account') ?> <a href="<?= url('/login') ?>"><?= __('auth.login') ?></a>
            </p>
        </div>
    </div>
</section>
