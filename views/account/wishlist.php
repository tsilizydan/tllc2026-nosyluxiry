<!-- Account Wishlist -->
<div class="page-header">
    <div class="container">
        <h1>My <span class="text-gold">Wishlist</span></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><a href="<?= url('/account') ?>"><?= __('nav.account') ?></a><span>/</span><span>Wishlist</span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <div style="display:grid;grid-template-columns:250px 1fr;gap:var(--space-8);">
            <!-- Sidebar -->
            <?php include VIEWS_PATH . '/account/_sidebar.php'; ?>

            <!-- Main Content -->
            <div>
                <?php if (!empty($tours)): ?>
                <div class="tour-grid" style="grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));">
                    <?php foreach ($tours as $tour): ?>
                    <div class="tour-card" style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);overflow:hidden;">
                        <div style="position:relative;">
                            <img src="<?= !empty($tour->image) ? upload_url($tour->image) : asset('/images/tour-placeholder.jpg') ?>" alt="<?= e($tour->name) ?>" style="width:100%;height:180px;object-fit:cover;">
                            <!-- Remove from wishlist -->
                            <button onclick="toggleWishlist(<?= $tour->id ?>, this)" style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,0.6);border:none;width:36px;height:36px;border-radius:var(--radius-full);color:#f87171;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        <div style="padding:var(--space-4);">
                            <h4 style="margin-bottom:var(--space-2);font-size:var(--text-base);">
                                <a href="<?= url('/tours/' . $tour->slug) ?>" style="color:var(--color-white);"><?= e($tour->name) ?></a>
                            </h4>
                            <?php if (!empty($tour->location)): ?>
                            <p style="font-size:var(--text-sm);color:var(--color-gray-400);margin-bottom:var(--space-3);">
                                <i class="fas fa-map-marker-alt text-gold"></i> <?= e($tour->location) ?>
                            </p>
                            <?php endif; ?>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="color:var(--color-gold);font-weight:700;font-family:var(--font-heading);font-size:var(--text-lg);">
                                    <?php if ($tour->sale_price): ?>
                                        <?= formatPrice($tour->sale_price) ?>
                                        <del style="font-size:var(--text-sm);color:var(--color-gray-500);font-weight:400;"><?= formatPrice($tour->price) ?></del>
                                    <?php else: ?>
                                        <?= formatPrice($tour->price) ?>
                                    <?php endif; ?>
                                </span>
                                <a href="<?= url('/book/' . $tour->id) ?>" class="btn btn-primary btn-sm"><i class="fas fa-calendar-check"></i> Book</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div style="text-align:center;padding:var(--space-16) 0;">
                    <i class="fas fa-heart" style="font-size:3rem;color:var(--color-gold);margin-bottom:var(--space-4);display:block;opacity:0.5;"></i>
                    <h3>Your Wishlist is Empty</h3>
                    <p style="color:var(--color-gray-400);margin-top:var(--space-2);margin-bottom:var(--space-6);">Save tours you're interested in and come back later.</p>
                    <a href="<?= url('/tours') ?>" class="btn btn-primary">Discover Tours <i class="fas fa-arrow-right"></i></a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
function toggleWishlist(tourId, btn) {
    fetch('<?= url('/account/wishlist/toggle') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': '<?= Session::getCsrfToken() ?>'},
        body: '<?= CSRF_TOKEN_NAME ?>=<?= Session::getCsrfToken() ?>&tour_id=' + tourId
    })
    .then(r => r.json())
    .then(data => {
        if (!data.wishlisted) {
            btn.closest('.tour-card').style.transition = 'opacity 0.3s';
            btn.closest('.tour-card').style.opacity = '0';
            setTimeout(() => btn.closest('.tour-card').remove(), 300);
        }
    });
}
</script>
