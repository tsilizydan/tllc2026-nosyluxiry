<!-- Booking Confirmation -->
<section class="section section-darker" style="min-height:80vh;display:flex;align-items:center;">
    <div class="container" style="max-width:700px;text-align:center;">

        <?php $isPaid = ($booking->payment_status ?? '') === 'paid'; ?>

        <div style="width:80px;height:80px;border-radius:var(--radius-full);background:<?= $isPaid ? 'rgba(26,86,50,0.2)' : 'rgba(212,175,55,0.15)' ?>;display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-6);font-size:var(--text-3xl);color:<?= $isPaid ? 'var(--color-emerald-light)' : 'var(--color-gold)' ?>;">
            <i class="fas fa-<?= $isPaid ? 'check' : 'clock' ?> animate__animated animate__bounceIn"></i>
        </div>

        <h1 style="margin-bottom:var(--space-4);">
            <?= $isPaid ? __('booking.success') : 'Booking Received' ?>
        </h1>
        <p style="color:var(--color-gray-400);font-size:var(--text-lg);margin-bottom:var(--space-8);">
            <?php if ($isPaid): ?>
                Your payment has been confirmed. Your booking is all set!
            <?php else: ?>
                Your booking is being processed. Please complete payment to confirm.
            <?php endif; ?>
        </p>

        <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-8);text-align:left;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);">
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Reference</span>
                    <strong style="color:var(--color-gold);font-size:var(--text-xl);"><?= e($booking->reference) ?></strong>
                </div>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Status</span>
                    <?php if ($isPaid): ?>
                        <span class="badge badge-success">Paid</span>
                    <?php else: ?>
                        <span class="badge badge-warning"><?= ucfirst(e($booking->status)) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Guest Name</span>
                    <span style="color:var(--color-white);"><?= e($booking->guest_name) ?></span>
                </div>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Total</span>
                    <span style="color:var(--color-gold);font-family:var(--font-heading);font-size:var(--text-xl);font-weight:700;"><?= formatPrice($booking->total) ?></span>
                </div>
                <?php if (!empty($booking->tour_name)): ?>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Tour</span>
                    <span style="color:var(--color-white);"><?= e($booking->tour_name) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($booking->payment_method)): ?>
                <div>
                    <span style="display:block;font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Payment Method</span>
                    <span style="color:var(--color-white);">
                        <?php
                        $methodLabels = [
                            'stripe' => '<i class="fab fa-cc-stripe"></i> Card (Stripe)',
                            'card' => '<i class="fab fa-cc-stripe"></i> Card',
                            'paypal' => '<i class="fab fa-paypal"></i> PayPal',
                            'bank_transfer' => '<i class="fas fa-building-columns"></i> Bank Transfer',
                            'mobile_money' => '<i class="fas fa-mobile-screen"></i> Mobile Money',
                            'cash' => '<i class="fas fa-money-bill"></i> Cash',
                            'pay_on_arrival' => '<i class="fas fa-handshake"></i> Pay on Arrival',
                        ];
                        echo $methodLabels[$booking->payment_method] ?? ucfirst(str_replace('_', ' ', $booking->payment_method));
                        ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!$isPaid): ?>
        <!-- Payment action for pending bookings -->
        <div style="margin-top:var(--space-6);padding:var(--space-5);background:rgba(212,175,55,0.1);border:1px solid rgba(212,175,55,0.2);border-radius:var(--radius-lg);">
            <p style="color:var(--color-gold);font-size:var(--text-sm);margin-bottom:var(--space-3);">
                <i class="fas fa-info-circle"></i> Payment not yet received.
            </p>
            <?php if (in_array($booking->payment_method, ['bank_transfer', 'mobile_money'])): ?>
                <a href="<?= url('/payment/bank/' . $booking->reference) ?>" class="btn btn-primary btn-sm">View Payment Instructions</a>
            <?php elseif (in_array($booking->payment_method, ['cash', 'pay_on_arrival'])): ?>
                <a href="<?= url('/payment/cash/' . $booking->reference) ?>" class="btn btn-primary btn-sm">View Deposit Info</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <p style="margin-top:var(--space-8);color:var(--color-gray-400);font-size:var(--text-sm);">We'll send a detailed confirmation to <strong class="text-gold"><?= e($booking->guest_email) ?></strong>. Our team will contact you within 24 hours to finalize your trip details.</p>

        <div style="display:flex;gap:var(--space-4);justify-content:center;margin-top:var(--space-8);">
            <a href="<?= url('/') ?>" class="btn btn-outline"><i class="fas fa-home"></i> Home</a>
            <a href="<?= url('/tours') ?>" class="btn btn-primary">Explore More Tours <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>
