<?php
$admin_title = 'Görsel ekle / düzenle';
$current_admin_page = 'gallery';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : null;
$item = null;
if ($id) {
    $st = $pdo->prepare("SELECT * FROM gallery_images WHERE id = ?");
    $st->execute([$id]);
    $item = $st->fetch(PDO::FETCH_ASSOC);
    if (!$item) {
        header('Location: gallery.php');
        exit;
    }
}

$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $image = $item['image'] ?? '';

    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['image']['tmp_name']);
        if (in_array($mime, $allowed_types)) {
            if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) ?: 'jpg';
            $filename = 'gallery-' . ($id ? $id : time()) . '-' . substr(uniqid(), -6) . '.' . $ext;
            $dest = UPLOAD_DIR . '/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image = UPLOAD_URL . '/' . $filename;
            }
        } else {
            $error = 'Sadece JPG, PNG, GIF, WebP yüklenebilir.';
        }
    } elseif (!$id) {
        $error = 'Görsel seçin.';
    }

    if (!$error) {
        if ($id) {
            $st = $pdo->prepare("UPDATE gallery_images SET image = ?, title = ?, link = ?, sort_order = ? WHERE id = ?");
            $st->execute([$image, $title, $link, $sort_order, $id]);
        } else {
            $st = $pdo->prepare("INSERT INTO gallery_images (image, title, link, sort_order) VALUES (?, ?, ?, ?)");
            $st->execute([$image, $title, $link, $sort_order]);
        }
        header('Location: gallery.php?saved=1');
        exit;
    }
}

if (!$item && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $item = ['image' => '', 'title' => '', 'link' => '', 'sort_order' => 0];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = array_merge(['image' => $item['image'] ?? ''], $_POST);
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="card">
    <h2><?= $id ? 'Görsel düzenle' : 'Yeni görsel' ?></h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="image">Görsel *</label>
            <?php if (!empty($item['image'])): ?>
                <p><img src="../<?= htmlspecialchars($item['image']) ?>?t=<?= time() ?>" alt="" class="img-preview"></p>
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/*" <?= !$id ? 'required' : '' ?>>
        </div>
        <div class="form-group">
            <label for="title">Başlık</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($item['title'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="link">Link (tıklanınca)</label>
            <input type="text" id="link" name="link" value="<?= htmlspecialchars($item['link'] ?? '') ?>" placeholder="projeler, about.php">
        </div>
        <div class="form-group">
            <label for="sort_order">Sıra</label>
            <input type="number" id="sort_order" name="sort_order" value="<?= (int)($item['sort_order'] ?? 0) ?>">
        </div>
        <p>
            <button type="submit" class="btn">Kaydet</button>
            <a href="gallery.php" class="btn btn-secondary" style="margin-left:8px;">İptal</a>
        </p>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
