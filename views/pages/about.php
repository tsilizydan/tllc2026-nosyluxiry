<!-- About Page -->
<div class="page-header">
    <div class="container">
        <p class="subtitle">Our Story</p>
        <h1><?= __('nav.about') ?></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><span><?= __('nav.about') ?></span></div>
    </div>
</div>

<!-- Story -->
<section class="section section-darker">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-12);align-items:center;" class="reveal">
            <div>
                <p class="subtitle">Why We Exist</p>
                <h2 style="margin-bottom:var(--space-6);">Born from <span class="text-gold">Love</span> for Madagascar</h2>
                <p style="color:var(--color-gray-300);line-height:1.9;margin-bottom:var(--space-4);">
                    Founded by TSILIZY LLC, Nosy Luxury was created to share the breathtaking beauty of Madagascar 
                    with the world — responsibly and authentically. We believe that luxury travel and environmental 
                    stewardship are not mutually exclusive.
                </p>
                <p style="color:var(--color-gray-300);line-height:1.9;margin-bottom:var(--space-4);">
                    Every experience we craft supports local communities, protects endemic wildlife, and 
                    preserves the traditions that make this island one-of-a-kind. From the sighting of a 
                    rare indri in the rainforest canopy to a sunset dinner on a private sandbank — every 
                    moment is designed to move you.
                </p>
                <p style="color:var(--color-gray-300);line-height:1.9;">
                    We are Malagasy-owned, locally guided, globally standard. Our team of expert naturalists, 
                    hospitality professionals, and travel designers ensures every journey is seamless, safe, 
                    and utterly unforgettable.
                </p>
            </div>
            <div style="border-radius:var(--radius-lg);overflow:hidden;">
                <img src="https://images.unsplash.com/photo-1549366021-9f761d450615?w=700&q=80" alt="Madagascar landscape" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="section section-dark">
    <div class="container">
        <div class="section-header reveal">
            <p class="subtitle">Our Core Values</p>
            <h2>Guiding <span class="text-gold">Principles</span></h2>
            <div class="gold-line"></div>
        </div>
        <div class="grid grid-4 reveal">
            <?php
            $values = [
                ['icon' => 'fa-leaf', 'title' => 'Sustainability', 'desc' => 'Carbon-offset programs, eco-lodges, and wildlife-first policies that protect Madagascar\'s biodiversity.'],
                ['icon' => 'fa-heart', 'title' => 'Community', 'desc' => '80% of our revenues go directly to Malagasy guides, artisans, and families in rural areas.'],
                ['icon' => 'fa-award', 'title' => 'Excellence', 'desc' => 'Five-star service standards combined with authentic, unscripted experiences that you won\'t find elsewhere.'],
                ['icon' => 'fa-handshake', 'title' => 'Integrity', 'desc' => 'Transparent pricing, no hidden fees, and genuine reviews from verified travelers.'],
            ];
            foreach ($values as $v): ?>
            <div class="value-card">
                <div class="value-icon"><i class="fas <?= $v['icon'] ?>"></i></div>
                <h4><?= $v['title'] ?></h4>
                <p><?= $v['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- The Team -->
<section class="section section-surface">
    <div class="container">
        <div class="section-header reveal">
            <p class="subtitle">Meet the Team</p>
            <h2>The <span class="text-gold">Architects</span> of Your Journey</h2>
            <div class="gold-line"></div>
        </div>
        <div class="grid grid-3 reveal">
            <?php 
            $team = [
                ['name' => 'Rakoto A.', 'role' => 'Founder & CEO', 'icon' => 'R'],
                ['name' => 'Nirina V.', 'role' => 'Head of Operations', 'icon' => 'N'],
                ['name' => 'Faly M.', 'role' => 'Lead Naturalist Guide', 'icon' => 'F'],
            ];
            foreach ($team as $member): ?>
            <div style="text-align:center;padding:var(--space-8);background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);">
                <div style="width:100px;height:100px;border-radius:var(--radius-full);background:var(--color-gold-muted);display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-4);font-family:var(--font-heading);font-size:var(--text-3xl);color:var(--color-gold);font-weight:700;">
                    <?= $member['icon'] ?>
                </div>
                <h4><?= $member['name'] ?></h4>
                <p style="color:var(--color-gold);font-size:var(--text-sm);margin-top:var(--space-1);"><?= $member['role'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section section-dark reveal">
    <div class="container" style="position:relative;z-index:1;">
        <p class="subtitle">Ready?</p>
        <h2>Begin Your <span class="text-gold">Madagascar</span> Story</h2>
        <div class="gold-line"></div>
        <p>Let us design the journey of a lifetime, tailored exclusively for you.</p>
        <a href="<?= url('/trip-builder') ?>" class="btn btn-primary btn-lg"><?= __('home.cta_btn') ?> <i class="fas fa-arrow-right"></i></a>
    </div>
</section>
