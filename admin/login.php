<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    if ($user === '' || $pass === '') {
        $error = 'Kullanıcı adı ve şifre gerekli.';
    } else {
        try {
            $pdo = primevilla_pdo();
            $st = $pdo->prepare("SELECT id, username, password_hash FROM admin_users WHERE username = ? LIMIT 1");
            $st->execute([$user]);
            $row = $st->fetch();
            if ($row && password_verify($pass, $row['password_hash'])) {
                $_SESSION['admin_id'] = (int)$row['id'];
                $_SESSION['admin_username'] = $row['username'];
                header('Location: index.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Veritabanı bağlantı hatası. Kurulumu yaptınız mı?';
        }
        if ($error === '') $error = 'Kullanıcı adı veya şifre hatalı.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Primevilla Admin - Giriş</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); }
        .login-box { width: 100%; max-width: 360px; padding: 32px; background: #fff; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,.2); }
        h1 { margin: 0 0 24px; font-size: 1.5rem; color: #333; text-align: center; }
        .logo { text-align: center; margin-bottom: 20px; font-size: 1.8rem; font-weight: 700; color: #c9a227; }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 500; color: #444; }
        input[type="text"], input[type="password"] { width: 100%; padding: 12px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; }
        input:focus { outline: none; border-color: #c9a227; }
        .btn { width: 100%; padding: 12px; background: #c9a227; color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #b8921f; }
        .error { background: #f8d7da; color: #721c24; padding: 10px 12px; border-radius: 8px; margin-bottom: 16px; font-size: 0.9rem; }
        .back { text-align: center; margin-top: 16px; }
        .back a { color: #666; text-decoration: none; font-size: 0.9rem; }
        .back a:hover { color: #c9a227; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">Primevilla</div>
        <h1>Yönetim paneline giriş</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Kullanıcı adı</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Giriş yap</button>
        </form>
        <div class="back"><a href="<?= dirname($_SERVER['SCRIPT_NAME'], 2) ?>/index.php">← Siteye dön</a></div>
    </div>
</body>
</html>
