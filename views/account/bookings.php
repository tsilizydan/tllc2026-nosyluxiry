<!-- Account Bookings -->
<div class="page-header">
    <div class="container">
        <h1>My <span class="text-gold">Bookings</span></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><a href="<?= url('/account') ?>"><?= __('nav.account') ?></a><span>/</span><span>Bookings</span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <div style="display:grid;grid-template-columns:250px 1fr;gap:var(--space-8);">
            <!-- Sidebar -->
            <?php include VIEWS_PATH . '/account/_sidebar.php'; ?>

            <!-- Main Content -->
            <div>
                <?php if (Session::hasFlash('success')): ?>
                <div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);border-radius:var(--radius-md);padding:var(--space-4);margin-bottom:var(--space-6);color:#34d399;">
                    <i class="fas fa-check-circle"></i> <?= Session::getFlash('success') ?>
                </div>
                <?php endif; ?>
                <?php if (Session::hasFlash('error')): ?>
                <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:var(--radius-md);padding:var(--space-4);margin-bottom:var(--space-6);color:#f87171;">
                    <i class="fas fa-exclamation-circle"></i> <?= Session::getFlash('error') ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($bookings)): ?>
                <div style="display:flex;flex-direction:column;gap:var(--space-4);">
                    <?php foreach ($bookings as $b): ?>
                    <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);">
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:var(--space-6);align-items:center;">
                            <div>
                                <div style="font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;">Reference</div>
                                <a href="<?= url('/booking/confirmation/' . $b->reference) ?>" class="text-gold" style="font-size:var(--text-lg);font-weight:700;"><?= e($b->reference) ?></a>
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
                            <div style="text-align:right;">
                                <span class="badge <?= $b->status === 'confirmed' ? 'badge-success' : ($b->status === 'pending' ? 'badge-warning' : ($b->status === 'completed' ? 'badge-success' : 'badge-danger')) ?>" style="margin-bottom:var(--space-1);">
                                    <?= ucfirst($b->status) ?>
                                </span>
                                <?php $ps = $b->payment_status ?? 'unpaid'; ?>
                                <br>
                                <span class="badge <?= $ps === 'paid' ? 'badge-success' : ($ps === 'partial' ? 'badge-warning' : 'badge-danger') ?>" style="font-size:0.65rem;">
                                    <i class="fas fa-<?= $ps === 'paid' ? 'check' : 'clock' ?>"></i> <?= ucfirst($ps) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Actions Row -->
                        <div style="display:flex;gap:var(--space-3);margin-top:var(--space-4);padding-top:var(--space-4);border-top:1px solid var(--color-dark-border);">
                            <a href="<?= url('/booking/confirmation/' . $b->reference) ?>" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> View Details</a>

                            <?php if (in_array($b->status, ['completed', 'confirmed']) && !in_array($b->id, $reviewedIds)): ?>
                                <a href="<?= url('/account/bookings/' . $b->reference . '/review') ?>" class="btn btn-primary btn-sm"><i class="fas fa-star"></i> Leave Review</a>
                            <?php elseif (in_array($b->id, $reviewedIds)): ?>
                                <span style="display:inline-flex;align-items:center;gap:var(--space-1);font-size:var(--text-sm);color:#34d399;"><i class="fas fa-check-circle"></i> Reviewed</span>
                            <?php endif; ?>

                            <?php if (in_array($b->status, ['pending'])): ?>
                                <form method="POST" action="<?= url('/account/bookings/cancel') ?>" style="display:inline;" onsubmit="return confirm('Cancel booking <?= e($b->reference) ?>?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="booking_id" value="<?= $b->id ?>">
                                    <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.3);"><i class="fas fa-times"></i> Cancel</button>
                                </form>
                            <?php endif; ?>

                            <?php if ($ps !== 'paid' && in_array($b->payment_method ?? '', ['bank_transfer', 'mobile_money'])): ?>
                                <a href="<?= url('/payment/bank/' . $b->reference) ?>" class="btn btn-sm" style="background:rgba(212,175,55,0.15);color:var(--color-gold);border:1px solid rgba(212,175,55,0.3);"><i class="fas fa-building-columns"></i> Payment Info</a>
                            <?php endif; ?>
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
        </div>
    </div>
</section>
