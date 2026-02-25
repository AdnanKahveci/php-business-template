<?php
$admin_title = 'Slider\'a Toplu Görsel Ekle';
$current_admin_page = 'slider';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$error = '';
$added = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_FILES['images']['tmp_name']) || !is_array($_FILES['images']['tmp_name'])) {
        $error = 'Lütfen en az bir görsel seçin.';
    } else {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);

        $files = $_FILES['images'];
        $count = is_array($files['tmp_name']) ? count($files['tmp_name']) : 0;

        for ($i = 0; $i < $count; $i++) {
            $tmp = $files['tmp_name'][$i] ?? '';
            if (empty($tmp) || !is_uploaded_file($tmp)) continue;

            $mime = $finfo->file($tmp);
            if (!in_array($mime, $allowed_types)) continue;

            $ext = pathinfo($files['name'][$i] ?? '', PATHINFO_EXTENSION) ?: 'jpg';
            $filename = 'slider-' . time() . '-' . $i . '-' . substr(uniqid(), -6) . '.' . $ext;
            $dest = UPLOAD_DIR . '/' . $filename;

            if (move_uploaded_file($tmp, $dest)) {
                $image = UPLOAD_URL . '/' . $filename;
                $maxOrder = (int)$pdo->query("SELECT COALESCE(MAX(sort_order), 0) FROM slider")->fetchColumn();
                $st = $pdo->prepare("INSERT INTO slider (image, title, subtitle, link, sort_order, is_active) VALUES (?, ?, ?, ?, ?, 1)");
                $st->execute([$image, 'Başlık', 'Alt başlık', 'projeler', $maxOrder + $i + 1]);
                $added++;
            }
        }

        if ($added > 0) {
            header('Location: slider.php?bulk=' . $added);
            exit;
        }
        if ($error === '' && $count > 0) {
            $error = 'Geçerli görsel yüklenemedi. Sadece JPG, PNG, GIF veya WebP kullanın.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <h2>Slider'a Toplu Görsel Ekle</h2>
    <p>Birden fazla görsel seçerek tek seferde slider'a ekleyebilirsiniz. Her görsel ayrı bir slide olarak eklenir. Başlık ve alt başlığı sonradan düzenleme sayfasından değiştirebilirsiniz.</p>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="images">Görseller (çoklu seçim)</label>
            <input type="file" id="images" name="images[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple required>
            <small>Ctrl/Cmd ile birden fazla dosya seçebilirsiniz.</small>
        </div>
        <p>
            <button type="submit" class="btn">Görselleri Ekle</button>
            <a href="slider.php" class="btn btn-secondary" style="margin-left:8px;">Geri</a>
        </p>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
