<!-- Destination Detail Page -->
<div class="page-header" style="padding-bottom:var(--space-8);">
    <div class="container">
        <div class="breadcrumb" style="margin-bottom:var(--space-4);">
            <a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span>
            <a href="<?= url('/destinations') ?>"><?= __('nav.destinations') ?></a><span>/</span>
            <span><?= e($destination->name) ?></span>
        </div>
        <p class="subtitle"><?= ucfirst(e($destination->region ?? '')) ?> Madagascar</p>
        <h1><?= e($destination->name) ?></h1>
    </div>
</div>

<section class="section section-darker" style="padding-top:var(--space-4);">
    <div class="container">
        <!-- Hero Image -->
        <?php $destImg = !empty($destination->image) ? upload_url($destination->image) : 'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=1200&q=80'; ?>
        <div style="border-radius:var(--radius-lg);overflow:hidden;aspect-ratio:21/9;margin-bottom:var(--space-10);">
            <img src="<?= $destImg ?>" alt="<?= e($destination->name) ?>" style="width:100%;height:100%;object-fit:cover;">
        </div>

        <!-- Content -->
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:var(--space-10);">
            <div>
                <h2 style="margin-bottom:var(--space-4);">About <span class="text-gold"><?= e($destination->name) ?></span></h2>
                <div style="color:var(--color-gray-300);line-height:1.9;white-space:pre-line;"><?= nl2br(e($destination->description ?? 'An extraordinary destination in Madagascar.')) ?></div>

                <!-- Highlights -->
                <?php if (!empty($destination->highlights)): ?>
                <div style="margin-top:var(--space-8);">
                    <h3 style="margin-bottom:var(--space-4);">Highlights</h3>
                    <?php foreach (json_decode($destination->highlights, true) as $h): ?>
                    <p style="display:flex;gap:var(--space-3);align-items:center;margin-bottom:var(--space-2);color:var(--color-gray-300);font-size:var(--text-sm);">
                        <i class="fas fa-star text-gold"></i> <?= e($h) ?>
                    </p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Quick Facts Sidebar -->
            <div style="position:sticky;top:100px;">
                <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);">
                    <h4 style="margin-bottom:var(--space-4);">Quick Facts</h4>
                    <?php
                    $facts = [
                        ['icon' => 'fa-map-marker-alt', 'label' => 'Region', 'value' => ucfirst($destination->region ?? 'Madagascar')],
                        ['icon' => 'fa-sun', 'label' => 'Best Time', 'value' => $destination->best_time ?? 'Apr - Nov'],
                        ['icon' => 'fa-temperatures-full', 'label' => 'Climate', 'value' => $destination->climate ?? 'Tropical'],
                    ];
                    foreach ($facts as $f): ?>
                    <div style="display:flex;gap:var(--space-3);margin-bottom:var(--space-4);align-items:center;">
                        <div style="width:36px;height:36px;background:var(--color-gold-muted);display:flex;align-items:center;justify-content:center;border-radius:var(--radius-md);color:var(--color-gold);flex-shrink:0;">
                            <i class="fas <?= $f['icon'] ?>"></i>
                        </div>
                        <div>
                            <div style="font-size:var(--text-xs);color:var(--color-gray-500);text-transform:uppercase;letter-spacing:0.05em;"><?= $f['label'] ?></div>
                            <div style="font-size:var(--text-sm);color:var(--color-white);"><?= e($f['value']) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Available Tours -->
        <?php if (!empty($tours)): ?>
        <div style="margin-top:var(--space-16);">
            <div class="section-header">
                <p class="subtitle">Experiences</p>
                <h2>Tours in <span class="text-gold"><?= e($destination->name) ?></span></h2>
                <div class="gold-line"></div>
            </div>
            <div class="grid grid-3">
                <?php foreach ($tours as $tour): ?>
                <div class="card">
                    <div class="card-image">
                        <img src="<?= !empty($tour->image) ? upload_url($tour->image) : 'https://images.unsplash.com/photo-1504893524553-b855bce32c67?w=600&q=80' ?>" alt="<?= e($tour->name) ?>" loading="lazy">
                    </div>
                    <div class="card-body">
                        <div class="card-meta">
                            <span><i class="fas fa-clock"></i> <?= $tour->duration_days ?> days</span>
                        </div>
                        <h3><a href="<?= url('/tours/' . $tour->slug) ?>"><?= e($tour->name) ?></a></h3>
                        <p><?= e(truncate($tour->short_description ?? '', 100)) ?></p>
                    </div>
                    <div class="card-footer">
                        <div class="card-price">
                            <span class="price-from">From</span>
                            <span class="price-amount"><?= formatPrice($tour->sale_price ?? $tour->price) ?></span>
                        </div>
                        <a href="<?= url('/tours/' . $tour->slug) ?>" class="btn btn-outline btn-sm">View Details</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
