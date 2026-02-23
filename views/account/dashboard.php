<!-- Account Dashboard -->
<div class="page-header">
    <div class="container">
        <h1>My <span class="text-gold">Account</span></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><span><?= __('nav.account') ?></span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <div style="display:grid;grid-template-columns:250px 1fr;gap:var(--space-8);">
            <!-- Sidebar -->
            <?php include VIEWS_PATH . '/account/_sidebar.php'; ?>

            <!-- Main Content -->
            <div>
                <h2 style="margin-bottom:var(--space-6);">Welcome back, <span class="text-gold"><?= e($user->first_name ?? 'Traveler') ?></span></h2>

                <!-- Quick Stats -->
                <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:var(--space-4);margin-bottom:var(--space-8);">
                    <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-5);text-align:center;">
                        <i class="fas fa-ticket text-gold" style="font-size:var(--text-2xl);margin-bottom:var(--space-2);display:block;"></i>
                        <span style="font-size:var(--text-3xl);font-weight:700;color:var(--color-white);font-family:var(--font-heading);"><?= count($bookings) ?></span>
                        <span style="display:block;font-size:var(--text-sm);color:var(--color-gray-400);">Total Bookings</span>
                    </div>
                    <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-5);text-align:center;">
                        <i class="fas fa-plane-departure text-gold" style="font-size:var(--text-2xl);margin-bottom:var(--space-2);display:block;"></i>
                        <span style="font-size:var(--text-3xl);font-weight:700;color:var(--color-white);font-family:var(--font-heading);"><?= $upcomingCount ?></span>
                        <span style="display:block;font-size:var(--text-sm);color:var(--color-gray-400);">Upcoming Trips</span>
                    </div>
                    <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-5);text-align:center;">
                        <i class="fas fa-heart text-gold" style="font-size:var(--text-2xl);margin-bottom:var(--space-2);display:block;"></i>
                        <span style="font-size:var(--text-3xl);font-weight:700;color:var(--color-white);font-family:var(--font-heading);"><?= $wishlistCount ?></span>
                        <span style="display:block;font-size:var(--text-sm);color:var(--color-gray-400);">Wishlist</span>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);overflow:hidden;">
                    <div style="padding:var(--space-4) var(--space-6);border-bottom:1px solid var(--color-dark-border);display:flex;justify-content:space-between;align-items:center;">
                        <h4>Recent Bookings</h4>
                        <a href="<?= url('/account/bookings') ?>" style="font-size:var(--text-sm);">View All <i class="fas fa-arrow-right"></i></a>
                    </div>

                    <?php if (!empty($bookings)): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Tour</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($bookings, 0, 5) as $b): ?>
                            <tr>
                                <td><a href="<?= url('/booking/confirmation/' . $b->reference) ?>" class="text-gold" style="font-weight:600;"><?= e($b->reference) ?></a></td>
                                <td><?= e($b->tour_name ?? 'Tour') ?></td>
                                <td><?= date('M d, Y', strtotime($b->travel_date ?? $b->created_at)) ?></td>
                                <td><?= formatPrice($b->total) ?></td>
                                <td>
                                    <span class="badge <?= $b->status === 'confirmed' ? 'badge-success' : ($b->status === 'pending' ? 'badge-warning' : ($b->status === 'completed' ? 'badge-success' : 'badge-danger')) ?>">
                                        <?= ucfirst($b->status) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php $ps = $b->payment_status ?? 'unpaid'; ?>
                                    <span class="badge <?= $ps === 'paid' ? 'badge-success' : ($ps === 'partial' ? 'badge-warning' : 'badge-danger') ?>">
                                        <?= ucfirst($ps) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div style="padding:var(--space-10);text-align:center;">
                        <i class="fas fa-suitcase-rolling" style="font-size:2rem;color:var(--color-gold);margin-bottom:var(--space-4);display:block;"></i>
                        <p style="color:var(--color-gray-400);">No bookings yet. Start exploring!</p>
                        <a href="<?= url('/tours') ?>" class="btn btn-primary btn-sm" style="margin-top:var(--space-4);">Browse Tours</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
