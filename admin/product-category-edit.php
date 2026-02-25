<?php
$admin_title = 'Kategori ekle / düzenle';
$current_admin_page = 'product-categories';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : null;
$item = null;
if ($id) {
    $st = $pdo->prepare("SELECT * FROM product_categories WHERE id = ?");
    $st->execute([$id]);
    $item = $st->fetch(PDO::FETCH_ASSOC);
    if (!$item) {
        header('Location: product-categories.php');
        exit;
    }
}

$error = '';
$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if ($name === '') {
        $error = 'Kategori adı gerekli.';
    } else {
        $slug = trim($slug) !== '' ? primevilla_slugify($slug) : primevilla_slugify($name);
        $description = trim($_POST['description'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);

        if ($id) {
            $st = $pdo->prepare("UPDATE product_categories SET name = ?, slug = ?, description = ?, sort_order = ? WHERE id = ?");
            $st->execute([$name, $slug, $description, $sort_order, $id]);
        } else {
            $st = $pdo->prepare("INSERT INTO product_categories (name, slug, description, sort_order) VALUES (?, ?, ?, ?)");
            $st->execute([$name, $slug, $description, $sort_order]);
        }
        header('Location: product-categories.php?saved=1');
        exit;
    }
}

if (!$saved && !$item && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $item = [
        'name' => '',
        'slug' => '',
        'description' => '',
        'sort_order' => 0,
    ];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = [
        'name' => $_POST['name'] ?? '',
        'slug' => $_POST['slug'] ?? '',
        'description' => $_POST['description'] ?? '',
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
    ];
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <h2><?= $id ? 'Kategori düzenle' : 'Yeni kategori' ?></h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="name">Kategori adı *</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="slug">Slug (URL)</label>
            <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($item['slug']) ?>" placeholder="Otomatik doldurulur">
        </div>
        <div class="form-group">
            <label for="description">Açıklama</label>
            <textarea id="description" name="description" rows="3"><?= htmlspecialchars($item['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="sort_order">Sıra</label>
            <input type="number" id="sort_order" name="sort_order" value="<?= (int)$item['sort_order'] ?>">
        </div>
        <p>
            <button type="submit" class="btn">Kaydet</button>
            <a href="product-categories.php" class="btn btn-secondary" style="margin-left:8px;">İptal</a>
        </p>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
