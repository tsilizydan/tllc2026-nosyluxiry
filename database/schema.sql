-- ============================================================
-- Nosy Luxury — Database Schema
-- Premium Tourism Booking Platform for TSILIZY LLC
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `nosy_luxury` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nosy_luxury`;

-- ─── Users ───
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) DEFAULT NULL,
    `avatar` VARCHAR(255) DEFAULT NULL,
    `role` ENUM('customer','editor','admin','super_admin') DEFAULT 'customer',
    `status` ENUM('active','inactive','banned') DEFAULT 'active',
    `bio` TEXT DEFAULT NULL,
    `last_login` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_role` (`role`)
) ENGINE=InnoDB;

-- ─── Destinations ───
CREATE TABLE `destinations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `region` ENUM('north','south','east','west','tsingy','central') NOT NULL,
    `description` TEXT NOT NULL,
    `short_description` VARCHAR(500) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `gallery` JSON DEFAULT NULL,
    `highlights` JSON DEFAULT NULL,
    `best_time` VARCHAR(255) DEFAULT NULL,
    `map_lat` DECIMAL(10,7) DEFAULT NULL,
    `map_lng` DECIMAL(10,7) DEFAULT NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `status` ENUM('active','draft') DEFAULT 'active',
    `meta_title` VARCHAR(255) DEFAULT NULL,
    `meta_description` VARCHAR(500) DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_dest_slug` (`slug`),
    INDEX `idx_dest_region` (`region`),
    INDEX `idx_dest_featured` (`is_featured`)
) ENGINE=InnoDB;

-- ─── Tours ───
CREATE TABLE `tours` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `destination_id` INT UNSIGNED DEFAULT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `subtitle` VARCHAR(500) DEFAULT NULL,
    `description` TEXT NOT NULL,
    `short_description` VARCHAR(500) DEFAULT NULL,
    `type` ENUM('adventure','wildlife','cultural','beach','luxury','photography','trekking') NOT NULL,
    `duration_days` INT NOT NULL DEFAULT 1,
    `price` DECIMAL(10,2) NOT NULL,
    `price_currency` VARCHAR(3) DEFAULT 'EUR',
    `sale_price` DECIMAL(10,2) DEFAULT NULL,
    `group_size_min` INT DEFAULT 1,
    `group_size_max` INT DEFAULT 12,
    `difficulty` ENUM('easy','moderate','challenging') DEFAULT 'moderate',
    `image` VARCHAR(255) DEFAULT NULL,
    `gallery` JSON DEFAULT NULL,
    `included` JSON DEFAULT NULL,
    `excluded` JSON DEFAULT NULL,
    `highlights` JSON DEFAULT NULL,
    `departure_location` VARCHAR(255) DEFAULT NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `is_bestseller` TINYINT(1) DEFAULT 0,
    `status` ENUM('active','draft','archived') DEFAULT 'active',
    `avg_rating` DECIMAL(2,1) DEFAULT 0.0,
    `total_reviews` INT DEFAULT 0,
    `total_bookings` INT DEFAULT 0,
    `meta_title` VARCHAR(255) DEFAULT NULL,
    `meta_description` VARCHAR(500) DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`id`) ON DELETE SET NULL,
    INDEX `idx_tours_slug` (`slug`),
    INDEX `idx_tours_type` (`type`),
    INDEX `idx_tours_featured` (`is_featured`),
    INDEX `idx_tours_status` (`status`)
) ENGINE=InnoDB;

-- ─── Tour Itineraries ───
CREATE TABLE `tour_itineraries` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tour_id` INT UNSIGNED NOT NULL,
    `day_number` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `meals` VARCHAR(255) DEFAULT NULL,
    `accommodation` VARCHAR(255) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`) ON DELETE CASCADE,
    INDEX `idx_itin_tour` (`tour_id`)
) ENGINE=InnoDB;

-- ─── Tour Images ───
CREATE TABLE `tour_images` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tour_id` INT UNSIGNED NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `alt_text` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ─── Tour Availability / Dates ───
CREATE TABLE `tour_dates` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tour_id` INT UNSIGNED NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `slots_total` INT NOT NULL DEFAULT 12,
    `slots_booked` INT NOT NULL DEFAULT 0,
    `price_override` DECIMAL(10,2) DEFAULT NULL,
    `status` ENUM('available','full','cancelled') DEFAULT 'available',
    FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`) ON DELETE CASCADE,
    INDEX `idx_dates_tour` (`tour_id`),
    INDEX `idx_dates_start` (`start_date`)
) ENGINE=InnoDB;

-- ─── Bookings ───
CREATE TABLE `bookings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `reference` VARCHAR(20) NOT NULL UNIQUE,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `tour_id` INT UNSIGNED NOT NULL,
    `tour_date_id` INT UNSIGNED DEFAULT NULL,
    `guest_name` VARCHAR(255) NOT NULL,
    `guest_email` VARCHAR(255) NOT NULL,
    `guest_phone` VARCHAR(50) DEFAULT NULL,
    `num_guests` INT NOT NULL DEFAULT 1,
    `special_requests` TEXT DEFAULT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    `tax` DECIMAL(10,2) DEFAULT 0.00,
    `total` DECIMAL(10,2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'EUR',
    `status` ENUM('pending','confirmed','paid','cancelled','completed','refunded') DEFAULT 'pending',
    `payment_method` VARCHAR(50) DEFAULT NULL,
    `payment_status` ENUM('unpaid','partial','paid','refunded') DEFAULT 'unpaid',
    `booking_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `travel_date` DATE DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tour_date_id`) REFERENCES `tour_dates`(`id`) ON DELETE SET NULL,
    INDEX `idx_book_ref` (`reference`),
    INDEX `idx_book_user` (`user_id`),
    INDEX `idx_book_status` (`status`)
) ENGINE=InnoDB;

-- ─── Payments ───
CREATE TABLE `payments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `booking_id` INT UNSIGNED NOT NULL,
    `transaction_id` VARCHAR(255) DEFAULT NULL,
    `gateway` ENUM('stripe','paypal','mobile_money','bank_transfer','cash') NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'EUR',
    `status` ENUM('pending','completed','failed','refunded') DEFAULT 'pending',
    `gateway_response` JSON DEFAULT NULL,
    `paid_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE,
    INDEX `idx_pay_booking` (`booking_id`),
    INDEX `idx_pay_status` (`status`)
) ENGINE=InnoDB;

-- ─── Reviews ───
CREATE TABLE `reviews` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tour_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `booking_id` INT UNSIGNED DEFAULT NULL,
    `reviewer_name` VARCHAR(255) NOT NULL,
    `reviewer_country` VARCHAR(100) DEFAULT NULL,
    `rating` TINYINT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `title` VARCHAR(255) DEFAULT NULL,
    `comment` TEXT NOT NULL,
    `is_verified` TINYINT(1) DEFAULT 0,
    `is_approved` TINYINT(1) DEFAULT 0,
    `is_featured` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL,
    INDEX `idx_rev_tour` (`tour_id`),
    INDEX `idx_rev_approved` (`is_approved`)
) ENGINE=InnoDB;

-- ─── Blog Categories ───
CREATE TABLE `blog_categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `sort_order` INT DEFAULT 0
) ENGINE=InnoDB;

-- ─── Blog Posts ───
CREATE TABLE `blog_posts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT UNSIGNED DEFAULT NULL,
    `author_id` INT UNSIGNED DEFAULT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `excerpt` VARCHAR(500) DEFAULT NULL,
    `content` LONGTEXT NOT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `tags` VARCHAR(500) DEFAULT NULL,
    `status` ENUM('published','draft') DEFAULT 'draft',
    `views` INT DEFAULT 0,
    `meta_title` VARCHAR(255) DEFAULT NULL,
    `meta_description` VARCHAR(500) DEFAULT NULL,
    `published_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `blog_categories`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_blog_slug` (`slug`),
    INDEX `idx_blog_status` (`status`)
) ENGINE=InnoDB;

-- ─── Ads ───
CREATE TABLE `ads` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `link` VARCHAR(500) DEFAULT NULL,
    `placement` ENUM('hero','sidebar','footer','inline','popup') DEFAULT 'sidebar',
    `html_content` TEXT DEFAULT NULL,
    `impressions` INT DEFAULT 0,
    `clicks` INT DEFAULT 0,
    `start_date` DATE DEFAULT NULL,
    `end_date` DATE DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_ads_active` (`is_active`),
    INDEX `idx_ads_placement` (`placement`)
) ENGINE=InnoDB;

-- ─── Trip Requests (Custom Trip Builder) ───
CREATE TABLE `trip_requests` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) DEFAULT NULL,
    `destinations` JSON DEFAULT NULL,
    `travel_dates` VARCHAR(255) DEFAULT NULL,
    `duration_days` INT DEFAULT NULL,
    `group_size` INT DEFAULT NULL,
    `budget_range` VARCHAR(100) DEFAULT NULL,
    `interests` JSON DEFAULT NULL,
    `accommodation_type` VARCHAR(100) DEFAULT NULL,
    `special_requests` TEXT DEFAULT NULL,
    `status` ENUM('new','contacted','quoted','booked','closed') DEFAULT 'new',
    `admin_notes` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_trip_status` (`status`)
) ENGINE=InnoDB;

-- ─── Contact Messages ───
CREATE TABLE `contact_messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) DEFAULT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ─── Newsletter Subscribers ───
CREATE TABLE `newsletter_subscribers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ─── Wishlists ───
CREATE TABLE `wishlists` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `tour_id` INT UNSIGNED NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_wishlist` (`user_id`, `tour_id`)
) ENGINE=InnoDB;

-- ─── Settings ───
CREATE TABLE `settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT DEFAULT NULL,
    `setting_group` VARCHAR(50) DEFAULT 'general',
    INDEX `idx_settings_key` (`setting_key`)
) ENGINE=InnoDB;

-- ─── Pages (CMS) ───
CREATE TABLE `pages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `content` LONGTEXT DEFAULT NULL,
    `meta_title` VARCHAR(255) DEFAULT NULL,
    `meta_description` VARCHAR(500) DEFAULT NULL,
    `status` ENUM('published','draft') DEFAULT 'published',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Admin user (password: Admin@123)
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`, `status`) VALUES
('Admin', 'Nosy Luxury', 'admin@nosyluxury.com', '$2y$12$LJ3m4iz7H.HQnGHvfYRZxOmXZqPFG.y6CqKB4tVOeFEXmKOaNSXKy', 'super_admin', 'active');

-- Destinations
INSERT INTO `destinations` (`name`, `slug`, `region`, `description`, `short_description`, `best_time`, `map_lat`, `map_lng`, `is_featured`, `status`, `sort_order`) VALUES
('Nosy Be', 'nosy-be', 'north', 'Nosy Be, often called the "Perfume Island," is Madagascar''s most famous beach destination. With its turquoise waters, pristine beaches, and lush tropical vegetation, it offers an idyllic escape. Explore the Lokobe Nature Reserve, swim with whale sharks, or simply relax on the powder-white sands of Andilana Beach.', 'The Perfume Island — turquoise waters, pristine beaches, and tropical paradise.', 'April - November', -13.3167, 48.2667, 1, 'active', 1),
('Avenue of the Baobabs', 'avenue-of-baobabs', 'west', 'One of Madagascar''s most iconic landscapes, the Avenue of the Baobabs features ancient Grandidier''s baobab trees lining a dirt road between Morondava and Belon''i Tsiribihina. These 800-year-old trees create a surreal, otherworldly atmosphere, especially at sunset when they cast long shadows across the land.', 'Iconic ancient baobab trees creating a surreal, otherworldly landscape.', 'May - October', -20.2509, 44.4188, 1, 'active', 2),
('Isalo National Park', 'isalo-national-park', 'south', 'Isalo National Park is a geological wonder featuring dramatic sandstone formations, deep canyons, palm-lined oases, and natural swimming pools. Often compared to the American Southwest, this park offers stunning hiking trails through otherworldly landscapes.', 'Dramatic sandstone formations, canyons, and natural swimming pools.', 'April - November', -22.5262, 45.3590, 1, 'active', 3),
('Andasibe-Mantadia', 'andasibe-mantadia', 'east', 'Home to the largest living lemur — the Indri — Andasibe-Mantadia National Park is a lush rainforest paradise. Wake up to the haunting calls of the Indri, spot chameleons and frogs, and immerse yourself in Madagascar''s incredible biodiversity.', 'Lush rainforest home to the iconic Indri lemur and incredible biodiversity.', 'September - December', -18.9333, 48.4167, 1, 'active', 4),
('Tsingy de Bemaraha', 'tsingy-de-bemaraha', 'tsingy', 'A UNESCO World Heritage Site, the Tsingy de Bemaraha is an extraordinary landscape of razor-sharp limestone pinnacles rising dramatically from the earth. Navigate via suspension bridges and harnesses through this geological marvel — a truly once-in-a-lifetime adventure.', 'UNESCO World Heritage — razor-sharp limestone pinnacles and adventure.', 'May - November', -18.6667, 44.7167, 1, 'active', 5),
('Île Sainte-Marie', 'ile-sainte-marie', 'east', 'A narrow tropical island off Madagascar''s east coast, Île Sainte-Marie is famous for its whale-watching season when humpback whales migrate through its waters. With its pirate history, coral reefs, and laid-back atmosphere, it''s the perfect tropical retreat.', 'Tropical island paradise — humpback whales and pirate history.', 'June - September', -17.0000, 49.8500, 1, 'active', 6);

-- Tours
INSERT INTO `tours` (`destination_id`, `name`, `slug`, `subtitle`, `description`, `short_description`, `type`, `duration_days`, `price`, `sale_price`, `group_size_max`, `difficulty`, `included`, `excluded`, `highlights`, `is_featured`, `is_bestseller`, `status`) VALUES
(1, 'Nosy Be Island Luxury Retreat', 'nosy-be-island-luxury-retreat', 'A Premium Tropical Escape',
'Indulge in the ultimate island escape on Nosy Be. This carefully curated luxury retreat combines pristine beach relaxation with exclusive experiences — from private snorkeling excursions to sunset catamaran cruises. Stay in a world-class eco-lodge, savor gourmet Malagasy cuisine, and discover hidden gems only accessible by boat.\n\nYour private guide will introduce you to the island''s rich culture, aromatic ylang-ylang plantations, and the enchanting Lokobe Natural Reserve, home to black lemurs and endemic flora.',
'Premium island escape with private excursions, luxury lodging, and authentic culture.', 'beach', 7, 2890.00, 2590.00, 8, 'easy',
'["5-star eco-lodge accommodation","All meals & premium beverages","Private guide & boat transfers","Snorkeling equipment","Sunset catamaran cruise","Lokobe Reserve excursion","Airport transfers","Travel insurance"]',
'["International flights","Personal expenses","Tips & gratuities","Visa fees"]',
'["Private beach picnic on Nosy Iranja","Swim with whale sharks","Sunset sailing cruise","Ylang-ylang plantation visit","Lokobe lemur encounter"]',
1, 1, 'active'),

(5, 'Tsingy Explorer Adventure', 'tsingy-explorer-adventure', 'Navigate the Stone Forest',
'Embark on an extraordinary journey to the Tsingy de Bemaraha, one of Madagascar''s most spectacular natural wonders. This adventure combines thrilling via ferrata climbing, suspension bridge crossings, and canyon exploration with comfortable overnight stays in premium lodges.\n\nTraverse the razor-sharp limestone pinnacles, descend into hidden grottos, and discover wildlife found nowhere else on Earth. This is genuine adventure travel at its finest.',
'Navigate limestone pinnacles via suspension bridges and via ferrata in this UNESCO site.', 'adventure', 5, 1990.00, NULL, 10, 'challenging',
'["Premium lodge accommodation","All meals","Expert climbing guide","Safety equipment & harness","4x4 transport","Park entry fees","Camping gear (1 night)"]',
'["International flights","Travel insurance","Personal equipment","Tips"]',
'["Via ferrata through stone forest","Suspension bridge crossings","Manambolo River gorge","Canyon descent","Endemic wildlife spotting"]',
1, 1, 'active'),

(3, 'Southern Madagascar Grand Tour', 'southern-madagascar-grand-tour', 'From Highlands to Coast',
'Journey through Madagascar''s dramatic south, from the capital Antananarivo through the highlands to the stunning coast. Discover Isalo''s sandstone canyons, Ranomafana''s misty rainforests, and the surreal spiny forest — a landscape found nowhere else on Earth.\n\nThis comprehensive tour showcases the incredible diversity of Madagascar''s south, combining world-class national parks with authentic village encounters and coastal relaxation.',
'Highlands to coast — Isalo canyons, rainforests, spiny desert, and beaches.', 'adventure', 12, 3490.00, 3190.00, 12, 'moderate',
'["Boutique hotel & lodge accommodation","All meals","Private guide & driver","4x4 vehicle","All park entry fees","Village visits","Domestic flight return"]',
'["International flights","Personal expenses","Tips","Visa fees","Travel insurance"]',
'["Isalo natural swimming pools","Ranomafana night walk","Spiny forest exploration","Ifaty beach relaxation","Traditional Antanosy village","Anja lemur reserve"]',
1, 0, 'active'),

(4, 'Rainforest & Lemur Encounter', 'rainforest-lemur-encounter', 'Into the Heart of the Forest',
'An immersive 4-day journey into Madagascar''s lush eastern rainforests, designed for wildlife enthusiasts and nature lovers. Led by expert naturalist guides, discover the incredible Indri lemur — the largest living lemur — along with dozens of frog, chameleon, and bird species.\n\nExperience night walks to spot nocturnal creatures, visit a local community project, and awaken to the magical dawn chorus of the forest.',
'Meet the iconic Indri lemur and discover incredible biodiversity in lush rainforest.', 'wildlife', 4, 1290.00, NULL, 8, 'easy',
'["Eco-lodge accommodation","All meals","Expert naturalist guide","Park entry fees","Night walk equipment","Community project visit","Transport from Antananarivo"]',
'["International flights","Tips","Personal items","Travel insurance"]',
'["Indri lemur encounter","Night walk wildlife spotting","Orchid garden visit","Community school visit","Mantadia canopy walk"]',
1, 1, 'active'),

(2, 'Baobab & West Coast Explorer', 'baobab-west-coast-explorer', 'Ancient Giants & Golden Sunsets',
'Witness Madagascar''s most iconic scene — the Avenue of the Baobabs — and explore the wild west coast. From Morondava''s laid-back charm to the Kirindy Forest''s elusive fossa, this journey reveals a side of Madagascar few travelers experience.\n\nCapture the perfect sunset among 800-year-old baobab trees, cruise the Tsiribihina River through dramatic gorges, and discover the traditions of the Sakalava people.',
'Iconic baobabs, wild west coast, and authentic Sakalava culture.', 'cultural', 6, 1890.00, 1690.00, 10, 'moderate',
'["Comfortable hotel & camp accommodation","All meals","Private guide","4x4 transport","Boat cruise","Kirindy Forest entry","Photography assistance"]',
'["International flights","Tips","Personal expenses","Visa","Travel insurance"]',
'["Avenue of the Baobabs sunset","Tsiribihina River cruise","Kirindy Forest fossa tracking","Sakalava village homestay","Morondava beach relaxation"]',
1, 0, 'active'),

(6, 'Whale Watching & Sainte-Marie', 'whale-watching-sainte-marie', 'Humpback Whale Season Special',
'Time your visit to coincide with one of nature''s greatest spectacles — humpback whales migrating past Île Sainte-Marie. This exclusive seasonal tour offers intimate whale encounters from a premium catamaran, combined with the island''s rich pirate history, vibrant coral reefs, and tropical relaxation.\n\nStay in a beachfront boutique hotel, snorkel pristine reefs, and explore the island by traditional pirogue.',
'Seasonal whale encounters, pirate history, and tropical island paradise.', 'wildlife', 5, 2190.00, NULL, 6, 'easy',
'["Beachfront boutique hotel","All meals","Whale watching catamaran","Snorkeling equipment","Island tour by pirogue","Pirate cemetery visit","Domestic flights"]',
'["International flights","Tips","Personal expenses","Travel insurance"]',
'["Humpback whale encounter","Coral reef snorkeling","Pirate cemetery exploration","Pirogue island tour","Tropical beach relaxation"]',
1, 0, 'active');

-- Tour Itineraries (for the Nosy Be tour)
INSERT INTO `tour_itineraries` (`tour_id`, `day_number`, `title`, `description`, `meals`, `accommodation`) VALUES
(1, 1, 'Arrival in Paradise', 'Welcome to Nosy Be! Your private transfer takes you from the airport to your luxury eco-lodge. Settle in and enjoy a welcome cocktail as the sun sets over the Mozambique Channel.', 'Dinner', '5-Star Eco-Lodge'),
(1, 2, 'Lokobe Nature Reserve', 'Explore the ancient Lokobe rainforest by pirogue and on foot. Meet black lemurs, chameleons, and endemic birds. Afternoon at leisure by the pool.', 'Breakfast, Lunch, Dinner', '5-Star Eco-Lodge'),
(1, 3, 'Nosy Iranja — Turtle Island', 'Full-day excursion to the stunning Nosy Iranja, connected by a spectacular sandbar at low tide. Private beach picnic and snorkeling in crystal-clear waters.', 'Breakfast, Lunch, Dinner', '5-Star Eco-Lodge'),
(1, 4, 'Whale Shark Swimming', 'An unforgettable morning swimming alongside gentle whale sharks. Afternoon visit to the Ylang-Ylang distillery and Hell-Ville market.', 'Breakfast, Lunch, Dinner', '5-Star Eco-Lodge'),
(1, 5, 'Nosy Komba & Nosy Tanikely', 'Island-hopping day! Visit Nosy Komba''s lemur colony and snorkel the marine reserve at Nosy Tanikely — Madagascar''s best underwater site.', 'Breakfast, Lunch, Dinner', '5-Star Eco-Lodge'),
(1, 6, 'Sunset Catamaran Cruise', 'Morning at leisure. Afternoon private catamaran cruise with champagne as the sun paints the sky gold and crimson.', 'Breakfast, Lunch, Dinner', '5-Star Eco-Lodge'),
(1, 7, 'Farewell', 'Final morning to relax. Private transfer to the airport with a gift bag of Malagasy artisan products.', 'Breakfast', 'Departure');

-- Reviews
INSERT INTO `reviews` (`tour_id`, `reviewer_name`, `reviewer_country`, `rating`, `title`, `comment`, `is_verified`, `is_approved`, `is_featured`) VALUES
(1, 'Sophie Laurent', 'France', 5, 'Absolutely magical experience!', 'From the moment we arrived, everything was perfect. The eco-lodge was stunning, our guide was incredibly knowledgeable, and swimming with whale sharks was a life-changing experience. Nosy Luxury exceeded all expectations.', 1, 1, 1),
(1, 'James Mitchell', 'United Kingdom', 5, 'Best holiday of our lives', 'My wife and I have traveled extensively, but this trip was something special. The attention to detail, the luxury touches, and the authentic cultural experiences made this truly unforgettable. Highly recommend!', 1, 1, 1),
(2, 'Marco Rossi', 'Italy', 5, 'Adventure of a lifetime', 'The Tsingy was absolutely breathtaking. I was nervous about the via ferrata but our guide was fantastic. The suspension bridges, the incredible views — this is genuine adventure travel at its finest.', 1, 1, 1),
(4, 'Anna Schmidt', 'Germany', 5, 'Incredible wildlife encounter', 'Hearing the Indri lemurs call at dawn brought tears to my eyes. Our naturalist guide spotted creatures I never would have seen alone. The night walk was particularly memorable.', 1, 1, 0),
(3, 'David Chen', 'Canada', 4, 'Comprehensive and well-organized', 'A wonderful tour that covers the best of southern Madagascar. The Isalo natural pools were a highlight. Only minor negative was some long driving days, but the scenery made up for it.', 1, 1, 0),
(5, 'Elena Petrova', 'Russia', 5, 'Unforgettable sunsets', 'The Avenue of the Baobabs at sunset was the most beautiful thing I have ever seen. The river cruise was also incredible. This tour perfectly captures the magic of western Madagascar.', 1, 1, 1);

-- Blog Categories
INSERT INTO `blog_categories` (`name`, `slug`, `description`, `sort_order`) VALUES
('Travel Tips', 'travel-tips', 'Essential advice for planning your Madagascar adventure.', 1),
('Wildlife', 'wildlife', 'Discover Madagascar''s unique and endemic wildlife.', 2),
('Culture', 'culture', 'Explore the rich traditions and heritage of Malagasy culture.', 3),
('Destinations', 'destinations-guide', 'In-depth guides to Madagascar''s most extraordinary places.', 4);

-- Blog Posts
INSERT INTO `blog_posts` (`category_id`, `author_id`, `title`, `slug`, `excerpt`, `content`, `status`, `views`, `published_at`) VALUES
(1, 1, 'The Ultimate Guide to Planning Your Madagascar Trip', 'ultimate-guide-madagascar-trip',
'Everything you need to know before visiting the world''s most unique island — from visa requirements to the best time to visit.',
'<h2>When to Visit Madagascar</h2><p>Madagascar can be visited year-round, but the dry season (April to November) offers the most comfortable conditions for travel. The whale-watching season on Île Sainte-Marie runs from June to September.</p><h2>Visa Requirements</h2><p>Most nationalities can obtain a visa on arrival at Ivato International Airport. The fee is approximately €35 for a 30-day visa.</p><h2>What to Pack</h2><p>Layers are essential — Madagascar''s climate varies dramatically from coast to highlands. Don''t forget: good walking shoes, insect repellent, sunscreen, binoculars, and a camera with a good zoom lens.</p>',
'published', 1250, '2026-01-15 10:00:00'),
(2, 1, '10 Incredible Lemur Species You Can See in Madagascar', '10-incredible-lemur-species',
'From the giant Indri to the tiny mouse lemur, discover the fascinating world of Madagascar''s most iconic residents.',
'<h2>1. Indri (Indri indri)</h2><p>The largest living lemur, the Indri is famous for its haunting, whale-like calls that echo through the eastern rainforests. Best seen in Andasibe-Mantadia National Park.</p><h2>2. Ring-tailed Lemur</h2><p>Perhaps the most recognizable lemur, with its distinctive black and white striped tail. Found in southern Madagascar, particularly at Anja Community Reserve.</p>',
'published', 980, '2026-02-01 10:00:00');

-- Settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('site_name', 'Nosy Luxury', 'general'),
('site_tagline', 'Discover Madagascar. Redefined.', 'general'),
('site_description', 'Madagascar''s premier luxury travel operator offering curated experiences.', 'general'),
('contact_email', 'info@nosyluxury.com', 'contact'),
('contact_phone', '+261 34 00 000 00', 'contact'),
('contact_address', 'Antananarivo, Madagascar', 'contact'),
('whatsapp_number', '+261340000000', 'contact'),
('facebook_url', 'https://facebook.com/nosyluxury', 'social'),
('instagram_url', 'https://instagram.com/nosyluxury', 'social'),
('youtube_url', 'https://youtube.com/@nosyluxury', 'social'),
('twitter_url', '', 'social'),
('default_currency', 'EUR', 'booking'),
('tax_rate', '0', 'booking'),
('meta_title', 'Nosy Luxury — Discover Madagascar. Redefined.', 'seo'),
('meta_description', 'Premium Madagascar travel experiences. Luxury tours, authentic culture, and world-class service. Book your extraordinary journey today.', 'seo');

SET FOREIGN_KEY_CHECKS = 1;
