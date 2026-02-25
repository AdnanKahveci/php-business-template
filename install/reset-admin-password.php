<?php
/**
 * Admin şifresini "admin123" olarak sıfırlar.
 * Tarayıcıdan bir kez çalıştırın: http://localhost/primevilla/install/reset-admin-password.php
 * İşlemden sonra bu dosyayı silin.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$done = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['run'])) {
    try {
        $pdo = primevilla_pdo();
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $st = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = 'admin'");
        $st->execute([$hash]);
        if ($st->rowCount() > 0) {
            $done = true;
        } else {
            $st = $pdo->prepare("INSERT INTO admin_users (username, password_hash, email) VALUES ('admin', ?, 'admin@primevilla.com')");
            $st->execute([$hash]);
            $done = true;
        }
    } catch (PDOException $e) {
        $error = 'Hata: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin şifre sıfırlama</title>
    <style>
        body { font-family: sans-serif; max-width: 420px; margin: 60px auto; padding: 20px; background: #f5f5f5; }
        .box { background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        h1 { margin: 0 0 16px; font-size: 1.25rem; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .btn { display: inline-block; background: #c9a227; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none; margin-top: 8px; border: none; font-size: 1rem; cursor: pointer; }
        .btn:hover { background: #b8921f; }
        p { color: #666; margin: 0 0 12px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Admin şifre sıfırlama</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($done): ?>
            <div class="success">Admin şifresi "admin123" olarak ayarlandı.</div>
            <p>Giriş: <strong>admin</strong> / <strong>admin123</strong></p>
            <a href="../admin/login.php" class="btn">Admin girişe git</a>
        <?php else: ?>
            <p>Bu işlem <strong>admin</strong> kullanıcısının şifresini <strong>admin123</strong> yapar.</p>
            <form method="post" action="">
                <button type="submit" class="btn">Şifreyi sıfırla</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
