<!-- Navigation -->
<nav class="navbar" id="navbar" x-data="{ scrolled: false, mobileOpen: false }"
     @scroll.window="scrolled = (window.scrollY > 50)"
     :class="{ 'scrolled': scrolled }">
    <div class="container">
        <!-- Brand -->
        <a href="<?= url('/') ?>" class="navbar-brand">
            <div class="brand-icon"><i class="fas fa-leaf"></i></div>
            <span>Nosy Luxury</span>
        </a>

        <!-- Desktop Nav -->
        <div class="nav-links">
            <a href="<?= url('/') ?>" class="<?= isActive('/') && $_SERVER['REQUEST_URI'] === '/' ? 'active' : '' ?>"><?= __('nav.home') ?></a>
            <a href="<?= url('/destinations') ?>" class="<?= isActive('/destinations') ?>"><?= __('nav.destinations') ?></a>
            <a href="<?= url('/tours') ?>" class="<?= isActive('/tours') ?>"><?= __('nav.tours') ?></a>
            <a href="<?= url('/trip-builder') ?>" class="<?= isActive('/trip-builder') ?>"><?= __('nav.trip_builder') ?></a>
            <a href="<?= url('/about') ?>" class="<?= isActive('/about') ?>"><?= __('nav.about') ?></a>
            <a href="<?= url('/blog') ?>" class="<?= isActive('/blog') ?>"><?= __('nav.blog') ?></a>
            <a href="<?= url('/contact') ?>" class="<?= isActive('/contact') ?>"><?= __('nav.contact') ?></a>
        </div>

        <!-- Actions -->
        <div class="nav-actions">
            <!-- Language Switcher -->
            <div class="nav-lang-switcher">
                <?php foreach (Language::available() as $code => $label): ?>
                    <a href="<?= url('/lang/' . $code) ?>" class="<?= Language::current() === $code ? 'active' : '' ?>"><?= strtoupper($code) ?></a>
                <?php endforeach; ?>
            </div>

            <!-- Auth -->
            <?php if (Auth::check()): ?>
                <a href="<?= url('/account') ?>" class="btn btn-outline btn-sm"><i class="fas fa-user"></i> <?= e(Session::get('user_name', '')) ?></a>
            <?php else: ?>
                <a href="<?= url('/login') ?>" class="btn btn-outline btn-sm"><?= __('nav.login') ?></a>
            <?php endif; ?>

            <!-- Mobile Toggle -->
            <button class="nav-toggle" @click="mobileOpen = !mobileOpen" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <!-- Mobile Nav -->
    <div class="mobile-nav" :class="{ 'active': mobileOpen }" @click.self="mobileOpen = false">
        <a href="<?= url('/') ?>" @click="mobileOpen = false"><?= __('nav.home') ?></a>
        <a href="<?= url('/destinations') ?>" @click="mobileOpen = false"><?= __('nav.destinations') ?></a>
        <a href="<?= url('/tours') ?>" @click="mobileOpen = false"><?= __('nav.tours') ?></a>
        <a href="<?= url('/trip-builder') ?>" @click="mobileOpen = false"><?= __('nav.trip_builder') ?></a>
        <a href="<?= url('/about') ?>" @click="mobileOpen = false"><?= __('nav.about') ?></a>
        <a href="<?= url('/blog') ?>" @click="mobileOpen = false"><?= __('nav.blog') ?></a>
        <a href="<?= url('/contact') ?>" @click="mobileOpen = false"><?= __('nav.contact') ?></a>
        <div class="gold-line" style="width:40px;margin:1rem 0;"></div>
        <?php if (Auth::check()): ?>
            <a href="<?= url('/account') ?>" @click="mobileOpen = false"><?= __('nav.account') ?></a>
            <a href="<?= url('/logout') ?>" @click="mobileOpen = false"><?= __('nav.logout') ?></a>
        <?php else: ?>
            <a href="<?= url('/login') ?>" @click="mobileOpen = false"><?= __('nav.login') ?></a>
            <a href="<?= url('/register') ?>" @click="mobileOpen = false"><?= __('nav.register') ?></a>
        <?php endif; ?>
    </div>
</nav>
