<!-- Admin Dashboard -->
<div class="admin-header">
    <div>
        <h1>Dashboard</h1>
        <p style="color:var(--color-gray-400);font-size:var(--text-sm);">Welcome back, <?= e(Session::get('user_name', 'Admin')) ?></p>
    </div>
    <div style="display:flex;gap:var(--space-3);">
        <a href="<?= url('/admin/bookings') ?>" class="btn btn-outline btn-sm"><i class="fas fa-ticket"></i> Bookings</a>
        <a href="<?= url('/') ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> View Site</a>
    </div>
</div>

<!-- KPI Cards -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon" style="background:rgba(201,168,76,0.15);color:var(--color-gold);"><i class="fas fa-dollar-sign"></i></div>
        <div class="kpi-value"><?= formatPrice($stats->total_revenue ?? 0) ?></div>
        <div class="kpi-label">Total Revenue</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:rgba(26,86,50,0.2);color:var(--color-emerald-light);"><i class="fas fa-ticket"></i></div>
        <div class="kpi-value"><?= $stats->total_bookings ?? 0 ?></div>
        <div class="kpi-label">Total Bookings</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:rgba(100,149,237,0.2);color:#6495ED;"><i class="fas fa-users"></i></div>
        <div class="kpi-value"><?= $totalUsers ?? 0 ?></div>
        <div class="kpi-label">Registered Users</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:rgba(212,101,74,0.2);color:var(--color-coral);"><i class="fas fa-envelope"></i></div>
        <div class="kpi-value"><?= $newMessages ?? 0 ?></div>
        <div class="kpi-label">New Messages</div>
    </div>
</div>

<!-- Quick Stats Row -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-3);margin-bottom:var(--space-8);">
    <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-md);padding:var(--space-4);display:flex;align-items:center;gap:var(--space-4);">
        <i class="fas fa-compass text-gold"></i>
        <span style="color:var(--color-gray-300);font-size:var(--text-sm);"><strong><?= $totalTours ?? 0 ?></strong> Active tours</span>
    </div>
    <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-md);padding:var(--space-4);display:flex;align-items:center;gap:var(--space-4);">
        <i class="fas fa-star text-gold"></i>
        <span style="color:var(--color-gray-300);font-size:var(--text-sm);"><strong><?= $pendingReviews ?? 0 ?></strong> Pending reviews</span>
    </div>
</div>

<!-- Recent Bookings Table -->
<div class="table-container">
    <div style="padding:var(--space-4) var(--space-6);border-bottom:1px solid var(--color-dark-border);display:flex;justify-content:space-between;align-items:center;">
        <h4>Recent Bookings</h4>
        <a href="<?= url('/admin/bookings') ?>" style="font-size:var(--text-sm);">View All <i class="fas fa-arrow-right"></i></a>
    </div>
    <?php if (!empty($recentBookings)): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Guest</th>
                <th>Tour</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentBookings as $b): ?>
            <tr>
                <td><strong class="text-gold"><?= e($b->reference) ?></strong></td>
                <td>
                    <div><?= e($b->guest_name) ?></div>
                    <small style="color:var(--color-gray-500);"><?= e($b->guest_email) ?></small>
                </td>
                <td><?= e($b->tour_name ?? '-') ?></td>
                <td class="text-gold" style="font-weight:700;"><?= formatPrice($b->total) ?></td>
                <td>
                    <span class="badge <?= $b->status === 'confirmed' ? 'badge-success' : ($b->status === 'pending' ? 'badge-warning' : ($b->status === 'cancelled' ? 'badge-danger' : 'badge-info')) ?>">
                        <?= ucfirst($b->status) ?>
                    </span>
                </td>
                <td style="white-space:nowrap;"><?= timeAgo($b->created_at) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="padding:var(--space-10);text-align:center;color:var(--color-gray-400);">
        <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:var(--space-4);display:block;color:var(--color-gold);"></i>
        No bookings yet.
    </div>
    <?php endif; ?>
</div>
