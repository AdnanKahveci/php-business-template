<?php
$admin_title = $admin_title ?? 'Dashboard';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/auth.php';

$current_admin_page = $current_admin_page ?? '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Primevilla Admin - <?= htmlspecialchars($admin_title) ?></title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f0f0f0; }
        .admin-wrap { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 260px; background: #1a1a2e; color: #fff; flex-shrink: 0; }
        .admin-sidebar .logo { padding: 20px; font-weight: 700; font-size: 1.2rem; color: #c9a227; border-bottom: 1px solid rgba(255,255,255,.1); }
        .admin-sidebar .logo a { color: inherit; text-decoration: none; }
        .admin-sidebar nav { padding: 16px 0; }
        .admin-sidebar .nav-section { margin-bottom: 8px; }
        .admin-sidebar .nav-section-title { padding: 8px 20px; font-size: 0.7rem; text-transform: uppercase; color: rgba(255,255,255,.5); }
        .admin-sidebar .nav-link { display: block; padding: 10px 20px; color: rgba(255,255,255,.9); text-decoration: none; transition: background .15s; }
        .admin-sidebar .nav-link:hover { background: rgba(255,255,255,.08); color: #c9a227; }
        .admin-sidebar .nav-link.active { background: rgba(201,162,39,.2); color: #c9a227; }
        .admin-main { flex: 1; display: flex; flex-direction: column; }
        .admin-header { background: #fff; padding: 14px 24px; box-shadow: 0 1px 3px rgba(0,0,0,.06); display: flex; align-items: center; justify-content: space-between; }
        .admin-header h1 { margin: 0; font-size: 1.25rem; font-weight: 600; }
        .admin-header .user { font-size: 0.9rem; color: #666; }
        .admin-header .user a { color: #c9a227; text-decoration: none; margin-left: 12px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 24px; flex: 1; }
        .card { background: #fff; border-radius: 10px; padding: 24px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .card h2 { margin: 0 0 16px; font-size: 1.25rem; }
        .btn { display: inline-block; padding: 10px 18px; background: #c9a227; color: #fff; text-decoration: none; border-radius: 6px; font-size: 0.9rem; border: none; cursor: pointer; }
        .btn:hover { background: #b8921f; }
        .btn-sm { padding: 6px 12px; font-size: 0.85rem; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; color: #333; }
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
        .stat-box { background: linear-gradient(135deg, #c9a227, #b8921f); color: #fff; padding: 20px; border-radius: 10px; }
        .stat-box span { display: block; font-size: 1.8rem; font-weight: 700; }
        .stat-box small { opacity: .9; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger { background: #f8d7da; color: #721c24; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 500; }
        .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="number"], .form-group select, .form-group textarea { width: 100%; max-width: 500px; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; }
        .form-group input[type="file"] { margin-top: 4px; }
        .form-actions { margin-top: 24px; padding-top: 20px; border-top: 1px solid #eee; }
        .form-actions .btn { margin-right: 10px; }
        .img-preview { max-width: 200px; max-height: 120px; object-fit: cover; border: 1px solid #eee; border-radius: 6px; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="admin-wrap">
        <aside class="admin-sidebar">
            <div class="logo"><a href="index.php">Primevilla Admin</a></div>
            <nav>
                <div class="nav-section">
                    <div class="nav-section-title">Genel</div>
                    <a href="index.php" class="nav-link <?= $current_admin_page === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Ürünler</div>
                    <a href="product-categories.php" class="nav-link <?= $current_admin_page === 'product-categories' ? 'active' : '' ?>">Ürün Kategorileri</a>
                    <a href="products.php" class="nav-link <?= $current_admin_page === 'products' ? 'active' : '' ?>">Ürünler</a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Ana Sayfa</div>
                    <a href="slider.php" class="nav-link <?= $current_admin_page === 'slider' ? 'active' : '' ?>">Slider Görselleri</a>
                    <a href="features.php" class="nav-link <?= $current_admin_page === 'features' ? 'active' : '' ?>">Özellik Kartları</a>
                    <a href="services.php" class="nav-link <?= $current_admin_page === 'services' ? 'active' : '' ?>">Hizmetler</a>
                    <a href="home-skills.php" class="nav-link <?= $current_admin_page === 'home-skills' ? 'active' : '' ?>">Skills Bölümü</a>
                    <a href="award-cards.php" class="nav-link <?= $current_admin_page === 'award-cards' ? 'active' : '' ?>">Ödül Kartları</a>
                    <a href="team.php" class="nav-link <?= $current_admin_page === 'team' ? 'active' : '' ?>">Ekip</a>
                    <a href="testimonials.php" class="nav-link <?= $current_admin_page === 'testimonials' ? 'active' : '' ?>">Müşteri Yorumları</a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Blog & Galeri</div>
                    <a href="blogs.php" class="nav-link <?= $current_admin_page === 'blogs' ? 'active' : '' ?>">Blog Yazıları</a>
                    <a href="gallery.php" class="nav-link <?= $current_admin_page === 'gallery' ? 'active' : '' ?>">Görseller</a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Site</div>
                    <a href="messages.php" class="nav-link <?= $current_admin_page === 'messages' ? 'active' : '' ?>">Mesajlar</a>
                    <a href="settings.php" class="nav-link <?= $current_admin_page === 'settings' ? 'active' : '' ?>">Ayarlar</a>
                </div>
                <div class="nav-section">
                    <a href="logout.php" class="nav-link">Çıkış (<?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?>)</a>
                </div>
            </nav>
        </aside>
        <div class="admin-main">
            <header class="admin-header">
                <h1><?= htmlspecialchars($admin_title) ?></h1>
                <div class="user">
                    <a href="<?= dirname($_SERVER['SCRIPT_NAME'], 2) ?>/index.php" target="_blank">Siteyi aç →</a>
                </div>
            </header>
            <main class="container">
