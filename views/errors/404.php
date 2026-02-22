<!-- 404 Error Page -->
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:var(--space-8);">
    <div>
        <div style="font-size:8rem;font-family:var(--font-heading);font-weight:700;background:linear-gradient(135deg,var(--color-gold-light),var(--color-gold),var(--color-gold-dark));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;">404</div>
        <h2 style="margin:var(--space-4) 0;">Page Not Found</h2>
        <p style="color:var(--color-gray-400);max-width:400px;margin:0 auto var(--space-8);line-height:1.7;">The page you're looking for seems to have wandered off into the rainforest. Let's get you back on track.</p>
        <div style="display:flex;gap:var(--space-4);justify-content:center;">
            <a href="<?= url('/') ?>" class="btn btn-primary"><i class="fas fa-home"></i> Go Home</a>
            <a href="<?= url('/tours') ?>" class="btn btn-outline">Browse Tours</a>
        </div>
    </div>
</div>
