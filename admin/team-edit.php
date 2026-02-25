<?php
$admin_title = 'Ekip üyesi ekle / düzenle';
$current_admin_page = 'team';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : null;
$item = null;
if ($id) {
    $st = $pdo->prepare("SELECT * FROM team_members WHERE id = ?");
    $st->execute([$id]);
    $item = $st->fetch(PDO::FETCH_ASSOC);
    if (!$item) { header('Location: team.php'); exit; }
}

$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = !empty($_POST['is_active']) ? 1 : 0;
    $image = $item['image'] ?? '';
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (in_array($finfo->file($_FILES['image']['tmp_name']), $allowed_types)) {
            if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) ?: 'jpg';
            $filename = 'team-' . ($id ?: time()) . '-' . substr(uniqid(), -6) . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . '/' . $filename)) {
                $image = UPLOAD_URL . '/' . $filename;
            }
        } else { $error = 'Sadece JPG, PNG, GIF, WebP yüklenebilir.'; }
    }
    if (!$error) {
        if ($id) {
            $pdo->prepare("UPDATE team_members SET name=?, designation=?, image=?, phone=?, sort_order=?, is_active=? WHERE id=?")->execute([$name, $designation, $image, $phone, $sort_order, $is_active, $id]);
        } else {
            $pdo->prepare("INSERT INTO team_members (name, designation, image, phone, sort_order, is_active) VALUES (?,?,?,?,?,?)")->execute([$name, $designation, $image, $phone, $sort_order, $is_active]);
        }
        header('Location: team.php?saved=1'); exit;
    }
}
if (!$item) $item = ['name'=>'', 'designation'=>'', 'image'=>'', 'phone'=>'', 'sort_order'=>0, 'is_active'=>1];
if ($_SERVER['REQUEST_METHOD'] === 'POST') $item = ['name'=>$_POST['name']??'', 'designation'=>$_POST['designation']??'', 'image'=>$item['image']??'', 'phone'=>$_POST['phone']??'', 'sort_order'=>(int)($_POST['sort_order']??0), 'is_active'=>!empty($_POST['is_active'])?1:0];

require_once __DIR__ . '/includes/header.php';
?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card">
    <h2><?= $id ? 'Ekip üyesi düzenle' : 'Yeni ekip üyesi' ?></h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Görsel</label>
            <?php if (!empty($item['image'])): ?><p><img src="../<?= htmlspecialchars($item['image']) ?>?t=<?= time() ?>" class="img-preview"></p><?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <div class="form-group">
            <label>Ad Soyad *</label>
            <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Unvan</label>
            <input type="text" name="designation" value="<?= htmlspecialchars($item['designation']) ?>" placeholder="Gayrimenkul Danışmanı">
        </div>
        <div class="form-group">
            <label>Telefon</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($item['phone']) ?>" placeholder="<?= htmlspecialchars(setting('contact_phone')) ?>">
        </div>
        <div class="form-group">
            <label>Sıra</label>
            <input type="number" name="sort_order" value="<?= (int)$item['sort_order'] ?>">
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="is_active" value="1" <?= ($item['is_active']??1) ? 'checked' : '' ?>> Aktif</label>
        </div>
        <p><button type="submit" class="btn">Kaydet</button> <a href="team.php" class="btn btn-secondary">İptal</a></p>
    </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
