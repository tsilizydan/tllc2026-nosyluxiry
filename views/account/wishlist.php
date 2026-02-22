<!-- Account Wishlist -->
<div class="page-header">
    <div class="container">
        <h1>My <span class="text-gold">Wishlist</span></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><a href="<?= url('/account') ?>"><?= __('nav.account') ?></a><span>/</span><span>Wishlist</span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <?php if (!empty($tours)): ?>
        <div class="grid grid-3">
            <?php foreach ($tours as $tour): ?>
            <div class="card">
                <div class="card-image">
                    <img src="<?= !empty($tour->image) ? upload_url($tour->image) : 'https://images.unsplash.com/photo-1504893524553-b855bce32c67?w=600&q=80' ?>" alt="<?= e($tour->name) ?>" loading="lazy">
                    <form action="<?= url('/account/wishlist/toggle') ?>" method="POST" style="position:absolute;top:var(--space-3);right:var(--space-3);">
                        <?= csrf_field() ?>
                        <input type="hidden" name="tour_id" value="<?= $tour->id ?>">
                        <button type="submit" style="background:rgba(0,0,0,0.5);border:none;color:var(--color-coral);width:36px;height:36px;border-radius:var(--radius-full);cursor:pointer;font-size:var(--text-lg);">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <h3><a href="<?= url('/tours/' . $tour->slug) ?>"><?= e($tour->name) ?></a></h3>
                    <p><?= e(truncate($tour->short_description ?? '', 100)) ?></p>
                </div>
                <div class="card-footer">
                    <div class="card-price">
                        <span class="price-from">From</span>
                        <span class="price-amount"><?= formatPrice($tour->sale_price ?? $tour->price) ?></span>
                    </div>
                    <a href="<?= url('/tours/' . $tour->slug) ?>" class="btn btn-outline btn-sm">View</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:var(--space-16) 0;">
            <i class="fas fa-heart" style="font-size:3rem;color:var(--color-gold);margin-bottom:var(--space-4);display:block;"></i>
            <h3>Your Wishlist is Empty</h3>
            <p style="color:var(--color-gray-400);margin-top:var(--space-2);margin-bottom:var(--space-6);">Save your favorite tours and come back to them later.</p>
            <a href="<?= url('/tours') ?>" class="btn btn-primary">Discover Tours <i class="fas fa-arrow-right"></i></a>
        </div>
        <?php endif; ?>
    </div>
</section>
