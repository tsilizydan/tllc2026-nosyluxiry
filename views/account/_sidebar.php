<!-- Account Sidebar (shared partial) -->
<?php $currentPage = basename($_SERVER['REQUEST_URI'] ?? '', '?' . ($_SERVER['QUERY_STRING'] ?? '')); ?>
<?php $activePage = match(true) {
    str_contains($_SERVER['REQUEST_URI'] ?? '', '/account/profile') => 'profile',
    str_contains($_SERVER['REQUEST_URI'] ?? '', '/account/bookings') => 'bookings',
    str_contains($_SERVER['REQUEST_URI'] ?? '', '/account/wishlist') => 'wishlist',
    default => 'dashboard',
}; ?>
<div>
    <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);text-align:center;">
        <?php $u = Auth::user(); ?>
        <div style="width:80px;height:80px;border-radius:var(--radius-full);overflow:hidden;background:var(--color-gold-muted);display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-4);font-family:var(--font-heading);font-size:var(--text-3xl);color:var(--color-gold);font-weight:700;">
            <?php if (!empty($u->avatar)): ?>
                <img src="<?= upload_url($u->avatar) ?>" style="width:100%;height:100%;object-fit:cover;" alt="Avatar">
            <?php else: ?>
                <?= strtoupper(substr($u->first_name ?? 'U', 0, 1)) ?>
            <?php endif; ?>
        </div>
        <h4><?= e(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?></h4>
        <p style="color:var(--color-gray-400);font-size:var(--text-sm);"><?= e($u->email ?? '') ?></p>
        <div style="margin-top:var(--space-4);display:flex;flex-direction:column;gap:var(--space-2);">
            <a href="<?= url('/account') ?>" style="padding:var(--space-2) var(--space-4);<?= $activePage === 'dashboard' ? 'background:var(--color-gold-muted);color:var(--color-gold);' : 'color:var(--color-gray-400);' ?>border-radius:var(--radius-md);font-size:var(--text-sm);transition:all 0.2s;">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="<?= url('/account/profile') ?>" style="padding:var(--space-2) var(--space-4);<?= $activePage === 'profile' ? 'background:var(--color-gold-muted);color:var(--color-gold);' : 'color:var(--color-gray-400);' ?>border-radius:var(--radius-md);font-size:var(--text-sm);transition:all 0.2s;">
                <i class="fas fa-user-edit"></i> Edit Profile
            </a>
            <a href="<?= url('/account/bookings') ?>" style="padding:var(--space-2) var(--space-4);<?= $activePage === 'bookings' ? 'background:var(--color-gold-muted);color:var(--color-gold);' : 'color:var(--color-gray-400);' ?>border-radius:var(--radius-md);font-size:var(--text-sm);transition:all 0.2s;">
                <i class="fas fa-ticket"></i> My Bookings
            </a>
            <a href="<?= url('/account/wishlist') ?>" style="padding:var(--space-2) var(--space-4);<?= $activePage === 'wishlist' ? 'background:var(--color-gold-muted);color:var(--color-gold);' : 'color:var(--color-gray-400);' ?>border-radius:var(--radius-md);font-size:var(--text-sm);transition:all 0.2s;">
                <i class="fas fa-heart"></i> Wishlist
            </a>
            <a href="<?= url('/logout') ?>" style="padding:var(--space-2) var(--space-4);border-radius:var(--radius-md);color:var(--color-coral);font-size:var(--text-sm);">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>
