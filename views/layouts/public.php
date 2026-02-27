<?php
// Security headers
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
?>
<!DOCTYPE html>
<html lang="<?= Language::current() ?>" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>[x-cloak] { display: none !important; }</style>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= e($pageTitle ?? APP_NAME . ' — ' . APP_TAGLINE) ?></title>
    <meta name="description" content="<?= e($metaDescription ?? setting('meta_description', 'Premium Madagascar travel experiences.')) ?>">
    <meta name="keywords" content="Madagascar travel, luxury tours, Nosy Be, Tsingy, lemurs, wildlife, TSILIZY">
    <meta name="author" content="TSILIZY LLC">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= e($pageTitle ?? APP_NAME) ?>">
    <meta property="og:description" content="<?= e($metaDescription ?? setting('meta_description', '')) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e(BASE_URL . ($_SERVER['REQUEST_URI'] ?? '')) ?>">
    <meta property="og:image" content="<?= e($ogImage ?? asset('images/og-default.jpg')) ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle ?? APP_NAME) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription ?? setting('meta_description', '')) ?>">
    <meta name="twitter:image" content="<?= e($ogImage ?? asset('images/og-default.jpg')) ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= e(BASE_URL . ($_SERVER['REQUEST_URI'] ?? '')) ?>">

    <!-- Theme Color (mobile browser chrome) -->
    <meta name="theme-color" content="#0A0A0A">

    <!-- Structured Data — TourOperator -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TravelAgency",
        "name": "<?= e(APP_NAME) ?>",
        "description": "<?= e(setting('meta_description', 'Premium Madagascar travel experiences.')) ?>",
        "url": "<?= e(BASE_URL) ?>",
        "telephone": "<?= e(setting('contact_phone', CONTACT_PHONE)) ?>",
        "email": "<?= e(setting('contact_email', CONTACT_EMAIL)) ?>",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "MG"
        },
        "sameAs": [
            <?php if ($fb = setting('facebook_url')): ?>"<?= e($fb) ?>"<?php endif; ?>
        ]
    }
    </script>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌿</text></svg>">
    <link rel="icon" type="image/png" href="<?= e($ogImage ?? asset('images/favicon/favicon-96x96.png')) ?>" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="<?= e($ogImage ?? asset('images/favicon/favicon.svg')) ?>" />
    <link rel="shortcut icon" href="<?= e($ogImage ?? asset('images/favicon/favicon.ico')) ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?= e($ogImage ?? asset('images/favicon/apple-touch-icon.png')) ?>" />
    <meta name="apple-mobile-web-app-title" content="Tsilizy Escapes™" />
    <link rel="manifest" href="<?= e($ogImage ?? asset('images/favicon/site.webmanifest')) ?>" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: { DEFAULT: '#C9A84C', light: '#DFC06A', dark: '#A88A3C', muted: 'rgba(201,168,76,0.15)' },
                        ivory: { DEFAULT: '#FAF3E0', dark: '#F0E6CC' },
                        dark: { DEFAULT: '#111111', surface: '#1A1A1A', card: '#1E1E1E', border: '#2A2A2A' }
                    },
                    fontFamily: {
                        heading: ['Playfair Display', 'Georgia', 'serif'],
                        body: ['Inter', 'sans-serif'],
                        accent: ['Cormorant Garamond', 'Georgia', 'serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=3.1">

    <!-- Theme Init (prevents flash of wrong theme) -->
    <script>
        (function() {
            var saved = localStorage.getItem('theme');
            if (saved) {
                document.documentElement.setAttribute('data-theme', saved);
            } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body x-data="app()" class="bg-[#0A0A0A]">

    <?php include VIEWS_PATH . '/partials/nav.php'; ?>

    <!-- Flash Messages -->
    <?php if (Session::hasFlash('success') || Session::hasFlash('error')): ?>
    <div class="toast-container" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <?php if ($msg = Session::getFlash('success')): ?>
            <div class="toast alert-success"><i class="fas fa-check-circle"></i> <?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = Session::getFlash('error')): ?>
            <div class="toast alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($msg) ?></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>

    <!-- Floating Button Stack -->
    <div class="float-stack">
        <button class="float-btn float-btn--theme" onclick="toggleTheme()" aria-label="Toggle dark/light mode">
            <i class="fas fa-sun" id="theme-icon-light" style="display:none"></i>
            <i class="fas fa-moon" id="theme-icon-dark"></i>
        </button>
        <a href="https://wa.me/<?= e(setting('whatsapp_number', WHATSAPP_NUMBER)) ?>" target="_blank" class="float-btn float-btn--whatsapp" aria-label="Chat on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <!-- Custom JS -->
    <script src="<?= asset('js/app.js') ?>?v=3.1"></script>
</body>
</html>


