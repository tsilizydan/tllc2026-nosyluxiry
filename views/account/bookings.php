<!-- Account Bookings -->
<div class="page-header">
    <div class="container">
        <h1>My <span class="text-gold">Bookings</span></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><a href="<?= url('/account') ?>"><?= __('nav.account') ?></a><span>/</span><span>Bookings</span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <?php if (!empty($bookings)): ?>
        <div style="display:flex;flex-direction:column;gap:var(--space-4);">
            <?php foreach ($bookings as $b): ?>
            <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:var(--space-6);align-items:center;">
                <div>
                    <div style="font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Reference</div>
                    <strong class="text-gold" style="font-size:var(--text-lg);"><?= e($b->reference) ?></strong>
                    <div style="font-size:var(--text-sm);color:var(--color-gray-400);margin-top:var(--space-1);"><?= e($b->tour_name ?? 'Tour') ?></div>
                </div>
                <div>
                    <div style="font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Travel Date</div>
                    <span style="color:var(--color-white);"><?= date('M d, Y', strtotime($b->travel_date ?? $b->created_at)) ?></span>
                    <div style="font-size:var(--text-sm);color:var(--color-gray-400);margin-top:var(--space-1);"><?= $b->num_guests ?> guest<?= $b->num_guests > 1 ? 's' : '' ?></div>
                </div>
                <div>
                    <div style="font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Total</div>
                    <span style="color:var(--color-gold);font-family:var(--font-heading);font-size:var(--text-xl);font-weight:700;"><?= formatPrice($b->total) ?></span>
                </div>
                <div>
                    <span class="badge <?= $b->status === 'confirmed' ? 'badge-success' : ($b->status === 'pending' ? 'badge-warning' : 'badge-danger') ?>">
                        <?= ucfirst($b->status) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:var(--space-16) 0;">
            <i class="fas fa-suitcase-rolling" style="font-size:3rem;color:var(--color-gold);margin-bottom:var(--space-4);display:block;"></i>
            <h3>No Bookings Yet</h3>
            <p style="color:var(--color-gray-400);margin-top:var(--space-2);margin-bottom:var(--space-6);">Your adventure starts here.</p>
            <a href="<?= url('/tours') ?>" class="btn btn-primary">Explore Tours <i class="fas fa-arrow-right"></i></a>
        </div>
        <?php endif; ?>
    </div>
</section>
