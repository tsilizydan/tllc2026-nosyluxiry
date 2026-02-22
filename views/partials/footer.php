<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div>
                <div class="footer-brand">
                    <a href="<?= url('/') ?>" class="navbar-brand">
                        <div class="brand-icon"><i class="fas fa-leaf"></i></div>
                        <span>Nosy Luxury</span>
                    </a>
                </div>
                <p class="footer-about"><?= __('footer.about') ?></p>
                <div class="footer-social">
                    <?php if ($fb = setting('facebook_url')): ?><a href="<?= e($fb) ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                    <?php if ($ig = setting('instagram_url')): ?><a href="<?= e($ig) ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a><?php endif; ?>
                    <?php if ($yt = setting('youtube_url')): ?><a href="<?= e($yt) ?>" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a><?php endif; ?>
                    <?php if ($tw = setting('twitter_url')): ?><a href="<?= e($tw) ?>" target="_blank" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a><?php endif; ?>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h5><?= __('footer.quick_links') ?></h5>
                <ul>
                    <li><a href="<?= url('/tours') ?>"><?= __('nav.tours') ?></a></li>
                    <li><a href="<?= url('/trip-builder') ?>"><?= __('nav.trip_builder') ?></a></li>
                    <li><a href="<?= url('/about') ?>"><?= __('nav.about') ?></a></li>
                    <li><a href="<?= url('/blog') ?>"><?= __('nav.blog') ?></a></li>
                    <li><a href="<?= url('/contact') ?>"><?= __('nav.contact') ?></a></li>
                </ul>
            </div>

            <!-- Destinations -->
            <div class="footer-col">
                <h5><?= __('footer.destinations') ?></h5>
                <ul>
                    <li><a href="<?= url('/destinations/nosy-be') ?>">Nosy Be</a></li>
                    <li><a href="<?= url('/destinations/tsingy-de-bemaraha') ?>">Tsingy de Bemaraha</a></li>
                    <li><a href="<?= url('/destinations/isalo-national-park') ?>">Isalo National Park</a></li>
                    <li><a href="<?= url('/destinations/andasibe-mantadia') ?>">Andasibe-Mantadia</a></li>
                    <li><a href="<?= url('/destinations/avenue-of-baobabs') ?>">Avenue of the Baobabs</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="footer-col">
                <h5><?= __('footer.newsletter_title') ?></h5>
                <p class="footer-newsletter-desc" style="color:var(--color-gray-400);font-size:var(--text-sm);margin-bottom:var(--space-4);"><?= __('footer.newsletter_desc') ?></p>
                <form action="<?= url('/newsletter') ?>" method="POST" class="newsletter-form">
                    <?= csrf_field() ?>
                    <input type="email" name="email" placeholder="<?= __('footer.newsletter_placeholder') ?>" required>
                    <button type="submit" class="btn btn-primary btn-sm"><?= __('footer.subscribe') ?></button>
                </form>

                <!-- Support Links -->
                <h5 style="margin-top:var(--space-8);"><?= __('footer.support') ?></h5>
                <ul>
                    <li><a href="<?= url('/trust') ?>"><?= __('nav.trust') ?></a></li>
                    <li><a href="mailto:<?= e(setting('contact_email', CONTACT_EMAIL)) ?>"><?= e(setting('contact_email', CONTACT_EMAIL)) ?></a></li>
                    <li><a href="tel:<?= e(setting('contact_phone', CONTACT_PHONE)) ?>"><?= e(setting('contact_phone', CONTACT_PHONE)) ?></a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom -->
        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> <?= e(APP_NAME) ?>. <?= __('footer.rights') ?></span>
            <span><?= __('footer.powered_by') ?></span>
        </div>
    </div>
</footer>
