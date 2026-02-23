<!-- Blog Listing Page -->
<div class="page-header">
    <div class="container">
        <p class="subtitle"><?= __('nav.blog') ?></p>
        <h1>Travel Journal & <span class="text-gold">Insights</span></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><span><?= __('nav.blog') ?></span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <?php if (!empty($posts)): ?>
        <div class="grid grid-3">
            <?php foreach ($posts as $post): ?>
            <div class="card reveal">
                <div class="card-image">
                    <img src="<?= !empty($post->image) ? upload_url($post->image) : 'https://images.unsplash.com/photo-1504893524553-b855bce32c67?w=600&q=80' ?>" alt="<?= e($post->title) ?>" loading="lazy">
                    <?php if (!empty($post->category_name)): ?>
                    <span class="card-badge"><?= e($post->category_name) ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="card-meta">
                        <span><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($post->published_at ?? $post->created_at)) ?></span>
                        <span><i class="fas fa-eye"></i> <?= $post->views ?? 0 ?></span>
                    </div>
                    <h3><a href="<?= url('/blog/' . $post->slug) ?>"><?= e($post->title) ?></a></h3>
                    <p><?= e(truncate($post->excerpt ?? strip_tags($post->content), 150)) ?></p>
                </div>
                <div class="card-footer">
                    <span style="font-size:var(--text-sm);color:var(--color-gray-400);">By <?= e($post->author_name ?? 'Nosy Luxury') ?></span>
                    <a href="<?= url('/blog/' . $post->slug) ?>" class="btn btn-outline btn-sm">Read More</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($pagination['totalPages'] > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                <?php if ($i == $pagination['page']): ?><span class="active"><?= $i ?></span>
                <?php else: ?><a href="<?= url('/blog?page=' . $i) ?>"><?= $i ?></a><?php endif; ?>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div style="text-align:center;padding:var(--space-16) 0;" class="reveal">
            <i class="fas fa-feather-pointed" style="font-size:3rem;color:var(--color-gold);margin-bottom:var(--space-4);display:block;"></i>
            <h3>Stories Coming Soon</h3>
            <p style="color:var(--color-gray-400);margin-top:var(--space-2);">Our travel journal is being prepared with inspiring stories and expert guides.</p>
        </div>
        <?php endif; ?>
    </div>
</section>
