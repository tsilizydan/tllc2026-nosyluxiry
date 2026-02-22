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
            <div>
                <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);text-align:center;">
                    <div style="width:80px;height:80px;border-radius:var(--radius-full);background:var(--color-gold-muted);display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-4);font-family:var(--font-heading);font-size:var(--text-3xl);color:var(--color-gold);font-weight:700;">
                        <?= strtoupper(substr($user->first_name ?? 'U', 0, 1)) ?>
                    </div>
                    <h4><?= e(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?></h4>
                    <p style="color:var(--color-gray-400);font-size:var(--text-sm);"><?= e($user->email ?? '') ?></p>
                    <div style="margin-top:var(--space-4);display:flex;flex-direction:column;gap:var(--space-2);">
                        <a href="<?= url('/account') ?>" style="padding:var(--space-2) var(--space-4);background:var(--color-gold-muted);border-radius:var(--radius-md);color:var(--color-gold);font-size:var(--text-sm);">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="<?= url('/account/bookings') ?>" style="padding:var(--space-2) var(--space-4);border-radius:var(--radius-md);color:var(--color-gray-400);font-size:var(--text-sm);">
                            <i class="fas fa-ticket"></i> My Bookings
                        </a>
                        <a href="<?= url('/account/wishlist') ?>" style="padding:var(--space-2) var(--space-4);border-radius:var(--radius-md);color:var(--color-gray-400);font-size:var(--text-sm);">
                            <i class="fas fa-heart"></i> Wishlist
                        </a>
                        <a href="<?= url('/logout') ?>" style="padding:var(--space-2) var(--space-4);border-radius:var(--radius-md);color:var(--color-coral);font-size:var(--text-sm);">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div>
                <h2 style="margin-bottom:var(--space-6);">Welcome back, <span class="text-gold"><?= e($user->first_name ?? 'Traveler') ?></span></h2>

                <!-- Recent Bookings -->
                <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);overflow:hidden;">
                    <div style="padding:var(--space-4) var(--space-6);border-bottom:1px solid var(--color-dark-border);display:flex;justify-content:space-between;align-items:center;">
                        <h4>Recent Bookings</h4>
                        <a href="<?= url('/account/bookings') ?>" style="font-size:var(--text-sm);">View All</a>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($bookings, 0, 5) as $b): ?>
                            <tr>
                                <td><strong class="text-gold"><?= e($b->reference) ?></strong></td>
                                <td><?= e($b->tour_name ?? 'Tour') ?></td>
                                <td><?= date('M d, Y', strtotime($b->travel_date ?? $b->created_at)) ?></td>
                                <td><?= formatPrice($b->total) ?></td>
                                <td>
                                    <span class="badge <?= $b->status === 'confirmed' ? 'badge-success' : ($b->status === 'pending' ? 'badge-warning' : 'badge-danger') ?>">
                                        <?= ucfirst($b->status) ?>
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
