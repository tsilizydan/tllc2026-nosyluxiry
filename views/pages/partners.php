<!-- Partners Page -->
<div class="page-header">
    <div class="container">
        <p class="subtitle">Trusted Partners</p>
        <h1>Our <span class="text-gold">Partners</span></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><span>Partners</span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <p style="text-align:center;max-width:700px;margin:0 auto var(--space-12);color:var(--color-gray-400);font-size:var(--text-lg);">
            We work with Madagascar's finest hospitality providers to ensure every aspect of your journey is exceptional.
        </p>

        <?php if (empty($grouped)): ?>
        <div style="text-align:center;padding:var(--space-16) 0;">
            <i class="fas fa-handshake" style="font-size:3rem;color:var(--color-gold);opacity:0.5;margin-bottom:var(--space-4);display:block;"></i>
            <h3>Partners coming soon</h3>
            <p style="color:var(--color-gray-400);margin-top:var(--space-2);">We're building our network of trusted partners.</p>
        </div>
        <?php else: ?>

        <?php
        $typeIcons = [
            'hotel' => 'fas fa-hotel',
            'car_rental' => 'fas fa-car',
            'currency_exchange' => 'fas fa-money-bill-transfer',
            'restaurant' => 'fas fa-utensils',
            'guide' => 'fas fa-person-hiking',
            'airline' => 'fas fa-plane',
        ];
        ?>

        <?php foreach ($grouped as $type => $partners): ?>
        <div style="margin-bottom:var(--space-12);">
            <h2 style="margin-bottom:var(--space-6);display:flex;align-items:center;gap:var(--space-3);">
                <i class="<?= $typeIcons[$type] ?? 'fas fa-building' ?> text-gold"></i>
                <?= e($typeLabels[$type] ?? ucfirst(str_replace('_', ' ', $type))) ?>
                <span style="font-size:var(--text-sm);color:var(--color-gray-500);font-weight:400;">(<?= count($partners) ?>)</span>
            </h2>

            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));gap:var(--space-6);">
                <?php foreach ($partners as $p): ?>
                <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);overflow:hidden;transition:transform 0.3s, box-shadow 0.3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 40px rgba(0,0,0,0.3)';" onmouseout="this.style.transform='';this.style.boxShadow='';">
                    <?php if (!empty($p->image)): ?>
                    <div style="height:180px;overflow:hidden;">
                        <img src="<?= upload_url($p->image) ?>" alt="<?= e($p->name) ?>" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <?php else: ?>
                    <div style="height:120px;background:linear-gradient(135deg, rgba(212,175,55,0.1), rgba(212,175,55,0.05));display:flex;align-items:center;justify-content:center;">
                        <i class="<?= $typeIcons[$type] ?? 'fas fa-building' ?>" style="font-size:2.5rem;color:var(--color-gold);opacity:0.4;"></i>
                    </div>
                    <?php endif; ?>

                    <div style="padding:var(--space-5);">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:var(--space-3);">
                            <h4 style="font-size:var(--text-lg);color:var(--color-white);"><?= e($p->name) ?></h4>
                            <?php if (!empty($p->rating)): ?>
                            <span style="display:flex;align-items:center;gap:4px;font-size:var(--text-sm);color:var(--color-gold);font-weight:600;">
                                <i class="fas fa-star"></i> <?= number_format($p->rating, 1) ?>
                            </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($p->location)): ?>
                        <p style="font-size:var(--text-sm);color:var(--color-gray-400);margin-bottom:var(--space-2);">
                            <i class="fas fa-map-marker-alt text-gold"></i> <?= e($p->location) ?>
                        </p>
                        <?php endif; ?>

                        <?php if (!empty($p->description)): ?>
                        <p style="font-size:var(--text-sm);color:var(--color-gray-400);margin-bottom:var(--space-4);line-height:1.5;">
                            <?= e(mb_strimwidth($p->description, 0, 120, '...')) ?>
                        </p>
                        <?php endif; ?>

                        <div style="display:flex;gap:var(--space-3);flex-wrap:wrap;align-items:center;">
                            <?php if (!empty($p->phone)): ?>
                            <a href="tel:<?= e($p->phone) ?>" style="font-size:var(--text-sm);color:var(--color-gray-300);display:flex;align-items:center;gap:4px;">
                                <i class="fas fa-phone text-gold"></i> <?= e($p->phone) ?>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($p->email)): ?>
                            <a href="mailto:<?= e($p->email) ?>" style="font-size:var(--text-sm);color:var(--color-gray-300);display:flex;align-items:center;gap:4px;">
                                <i class="fas fa-envelope text-gold"></i> Email
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($p->website)): ?>
                            <a href="<?= e($p->website) ?>" target="_blank" style="font-size:var(--text-sm);color:var(--color-gold);display:flex;align-items:center;gap:4px;">
                                <i class="fas fa-external-link-alt"></i> Website
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <!-- Become a Partner CTA -->
        <div style="text-align:center;margin-top:var(--space-12);padding:var(--space-10);background:linear-gradient(135deg, rgba(212,175,55,0.1), rgba(212,175,55,0.03));border:1px solid rgba(212,175,55,0.2);border-radius:var(--radius-xl);">
            <h3 style="margin-bottom:var(--space-3);">Become a Partner</h3>
            <p style="color:var(--color-gray-400);margin-bottom:var(--space-6);max-width:500px;margin-left:auto;margin-right:auto;">
                Join our curated network of hospitality providers and reach travelers from around the world.
            </p>
            <a href="<?= url('/contact') ?>" class="btn btn-primary"><i class="fas fa-handshake"></i> Get in Touch</a>
        </div>
    </div>
</section>
