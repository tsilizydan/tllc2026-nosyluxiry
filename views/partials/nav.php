<!-- ═══════════════════════════════════════════════════════════
     NAVBAR — Self-contained Alpine component
     Features: sticky scroll effect, animated hamburger,
     offcanvas drawer with overlay, escape/outside-click close,
     body scroll lock, ARIA accessible
     ═══════════════════════════════════════════════════════════ -->
<header x-data="{
    scrolled: false,
    mobileOpen: false,
    open()  { this.mobileOpen = true;  document.body.style.overflow = 'hidden'; },
    close() { this.mobileOpen = false; document.body.style.overflow = ''; }
}" @scroll.window="scrolled = (window.scrollY > 50)"
   @keydown.escape.window="close()">

    <!-- ─── Top Bar ─── -->
    <nav class="navbar" :class="{ 'navbar--scrolled': scrolled }" role="navigation" aria-label="Main navigation">
        <div class="navbar__container">
            <!-- Brand -->
            <a href="<?= url('/') ?>" class="navbar__brand">
                <span class="navbar__brand-icon"><i class="fas fa-leaf"></i></span>
                <span class="navbar__brand-text">Nosy Luxury</span>
            </a>

            <!-- Desktop Links -->
            <ul class="navbar__links" role="menubar">
                <li role="none"><a href="<?= url('/') ?>" role="menuitem" class="<?= isActive('/') && ($_SERVER['REQUEST_URI'] ?? '') === '/' ? 'active' : '' ?>"><?= __('nav.home') ?></a></li>
                <li role="none"><a href="<?= url('/destinations') ?>" role="menuitem" class="<?= isActive('/destinations') ?>"><?= __('nav.destinations') ?></a></li>
                <li role="none"><a href="<?= url('/tours') ?>" role="menuitem" class="<?= isActive('/tours') ?>"><?= __('nav.tours') ?></a></li>
                <li role="none"><a href="<?= url('/trip-builder') ?>" role="menuitem" class="<?= isActive('/trip-builder') ?>"><?= __('nav.trip_builder') ?></a></li>
                <li role="none"><a href="<?= url('/about') ?>" role="menuitem" class="<?= isActive('/about') ?>"><?= __('nav.about') ?></a></li>
                <li role="none"><a href="<?= url('/blog') ?>" role="menuitem" class="<?= isActive('/blog') ?>"><?= __('nav.blog') ?></a></li>
                <li role="none"><a href="<?= url('/contact') ?>" role="menuitem" class="<?= isActive('/contact') ?>"><?= __('nav.contact') ?></a></li>
            </ul>

            <!-- Desktop Actions -->
            <div class="navbar__actions">
                <!-- Language Switcher -->
                <div class="navbar__lang">
                    <?php foreach (Language::available() as $code => $label): ?>
                        <a href="<?= url('/lang/' . $code) ?>" class="<?= Language::current() === $code ? 'active' : '' ?>"><?= strtoupper($code) ?></a>
                    <?php endforeach; ?>
                </div>

                <!-- Auth Button -->
                <?php if (Auth::check()): ?>
                    <a href="<?= url('/account') ?>" class="navbar__cta">
                        <i class="fas fa-user"></i>
                        <span><?= e(Session::get('user_name', '')) ?></span>
                    </a>
                <?php else: ?>
                    <a href="<?= url('/login') ?>" class="navbar__cta"><?= __('nav.login') ?></a>
                <?php endif; ?>

                <!-- Hamburger Toggle -->
                <button class="navbar__hamburger"
                        :class="{ 'is-active': mobileOpen }"
                        @click="mobileOpen ? close() : open()"
                        :aria-expanded="mobileOpen.toString()"
                        aria-controls="mobile-drawer"
                        aria-label="Toggle navigation">
                    <span class="navbar__hamburger-line"></span>
                    <span class="navbar__hamburger-line"></span>
                    <span class="navbar__hamburger-line"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- ─── Mobile Overlay ─── -->
    <div class="drawer-overlay"
         :class="{ 'is-visible': mobileOpen }"
         @click="close()"
         aria-hidden="true"></div>

    <!-- ─── Mobile Drawer ─── -->
    <aside id="mobile-drawer"
           class="drawer"
           :class="{ 'is-open': mobileOpen }"
           role="dialog"
           :aria-modal="mobileOpen.toString()"
           aria-label="Mobile navigation">

        <!-- Drawer Header -->
        <div class="drawer__header">
            <a href="<?= url('/') ?>" class="navbar__brand" @click="close()">
                <span class="navbar__brand-icon"><i class="fas fa-leaf"></i></span>
                <span class="navbar__brand-text">Nosy Luxury</span>
            </a>
            <button class="drawer__close" @click="close()" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Drawer Navigation -->
        <nav class="drawer__nav" aria-label="Mobile navigation links">
            <a href="<?= url('/') ?>" @click="close()" class="<?= isActive('/') && ($_SERVER['REQUEST_URI'] ?? '') === '/' ? 'active' : '' ?>">
                <i class="fas fa-home"></i><?= __('nav.home') ?>
            </a>
            <a href="<?= url('/destinations') ?>" @click="close()" class="<?= isActive('/destinations') ?>">
                <i class="fas fa-map-marker-alt"></i><?= __('nav.destinations') ?>
            </a>
            <a href="<?= url('/tours') ?>" @click="close()" class="<?= isActive('/tours') ?>">
                <i class="fas fa-compass"></i><?= __('nav.tours') ?>
            </a>
            <a href="<?= url('/trip-builder') ?>" @click="close()" class="<?= isActive('/trip-builder') ?>">
                <i class="fas fa-magic"></i><?= __('nav.trip_builder') ?>
            </a>
            <a href="<?= url('/about') ?>" @click="close()" class="<?= isActive('/about') ?>">
                <i class="fas fa-info-circle"></i><?= __('nav.about') ?>
            </a>
            <a href="<?= url('/blog') ?>" @click="close()" class="<?= isActive('/blog') ?>">
                <i class="fas fa-newspaper"></i><?= __('nav.blog') ?>
            </a>
            <a href="<?= url('/contact') ?>" @click="close()" class="<?= isActive('/contact') ?>">
                <i class="fas fa-envelope"></i><?= __('nav.contact') ?>
            </a>

            <div class="drawer__divider"></div>

            <!-- Auth -->
            <?php if (Auth::check()): ?>
                <a href="<?= url('/account') ?>" @click="close()"><i class="fas fa-user-circle"></i><?= __('nav.account') ?></a>
                <a href="<?= url('/logout') ?>" @click="close()"><i class="fas fa-sign-out-alt"></i><?= __('nav.logout') ?></a>
            <?php else: ?>
                <a href="<?= url('/login') ?>" @click="close()"><i class="fas fa-sign-in-alt"></i><?= __('nav.login') ?></a>
                <a href="<?= url('/register') ?>" @click="close()"><i class="fas fa-user-plus"></i><?= __('nav.register') ?></a>
            <?php endif; ?>
        </nav>

        <!-- Drawer Footer -->
        <div class="drawer__footer">
            <div class="drawer__lang">
                <?php foreach (Language::available() as $code => $label): ?>
                    <a href="<?= url('/lang/' . $code) ?>"
                       class="<?= Language::current() === $code ? 'active' : '' ?>"
                       @click="close()"><?= strtoupper($code) ?></a>
                <?php endforeach; ?>
            </div>
            <p class="drawer__copyright">© <?= date('Y') ?> Nosy Luxury</p>
        </div>
    </aside>
</header>
