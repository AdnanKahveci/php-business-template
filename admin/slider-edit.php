<?php
$admin_title = 'Slider ekle / düzenle';
$current_admin_page = 'slider';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : null;
$item = null;
if ($id) {
    $st = $pdo->prepare("SELECT * FROM slider WHERE id = ?");
    $st->execute([$id]);
    $item = $st->fetch(PDO::FETCH_ASSOC);
    if (!$item) {
        header('Location: slider.php');
        exit;
    }
}

$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $subtitle = trim($_POST['subtitle'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = !empty($_POST['is_active']) ? 1 : 0;
    $image = $item['image'] ?? '';

    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['image']['tmp_name']);
        if (in_array($mime, $allowed_types)) {
            if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) ?: 'jpg';
            $filename = 'slider-' . ($id ? $id : time()) . '-' . substr(uniqid(), -6) . '.' . $ext;
            $dest = UPLOAD_DIR . '/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image = UPLOAD_URL . '/' . $filename;
            }
        } else {
            $error = 'Sadece resim dosyaları (JPG, PNG, GIF, WebP) yüklenebilir.';
        }
    } elseif ($id && empty($image)) {
        $error = 'Mevcut bir görsel yoksa yeni görsel yüklemeniz gerekir.';
    } elseif (!$id && empty($_FILES['image']['tmp_name'])) {
        $error = 'Slider için görsel seçin.';
    }

    if (!$error) {
        if ($id) {
            $st = $pdo->prepare("UPDATE slider SET image = ?, title = ?, subtitle = ?, link = ?, sort_order = ?, is_active = ? WHERE id = ?");
            $st->execute([$image, $title, $subtitle, $link, $sort_order, $is_active, $id]);
        } else {
            $st = $pdo->prepare("INSERT INTO slider (image, title, subtitle, link, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?)");
            $st->execute([$image, $title, $subtitle, $link, $sort_order, $is_active]);
        }
        header('Location: slider.php?saved=1');
        exit;
    }
}

if (!$item && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $item = [
        'image' => '',
        'title' => '',
        'subtitle' => '',
        'link' => '',
        'sort_order' => 0,
        'is_active' => 1,
    ];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = [
        'image' => $item['image'] ?? '',
        'title' => $_POST['title'] ?? '',
        'subtitle' => $_POST['subtitle'] ?? '',
        'link' => $_POST['link'] ?? '',
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
        'is_active' => !empty($_POST['is_active']) ? 1 : 0,
    ];
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <h2><?= $id ? 'Slider düzenle' : 'Yeni slider' ?></h2>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="image">Görsel *</label>
            <?php if (!empty($item['image'])): ?>
                <p><img src="../<?= htmlspecialchars($item['image']) ?>?t=<?= time() ?>" alt="" class="img-preview"></p>
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/*" <?= !$id ? 'required' : '' ?>>
            <?php if ($id): ?><br><small>Değiştirmek için yeni dosya seçin; boş bırakırsanız mevcut görsel kalır.</small><?php endif; ?>
        </div>
        <div class="form-group">
            <label for="title">Başlık</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($item['title']) ?>">
        </div>
        <div class="form-group">
            <label for="subtitle">Alt başlık</label>
            <input type="text" id="subtitle" name="subtitle" value="<?= htmlspecialchars($item['subtitle']) ?>">
        </div>
        <div class="form-group">
            <label for="link">Link (tıklanınca gidilecek URL)</label>
            <input type="text" id="link" name="link" value="<?= htmlspecialchars($item['link']) ?>" placeholder="https://...">
        </div>
        <div class="form-group">
            <label for="sort_order">Sıra</label>
            <input type="number" id="sort_order" name="sort_order" value="<?= (int)$item['sort_order'] ?>">
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" <?= (int)($item['is_active'] ?? 1) ? 'checked' : '' ?>>
                Aktif (sitede göster)
            </label>
        </div>
        <p>
            <button type="submit" class="btn">Kaydet</button>
            <a href="slider.php" class="btn btn-secondary" style="margin-left:8px;">İptal</a>
        </p>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
