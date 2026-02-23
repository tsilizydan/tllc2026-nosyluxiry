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
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=2.2">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Admin-specific styles */
        .admin-layout { display: flex; min-height: 100vh; }
        .admin-sidebar {
            width: 260px; min-height: 100vh; background: #0d0d1a;
            border-right: 1px solid rgba(212,175,55,0.1); position: fixed;
            top: 0; left: 0; z-index: 100; overflow-y: auto;
            transition: transform 0.3s ease;
        }
        .admin-main { flex: 1; margin-left: 260px; padding: 2rem; min-height: 100vh; }
        .sidebar-brand {
            display: flex; align-items: center; gap: 12px;
            padding: 1.5rem; border-bottom: 1px solid rgba(212,175,55,0.1);
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px; background: linear-gradient(135deg, #d4af37, #b8941f);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            color: #0d0d1a; font-size: 1rem;
        }
        .sidebar-brand h4 { color: #d4af37; margin: 0; font-size: 1rem; font-weight: 600; }
        .sidebar-brand small { color: #888; font-size: 0.75rem; }
        .sidebar-menu { padding: 1rem 0; }
        .sidebar-menu .menu-label {
            display: block; padding: 0.75rem 1.5rem 0.35rem; font-size: 0.65rem;
            text-transform: uppercase; letter-spacing: 1.5px; color: #555; font-weight: 600;
        }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 12px; padding: 0.65rem 1.5rem;
            color: #aaa; text-decoration: none; font-size: 0.85rem;
            transition: all 0.2s; border-left: 3px solid transparent;
        }
        .sidebar-menu a:hover { color: #d4af37; background: rgba(212,175,55,0.05); }
        .sidebar-menu a.active {
            color: #d4af37; background: rgba(212,175,55,0.08);
            border-left-color: #d4af37;
        }
        .sidebar-menu a i { width: 20px; text-align: center; font-size: 0.9rem; }
        .sidebar-menu .badge {
            margin-left: auto; background: #d4af37; color: #0d0d1a;
            font-size: 0.65rem; padding: 2px 7px; border-radius: 10px; font-weight: 700;
        }

        /* Admin content cards */
        .admin-card {
            background: #141428; border: 1px solid rgba(212,175,55,0.1);
            border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;
        }
        .admin-card h2, .admin-card h3 {
            color: #d4af37; margin: 0 0 1rem; font-size: 1.1rem;
        }
        .admin-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;
        }
        .admin-header h1 { color: #fff; font-size: 1.5rem; margin: 0; }

        /* Admin table */
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th {
            text-align: left; padding: 0.75rem 1rem; color: #888;
            font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;
            border-bottom: 1px solid rgba(212,175,55,0.1); font-weight: 600;
        }
        .admin-table td {
            padding: 0.75rem 1rem; color: #ccc; font-size: 0.85rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .admin-table tr:hover td { background: rgba(212,175,55,0.03); }
        .admin-table img.thumb {
            width: 50px; height: 50px; object-fit: cover; border-radius: 8px;
        }

        /* Buttons */
        .btn-admin {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8rem;
            font-weight: 600; cursor: pointer; border: none; text-decoration: none;
            transition: all 0.2s;
        }
        .btn-admin-gold { background: #d4af37; color: #0d0d1a; }
        .btn-admin-gold:hover { background: #e5c142; transform: translateY(-1px); }
        .btn-admin-sm { padding: 0.35rem 0.7rem; font-size: 0.75rem; }
        .btn-admin-danger { background: rgba(220,53,69,0.15); color: #dc3545; }
        .btn-admin-danger:hover { background: rgba(220,53,69,0.25); }
        .btn-admin-outline { background: transparent; border: 1px solid rgba(212,175,55,0.3); color: #d4af37; }
        .btn-admin-outline:hover { background: rgba(212,175,55,0.1); }
        .btn-admin-success { background: rgba(40,167,69,0.15); color: #28a745; }

        /* Status badges */
        .status-badge {
            display: inline-block; padding: 3px 10px; border-radius: 20px;
            font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .status-active, .status-completed, .status-paid, .status-published { background: rgba(40,167,69,0.15); color: #28a745; }
        .status-pending, .status-new { background: rgba(255,193,7,0.15); color: #ffc107; }
        .status-draft, .status-inactive { background: rgba(108,117,125,0.15); color: #6c757d; }
        .status-cancelled, .status-failed, .status-banned { background: rgba(220,53,69,0.15); color: #dc3545; }
        .status-confirmed { background: rgba(23,162,184,0.15); color: #17a2b8; }
        .status-refunded { background: rgba(111,66,193,0.15); color: #6f42c1; }

        /* Forms */
        .admin-form .form-group { margin-bottom: 1.25rem; }
        .admin-form label {
            display: block; color: #aaa; font-size: 0.8rem;
            margin-bottom: 0.4rem; font-weight: 500;
        }
        .admin-form input, .admin-form select, .admin-form textarea {
            width: 100%; padding: 0.65rem 1rem; background: #0d0d1a;
            border: 1px solid rgba(212,175,55,0.15); border-radius: 8px;
            color: #eee; font-size: 0.85rem; transition: border-color 0.2s;
            font-family: inherit;
        }
        .admin-form input:focus, .admin-form select:focus, .admin-form textarea:focus {
            outline: none; border-color: #d4af37;
        }
        .admin-form textarea { min-height: 120px; resize: vertical; }
        .admin-form .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .admin-form .form-check {
            display: flex; align-items: center; gap: 8px; margin-top: 0.5rem;
        }
        .admin-form .form-check input { width: auto; }
        .admin-form .form-hint { font-size: 0.72rem; color: #666; margin-top: 0.25rem; }

        /* Stats grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card {
            background: #141428; border: 1px solid rgba(212,175,55,0.1);
            border-radius: 12px; padding: 1.25rem; text-align: center;
        }
        .stat-card .stat-value { font-size: 1.8rem; font-weight: 700; color: #d4af37; }
        .stat-card .stat-label { font-size: 0.75rem; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-top: 0.25rem; }
        .stat-card .stat-icon { font-size: 1.5rem; color: rgba(212,175,55,0.3); margin-bottom: 0.5rem; }

        /* Pagination */
        .admin-pagination {
            display: flex; gap: 4px; justify-content: center; margin-top: 1.5rem;
        }
        .admin-pagination a, .admin-pagination span {
            padding: 6px 12px; border-radius: 6px; font-size: 0.8rem;
            text-decoration: none; color: #aaa; border: 1px solid rgba(255,255,255,0.08);
        }
        .admin-pagination a:hover { border-color: #d4af37; color: #d4af37; }
        .admin-pagination .current { background: #d4af37; color: #0d0d1a; border-color: #d4af37; font-weight: 700; }

        /* Tabs */
        .admin-tabs {
            display: flex; gap: 0; border-bottom: 1px solid rgba(212,175,55,0.1);
            margin-bottom: 1.5rem; overflow-x: auto;
        }
        .admin-tabs a {
            padding: 0.75rem 1.25rem; color: #888; text-decoration: none;
            font-size: 0.85rem; font-weight: 500; border-bottom: 2px solid transparent;
            white-space: nowrap; transition: all 0.2s;
        }
        .admin-tabs a:hover { color: #d4af37; }
        .admin-tabs a.active { color: #d4af37; border-bottom-color: #d4af37; }

        /* Toast */
        .toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 9999; }
        .toast {
            padding: 0.75rem 1.25rem; border-radius: 8px; margin-bottom: 0.5rem;
            font-size: 0.85rem; display: flex; align-items: center; gap: 8px;
            animation: slideIn 0.3s ease;
        }
        .alert-success { background: rgba(40,167,69,0.15); color: #28a745; border: 1px solid rgba(40,167,69,0.2); }
        .alert-error { background: rgba(220,53,69,0.15); color: #dc3545; border: 1px solid rgba(220,53,69,0.2); }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                position: fixed; top: 0; left: 0; z-index: 300;
                width: 260px; height: 100vh;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; padding: 1rem; padding-top: 4rem; }
            .admin-form .form-row { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .admin-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .admin-table { font-size: 0.75rem; min-width: 600px; }
            .admin-table th, .admin-table td { padding: 0.5rem; white-space: nowrap; }
            .admin-header { flex-direction: column; align-items: flex-start; }
            .admin-header h1 { font-size: 1.25rem; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .admin-main { padding: 0.75rem; padding-top: 3.5rem; }
        }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5);
            z-index: 299; backdrop-filter: blur(2px); transition: opacity 0.3s;
        }
        .sidebar-overlay.visible { display: block; }
        .sidebar-close {
            display: none; position: absolute; top: 1rem; right: 1rem; width: 32px; height: 32px;
            background: rgba(220,53,69,0.15); border: none; border-radius: 6px; color: #dc3545;
            font-size: 0.9rem; cursor: pointer; align-items: center; justify-content: center;
        }
        @media (max-width: 768px) { .sidebar-close { display: flex; } }
    </style>
</head>
<body style="background:#0a0a18; color:#eee;" x-data="{ sidebarOpen: window.innerWidth > 768 }">
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" :class="{ 'open': sidebarOpen }">
            <button class="sidebar-close" @click="sidebarOpen = false" aria-label="Close sidebar">
                <i class="fas fa-times"></i>
            </button>
            <div class="sidebar-brand">
                <div class="brand-icon"><i class="fas fa-leaf"></i></div>
                <div>
                    <h4>Nosy Luxury</h4>
                    <small>Admin Panel</small>
                </div>
            </div>

            <div class="sidebar-menu">
                <?php $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>

                <span class="menu-label">Main</span>
                <a href="<?= url('/admin') ?>" class="<?= $uri === '/admin' || $uri === rtrim(BASE_URL, '/') . '/admin' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="<?= url('/admin/bookings') ?>" class="<?= isActive('/admin/bookings') ?>">
                    <i class="fas fa-ticket"></i> Bookings
                    <?php $pc = Database::getInstance()->fetch("SELECT COUNT(*) as c FROM bookings WHERE status='pending'"); if ($pc && $pc->c > 0): ?>
                        <span class="badge"><?= $pc->c ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= url('/admin/payments') ?>" class="<?= isActive('/admin/payments') ?>">
                    <i class="fas fa-credit-card"></i> Payments
                </a>

                <span class="menu-label">Catalog</span>
                <a href="<?= url('/admin/tours') ?>" class="<?= isActive('/admin/tours') ?>">
                    <i class="fas fa-compass"></i> Tours
                </a>
                <a href="<?= url('/admin/destinations') ?>" class="<?= isActive('/admin/destinations') ?>">
                    <i class="fas fa-map-marker-alt"></i> Destinations
                </a>

                <span class="menu-label">Content</span>
                <a href="<?= url('/admin/blog') ?>" class="<?= isActive('/admin/blog') ?>">
                    <i class="fas fa-newspaper"></i> Blog
                </a>
                <a href="<?= url('/admin/reviews') ?>" class="<?= isActive('/admin/reviews') ?>">
                    <i class="fas fa-star"></i> Reviews
                    <?php $pr = Database::getInstance()->fetch("SELECT COUNT(*) as c FROM reviews WHERE is_approved=0"); if ($pr && $pr->c > 0): ?>
                        <span class="badge"><?= $pr->c ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= url('/admin/messages') ?>" class="<?= isActive('/admin/messages') ?>">
                    <i class="fas fa-envelope"></i> Messages
                    <?php $pm = Database::getInstance()->fetch("SELECT COUNT(*) as c FROM contact_messages WHERE is_read=0"); if ($pm && $pm->c > 0): ?>
                        <span class="badge"><?= $pm->c ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= url('/admin/trip-requests') ?>" class="<?= isActive('/admin/trip-requests') ?>">
                    <i class="fas fa-route"></i> Trip Requests
                </a>

                <span class="menu-label">Business</span>
                <a href="<?= url('/admin/partners') ?>" class="<?= isActive('/admin/partners') ?>">
                    <i class="fas fa-handshake"></i> Partners
                </a>
                <a href="<?= url('/admin/ads') ?>" class="<?= isActive('/admin/ads') ?>">
                    <i class="fas fa-ad"></i> Ads
                </a>

                <span class="menu-label">System</span>
                <a href="<?= url('/admin/users') ?>" class="<?= isActive('/admin/users') ?>">
                    <i class="fas fa-users"></i> Users
                </a>
                <a href="<?= url('/admin/settings') ?>" class="<?= isActive('/admin/settings') ?>">
                    <i class="fas fa-cog"></i> Settings
                </a>

                <span class="menu-label" style="margin-top:1rem;"></span>
                <a href="<?= url('/') ?>" target="_blank"><i class="fas fa-external-link-alt"></i> View Site</a>
                <a href="<?= url('/logout') ?>" style="color:#dc3545;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" :class="{ 'visible': sidebarOpen && window.innerWidth <= 768 }" @click="sidebarOpen = false"></div>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Mobile sidebar toggle -->
            <button @click="sidebarOpen = !sidebarOpen" style="display:none;position:fixed;top:1rem;left:1rem;z-index:200;width:40px;height:40px;background:#141428;border:1px solid rgba(212,175,55,0.2);border-radius:8px;color:#d4af37;font-size:1.2rem;cursor:pointer;align-items:center;justify-content:center;" class="admin-mobile-toggle">
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
