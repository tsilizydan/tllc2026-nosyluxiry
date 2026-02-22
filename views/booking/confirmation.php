<!-- Booking Confirmation -->
<section class="section section-darker" style="min-height:80vh;display:flex;align-items:center;">
    <div class="container" style="max-width:700px;text-align:center;">
        <div style="width:80px;height:80px;border-radius:var(--radius-full);background:rgba(26,86,50,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-6);font-size:var(--text-3xl);color:var(--color-emerald-light);">
            <i class="fas fa-check animate__animated animate__bounceIn"></i>
        </div>
        <h1 style="margin-bottom:var(--space-4);"><?= __('booking.success') ?></h1>
        <p style="color:var(--color-gray-400);font-size:var(--text-lg);margin-bottom:var(--space-8);">Your booking has been received and is being processed.</p>

        <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-8);text-align:left;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);">
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Reference</span>
                    <strong style="color:var(--color-gold);font-size:var(--text-xl);"><?= e($booking->reference) ?></strong>
                </div>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Status</span>
                    <span class="badge badge-warning"><?= ucfirst(e($booking->status)) ?></span>
                </div>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Guest Name</span>
                    <span style="color:var(--color-white);"><?= e($booking->guest_name) ?></span>
                </div>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Total</span>
                    <span style="color:var(--color-gold);font-family:var(--font-heading);font-size:var(--text-xl);font-weight:700;"><?= formatPrice($booking->total) ?></span>
                </div>
            </div>
        </div>

        <p style="margin-top:var(--space-8);color:var(--color-gray-400);font-size:var(--text-sm);">We'll send a detailed confirmation to <strong class="text-gold"><?= e($booking->guest_email) ?></strong>. Our team will contact you within 24 hours to finalize your trip details.</p>

        <div style="display:flex;gap:var(--space-4);justify-content:center;margin-top:var(--space-8);">
            <a href="<?= url('/') ?>" class="btn btn-outline"><i class="fas fa-home"></i> Home</a>
            <a href="<?= url('/tours') ?>" class="btn btn-primary">Explore More Tours <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>
