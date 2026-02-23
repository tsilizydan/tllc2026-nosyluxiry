<!-- Cash / Pay on Arrival Deposit -->
<div class="page-header">
    <div class="container">
        <p class="subtitle">Payment Instructions</p>
        <h1>Deposit Required</h1>
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a><span>/</span>
            <span>Payment</span>
        </div>
    </div>
</div>

<section class="section section-darker">
    <div class="container" style="max-width:700px;">

        <!-- Deposit Info Banner -->
        <div style="background:linear-gradient(135deg, rgba(212,175,55,0.15), rgba(212,175,55,0.05));border:1px solid rgba(212,175,55,0.3);border-radius:var(--radius-lg);padding:var(--space-8);text-align:center;margin-bottom:var(--space-8);">
            <div style="width:60px;height:60px;border-radius:var(--radius-full);background:rgba(212,175,55,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-4);font-size:var(--text-2xl);color:var(--color-gold);">
                <i class="fas fa-handshake"></i>
            </div>
            <h2 style="margin-bottom:var(--space-2);">Pay on Arrival Selected</h2>
            <p style="color:var(--color-gray-400);">A <?= $depositPercent ?>% deposit is required to confirm your booking.</p>
        </div>

        <!-- Deposit Breakdown -->
        <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);margin-bottom:var(--space-6);">
            <h3 style="margin-bottom:var(--space-5);"><i class="fas fa-calculator text-gold"></i> Payment Breakdown</h3>

            <div style="display:flex;justify-content:space-between;padding:var(--space-3) 0;border-bottom:1px solid var(--color-dark-border);color:var(--color-gray-300);">
                <span>Total booking cost</span>
                <span><?= formatPrice($booking->total) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:var(--space-3) 0;border-bottom:1px solid var(--color-dark-border);color:var(--color-gold);font-weight:700;font-size:var(--text-lg);">
                <span>Deposit now (<?= $depositPercent ?>%)</span>
                <span><?= formatPrice($depositAmount) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:var(--space-3) 0;color:var(--color-gray-400);">
                <span>Remaining (pay on arrival)</span>
                <span><?= formatPrice($booking->total - $depositAmount) ?></span>
            </div>
        </div>

        <!-- How to Pay Deposit -->
        <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);margin-bottom:var(--space-6);">
            <h3 style="margin-bottom:var(--space-5);"><i class="fas fa-credit-card text-gold"></i> How to Pay Your Deposit</h3>
            <p style="color:var(--color-gray-400);margin-bottom:var(--space-4);font-size:var(--text-sm);">Choose one of the following methods to pay your <strong class="text-gold"><?= formatPrice($depositAmount) ?></strong> deposit:</p>

            <div style="display:flex;flex-direction:column;gap:var(--space-3);">
                <div style="display:flex;align-items:flex-start;gap:var(--space-3);padding:var(--space-4);background:var(--color-dark-surface);border-radius:var(--radius-md);">
                    <i class="fas fa-building-columns text-gold" style="margin-top:2px;"></i>
                    <div>
                        <strong style="color:var(--color-white);">Bank Transfer</strong>
                        <p style="font-size:var(--text-sm);color:var(--color-gray-400);margin:var(--space-1) 0 0;">Transfer to TSILIZY LLC — include reference <strong class="text-gold"><?= e($booking->reference) ?></strong></p>
                    </div>
                </div>
                <div style="display:flex;align-items:flex-start;gap:var(--space-3);padding:var(--space-4);background:var(--color-dark-surface);border-radius:var(--radius-md);">
                    <i class="fas fa-mobile-screen text-gold" style="margin-top:2px;"></i>
                    <div>
                        <strong style="color:var(--color-white);">Mobile Money</strong>
                        <p style="font-size:var(--text-sm);color:var(--color-gray-400);margin:var(--space-1) 0 0;">MVola or Orange Money to <?= CONTACT_PHONE ?></p>
                    </div>
                </div>
                <div style="display:flex;align-items:flex-start;gap:var(--space-3);padding:var(--space-4);background:var(--color-dark-surface);border-radius:var(--radius-md);">
                    <i class="fab fa-whatsapp" style="color:#25D366;margin-top:2px;"></i>
                    <div>
                        <strong style="color:var(--color-white);">WhatsApp</strong>
                        <p style="font-size:var(--text-sm);color:var(--color-gray-400);margin:var(--space-1) 0 0;">Send proof of payment to <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="text-gold" target="_blank"><?= WHATSAPP_NUMBER ?></a></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Reference Card -->
        <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);margin-bottom:var(--space-6);">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);">
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;">Reference</span>
                    <strong style="color:var(--color-gold);font-size:var(--text-lg);font-family:monospace;"><?= e($booking->reference) ?></strong>
                </div>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;">Guest</span>
                    <strong style="color:var(--color-white);"><?= e($booking->guest_name) ?></strong>
                </div>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;">Tour</span>
                    <strong style="color:var(--color-white);"><?= e($booking->tour_name ?? 'Tour') ?></strong>
                </div>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;">Status</span>
                    <span class="badge badge-warning">Awaiting Deposit</span>
                </div>
            </div>
        </div>

        <div style="padding:var(--space-3);background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:var(--radius-md);text-align:center;margin-bottom:var(--space-6);">
            <p style="font-size:var(--text-sm);color:#f87171;margin:0;"><i class="fas fa-clock"></i> Deposit must be received within <strong>72 hours</strong> or your booking may be released.</p>
        </div>

        <!-- Actions -->
        <div style="display:flex;gap:var(--space-4);justify-content:center;margin-top:var(--space-6);">
            <a href="<?= url('/') ?>" class="btn btn-outline"><i class="fas fa-home"></i> Home</a>
            <a href="<?= url('/booking/confirmation/' . $booking->reference) ?>" class="btn btn-primary">View Booking <i class="fas fa-arrow-right"></i></a>
        </div>

        <p style="text-align:center;margin-top:var(--space-6);font-size:var(--text-sm);color:var(--color-gray-500);">
            Details sent to <strong class="text-gold"><?= e($booking->guest_email) ?></strong>. Need help? <a href="mailto:<?= CONTACT_EMAIL ?>" class="text-gold"><?= CONTACT_EMAIL ?></a>
        </p>
    </div>
</section>
