<?php
/**
 * Ana sayfa bölümleri için tabloları ekler.
 * http://localhost/primevilla/install/update-db-homepage.php
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$error = '';
$done = false;

try {
    $pdo = primevilla_pdo();
    $pdo->exec("CREATE TABLE IF NOT EXISTS `services` (`id` int unsigned NOT NULL AUTO_INCREMENT, `title` varchar(128) NOT NULL, `image` varchar(255) DEFAULT NULL, `link` varchar(500) DEFAULT NULL, `sort_order` int DEFAULT 0, `is_active` tinyint(1) DEFAULT 1, `created_at` datetime DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `team_members` (`id` int unsigned NOT NULL AUTO_INCREMENT, `name` varchar(128) NOT NULL, `designation` varchar(128) DEFAULT NULL, `image` varchar(255) DEFAULT NULL, `phone` varchar(64) DEFAULT NULL, `sort_order` int DEFAULT 0, `is_active` tinyint(1) DEFAULT 1, `created_at` datetime DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `testimonials` (`id` int unsigned NOT NULL AUTO_INCREMENT, `quote` text NOT NULL, `author_name` varchar(128) DEFAULT NULL, `designation` varchar(128) DEFAULT NULL, `image` varchar(255) DEFAULT NULL, `sort_order` int DEFAULT 0, `is_active` tinyint(1) DEFAULT 1, `created_at` datetime DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `home_progress` (`id` int unsigned NOT NULL AUTO_INCREMENT, `title` varchar(128) NOT NULL, `percent` int unsigned NOT NULL DEFAULT 0, `sort_order` int DEFAULT 0, `is_active` tinyint(1) DEFAULT 1, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `home_amenities` (`id` int unsigned NOT NULL AUTO_INCREMENT, `title` varchar(128) NOT NULL, `icon_num` tinyint unsigned DEFAULT 1, `sort_order` int DEFAULT 0, `is_active` tinyint(1) DEFAULT 1, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `home_counters` (`id` int unsigned NOT NULL AUTO_INCREMENT, `number` int unsigned NOT NULL DEFAULT 0, `suffix` varchar(8) DEFAULT NULL, `label` varchar(128) NOT NULL, `icon_class` varchar(32) DEFAULT 'icon-14', `sort_order` int DEFAULT 0, `is_active` tinyint(1) DEFAULT 1, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `award_cards` (`id` int unsigned NOT NULL AUTO_INCREMENT, `title` varchar(255) NOT NULL, `subtitle` varchar(255) DEFAULT NULL, `image` varchar(255) DEFAULT NULL, `link` varchar(500) DEFAULT NULL, `sort_order` int DEFAULT 0, `is_active` tinyint(1) DEFAULT 1, `created_at` datetime DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
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
    <title>Ana Sayfa Tabloları</title>
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
        <h1>Ana sayfa tabloları</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($done): ?>
            <div class="success">Hizmetler, Ekip, Müşteri Yorumları, Skills ve Ödül tabloları eklendi.</div>
            <a href="../admin/" class="btn">Admin panele git</a>
        <?php endif; ?>
    </div>
</body>
</html>
