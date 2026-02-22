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
<body style="background:var(--color-black);" x-data="{ sidebarOpen: window.innerWidth > 768 }">
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
                <?php $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
                <a href="<?= url('/admin') ?>" class="<?= rtrim($uri, '/') === rtrim(BASE_URL . '/admin', '/') || $uri === '/admin' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="<?= url('/admin/bookings') ?>" class="<?= isActive('/admin/bookings') ?>"><i class="fas fa-ticket"></i> Bookings</a>
                <a href="<?= url('/admin/tours') ?>" class="<?= isActive('/admin/tours') ?>"><i class="fas fa-compass"></i> Tours</a>

                <span class="menu-label">Content</span>
                <a href="<?= url('/admin/reviews') ?>" class="<?= isActive('/admin/reviews') ?>"><i class="fas fa-star"></i> Reviews</a>
                <a href="<?= url('/admin/messages') ?>" class="<?= isActive('/admin/messages') ?>"><i class="fas fa-envelope"></i> Messages</a>

                <span class="menu-label">System</span>
                <a href="<?= url('/admin/users') ?>" class="<?= isActive('/admin/users') ?>"><i class="fas fa-users"></i> Users</a>
                <a href="<?= url('/admin/settings') ?>" class="<?= isActive('/admin/settings') ?>"><i class="fas fa-cog"></i> Settings</a>

                <span class="menu-label" style="margin-top:var(--space-6);"></span>
                <a href="<?= url('/') ?>" target="_blank"><i class="fas fa-external-link-alt"></i> View Site</a>
                <a href="<?= url('/logout') ?>" style="color:var(--color-coral);"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Mobile sidebar toggle -->
            <button @click="sidebarOpen = !sidebarOpen" style="display:none;position:fixed;top:1rem;left:1rem;z-index:200;width:40px;height:40px;background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-md);color:var(--color-gold);font-size:1.2rem;cursor:pointer;align-items:center;justify-content:center;" class="admin-mobile-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <style>
                @media (max-width: 768px) {
                    .admin-mobile-toggle { display: flex !important; }
                }
            </style>

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
