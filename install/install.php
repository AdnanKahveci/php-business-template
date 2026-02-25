<?php
/**
 * Primevilla kurulum - Sadece bir kez çalıştırın.
 * Tarayıcıdan: http://localhost/primevilla/install/install.php
 * Kurulumdan sonra bu klasörü silin veya install.php dosyasını kaldırın.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'primevilla');
define('DB_CHARSET', 'utf8mb4');

$step = isset($_GET['step']) ? (int)$_GET['step'] : 0;
$error = '';
$done = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $step === 1) {
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET,
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET " . DB_CHARSET . " COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `" . DB_NAME . "`");

        $pdo->exec("DROP TABLE IF EXISTS `contact_messages`");
        $pdo->exec("DROP TABLE IF EXISTS `pages`");
        $pdo->exec("DROP TABLE IF EXISTS `site_settings`");
        $pdo->exec("DROP TABLE IF EXISTS `admin_users`");

        $pdo->exec("CREATE TABLE `admin_users` (
          `id` int unsigned NOT NULL AUTO_INCREMENT,
          `username` varchar(64) NOT NULL,
          `password_hash` varchar(255) NOT NULL,
          `email` varchar(128) DEFAULT NULL,
          `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `username` (`username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
        $st = $pdo->prepare("INSERT INTO `admin_users` (`username`, `password_hash`, `email`) VALUES (?, ?, ?)");
        $st->execute(['admin', $adminPass, 'admin@primevilla.com']);

        $pdo->exec("CREATE TABLE `site_settings` (
          `id` int unsigned NOT NULL AUTO_INCREMENT,
          `setting_key` varchar(64) NOT NULL,
          `setting_value` text,
          PRIMARY KEY (`id`),
          UNIQUE KEY `setting_key` (`setting_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $settings = [
            ['site_name', 'Primevilla'],
            ['site_tagline', 'Emlak & Gayrimenkul'],
            ['contact_phone', '+90 212 000 00 00'],
            ['contact_email', 'info@primevilla.com'],
            ['contact_address', 'İstanbul, Türkiye'],
            ['footer_text', '© Primevilla. Tüm hakları saklıdır.'],
            ['google_map_embed', ''],
        ];
        $st = $pdo->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES (?, ?)");
        foreach ($settings as $s) $st->execute($s);

        $pdo->exec("CREATE TABLE `contact_messages` (
          `id` int unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(128) NOT NULL,
          `email` varchar(128) NOT NULL,
          `phone` varchar(32) DEFAULT NULL,
          `subject` varchar(255) DEFAULT NULL,
          `message` text NOT NULL,
          `is_read` tinyint(1) DEFAULT 0,
          `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `is_read` (`is_read`),
          KEY `created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE `pages` (
          `id` int unsigned NOT NULL AUTO_INCREMENT,
          `slug` varchar(64) NOT NULL,
          `title` varchar(255) NOT NULL,
          `content` longtext,
          `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->prepare("INSERT INTO `pages` (`slug`, `title`, `content`) VALUES (?, ?, ?)")->execute(['about', 'Hakkımızda', '<p>Primevilla olarak güvenilir gayrimenkul hizmeti sunuyoruz.</p>']);
        $pdo->prepare("INSERT INTO `pages` (`slug`, `title`, `content`) VALUES (?, ?, ?)")->execute(['home_hero', 'Ana Sayfa Başlık', 'Hayalinizdeki Eve Kavuşun']);

        $done = true;
    } catch (PDOException $e) {
        $error = 'Veritabanı hatası: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Primevilla Kurulum</title>
    <style>
        body { font-family: sans-serif; max-width: 520px; margin: 60px auto; padding: 20px; background: #f5f5f5; }
        .box { background: #fff; padding: 28px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        h1 { margin: 0 0 20px; font-size: 1.5rem; color: #333; }
        p { color: #666; margin: 0 0 20px; line-height: 1.5; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .btn { display: inline-block; background: #c9a227; color: #fff; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 1rem; }
        .btn:hover { background: #b8921f; }
        .muted { font-size: 0.9rem; color: #999; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Primevilla Kurulum</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($done): ?>
            <div class="success">Kurulum tamamlandı. Veritabanı ve varsayılan admin oluşturuldu.</div>
            <p><strong>Admin giriş:</strong><br>URL: <a href="../admin/login.php">../admin/login.php</a><br>Kullanıcı: <code>admin</code><br>Şifre: <code>admin123</code></p>
            <p class="muted">Güvenlik için kurulumdan sonra <strong>install</strong> klasörünü silin veya <strong>install.php</strong> dosyasını kaldırın.</p>
            <a href="../admin/login.php" class="btn">Admin Panele Git</a>
            <a href="../index.php" class="btn" style="background:#555; margin-left:8px;">Siteye Git</a>
        <?php else: ?>
            <p>Bu işlem <strong>primevilla</strong> veritabanını ve gerekli tabloları oluşturur. XAMPP MySQL çalışıyor olmalı.</p>
            <form method="post" action="?step=1">
                <button type="submit" class="btn">Kurulumu Başlat</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
