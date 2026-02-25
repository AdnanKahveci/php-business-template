<?php
/**
 * Mevcut veritabanına yeni tabloları ekler (ürün kategorileri, ürünler, slider).
 * Bir kez çalıştırın: http://localhost/primevilla/install/update-db.php
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$error = '';
$done = false;

try {
    $pdo = primevilla_pdo();
    $pdo->exec("CREATE TABLE IF NOT EXISTS `product_categories` (
      `id` int unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(128) NOT NULL,
      `slug` varchar(128) NOT NULL,
      `description` text,
      `sort_order` int DEFAULT 0,
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `slug` (`slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `products` (
      `id` int unsigned NOT NULL AUTO_INCREMENT,
      `category_id` int unsigned DEFAULT NULL,
      `title` varchar(255) NOT NULL,
      `slug` varchar(255) NOT NULL,
      `description` text,
      `content` longtext,
      `image` varchar(255) DEFAULT NULL,
      `price` varchar(64) DEFAULT NULL,
      `location` varchar(255) DEFAULT NULL,
      `area` varchar(64) DEFAULT NULL,
      `status` enum('active','draft') DEFAULT 'active',
      `sort_order` int DEFAULT 0,
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `slug` (`slug`),
      KEY `category_id` (`category_id`),
      KEY `status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `features` (
      `id` int unsigned NOT NULL AUTO_INCREMENT,
      `image` varchar(255) DEFAULT NULL,
      `title` varchar(255) DEFAULT NULL,
      `link` varchar(500) DEFAULT NULL,
      `sort_order` int DEFAULT 0,
      `is_active` tinyint(1) DEFAULT 1,
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `slider` (
      `id` int unsigned NOT NULL AUTO_INCREMENT,
      `image` varchar(255) NOT NULL,
      `title` varchar(255) DEFAULT NULL,
      `subtitle` varchar(255) DEFAULT NULL,
      `link` varchar(500) DEFAULT NULL,
      `sort_order` int DEFAULT 0,
      `is_active` tinyint(1) DEFAULT 1,
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `blogs` (
      `id` int unsigned NOT NULL AUTO_INCREMENT,
      `title` varchar(255) NOT NULL,
      `slug` varchar(255) NOT NULL,
      `excerpt` text,
      `content` longtext,
      `image` varchar(255) DEFAULT NULL,
      `category_id` int unsigned DEFAULT NULL,
      `author` varchar(128) DEFAULT NULL,
      `status` enum('active','draft') DEFAULT 'active',
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `slug` (`slug`),
      KEY `category_id` (`category_id`),
      KEY `status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `gallery_images` (
      `id` int unsigned NOT NULL AUTO_INCREMENT,
      `image` varchar(255) NOT NULL,
      `title` varchar(255) DEFAULT NULL,
      `link` varchar(500) DEFAULT NULL,
      `sort_order` int DEFAULT 0,
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $done = true;
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Veritabanı Güncelleme</title>
    <style>
        body { font-family: sans-serif; max-width: 480px; margin: 60px auto; padding: 20px; background: #f5f5f5; }
        .box { background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .btn { display: inline-block; background: #c9a227; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Veritabanı güncelleme</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($done): ?>
            <div class="success">Ürün kategorileri, ürünler, özellik kartları, slider, blog ve galeri tabloları eklendi.</div>
            <a href="../admin/" class="btn">Admin panele git</a>
        <?php endif; ?>
    </div>
</body>
</html>
