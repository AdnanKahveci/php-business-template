<?php
$admin_title = 'Blog yazısı ekle / düzenle';
$current_admin_page = 'blogs';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : null;
$item = null;
if ($id) {
    $st = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
    $st->execute([$id]);
    $item = $st->fetch(PDO::FETCH_ASSOC);
    if (!$item) {
        header('Location: blogs.php');
        exit;
    }
}

$categories = $pdo->query("SELECT id, name FROM product_categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? '';
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $author = trim($_POST['author'] ?? '');
    $status = ($_POST['status'] ?? '') === 'active' ? 'active' : 'draft';
    $image = $item['image'] ?? '';

    if (empty($title)) $error = 'Başlık gerekli.';
    $slug = trim($slug) !== '' ? primevilla_slugify($slug) : primevilla_slugify($title);

    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['image']['tmp_name']);
        if (in_array($mime, $allowed_types)) {
            if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) ?: 'jpg';
            $filename = 'blog-' . ($id ? $id : time()) . '-' . substr(uniqid(), -6) . '.' . $ext;
            $dest = UPLOAD_DIR . '/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image = UPLOAD_URL . '/' . $filename;
            }
        } else {
            $error = 'Sadece JPG, PNG, GIF, WebP yüklenebilir.';
        }
    }

    if (!$error) {
        $st = $pdo->prepare("SELECT id FROM blogs WHERE slug = ? AND id != ?");
        $st->execute([$slug, $id ?: 0]);
        if ($st->fetch()) $error = 'Bu slug zaten kullanılıyor.';
    }

    if (!$error) {
        if ($id) {
            $st = $pdo->prepare("UPDATE blogs SET title = ?, slug = ?, excerpt = ?, content = ?, image = ?, category_id = ?, author = ?, status = ? WHERE id = ?");
            $st->execute([$title, $slug, $excerpt, $content, $image, $category_id, $author, $status, $id]);
        } else {
            $st = $pdo->prepare("INSERT INTO blogs (title, slug, excerpt, content, image, category_id, author, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $st->execute([$title, $slug, $excerpt, $content, $image, $category_id, $author, $status]);
        }
        header('Location: blogs.php?saved=1');
        exit;
    }
}

if (!$item && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $item = ['title' => '', 'slug' => '', 'excerpt' => '', 'content' => '', 'image' => '', 'category_id' => null, 'author' => 'Admin', 'status' => 'active'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = array_merge(['image' => $item['image'] ?? ''], $_POST);
    $item['category_id'] = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="card">
    <h2><?= $id ? 'Blog düzenle' : 'Yeni blog yazısı' ?></h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Başlık *</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug (URL) *</label>
            <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($item['slug']) ?>" placeholder="otomatik oluşturulur">
        </div>
        <div class="form-group">
            <label for="image">Görsel</label>
            <?php if (!empty($item['image'])): ?>
                <p><img src="../<?= htmlspecialchars($item['image']) ?>?t=<?= time() ?>" alt="" class="img-preview"></p>
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/*">
        </div>
        <div class="form-group">
            <label for="excerpt">Özet / Kısa açıklama</label>
            <textarea id="excerpt" name="excerpt" rows="3"><?= htmlspecialchars($item['excerpt']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="content">İçerik</label>
            <textarea id="content" name="content" rows="12"><?= htmlspecialchars($item['content']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="category_id">Kategori</label>
            <select id="category_id" name="category_id">
                <option value="">— Seçin —</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= (int)$c['id'] ?>" <?= (($item['category_id'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="author">Yazar</label>
            <input type="text" id="author" name="author" value="<?= htmlspecialchars($item['author'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="status">Durum</label>
            <select id="status" name="status">
                <option value="active" <?= ($item['status'] ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Taslak</option>
            </select>
        </div>
        <p>
            <button type="submit" class="btn">Kaydet</button>
            <a href="blogs.php" class="btn btn-secondary" style="margin-left:8px;">İptal</a>
        </p>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
