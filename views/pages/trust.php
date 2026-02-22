<!-- Trust & Safety Page -->
<div class="page-header">
    <div class="container">
        <p class="subtitle">Trust & Safety</p>
        <h1>Your Safety, Our <span class="text-gold">Priority</span></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><span><?= __('nav.trust') ?></span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <div class="grid grid-2" style="gap:var(--space-12);margin-bottom:var(--space-16);">
            <div class="reveal">
                <h2 style="margin-bottom:var(--space-6);">Why <span class="text-gold">Trust</span> Nosy Luxury?</h2>
                <p style="color:var(--color-gray-300);line-height:1.9;margin-bottom:var(--space-4);">We understand that booking a luxury trip to Madagascar requires trust. That's why we've built every aspect of our business around transparency, security, and delivering exceptional experiences.</p>
                <p style="color:var(--color-gray-300);line-height:1.9;">TSILIZY LLC is a registered company operating in compliance with Madagascar's tourism regulations. We carry full liability insurance and maintain the highest safety standards across all our operations.</p>
            </div>
            <div class="grid grid-2 reveal" style="gap:var(--space-4);">
                <?php
                $trustItems = [
                    ['icon' => 'fa-shield-halved', 'title' => 'Licensed & Insured','desc' => 'Fully registered with Madagascar Ministry of Tourism'],
                    ['icon' => 'fa-lock', 'title' => 'Secure Payments','desc' => 'SSL-encrypted transactions via bank transfer or mobile money'],
                    ['icon' => 'fa-user-check', 'title' => 'Verified Reviews','desc' => 'All reviews come from guests who actually traveled with us'],
                    ['icon' => 'fa-phone', 'title' => '24/7 Support','desc' => 'Emergency support line available throughout your journey'],
                ];
                foreach ($trustItems as $item): ?>
                <div class="value-card" style="text-align:left;padding:var(--space-6);">
                    <div class="value-icon" style="margin:0 0 var(--space-4);width:50px;height:50px;font-size:var(--text-xl);"><i class="fas <?= $item['icon'] ?>"></i></div>
                    <h5 style="margin-bottom:var(--space-2);"><?= $item['title'] ?></h5>
                    <p style="font-size:var(--text-sm);color:var(--color-gray-400);"><?= $item['desc'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Cancellation Policy -->
        <div class="reveal" style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-10);margin-bottom:var(--space-10);">
            <h3 style="margin-bottom:var(--space-6);"><i class="fas fa-calendar-xmark text-gold"></i> Cancellation Policy</h3>
            <div class="grid grid-3" style="gap:var(--space-6);">
                <div style="text-align:center;padding:var(--space-6);background:var(--color-dark-surface);border-radius:var(--radius-md);">
                    <div style="font-family:var(--font-heading);font-size:var(--text-3xl);color:var(--color-emerald-light);font-weight:700;">100%</div>
                    <p style="color:var(--color-gray-300);margin-top:var(--space-2);font-size:var(--text-sm);">Full refund if cancelled <strong>30+ days</strong> before departure</p>
                </div>
                <div style="text-align:center;padding:var(--space-6);background:var(--color-dark-surface);border-radius:var(--radius-md);">
                    <div style="font-family:var(--font-heading);font-size:var(--text-3xl);color:var(--color-gold);font-weight:700;">50%</div>
                    <p style="color:var(--color-gray-300);margin-top:var(--space-2);font-size:var(--text-sm);">Half refund if cancelled <strong>15-29 days</strong> before departure</p>
                </div>
                <div style="text-align:center;padding:var(--space-6);background:var(--color-dark-surface);border-radius:var(--radius-md);">
                    <div style="font-family:var(--font-heading);font-size:var(--text-3xl);color:var(--color-coral);font-weight:700;">0%</div>
                    <p style="color:var(--color-gray-300);margin-top:var(--space-2);font-size:var(--text-sm);">No refund if cancelled <strong>less than 14 days</strong> before departure</p>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="reveal">
            <div class="section-header"><h2>Frequently Asked <span class="text-gold">Questions</span></h2><div class="gold-line"></div></div>
            <div style="max-width:800px;margin:0 auto;" x-data="{ open: null }">
                <?php
                $faqs = [
                    ['q' => 'Is Madagascar safe for tourists?', 'a' => 'Yes, Madagascar is generally safe for tourists, especially when traveling with a reputable operator like Nosy Luxury. Our guides are all locally trained and deeply familiar with every region. We avoid any areas with security concerns and provide 24/7 support.'],
                    ['q' => 'What is included in the tour price?', 'a' => 'All tours include accommodation, transportation, local guide services, and most meals. Specific inclusions are listed on each tour\'s detail page. International flights and travel insurance are not included.'],
                    ['q' => 'How do I pay?', 'a' => 'We accept bank transfers (EUR/USD), mobile money (MVola/Orange Money for local clients), and can arrange alternative payment methods for international clients. A 30% deposit secures your booking.'],
                    ['q' => 'Do I need a visa?', 'a' => 'Most nationalities can obtain a visa on arrival at Antananarivo airport for stays up to 90 days. We provide detailed visa guidance after booking.'],
                    ['q' => 'What should I pack?', 'a' => 'We provide a comprehensive packing guide tailored to your specific itinerary after booking. Generally, light breathable clothing, good walking shoes, sunscreen, and insect repellent are essentials.'],
                ];
                foreach ($faqs as $i => $faq): ?>
                <div style="border-bottom:1px solid var(--color-dark-border);padding:var(--space-4) 0;cursor:pointer;" @click="open = open === <?= $i ?> ? null : <?= $i ?>">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <h5 style="font-size:var(--text-base);"><?= $faq['q'] ?></h5>
                        <i class="fas fa-chevron-down text-gold" style="transition:transform 0.3s;" :style="open === <?= $i ?> ? 'transform:rotate(180deg)' : ''"></i>
                    </div>
                    <div x-show="open === <?= $i ?>" x-collapse style="padding-top:var(--space-3);color:var(--color-gray-400);font-size:var(--text-sm);line-height:1.8;">
                        <?= $faq['a'] ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
