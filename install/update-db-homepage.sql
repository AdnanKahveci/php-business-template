-- Ana sayfa bölümleri için tablolar
-- phpMyAdmin'de çalıştırın veya update-db-homepage.php kullanın

SET NAMES utf8mb4;

-- Hizmetler (service-section)
CREATE TABLE IF NOT EXISTS `services` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(500) DEFAULT NULL,
  `sort_order` int DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ekip üyeleri (team-section)
CREATE TABLE IF NOT EXISTS `team_members` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `designation` varchar(128) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `sort_order` int DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Müşteri yorumları (testimonial-section)
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `quote` text NOT NULL,
  `author_name` varchar(128) DEFAULT NULL,
  `designation` varchar(128) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Skills bölümü - ilerleme çubukları
CREATE TABLE IF NOT EXISTS `home_progress` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `percent` int unsigned NOT NULL DEFAULT 0,
  `sort_order` int DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Skills bölümü - amenity ikonları (icon-1..icon-6)
CREATE TABLE IF NOT EXISTS `home_amenities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `icon_num` tinyint unsigned DEFAULT 1,
  `sort_order` int DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Skills bölümü - sayaçlar
CREATE TABLE IF NOT EXISTS `home_counters` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `number` int unsigned NOT NULL DEFAULT 0,
  `suffix` varchar(8) DEFAULT NULL,
  `label` varchar(128) NOT NULL,
  `icon_class` varchar(32) DEFAULT 'icon-14',
  `sort_order` int DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ödül kartları (award-section)
CREATE TABLE IF NOT EXISTS `award_cards` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(500) DEFAULT NULL,
  `sort_order` int DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
