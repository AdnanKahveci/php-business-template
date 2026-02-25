<?php
$admin_title = 'Müşteri yorumu ekle / düzenle';
$current_admin_page = 'testimonials';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : null;
$item = null;
if ($id) {
    $st = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
    $st->execute([$id]);
    $item = $st->fetch(PDO::FETCH_ASSOC);
    if (!$item) { header('Location: testimonials.php'); exit; }
}

$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quote = trim($_POST['quote'] ?? '');
    $author_name = trim($_POST['author_name'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = !empty($_POST['is_active']) ? 1 : 0;
    $image = $item['image'] ?? '';
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (in_array($finfo->file($_FILES['image']['tmp_name']), $allowed_types)) {
            if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) ?: 'jpg';
            $filename = 'testimonial-' . ($id ?: time()) . '-' . substr(uniqid(), -6) . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . '/' . $filename)) {
                $image = UPLOAD_URL . '/' . $filename;
            }
        } else { $error = 'Sadece JPG, PNG, GIF, WebP yüklenebilir.'; }
    }
    if (!$error) {
        if ($id) {
            $pdo->prepare("UPDATE testimonials SET quote=?, author_name=?, designation=?, image=?, sort_order=?, is_active=? WHERE id=?")->execute([$quote, $author_name, $designation, $image, $sort_order, $is_active, $id]);
        } else {
            $pdo->prepare("INSERT INTO testimonials (quote, author_name, designation, image, sort_order, is_active) VALUES (?,?,?,?,?,?)")->execute([$quote, $author_name, $designation, $image, $sort_order, $is_active]);
        }
        header('Location: testimonials.php?saved=1'); exit;
    }
}
if (!$item) $item = ['quote'=>'', 'author_name'=>'', 'designation'=>'', 'image'=>'', 'sort_order'=>0, 'is_active'=>1];
if ($_SERVER['REQUEST_METHOD'] === 'POST') $item = ['quote'=>$_POST['quote']??'', 'author_name'=>$_POST['author_name']??'', 'designation'=>$_POST['designation']??'', 'image'=>$item['image']??'', 'sort_order'=>(int)($_POST['sort_order']??0), 'is_active'=>!empty($_POST['is_active'])?1:0];

require_once __DIR__ . '/includes/header.php';
?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <h2><?= $id ? 'Yorum düzenle' : 'Yeni yorum' ?></h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Yorum metni *</label>
            <textarea name="quote" rows="4" required><?= htmlspecialchars($item['quote']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Yazar adı</label>
            <input type="text" name="author_name" value="<?= htmlspecialchars($item['author_name']) ?>" placeholder="Mutlu Müşteri">
        </div>
        <div class="form-group">
            <label>Unvan</label>
            <input type="text" name="designation" value="<?= htmlspecialchars($item['designation']) ?>" placeholder="Emlak Sahibi">
        </div>
        <div class="form-group">
            <label>Görsel</label>
            <?php if (!empty($item['image'])): ?><p><img src="../<?= htmlspecialchars($item['image']) ?>?t=<?= time() ?>" class="img-preview"></p><?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <div class="form-group">
            <label>Sıra</label>
            <input type="number" name="sort_order" value="<?= (int)$item['sort_order'] ?>">
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="is_active" value="1" <?= ($item['is_active']??1) ? 'checked' : '' ?>> Aktif</label>
        </div>
        <p><button type="submit" class="btn">Kaydet</button> <a href="testimonials.php" class="btn btn-secondary">İptal</a></p>
    </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
