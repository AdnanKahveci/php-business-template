<?php
$admin_title = 'Ürün ekle / düzenle';
$current_admin_page = 'products';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : null;
$item = null;
if ($id) {
    $st = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $st->execute([$id]);
    $item = $st->fetch(PDO::FETCH_ASSOC);
    if (!$item) {
        header('Location: products.php');
        exit;
    }
}

$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if ($title === '') {
        $error = 'Başlık gerekli.';
    } else {
        $slug = trim($slug) !== '' ? primevilla_slugify($slug) : primevilla_slugify($title);
        $category_id = !empty($_POST['category_id']) && ctype_digit($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $description = trim($_POST['description'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $area = trim($_POST['area'] ?? '');
        $status = in_array($_POST['status'] ?? '', ['active', 'draft']) ? $_POST['status'] : 'active';
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $image = $item['image'] ?? null;

        if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['image']['tmp_name']);
            if (in_array($mime, $allowed_types)) {
                if (!is_dir(UPLOAD_DIR)) @mkdir(UPLOAD_DIR, 0755, true);
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) ?: 'jpg';
                $filename = 'product-' . ($id ? $id : time()) . '-' . substr(uniqid(), -6) . '.' . $ext;
                $dest = UPLOAD_DIR . '/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $image = UPLOAD_URL . '/' . $filename;
                }
            }
        }

        if ($id) {
            $st = $pdo->prepare("UPDATE products SET category_id = ?, title = ?, slug = ?, description = ?, content = ?, image = ?, price = ?, location = ?, area = ?, status = ?, sort_order = ?, updated_at = NOW() WHERE id = ?");
            $st->execute([$category_id, $title, $slug, $description, $content, $image, $price, $location, $area, $status, $sort_order, $id]);
        } else {
            $st = $pdo->prepare("INSERT INTO products (category_id, title, slug, description, content, image, price, location, area, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $st->execute([$category_id, $title, $slug, $description, $content, $image, $price, $location, $area, $status, $sort_order]);
        }
        header('Location: products.php?saved=1');
        exit;
    }
}

if (!$item && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $item = [
        'category_id' => null,
        'title' => '',
        'slug' => '',
        'description' => '',
        'content' => '',
        'image' => null,
        'price' => '',
        'location' => '',
        'area' => '',
        'status' => 'active',
        'sort_order' => 0,
    ];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = [
        'category_id' => $_POST['category_id'] ?? null,
        'title' => $_POST['title'] ?? '',
        'slug' => $_POST['slug'] ?? '',
        'description' => $_POST['description'] ?? '',
        'content' => $_POST['content'] ?? '',
        'image' => $item['image'] ?? null,
        'price' => $_POST['price'] ?? '',
        'location' => $_POST['location'] ?? '',
        'area' => $_POST['area'] ?? '',
        'status' => $_POST['status'] ?? 'active',
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
    ];
}

$categories = $pdo->query("SELECT id, name FROM product_categories ORDER BY sort_order, name")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <h2><?= $id ? 'Ürün düzenle' : 'Yeni ürün' ?></h2>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="category_id">Kategori</label>
            <select id="category_id" name="category_id">
                <option value="">— Seçin —</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= (isset($item['category_id']) && (int)$item['category_id'] === (int)$c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="title">Başlık *</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug (URL)</label>
            <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($item['slug']) ?>" placeholder="Otomatik">
        </div>
        <div class="form-group">
            <label for="description">Kısa açıklama</label>
            <textarea id="description" name="description" rows="2"><?= htmlspecialchars($item['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="content">İçerik (HTML)</label>
            <textarea id="content" name="content" rows="6"><?= htmlspecialchars($item['content']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Görsel</label>
            <?php if (!empty($item['image'])): ?>
                <p><img src="../<?= htmlspecialchars($item['image']) ?>?t=<?= time() ?>" alt="" class="img-preview"></p>
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/*">
        </div>
        <div class="form-group">
            <label for="price">Fiyat</label>
            <input type="text" id="price" name="price" value="<?= htmlspecialchars($item['price']) ?>" placeholder="Örn: 2.500.000 TL">
        </div>
        <div class="form-group">
            <label for="location">Konum</label>
            <input type="text" id="location" name="location" value="<?= htmlspecialchars($item['location']) ?>">
        </div>
        <div class="form-group">
            <label for="area">m²</label>
            <input type="text" id="area" name="area" value="<?= htmlspecialchars($item['area']) ?>" placeholder="Örn: 120">
        </div>
        <div class="form-group">
            <label for="status">Durum</label>
            <select id="status" name="status">
                <option value="active" <?= ($item['status'] ?? '') === 'active' ? 'selected' : '' ?>>Yayında</option>
                <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Taslak</option>
            </select>
        </div>
        <div class="form-group">
            <label for="sort_order">Sıra</label>
            <input type="number" id="sort_order" name="sort_order" value="<?= (int)($item['sort_order'] ?? 0) ?>">
        </div>
        <p>
            <button type="submit" class="btn">Kaydet</button>
            <a href="products.php" class="btn btn-secondary" style="margin-left:8px;">İptal</a>
        </p>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
