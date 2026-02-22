<!DOCTYPE html>
<html lang="<?= Language::current() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> — <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="background:var(--color-black);" x-data="{ sidebarOpen: true }">
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" :class="{ 'open': sidebarOpen }">
            <div class="sidebar-brand">
                <div class="brand-icon" style="width:36px;height:36px;font-size:var(--text-base);"><i class="fas fa-leaf"></i></div>
                <div>
                    <h4>Nosy Luxury</h4>
                    <small>Admin Panel</small>
                </div>
            </div>

            <div class="sidebar-menu">
                <span class="menu-label">Main</span>
                <a href="<?= url('/admin') ?>" class="<?= isActive('/admin') && !isActive('/admin/') ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="<?= url('/admin/bookings') ?>" class="<?= isActive('/admin/bookings') ? 'active' : '' ?>"><i class="fas fa-ticket"></i> Bookings</a>
                <a href="<?= url('/admin/tours') ?>" class="<?= isActive('/admin/tours') ? 'active' : '' ?>"><i class="fas fa-compass"></i> Tours</a>

                <span class="menu-label">Content</span>
                <a href="<?= url('/admin/reviews') ?>" class="<?= isActive('/admin/reviews') ? 'active' : '' ?>"><i class="fas fa-star"></i> Reviews</a>
                <a href="<?= url('/admin/messages') ?>" class="<?= isActive('/admin/messages') ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Messages</a>

                <span class="menu-label">System</span>
                <a href="<?= url('/admin/users') ?>" class="<?= isActive('/admin/users') ? 'active' : '' ?>"><i class="fas fa-users"></i> Users</a>
                <a href="<?= url('/admin/settings') ?>" class="<?= isActive('/admin/settings') ? 'active' : '' ?>"><i class="fas fa-cog"></i> Settings</a>

                <span class="menu-label" style="margin-top:var(--space-6);"></span>
                <a href="<?= url('/') ?>" target="_blank"><i class="fas fa-external-link-alt"></i> View Site</a>
                <a href="<?= url('/logout') ?>" style="color:var(--color-coral);"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Flash Messages -->
            <?php if (Session::hasFlash('success') || Session::hasFlash('error')): ?>
            <div class="toast-container" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <?php if ($msg = Session::getFlash('success')): ?>
                    <div class="toast alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div>
                <?php endif; ?>
                <?php if ($msg = Session::getFlash('error')): ?>
                    <div class="toast alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($msg) ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>
</body>
</html>
