<!-- Blog Post Detail -->
<div class="page-header" style="padding-bottom:var(--space-8);">
    <div class="container">
        <div class="breadcrumb" style="margin-bottom:var(--space-4);">
            <a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span>
            <a href="<?= url('/blog') ?>"><?= __('nav.blog') ?></a><span>/</span>
            <span><?= e(truncate($post->title, 40)) ?></span>
        </div>
        <?php if (!empty($post->category_name)): ?><span class="badge badge-warning" style="margin-bottom:var(--space-3);"><?= e($post->category_name) ?></span><?php endif; ?>
        <h1 style="max-width:800px;margin:0 auto;"><?= e($post->title) ?></h1>
        <div style="display:flex;gap:var(--space-6);justify-content:center;margin-top:var(--space-4);color:var(--color-gray-400);font-size:var(--text-sm);">
            <span><i class="fas fa-user text-gold"></i> <?= e($post->author_name ?? 'Nosy Luxury') ?></span>
            <span><i class="fas fa-calendar text-gold"></i> <?= date('F d, Y', strtotime($post->published_at ?? $post->created_at)) ?></span>
            <span><i class="fas fa-eye text-gold"></i> <?= $post->views ?? 0 ?> views</span>
        </div>
    </div>
</div>

<section class="section section-darker" style="padding-top:var(--space-4);">
    <div class="container container-narrow">
        <?php if (!empty($post->featured_image)): ?>
        <div style="border-radius:var(--radius-lg);overflow:hidden;margin-bottom:var(--space-10);">
            <img src="<?= upload_url($post->featured_image) ?>" alt="<?= e($post->title) ?>" style="width:100%;">
        </div>
        <?php endif; ?>

        <article class="blog-content" style="color:var(--color-gray-300);line-height:2;font-size:var(--text-lg);">
            <?= $post->content ?>
        </article>

        <!-- Tags -->
        <?php if (!empty($post->tags)): ?>
        <div style="margin-top:var(--space-10);display:flex;gap:var(--space-2);flex-wrap:wrap;">
            <?php foreach (explode(',', $post->tags) as $tag): ?>
            <span style="padding:var(--space-1) var(--space-3);background:var(--color-dark-surface);border:1px solid var(--color-dark-border);border-radius:var(--radius-full);font-size:var(--text-sm);color:var(--color-gray-400);">#<?= trim(e($tag)) ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Share -->
        <div style="margin-top:var(--space-10);padding:var(--space-6);background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:space-between;">
            <h5>Share this article</h5>
            <div style="display:flex;gap:var(--space-3);">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . '/blog/' . $post->slug) ?>" target="_blank" class="btn btn-icon btn-outline" style="padding:var(--space-3);"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode(BASE_URL . '/blog/' . $post->slug) ?>&text=<?= urlencode($post->title) ?>" target="_blank" class="btn btn-icon btn-outline" style="padding:var(--space-3);"><i class="fab fa-x-twitter"></i></a>
                <a href="https://wa.me/?text=<?= urlencode($post->title . ' ' . BASE_URL . '/blog/' . $post->slug) ?>" target="_blank" class="btn btn-icon" style="padding:var(--space-3);background:#25D366;color:white;border-radius:var(--radius-full);"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>

        <!-- Related Posts -->
        <?php if (!empty($recentPosts)): ?>
        <div style="margin-top:var(--space-16);">
            <h3 style="margin-bottom:var(--space-6);">More from the Journal</h3>
            <div class="grid grid-3">
                <?php foreach ($recentPosts as $p): ?>
                <div class="card">
                    <div class="card-image">
                        <img src="<?= !empty($p->featured_image) ? upload_url($p->featured_image) : 'https://images.unsplash.com/photo-1504893524553-b855bce32c67?w=600&q=80' ?>" alt="<?= e($p->title) ?>" loading="lazy">
                    </div>
                    <div class="card-body">
                        <h3 style="font-size:var(--text-lg);"><a href="<?= url('/blog/' . $p->slug) ?>"><?= e($p->title) ?></a></h3>
                        <p><?= e(truncate($p->excerpt ?? '', 80)) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
