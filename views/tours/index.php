<!-- Tours Listing Page -->
<div class="page-header">
    <div class="container">
        <p class="subtitle"><?= __('tours.title') ?></p>
        <h1><?= __('tours.title') ?></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a> <span>/</span> <span><?= __('nav.tours') ?></span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <!-- Filter Bar -->
        <div class="filter-bar" x-data="{ active: '<?= e($filters['type'] ?? 'all') ?>' }">
            <a href="<?= url('/tours') ?>" class="filter-btn" :class="{ 'active': active === 'all' }" @click="active = 'all'"><?= __('tours.filter_all') ?></a>
            <a href="<?= url('/tours?type=adventure') ?>" class="filter-btn" :class="{ 'active': active === 'adventure' }" @click="active = 'adventure'"><?= __('tours.filter_adventure') ?></a>
            <a href="<?= url('/tours?type=wildlife') ?>" class="filter-btn" :class="{ 'active': active === 'wildlife' }" @click="active = 'wildlife'"><?= __('tours.filter_wildlife') ?></a>
            <a href="<?= url('/tours?type=cultural') ?>" class="filter-btn" :class="{ 'active': active === 'cultural' }" @click="active = 'cultural'"><?= __('tours.filter_culture') ?></a>
            <a href="<?= url('/tours?type=beach') ?>" class="filter-btn" :class="{ 'active': active === 'beach' }" @click="active = 'beach'"><?= __('tours.filter_beach') ?></a>
            <a href="<?= url('/tours?type=luxury') ?>" class="filter-btn" :class="{ 'active': active === 'luxury' }" @click="active = 'luxury'"><?= __('tours.filter_luxury') ?></a>
        </div>

        <!-- Tour Grid -->
        <?php if (!empty($tours)): ?>
        <div class="grid grid-3">
            <?php foreach ($tours as $tour): ?>
            <div class="card reveal">
                <div class="card-image">
                    <img src="<?= !empty($tour->image) ? upload_url($tour->image) : 'https://images.unsplash.com/photo-1504893524553-b855bce32c67?w=600&q=80' ?>" alt="<?= e($tour->name) ?>" loading="lazy">
                    <?php if ($tour->is_bestseller): ?><span class="card-badge">Bestseller</span>
                    <?php elseif ($tour->sale_price): ?><span class="card-badge" style="background:var(--color-coral)">Sale</span><?php endif; ?>
                    <button class="card-wishlist"><i class="far fa-heart"></i></button>
                </div>
                <div class="card-body">
                    <div class="card-meta">
                        <span><i class="fas fa-map-marker-alt"></i> <?= e($tour->destination_name ?? 'Madagascar') ?></span>
                        <span><i class="fas fa-clock"></i> <?= $tour->duration_days ?> <?= __('tours.days') ?></span>
                        <?php if ($tour->avg_rating > 0): ?><span><?= starRating($tour->avg_rating) ?></span><?php endif; ?>
                    </div>
                    <h3><a href="<?= url('/tours/' . $tour->slug) ?>"><?= e($tour->name) ?></a></h3>
                    <p><?= e(truncate($tour->short_description ?? '', 120)) ?></p>
                </div>
                <div class="card-footer">
                    <div class="card-price">
                        <span class="price-from"><?= __('tours.from') ?></span>
                        <span class="price-amount"><?= formatPrice($tour->sale_price ?? $tour->price) ?></span>
                        <?php if ($tour->sale_price): ?><span class="price-original"><?= formatPrice($tour->price) ?></span><?php endif; ?>
                    </div>
                    <a href="<?= url('/tours/' . $tour->slug) ?>" class="btn btn-outline btn-sm"><?= __('tours.view_details') ?></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <?php if ($i == $pagination['page']): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= url('/tours?page=' . $i . ($filters['type'] ? '&type=' . $filters['type'] : '')) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div style="text-align:center;padding:var(--space-16) 0;">
            <i class="fas fa-compass" style="font-size:3rem;color:var(--color-gold);margin-bottom:var(--space-4);display:block;"></i>
            <p style="color:var(--color-gray-400);"><?= __('tours.no_tours') ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>
