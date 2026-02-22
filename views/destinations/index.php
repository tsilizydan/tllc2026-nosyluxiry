<!-- Destinations Page -->
<div class="page-header">
    <div class="container">
        <p class="subtitle"><?= __('home.destinations_title') ?></p>
        <h1><?= __('home.destinations_subtitle') ?></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><span><?= __('nav.destinations') ?></span></div>
    </div>
</div>

<!-- Interactive Map -->
<section class="section section-surface">
    <div class="container">
        <div class="section-header reveal">
            <h2>Explore the <span class="text-gold">Island</span></h2>
            <p>Madagascar — the world's fourth-largest island — offers unparalleled biodiversity and landscapes.</p>
            <div class="gold-line"></div>
        </div>

        <!-- SVG Map of Madagascar -->
        <div class="reveal" style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-10);align-items:center;">
            <div style="text-align:center;">
                <svg viewBox="0 0 400 600" style="max-width:350px;margin:0 auto;">
                    <!-- Simplified Madagascar outline -->
                    <path d="M200 50 C250 80, 300 150, 310 200 C320 250, 310 300, 300 350 C290 400, 270 450, 250 480 C230 510, 200 540, 180 550 C160 540, 140 500, 130 460 C120 420, 110 380, 110 340 C110 300, 120 260, 130 220 C140 180, 160 120, 200 50Z" 
                        fill="var(--color-dark-card)" stroke="var(--color-gold)" stroke-width="2"/>
                    <!-- Region markers -->
                    <circle cx="220" cy="120" r="8" fill="var(--color-gold)" class="map-marker" style="cursor:pointer;"/>
                    <text x="240" y="125" fill="var(--color-ivory)" font-size="12" font-family="Inter">North</text>
                    <circle cx="280" cy="230" r="8" fill="var(--color-gold)" class="map-marker" style="cursor:pointer;"/>
                    <text x="290" y="235" fill="var(--color-ivory)" font-size="12" font-family="Inter">East</text>
                    <circle cx="170" cy="280" r="8" fill="var(--color-gold)" class="map-marker" style="cursor:pointer;"/>
                    <text x="120" y="285" fill="var(--color-ivory)" font-size="12" font-family="Inter">Tsingy</text>
                    <circle cx="160" cy="370" r="8" fill="var(--color-gold)" class="map-marker" style="cursor:pointer;"/>
                    <text x="110" y="375" fill="var(--color-ivory)" font-size="12" font-family="Inter">West</text>
                    <circle cx="230" cy="430" r="8" fill="var(--color-gold)" class="map-marker" style="cursor:pointer;"/>
                    <text x="250" y="435" fill="var(--color-ivory)" font-size="12" font-family="Inter">South</text>
                </svg>
            </div>
            <div>
                <h3 style="margin-bottom:var(--space-4);">Five Extraordinary <span class="text-gold">Regions</span></h3>
                <p style="color:var(--color-gray-400);margin-bottom:var(--space-6);line-height:1.8;">From the turquoise waters of the north to the dramatic formations of the Tsingy, each region of Madagascar offers completely unique landscapes, wildlife, and cultural experiences.</p>
                <div style="display:flex;flex-direction:column;gap:var(--space-4);">
                    <?php
                    $regions = [
                        ['north', 'Northern Madagascar', 'Tropical islands, beaches, and diving'],
                        ['east', 'Eastern Madagascar', 'Lush rainforests and lemur encounters'],
                        ['tsingy', 'Tsingy Region', 'UNESCO stone forests and adventure'],
                        ['west', 'Western Madagascar', 'Baobabs, rivers, and sunsets'],
                        ['south', 'Southern Madagascar', 'Canyons, desert, and unique flora'],
                    ];
                    foreach ($regions as $r): ?>
                    <a href="#region-<?= $r[0] ?>" style="display:flex;align-items:center;gap:var(--space-4);padding:var(--space-3);background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-md);transition:all 0.3s;">
                        <i class="fas fa-compass text-gold"></i>
                        <div>
                            <strong style="color:var(--color-white);"><?= $r[1] ?></strong>
                            <span style="color:var(--color-gray-400);font-size:var(--text-sm);display:block;"><?= $r[2] ?></span>
                        </div>
                        <i class="fas fa-arrow-right text-gold" style="margin-left:auto;"></i>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Destination Cards Grid -->
<section class="section section-darker">
    <div class="container">
        <div class="section-header reveal">
            <p class="subtitle">All Destinations</p>
            <h2>Choose Your <span class="text-gold">Adventure</span></h2>
            <div class="gold-line"></div>
        </div>

        <div class="grid grid-3 reveal">
            <?php 
            $fallbackImages = [
                'https://images.unsplash.com/photo-1505881502353-a1986add3762?w=600&q=80',
                'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=600&q=80',
                'https://images.unsplash.com/photo-1504893524553-b855bce32c67?w=600&q=80',
                'https://images.unsplash.com/photo-1590523741831-ab7e8b8f9c7f?w=600&q=80',
                'https://images.unsplash.com/photo-1549366021-9f761d450615?w=600&q=80',
                'https://images.unsplash.com/photo-1568430462989-44163eb1752f?w=600&q=80',
            ];
            if (!empty($destinations)):
                foreach ($destinations as $i => $dest): ?>
                <a href="<?= url('/destinations/' . $dest->slug) ?>" class="destination-card" id="region-<?= $dest->region ?>">
                    <img src="<?= !empty($dest->image) ? upload_url($dest->image) : ($fallbackImages[$i % count($fallbackImages)]) ?>" alt="<?= e($dest->name) ?>" loading="lazy">
                    <div class="destination-overlay">
                        <span style="font-size:var(--text-xs);color:var(--color-gold);text-transform:uppercase;letter-spacing:0.1em;"><?= ucfirst($dest->region) ?> Madagascar</span>
                        <h3><?= e($dest->name) ?></h3>
                        <span class="tour-count"><?= $dest->tour_count ?? 0 ?> experience<?= ($dest->tour_count ?? 0) !== 1 ? 's' : '' ?></span>
                    </div>
                </a>
                <?php endforeach;
            else: ?>
            <p style="grid-column:span 3;text-align:center;color:var(--color-gray-400);">Destinations coming soon...</p>
            <?php endif; ?>
        </div>
    </div>
</section>
