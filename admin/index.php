<?php
$admin_title = 'Dashboard';
$current_admin_page = 'dashboard';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = primevilla_pdo();
$msgCount = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$unreadCount = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
$productCount = 0;
$categoryCount = 0;
$sliderCount = 0;
try {
    $productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $categoryCount = $pdo->query("SELECT COUNT(*) FROM product_categories")->fetchColumn();
    $sliderCount = $pdo->query("SELECT COUNT(*) FROM slider")->fetchColumn();
} catch (Exception $e) {}
?>

<div class="card">
    <h2>Hoş geldiniz, <?= htmlspecialchars($_SESSION['admin_username']) ?></h2>
    <p>Primevilla yönetim paneline giriş yaptınız. Soldaki menüden mesajları ve site ayarlarını yönetebilirsiniz.</p>
</div>

<div class="stat-grid">
    <div class="stat-box">
        <span><?= (int)$msgCount ?></span>
        <small>Toplam iletişim mesajı</small>
    </div>
    <div class="stat-box">
        <span><?= (int)$unreadCount ?></span>
        <small>Okunmamış mesaj</small>
    </div>
    <div class="stat-box">
        <span><?= (int)$categoryCount ?></span>
        <small>Ürün kategorisi</small>
    </div>
    <div class="stat-box">
        <span><?= (int)$productCount ?></span>
        <small>Ürün</small>
    </div>
    <div class="stat-box">
        <span><?= (int)$sliderCount ?></span>
        <small>Slider görseli</small>
    </div>
</div>

<div class="card">
    <h2>Hızlı işlemler</h2>
    <p>
        <a href="product-categories.php" class="btn">Ürün Kategorileri</a>
        <a href="products.php" class="btn" style="margin-left:8px;">Ürünler</a>
        <a href="slider.php" class="btn" style="margin-left:8px;">Slider</a>
        <a href="messages.php" class="btn" style="margin-left:8px;">Mesajlar</a>
        <a href="settings.php" class="btn" style="margin-left:8px;">Ayarlar</a>
        <a href="<?= dirname($_SERVER['SCRIPT_NAME'], 2) ?>/index.php" class="btn btn-secondary" style="margin-left:8px;">Siteyi aç</a>
    </p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
