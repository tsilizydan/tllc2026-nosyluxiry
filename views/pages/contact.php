<!-- Contact Page -->
<div class="page-header">
    <div class="container">
        <p class="subtitle"><?= __('contact.subtitle') ?></p>
        <h1><?= __('contact.title') ?></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><span><?= __('nav.contact') ?></span></div>
    </div>
</div>

<section class="section section-darker">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1.5fr;gap:var(--space-12);" class="reveal">
            <!-- Contact Info -->
            <div>
                <h3 style="margin-bottom:var(--space-6);">Get in <span class="text-gold">Touch</span></h3>
                <p style="color:var(--color-gray-400);margin-bottom:var(--space-8);line-height:1.8;"><?= __('contact.desc') ?></p>

                <div style="display:flex;flex-direction:column;gap:var(--space-6);">
                    <div style="display:flex;gap:var(--space-4);align-items:flex-start;">
                        <div style="width:48px;height:48px;border-radius:var(--radius-md);background:var(--color-gold-muted);display:flex;align-items:center;justify-content:center;color:var(--color-gold);flex-shrink:0;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h5 style="font-size:var(--text-base);margin-bottom:var(--space-1);">Email</h5>
                            <a href="mailto:<?= e(setting('contact_email', CONTACT_EMAIL)) ?>" style="color:var(--color-gray-400);font-size:var(--text-sm);"><?= e(setting('contact_email', CONTACT_EMAIL)) ?></a>
                        </div>
                    </div>
                    <div style="display:flex;gap:var(--space-4);align-items:flex-start;">
                        <div style="width:48px;height:48px;border-radius:var(--radius-md);background:var(--color-gold-muted);display:flex;align-items:center;justify-content:center;color:var(--color-gold);flex-shrink:0;">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h5 style="font-size:var(--text-base);margin-bottom:var(--space-1);">Phone</h5>
                            <a href="tel:<?= e(setting('contact_phone', CONTACT_PHONE)) ?>" style="color:var(--color-gray-400);font-size:var(--text-sm);"><?= e(setting('contact_phone', CONTACT_PHONE)) ?></a>
                        </div>
                    </div>
                    <div style="display:flex;gap:var(--space-4);align-items:flex-start;">
                        <div style="width:48px;height:48px;border-radius:var(--radius-md);background:#25D366;display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;border-radius:var(--radius-full);">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <h5 style="font-size:var(--text-base);margin-bottom:var(--space-1);">WhatsApp</h5>
                            <a href="https://wa.me/<?= e(setting('whatsapp_number', WHATSAPP_NUMBER)) ?>" target="_blank" style="color:var(--color-gray-400);font-size:var(--text-sm);">Chat with us instantly</a>
                        </div>
                    </div>
                    <div style="display:flex;gap:var(--space-4);align-items:flex-start;">
                        <div style="width:48px;height:48px;border-radius:var(--radius-md);background:var(--color-gold-muted);display:flex;align-items:center;justify-content:center;color:var(--color-gold);flex-shrink:0;">
                            <i class="fas fa-location-dot"></i>
                        </div>
                        <div>
                            <h5 style="font-size:var(--text-base);margin-bottom:var(--space-1);">Office</h5>
                            <span style="color:var(--color-gray-400);font-size:var(--text-sm);">Antananarivo, Madagascar</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-10);">
                <h3 style="margin-bottom:var(--space-6);">Send a <span class="text-gold">Message</span></h3>
                <form action="<?= url('/contact') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="grid grid-2" style="gap:var(--space-4);">
                        <div class="form-group">
                            <label class="form-label"><?= __('contact.name') ?></label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?= __('contact.email') ?></label>
                            <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?= __('contact.subject') ?></label>
                        <select name="subject" class="form-control">
                            <option value="general">General Inquiry</option>
                            <option value="booking">Booking Question</option>
                            <option value="custom_trip">Custom Trip Request</option>
                            <option value="partnership">Business Partnership</option>
                            <option value="feedback">Feedback</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?= __('contact.message') ?></label>
                        <textarea name="message" class="form-control" rows="6" placeholder="Tell us about your dream trip..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;"><?= __('contact.send') ?> <i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
</section>
