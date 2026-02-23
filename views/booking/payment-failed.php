<!-- Payment Failed / Cancelled -->
<div class="page-header">
    <div class="container">
        <p class="subtitle">Payment</p>
        <h1><?= ($reason ?? 'failed') === 'cancelled' ? 'Payment Cancelled' : 'Payment Failed' ?></h1>
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a><span>/</span>
            <span>Payment</span>
        </div>
    </div>
</div>

<section class="section section-darker" style="min-height:60vh;display:flex;align-items:center;">
    <div class="container" style="max-width:600px;text-align:center;">

        <div style="width:80px;height:80px;border-radius:var(--radius-full);background:rgba(239,68,68,0.15);display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-6);font-size:var(--text-3xl);color:#f87171;">
            <i class="fas fa-<?= ($reason ?? 'failed') === 'cancelled' ? 'ban' : 'exclamation-triangle' ?>"></i>
        </div>

        <?php if (($reason ?? 'failed') === 'cancelled'): ?>
            <h2 style="margin-bottom:var(--space-3);">Payment Cancelled</h2>
            <p style="color:var(--color-gray-400);font-size:var(--text-lg);margin-bottom:var(--space-6);">
                You cancelled the payment process. Your booking is still saved — you can try again or choose a different payment method.
            </p>
        <?php else: ?>
            <h2 style="margin-bottom:var(--space-3);">Payment Failed</h2>
            <p style="color:var(--color-gray-400);font-size:var(--text-lg);margin-bottom:var(--space-6);">
                Something went wrong while processing your payment. No charges were made. Please try again or use a different method.
            </p>
        <?php endif; ?>

        <?php if ($booking): ?>
        <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);text-align:left;margin-bottom:var(--space-6);">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);">
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;">Reference</span>
                    <strong style="color:var(--color-gold);"><?= e($booking->reference) ?></strong>
                </div>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;">Amount</span>
                    <strong style="color:var(--color-white);"><?= formatPrice($booking->total) ?></strong>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div style="display:flex;flex-direction:column;gap:var(--space-3);align-items:center;">
            <p style="font-size:var(--text-sm);color:var(--color-gray-500);margin-bottom:var(--space-2);">What would you like to do?</p>

            <?php if ($booking): ?>
            <div style="display:flex;gap:var(--space-3);flex-wrap:wrap;justify-content:center;">
                <!-- Retry with same method -->
                <form method="POST" action="<?= url('/payment/process/' . $booking->id) ?>" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-redo"></i> Try Again</button>
                </form>

                <!-- Use bank transfer instead -->
                <a href="<?= url('/payment/bank/' . $booking->reference) ?>" class="btn btn-outline">
                    <i class="fas fa-building-columns"></i> Pay by Bank Transfer
                </a>
            </div>
            <?php endif; ?>

            <div style="margin-top:var(--space-4);">
                <a href="<?= url('/tours') ?>" style="color:var(--color-gray-400);font-size:var(--text-sm);">
                    <i class="fas fa-arrow-left"></i> Back to Tours
                </a>
            </div>
        </div>

        <p style="margin-top:var(--space-8);font-size:var(--text-sm);color:var(--color-gray-500);">
            Need assistance? Contact us at <a href="mailto:<?= CONTACT_EMAIL ?>" class="text-gold"><?= CONTACT_EMAIL ?></a>
            or <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="text-gold" target="_blank">WhatsApp</a>
        </p>
    </div>
</section>
