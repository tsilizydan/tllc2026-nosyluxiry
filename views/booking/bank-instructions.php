<!-- Bank Transfer Instructions -->
<div class="page-header">
    <div class="container">
        <p class="subtitle">Payment Instructions</p>
        <h1>Bank Transfer</h1>
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a><span>/</span>
            <span>Payment</span>
        </div>
    </div>
</div>

<section class="section section-darker">
    <div class="container" style="max-width:800px;">

        <!-- Status Banner -->
        <div style="background:linear-gradient(135deg, rgba(212,175,55,0.15), rgba(212,175,55,0.05));border:1px solid rgba(212,175,55,0.3);border-radius:var(--radius-lg);padding:var(--space-8);text-align:center;margin-bottom:var(--space-8);">
            <div style="width:60px;height:60px;border-radius:var(--radius-full);background:rgba(212,175,55,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-4);font-size:var(--text-2xl);color:var(--color-gold);">
                <i class="fas fa-building-columns"></i>
            </div>
            <h2 style="margin-bottom:var(--space-2);">Booking Received!</h2>
            <p style="color:var(--color-gray-400);">Please complete your bank transfer to confirm your booking.</p>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-6);">
            <!-- Bank Details -->
            <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);">
                <h3 style="margin-bottom:var(--space-5);display:flex;align-items:center;gap:var(--space-2);"><i class="fas fa-university text-gold"></i> Bank Details</h3>

                <div style="margin-bottom:var(--space-4);">
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Bank Name</span>
                    <strong style="color:var(--color-white);">BFV-Société Générale Madagascar</strong>
                </div>
                <div style="margin-bottom:var(--space-4);">
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Account Name</span>
                    <strong style="color:var(--color-white);">TSILIZY LLC</strong>
                </div>
                <div style="margin-bottom:var(--space-4);">
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">IBAN</span>
                    <strong style="color:var(--color-white);font-family:monospace;">MG46 0000 5000 0123 4567 8901 234</strong>
                </div>
                <div style="margin-bottom:var(--space-4);">
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">SWIFT/BIC</span>
                    <strong style="color:var(--color-white);font-family:monospace;">BFVMMGMG</strong>
                </div>

                <div style="background:rgba(212,175,55,0.1);border:1px solid rgba(212,175,55,0.2);border-radius:var(--radius-md);padding:var(--space-3);margin-top:var(--space-4);">
                    <p style="font-size:var(--text-sm);color:var(--color-gold);margin:0;"><i class="fas fa-info-circle"></i> Include your booking reference <strong><?= e($booking->reference) ?></strong> in the transfer description.</p>
                </div>
            </div>

            <!-- Booking Summary -->
            <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);">
                <h3 style="margin-bottom:var(--space-5);display:flex;align-items:center;gap:var(--space-2);"><i class="fas fa-receipt text-gold"></i> Your Booking</h3>

                <div style="margin-bottom:var(--space-4);">
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Reference</span>
                    <strong style="color:var(--color-gold);font-size:var(--text-lg);font-family:monospace;"><?= e($booking->reference) ?></strong>
                </div>
                <div style="margin-bottom:var(--space-4);">
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Tour</span>
                    <strong style="color:var(--color-white);"><?= e($booking->tour_name ?? 'Tour') ?></strong>
                </div>
                <div style="margin-bottom:var(--space-4);">
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Guest</span>
                    <strong style="color:var(--color-white);"><?= e($booking->guest_name) ?></strong>
                </div>

                <div style="border-top:1px solid var(--color-dark-border);padding-top:var(--space-4);margin-top:var(--space-4);">
                    <div style="display:flex;justify-content:space-between;font-weight:700;color:var(--color-gold);font-size:var(--text-xl);">
                        <span>Amount Due</span>
                        <span><?= formatPrice($booking->total) ?></span>
                    </div>
                </div>

                <div style="margin-top:var(--space-4);padding:var(--space-3);background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:var(--radius-md);">
                    <p style="font-size:var(--text-sm);color:#f87171;margin:0;"><i class="fas fa-clock"></i> Please complete the transfer within <strong>48 hours</strong> to secure your booking.</p>
                </div>
            </div>
        </div>

        <!-- Alternative: Wise -->
        <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);margin-top:var(--space-6);">
            <h4 style="margin-bottom:var(--space-3);"><i class="fas fa-bolt text-gold"></i> Faster option: Wise (TransferWise)</h4>
            <p style="color:var(--color-gray-400);font-size:var(--text-sm);margin-bottom:var(--space-3);">For lower fees and faster transfers, you can send payment via Wise to:</p>
            <div style="display:flex;gap:var(--space-6);flex-wrap:wrap;">
                <div>
                    <span style="font-size:var(--text-xs);color:var(--color-gray-500);">Email</span>
                    <strong style="display:block;color:var(--color-white);">payments@tsilizy.com</strong>
                </div>
                <div>
                    <span style="font-size:var(--text-xs);color:var(--color-gray-500);">Reference</span>
                    <strong style="display:block;color:var(--color-gold);"><?= e($booking->reference) ?></strong>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div style="display:flex;gap:var(--space-4);justify-content:center;margin-top:var(--space-8);">
            <a href="<?= url('/') ?>" class="btn btn-outline"><i class="fas fa-home"></i> Home</a>
            <a href="<?= url('/booking/confirmation/' . $booking->reference) ?>" class="btn btn-primary">View Booking <i class="fas fa-arrow-right"></i></a>
        </div>

        <p style="text-align:center;margin-top:var(--space-6);font-size:var(--text-sm);color:var(--color-gray-500);">
            A confirmation email has been sent to <strong class="text-gold"><?= e($booking->guest_email) ?></strong>.<br>
            Questions? Contact us at <a href="mailto:<?= CONTACT_EMAIL ?>" class="text-gold"><?= CONTACT_EMAIL ?></a>
        </p>
    </div>
</section>
