<!-- Leave a Review -->
<div class="page-header">
    <div class="container">
        <h1>Leave a <span class="text-gold">Review</span></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><a href="<?= url('/account') ?>"><?= __('nav.account') ?></a><span>/</span><a href="<?= url('/account/bookings') ?>">Bookings</a><span>/</span><span>Review</span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <div style="display:grid;grid-template-columns:250px 1fr;gap:var(--space-8);">
            <?php include VIEWS_PATH . '/account/_sidebar.php'; ?>

            <div style="max-width:600px;">
                <!-- Booking Context -->
                <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);margin-bottom:var(--space-6);">
                    <div style="display:flex;gap:var(--space-4);align-items:center;">
                        <?php if (!empty($booking->tour_image)): ?>
                        <img src="<?= upload_url($booking->tour_image) ?>" style="width:80px;height:60px;object-fit:cover;border-radius:var(--radius-md);">
                        <?php endif; ?>
                        <div>
                            <strong style="color:var(--color-white);"><?= e($booking->tour_name ?? 'Tour') ?></strong>
                            <div style="font-size:var(--text-sm);color:var(--color-gray-400);">Ref: <?= e($booking->reference) ?> • <?= date('M d, Y', strtotime($booking->travel_date ?? $booking->created_at)) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Review Form -->
                <form method="POST" action="<?= url('/account/bookings/' . $booking->reference . '/review') ?>">
                    <?= csrf_field() ?>
                    <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);">
                        <h3 style="margin-bottom:var(--space-5);"><i class="fas fa-star text-gold"></i> Your Review</h3>

                        <!-- Star Rating -->
                        <div class="form-group" x-data="{ rating: 5 }">
                            <label class="form-label">Rating *</label>
                            <div style="display:flex;gap:var(--space-2);font-size:var(--text-2xl);">
                                <template x-for="star in [1,2,3,4,5]">
                                    <i class="fas fa-star" :style="star <= rating ? 'color:var(--color-gold);cursor:pointer;' : 'color:var(--color-gray-600);cursor:pointer;'" @click="rating = star"></i>
                                </template>
                            </div>
                            <input type="hidden" name="rating" :value="rating">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Review Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Summarize your experience..." maxlength="255">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Your Review *</label>
                            <textarea name="comment" class="form-control" rows="5" required placeholder="Tell us about your experience. What did you enjoy? Any tips for future travelers?"></textarea>
                        </div>

                        <div style="display:flex;gap:var(--space-3);">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit Review</button>
                            <a href="<?= url('/account/bookings') ?>" class="btn btn-outline">Cancel</a>
                        </div>

                        <p style="font-size:var(--text-xs);color:var(--color-gray-500);margin-top:var(--space-4);">
                            <i class="fas fa-info-circle"></i> Your review will be published after it's approved by our team.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
