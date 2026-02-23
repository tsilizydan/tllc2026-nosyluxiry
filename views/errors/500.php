<!-- 500 Error Page -->
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:var(--space-8);">
    <div>
        <div style="font-size:8rem;font-family:var(--font-heading);font-weight:700;background:linear-gradient(135deg,#f87171,#ef4444,#dc2626);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;">500</div>
        <h2 style="margin:var(--space-4) 0;">Something Went Wrong</h2>
        <p style="color:var(--color-gray-400);max-width:400px;margin:0 auto var(--space-8);line-height:1.7;">
            We've encountered an unexpected error. Our team has been notified. Please try again in a moment.
        </p>
        <div style="display:flex;gap:var(--space-4);justify-content:center;">
            <a href="<?= url('/') ?>" class="btn btn-primary"><i class="fas fa-home"></i> Go Home</a>
            <a href="javascript:location.reload()" class="btn btn-outline"><i class="fas fa-redo"></i> Retry</a>
        </div>
    </div>
</div>
