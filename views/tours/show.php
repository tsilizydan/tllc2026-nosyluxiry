<!-- Tour Detail Page -->
<div class="page-header" style="padding-bottom:var(--space-8);">
    <div class="container">
        <div class="breadcrumb" style="margin-bottom:var(--space-4);">
            <a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span>
            <a href="<?= url('/tours') ?>"><?= __('nav.tours') ?></a><span>/</span>
            <span><?= e($tour->name) ?></span>
        </div>
        <h1><?= e($tour->name) ?></h1>
        <?php if ($tour->subtitle): ?><p style="color:var(--color-gold);font-family:var(--font-accent);font-size:var(--text-xl);margin-top:var(--space-2);"><?= e($tour->subtitle) ?></p><?php endif; ?>
        <div style="display:flex;gap:var(--space-6);justify-content:center;margin-top:var(--space-4);color:var(--color-gray-400);font-size:var(--text-sm);">
            <span><i class="fas fa-map-marker-alt text-gold"></i> <?= e($tour->destination_name ?? 'Madagascar') ?></span>
            <span><i class="fas fa-clock text-gold"></i> <?= $tour->duration_days ?> <?= __('tours.days') ?></span>
            <span><i class="fas fa-users text-gold"></i> Max <?= $tour->group_size_max ?> guests</span>
            <span><i class="fas fa-signal text-gold"></i> <?= ucfirst($tour->difficulty ?? 'moderate') ?></span>
            <?php if ($tour->avg_rating > 0): ?><span><?= starRating($tour->avg_rating) ?> (<?= $tour->total_reviews ?>)</span><?php endif; ?>
        </div>
    </div>
</div>

<section class="section section-darker" style="padding-top:var(--space-8);">
    <div class="container">
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:var(--space-10);align-items:start;">
            <!-- Main Content -->
            <div>
                <!-- Gallery -->
                <div class="gallery-grid reveal" style="margin-bottom:var(--space-10);">
                    <?php 
                    $galleryImages = json_decode($tour->gallery ?? '[]', true);
                    $defaultGallery = [
                        'https://images.unsplash.com/photo-1504893524553-b855bce32c67?w=800&q=80',
                        'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=600&q=80',
                        'https://images.unsplash.com/photo-1549366021-9f761d450615?w=600&q=80',
                        'https://images.unsplash.com/photo-1505881502353-a1986add3762?w=600&q=80',
                        'https://images.unsplash.com/photo-1590523741831-ab7e8b8f9c7f?w=600&q=80',
                    ];
                    $images = !empty($galleryImages) ? $galleryImages : $defaultGallery;
                    foreach (array_slice($images, 0, 5) as $img): ?>
                    <div class="gallery-item">
                        <img src="<?= e($img) ?>" alt="<?= e($tour->name) ?>" loading="lazy">
                        <div class="gallery-overlay"><i class="fas fa-expand"></i></div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Description -->
                <div class="reveal" style="margin-bottom:var(--space-10);">
                    <h2 style="margin-bottom:var(--space-4);">About This Experience</h2>
                    <div style="color:var(--color-gray-300);line-height:1.9;white-space:pre-line;"><?= nl2br(e($tour->description)) ?></div>
                </div>

                <!-- Highlights -->
                <?php if (!empty($highlights)): ?>
                <div class="reveal" style="margin-bottom:var(--space-10);">
                    <h2 style="margin-bottom:var(--space-6);">Highlights</h2>
                    <div class="grid grid-2" style="gap:var(--space-4);">
                        <?php foreach ($highlights as $h): ?>
                        <div style="display:flex;gap:var(--space-3);align-items:flex-start;">
                            <i class="fas fa-star text-gold" style="margin-top:4px;"></i>
                            <span style="color:var(--color-gray-300);"><?= e($h) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Itinerary -->
                <?php if (!empty($itinerary)): ?>
                <div class="reveal" style="margin-bottom:var(--space-10);">
                    <h2 style="margin-bottom:var(--space-6);">Day-by-Day Itinerary</h2>
                    <div class="timeline">
                        <?php foreach ($itinerary as $day): ?>
                        <div class="timeline-item">
                            <span class="day-label">Day <?= $day->day_number ?></span>
                            <h4><?= e($day->title) ?></h4>
                            <p><?= e($day->description) ?></p>
                            <?php if ($day->meals): ?><p style="margin-top:var(--space-2);color:var(--color-gold);font-size:var(--text-sm);"><i class="fas fa-utensils"></i> <?= e($day->meals) ?></p><?php endif; ?>
                            <?php if ($day->accommodation): ?><p style="color:var(--color-gray-500);font-size:var(--text-sm);"><i class="fas fa-bed"></i> <?= e($day->accommodation) ?></p><?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Included / Excluded -->
                <div class="grid grid-2 reveal" style="margin-bottom:var(--space-10);">
                    <?php if (!empty($included)): ?>
                    <div style="background:var(--color-dark-card);padding:var(--space-6);border-radius:var(--radius-lg);border:1px solid var(--color-dark-border);">
                        <h4 style="color:var(--color-emerald-light);margin-bottom:var(--space-4);"><i class="fas fa-check-circle"></i> What's Included</h4>
                        <?php foreach ($included as $item): ?>
                        <p style="color:var(--color-gray-300);font-size:var(--text-sm);margin-bottom:var(--space-2);"><i class="fas fa-check" style="color:var(--color-emerald-light);margin-right:var(--space-2);"></i><?= e($item) ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($excluded)): ?>
                    <div style="background:var(--color-dark-card);padding:var(--space-6);border-radius:var(--radius-lg);border:1px solid var(--color-dark-border);">
                        <h4 style="color:var(--color-coral);margin-bottom:var(--space-4);"><i class="fas fa-times-circle"></i> Not Included</h4>
                        <?php foreach ($excluded as $item): ?>
                        <p style="color:var(--color-gray-300);font-size:var(--text-sm);margin-bottom:var(--space-2);"><i class="fas fa-times" style="color:var(--color-coral);margin-right:var(--space-2);"></i><?= e($item) ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Reviews -->
                <?php if (!empty($reviews)): ?>
                <div class="reveal">
                    <h2 style="margin-bottom:var(--space-6);">Guest Reviews (<?= count($reviews) ?>)</h2>
                    <?php foreach ($reviews as $review): ?>
                    <div class="testimonial-card" style="margin-bottom:var(--space-4);">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-3);">
                            <?= starRating($review->rating) ?>
                            <?php if ($review->is_verified): ?><span class="badge badge-success"><i class="fas fa-check"></i> Verified</span><?php endif; ?>
                        </div>
                        <?php if ($review->title): ?><h5 style="margin-bottom:var(--space-2);"><?= e($review->title) ?></h5><?php endif; ?>
                        <p style="color:var(--color-gray-300);font-size:var(--text-sm);line-height:1.7;margin-bottom:var(--space-3);"><?= e($review->comment) ?></p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar"><?= strtoupper(substr($review->reviewer_name, 0, 1)) ?></div>
                            <div class="testimonial-info">
                                <strong><?= e($review->reviewer_name) ?></strong>
                                <span><?= e($review->reviewer_country ?? '') ?> · <?= timeAgo($review->created_at) ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar — Booking Module -->
            <div style="position:sticky;top:100px;">
                <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-8);">
                    <div style="text-align:center;margin-bottom:var(--space-6);">
                        <span style="color:var(--color-gray-400);font-size:var(--text-sm);"><?= __('tours.from') ?></span>
                        <div style="font-family:var(--font-heading);font-size:var(--text-4xl);color:var(--color-gold);font-weight:700;">
                            <?= formatPrice($tour->sale_price ?? $tour->price) ?>
                        </div>
                        <?php if ($tour->sale_price): ?>
                        <span style="color:var(--color-gray-500);text-decoration:line-through;"><?= formatPrice($tour->price) ?></span>
                        <?php endif; ?>
                        <span style="color:var(--color-gray-400);font-size:var(--text-sm);"><?= __('tours.per_person') ?></span>
                    </div>

                    <form action="<?= url('/booking/' . $tour->id) ?>" method="POST" x-data="{ guests: 1 }">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label class="form-label"><?= __('booking.date') ?></label>
                            <input type="date" name="travel_date" class="form-control" required min="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?= __('booking.guests') ?></label>
                            <select name="guests" class="form-control" x-model="guests">
                                <?php for ($i = 1; $i <= ($tour->group_size_max ?? 12); $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?> guest<?= $i > 1 ? 's' : '' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div style="background:var(--color-dark-surface);padding:var(--space-4);border-radius:var(--radius-md);margin-bottom:var(--space-6);">
                            <div style="display:flex;justify-content:space-between;color:var(--color-gray-300);font-size:var(--text-sm);margin-bottom:var(--space-2);">
                                <span><?= formatPrice($tour->sale_price ?? $tour->price) ?> × <span x-text="guests"></span> guest(s)</span>
                                <span x-text="'€' + (<?= $tour->sale_price ?? $tour->price ?> * guests).toFixed(2)"></span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-weight:700;color:var(--color-gold);font-size:var(--text-lg);border-top:1px solid var(--color-dark-border);padding-top:var(--space-2);">
                                <span><?= __('booking.total') ?></span>
                                <span x-text="'€' + (<?= $tour->sale_price ?? $tour->price ?> * guests).toFixed(2)"></span>
                            </div>
                        </div>

                        <a href="<?= url('/booking/' . $tour->id) ?>" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;"><?= __('tours.book_now') ?> <i class="fas fa-arrow-right"></i></a>
                    </form>

                    <div style="margin-top:var(--space-6);text-align:center;">
                        <p style="color:var(--color-gray-500);font-size:var(--text-xs);"><i class="fas fa-shield-halved text-gold"></i> Free cancellation up to 14 days before</p>
                        <p style="color:var(--color-gray-500);font-size:var(--text-xs);margin-top:var(--space-2);"><i class="fas fa-lock text-gold"></i> Secure payment · Instant confirmation</p>
                    </div>
                </div>

                <!-- WhatsApp CTA -->
                <a href="https://wa.me/<?= e(setting('whatsapp_number', WHATSAPP_NUMBER)) ?>?text=Hello! I'm interested in: <?= urlencode($tour->name) ?>" target="_blank" style="display:flex;align-items:center;justify-content:center;gap:var(--space-2);margin-top:var(--space-4);padding:var(--space-3);background:#25D366;border-radius:var(--radius-md);color:white;font-weight:600;font-size:var(--text-sm);">
                    <i class="fab fa-whatsapp"></i> Ask a Question on WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>
