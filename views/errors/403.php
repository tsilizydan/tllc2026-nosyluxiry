<!-- 403 Error Page -->
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:var(--space-8);">
    <div>
        <div style="font-size:8rem;font-family:var(--font-heading);font-weight:700;background:linear-gradient(135deg,#fbbf24,#f59e0b,#d97706);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;">403</div>
        <h2 style="margin:var(--space-4) 0;">Access Denied</h2>
        <p style="color:var(--color-gray-400);max-width:400px;margin:0 auto var(--space-8);line-height:1.7;">
            You don't have permission to access this page. Please log in with the correct account or go back.
        </p>
        <div style="display:flex;gap:var(--space-4);justify-content:center;">
            <a href="<?= url('/') ?>" class="btn btn-primary"><i class="fas fa-home"></i> Go Home</a>
            <a href="<?= url('/login') ?>" class="btn btn-outline"><i class="fas fa-sign-in-alt"></i> Login</a>
        </div>
    </div>
</div>
