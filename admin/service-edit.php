<?php
$admin_title = 'Hizmet ekle / düzenle';
$current_admin_page = 'services';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : null;
$item = null;
if ($id) {
    $st = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $st->execute([$id]);
    $item = $st->fetch(PDO::FETCH_ASSOC);
    if (!$item) { header('Location: services.php'); exit; }
}

$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = !empty($_POST['is_active']) ? 1 : 0;
    $image = $item['image'] ?? '';
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (in_array($finfo->file($_FILES['image']['tmp_name']), $allowed_types)) {
            if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) ?: 'jpg';
            $filename = 'service-' . ($id ?: time()) . '-' . substr(uniqid(), -6) . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . '/' . $filename)) {
                $image = UPLOAD_URL . '/' . $filename;
            }
        } else { $error = 'Sadece JPG, PNG, GIF, WebP yüklenebilir.'; }
    }
    if (!$error) {
        if ($id) {
            $pdo->prepare("UPDATE services SET title=?, image=?, link=?, sort_order=?, is_active=? WHERE id=?")->execute([$title, $image, $link, $sort_order, $is_active, $id]);
        } else {
            $pdo->prepare("INSERT INTO services (title, image, link, sort_order, is_active) VALUES (?,?,?,?,?)")->execute([$title, $image, $link, $sort_order, $is_active]);
        }
        header('Location: services.php?saved=1'); exit;
    }
}
if (!$item) $item = ['title'=>'', 'image'=>'', 'link'=>'', 'sort_order'=>0, 'is_active'=>1];
if ($_SERVER['REQUEST_METHOD'] === 'POST') $item = ['title'=>$_POST['title']??'', 'image'=>$item['image']??'', 'link'=>$_POST['link']??'', 'sort_order'=>(int)($_POST['sort_order']??0), 'is_active'=>!empty($_POST['is_active'])?1:0];

require_once __DIR__ . '/includes/header.php';
?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <h2><?= $id ? 'Hizmet düzenle' : 'Yeni hizmet' ?></h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Görsel</label>
            <?php if (!empty($item['image'])): ?><p><img src="../<?= htmlspecialchars($item['image']) ?>?t=<?= time() ?>" class="img-preview"></p><?php endif; ?>
            <input type="file" name="image" accept="image/*"> <?php if ($id): ?><small>Boş bırakırsanız mevcut kalır.</small><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Başlık *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required placeholder="Satılık Konut">
        </div>
        <div class="form-group">
            <label>Link</label>
            <input type="text" name="link" value="<?= htmlspecialchars($item['link']) ?>" placeholder="projeler, contact.php">
        </div>
        <div class="form-group">
            <label>Sıra</label>
            <input type="number" name="sort_order" value="<?= (int)$item['sort_order'] ?>">
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="is_active" value="1" <?= ($item['is_active']??1) ? 'checked' : '' ?>> Aktif</label>
        </div>
        <p><button type="submit" class="btn">Kaydet</button> <a href="services.php" class="btn btn-secondary">İptal</a></p>
    </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
