<!-- ═══════════════════════════════════════════
     HOME PAGE — Nosy Luxury
     ═══════════════════════════════════════════ -->

<!-- ─── CINEMATIC HERO ─── -->
<section class="hero">
    <div class="hero-bg" style="background-image: url('https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=1920&q=80')"></div>
    <div class="hero-content">
        <p class="subtitle animate__animated animate__fadeInDown"><?= __('home.hero_title') ?></p>
        <h1 class="animate__animated animate__fadeIn">
            <?= __('home.hero_title') ?><br>
            <span class="highlight"><?= __('home.hero_subtitle') ?></span>
        </h1>
        <p class="hero-desc animate__animated animate__fadeInUp"><?= __('home.hero_desc') ?></p>
        <div class="hero-actions animate__animated animate__fadeInUp">
            <a href="<?= url('/tours') ?>" class="btn btn-primary btn-lg"><?= __('home.explore_btn') ?> <i class="fas fa-arrow-right"></i></a>
            <a href="<?= url('/trip-builder') ?>" class="btn btn-outline btn-lg"><?= __('home.plan_btn') ?></a>
        </div>
    </div>
    <a href="#why-us" class="hero-scroll"><i class="fas fa-chevron-down"></i></a>
</section>

<!-- ─── WHY NOSY LUXURY ─── -->
<section class="section section-dark" id="why-us">
    <div class="container">
        <div class="section-header reveal">
            <p class="subtitle"><?= __('home.why_title') ?></p>
            <h2><?= __('home.difference') ?></h2>
            <div class="gold-line"></div>
        </div>

        <div class="grid grid-3 reveal">
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-shield-halved"></i></div>
                <h4><?= __('home.trust_title') ?></h4>
                <p><?= __('home.trust_desc') ?></p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-gem"></i></div>
                <h4><?= __('home.luxury_title') ?></h4>
                <p><?= __('home.luxury_desc') ?></p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-feather-pointed"></i></div>
                <h4><?= __('home.authentic_title') ?></h4>
                <p><?= __('home.authentic_desc') ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ─── FEATURED TOURS ─── -->
<section class="section section-darker">
    <div class="container">
        <div class="section-header reveal">
            <p class="subtitle"><?= __('home.featured_title') ?></p>
            <h2><?= __('home.featured_subtitle') ?></h2>
            <div class="gold-line"></div>
        </div>

        <div class="grid grid-3 reveal">
            <?php if (!empty($featuredTours)): ?>
                <?php foreach ($featuredTours as $tour): ?>
                <div class="card">
                    <div class="card-image">
                        <img src="<?= !empty($tour->image) ? upload_url($tour->image) : 'https://images.unsplash.com/photo-1504893524553-b855bce32c67?w=600&q=80' ?>" alt="<?= e($tour->name) ?>" loading="lazy">
                        <?php if ($tour->is_bestseller): ?>
                            <span class="card-badge"><?= __('home.bestseller') ?></span>
                        <?php elseif ($tour->sale_price): ?>
                            <span class="card-badge" style="background:var(--color-coral)"><?= __('home.sale') ?></span>
                        <?php endif; ?>
                        <button class="card-wishlist" aria-label="Add to wishlist"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="card-meta">
                            <span><i class="fas fa-map-marker-alt"></i> <?= e($tour->destination_name ?? 'Madagascar') ?></span>
                            <span><i class="fas fa-clock"></i> <?= $tour->duration_days ?> <?= __('tours.days') ?></span>
                        </div>
                        <h3><a href="<?= url('/tours/' . $tour->slug) ?>"><?= e($tour->name) ?></a></h3>
                        <p><?= e(truncate($tour->short_description ?? '', 120)) ?></p>
                    </div>
                    <div class="card-footer">
                        <div class="card-price">
                            <span class="price-from"><?= __('tours.from') ?></span>
                            <span class="price-amount"><?= formatPrice($tour->sale_price ?? $tour->price) ?></span>
                            <?php if ($tour->sale_price): ?>
                                <span class="price-original"><?= formatPrice($tour->price) ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?= url('/tours/' . $tour->slug) ?>" class="btn btn-outline btn-sm"><?= __('tours.view_details') ?></a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Placeholder cards when DB is not connected -->
                <?php 
                $placeholders = [
                    ['Nosy Be Island Luxury Retreat', '7 days', '€2,590', 'beach', 'https://images.unsplash.com/photo-1505881502353-a1986add3762?w=600&q=80'],
                    ['Tsingy Explorer Adventure', '5 days', '€1,990', 'adventure', 'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=600&q=80'],
                    ['Rainforest & Lemur Encounter', '4 days', '€1,290', 'wildlife', 'https://images.unsplash.com/photo-1549366021-9f761d450615?w=600&q=80'],
                    ['Southern Madagascar Grand Tour', '12 days', '€3,190', 'adventure', 'https://images.unsplash.com/photo-1504893524553-b855bce32c67?w=600&q=80'],
                    ['Baobab & West Coast Explorer', '6 days', '€1,690', 'cultural', 'https://images.unsplash.com/photo-1590523741831-ab7e8b8f9c7f?w=600&q=80'],
                    ['Whale Watching & Sainte-Marie', '5 days', '€2,190', 'wildlife', 'https://images.unsplash.com/photo-1568430462989-44163eb1752f?w=600&q=80'],
                ];
                foreach ($placeholders as $i => $ph): ?>
                <div class="card">
                    <div class="card-image">
                        <img src="<?= $ph[4] ?>" alt="<?= $ph[0] ?>" loading="lazy">
                        <?php if ($i === 0): ?><span class="card-badge"><?= __('home.bestseller') ?></span><?php endif; ?>
                        <button class="card-wishlist" aria-label="Add to wishlist"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="card-meta">
                            <span><i class="fas fa-map-marker-alt"></i> Madagascar</span>
                            <span><i class="fas fa-clock"></i> <?= $ph[1] ?></span>
                        </div>
                        <h3><a href="<?= url('/tours') ?>"><?= $ph[0] ?></a></h3>
                        <p>An extraordinary journey through Madagascar's most captivating landscapes and wildlife.</p>
                    </div>
                    <div class="card-footer">
                        <div class="card-price">
                            <span class="price-from"><?= __('tours.from') ?></span>
                            <span class="price-amount"><?= $ph[2] ?></span>
                        </div>
                        <a href="<?= url('/tours') ?>" class="btn btn-outline btn-sm"><?= __('tours.view_details') ?></a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div style="text-align:center;margin-top:var(--space-10);">
            <a href="<?= url('/tours') ?>" class="btn btn-outline btn-lg"><?= __('home.explore_btn') ?> <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- ─── DESTINATIONS ─── -->
<section class="section section-dark">
    <div class="container">
        <div class="section-header reveal">
            <p class="subtitle"><?= __('home.destinations_title') ?></p>
            <h2><?= __('home.destinations_subtitle') ?></h2>
            <div class="gold-line"></div>
        </div>

        <div class="grid dest-grid reveal">
            <?php 
            $destImages = [
                'https://images.unsplash.com/photo-1505881502353-a1986add3762?w=800&q=80',
                'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=800&q=80',
                'https://images.unsplash.com/photo-1504893524553-b855bce32c67?w=800&q=80',
                'https://images.unsplash.com/photo-1590523741831-ab7e8b8f9c7f?w=800&q=80',
                'https://images.unsplash.com/photo-1549366021-9f761d450615?w=800&q=80',
                'https://images.unsplash.com/photo-1568430462989-44163eb1752f?w=800&q=80',
            ];
            $destNames = ['Nosy Be', 'Tsingy de Bemaraha', 'Isalo National Park', 'Avenue of the Baobabs', 'Andasibe-Mantadia', 'Île Sainte-Marie'];
            $destSlugs = ['nosy-be', 'tsingy-de-bemaraha', 'isalo-national-park', 'avenue-of-baobabs', 'andasibe-mantadia', 'ile-sainte-marie'];

            if (!empty($destinations)):
                foreach ($destinations as $i => $dest): ?>
                <a href="<?= url('/destinations/' . $dest->slug) ?>" class="destination-card" style="<?= $i === 0 ? 'grid-row: span 2;' : '' ?>">
                    <img src="<?= !empty($dest->image) ? upload_url($dest->image) : ($destImages[$i] ?? $destImages[0]) ?>" alt="<?= e($dest->name) ?>" loading="lazy">
                    <div class="destination-overlay">
                        <h3><?= e($dest->name) ?></h3>
                        <span class="tour-count"><?= $dest->tour_count ?? 0 ?> experience<?= ($dest->tour_count ?? 0) > 1 ? 's' : '' ?></span>
                    </div>
                </a>
                <?php endforeach;
            else:
                foreach ($destNames as $i => $name): ?>
                <a href="<?= url('/destinations/' . $destSlugs[$i]) ?>" class="destination-card" style="<?= $i === 0 ? 'grid-row: span 2;' : '' ?>">
                    <img src="<?= $destImages[$i] ?>" alt="<?= $name ?>" loading="lazy">
                    <div class="destination-overlay">
                        <h3><?= $name ?></h3>
                        <span class="tour-count"><?= __('home.discover_more') ?></span>
                    </div>
                </a>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

<!-- ─── STATS COUNTER ─── -->
<section class="section section-surface" style="padding: var(--space-16) 0;">
    <div class="container">
        <div class="grid grid-4 reveal" style="text-align:center;">
            <div x-data="counterAnimation(250)">
                <div class="text-gold" style="font-family:var(--font-heading);font-size:var(--text-4xl);font-weight:700;">
                    <span x-text="count">0</span>+
                </div>
                <p style="color:var(--color-gray-400);font-size:var(--text-sm);margin-top:var(--space-2);text-transform:uppercase;letter-spacing:0.1em;"><?= __('home.stat_travelers') ?></p>
            </div>
            <div x-data="counterAnimation(15)">
                <div class="text-gold" style="font-family:var(--font-heading);font-size:var(--text-4xl);font-weight:700;">
                    <span x-text="count">0</span>+
                </div>
                <p style="color:var(--color-gray-400);font-size:var(--text-sm);margin-top:var(--space-2);text-transform:uppercase;letter-spacing:0.1em;"><?= __('home.stat_experiences') ?></p>
            </div>
            <div x-data="counterAnimation(6)">
                <div class="text-gold" style="font-family:var(--font-heading);font-size:var(--text-4xl);font-weight:700;">
                    <span x-text="count">0</span>
                </div>
                <p style="color:var(--color-gray-400);font-size:var(--text-sm);margin-top:var(--space-2);text-transform:uppercase;letter-spacing:0.1em;"><?= __('home.stat_destinations') ?></p>
            </div>
            <div x-data="counterAnimation(98)">
                <div class="text-gold" style="font-family:var(--font-heading);font-size:var(--text-4xl);font-weight:700;">
                    <span x-text="count">0</span>%
                </div>
                <p style="color:var(--color-gray-400);font-size:var(--text-sm);margin-top:var(--space-2);text-transform:uppercase;letter-spacing:0.1em;"><?= __('home.stat_satisfaction') ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ─── TESTIMONIALS ─── -->
<section class="section section-dark">
    <div class="container">
        <div class="section-header reveal">
            <p class="subtitle"><?= __('home.testimonials_title') ?></p>
            <h2><?= __('home.testimonials_heading') ?></h2>
            <div class="gold-line"></div>
        </div>

        <div class="grid grid-2 reveal">
            <?php 
            $defaultTestimonials = [
                ['name' => 'Sophie Laurent', 'country' => 'France', 'rating' => 5, 'title' => 'Absolutely magical experience!', 'comment' => 'From the moment we arrived, everything was perfect. The eco-lodge was stunning, our guide was incredibly knowledgeable, and swimming with whale sharks was a life-changing experience.', 'tour' => 'Nosy Be Island Retreat'],
                ['name' => 'James Mitchell', 'country' => 'United Kingdom', 'rating' => 5, 'title' => 'Best holiday of our lives', 'comment' => 'My wife and I have traveled extensively, but this trip was something special. The attention to detail, the luxury touches, and the authentic cultural experiences made this truly unforgettable.', 'tour' => 'Southern Grand Tour'],
                ['name' => 'Marco Rossi', 'country' => 'Italy', 'rating' => 5, 'title' => 'Adventure of a lifetime', 'comment' => 'The Tsingy was absolutely breathtaking. I was nervous about the via ferrata but our guide was fantastic. This is genuine adventure travel at its finest.', 'tour' => 'Tsingy Explorer'],
                ['name' => 'Elena Petrova', 'country' => 'Russia', 'rating' => 5, 'title' => 'Unforgettable sunsets', 'comment' => 'The Avenue of the Baobabs at sunset was the most beautiful thing I have ever seen. This tour perfectly captures the magic of western Madagascar.', 'tour' => 'Baobab Explorer'],
            ];

            $reviews = !empty($testimonials) ? $testimonials : [];
            if (empty($reviews)):
                foreach ($defaultTestimonials as $t): ?>
                <div class="testimonial-card">
                    <div style="margin-bottom:var(--space-4);"><?= starRating($t['rating']) ?></div>
                    <p class="testimonial-text"><?= $t['comment'] ?></p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar"><?= strtoupper(substr($t['name'], 0, 1)) ?></div>
                        <div class="testimonial-info">
                            <strong><?= $t['name'] ?></strong>
                            <span><?= $t['country'] ?> — <?= $t['tour'] ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach;
            else:
                foreach ($reviews as $review): ?>
                <div class="testimonial-card">
                    <div style="margin-bottom:var(--space-4);"><?= starRating($review->rating) ?></div>
                    <p class="testimonial-text"><?= e($review->comment) ?></p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar"><?= strtoupper(substr($review->reviewer_name, 0, 1)) ?></div>
                        <div class="testimonial-info">
                            <strong><?= e($review->reviewer_name) ?></strong>
                            <span><?= e($review->reviewer_country ?? '') ?> — <?= e($review->tour_name ?? '') ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

<!-- ─── CTA ─── -->
<section class="cta-section section-surface reveal">
    <div class="container" style="position:relative;z-index:1;">
        <p class="subtitle"><?= __('home.cta_title') ?></p>
        <h2><?= __('home.cta_title') ?></h2>
        <div class="gold-line"></div>
        <p><?= __('home.cta_desc') ?></p>
        <div style="display:flex;gap:var(--space-4);justify-content:center;flex-wrap:wrap;">
            <a href="<?= url('/trip-builder') ?>" class="btn btn-primary btn-lg"><?= __('home.cta_btn') ?> <i class="fas fa-arrow-right"></i></a>
            <a href="<?= url('/contact') ?>" class="btn btn-outline btn-lg"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        </div>
    </div>
</section>
