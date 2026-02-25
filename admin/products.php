<?php
$admin_title = 'Ürünler';
$current_admin_page = 'products';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = primevilla_pdo();

// Sil
if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    header('Location: products.php?deleted=1');
    exit;
}

$categoryFilter = isset($_GET['category_id']) && ctype_digit($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$categories = $pdo->query("SELECT id, name FROM product_categories ORDER BY sort_order, name")->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN product_categories c ON p.category_id = c.id WHERE 1=1";
$params = [];
if ($categoryFilter) {
    $sql .= " AND p.category_id = ?";
    $params[] = $categoryFilter;
}
$sql .= " ORDER BY p.sort_order ASC, p.created_at DESC";
$st = $pdo->prepare($sql);
$st->execute($params);
$products = $st->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert alert-success">Ürün silindi.</div>
<?php endif; ?>
<?php if (!empty($_GET['saved'])): ?>
    <div class="alert alert-success">Ürün kaydedildi.</div>
<?php endif; ?>

<div class="card">
    <h2>Ürünler</h2>
    <p>
        <a href="product-edit.php" class="btn">Yeni ürün ekle</a>
        <form method="get" action="" style="display:inline-block; margin-left:16px;">
            <select name="category_id" onchange="this.form.submit()">
                <option value="">Tüm kategoriler</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int)$cat['id'] ?>" <?= $categoryFilter === (int)$cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Görsel</th>
                <th>Başlık</th>
                <th>Kategori</th>
                <th>Fiyat / Konum</th>
                <th>Durum</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><?= (int)$p['id'] ?></td>
                <td>
                    <?php if (!empty($p['image'])): ?>
                        <img src="../<?= htmlspecialchars($p['image']) ?>" alt="" style="width:60px; height:40px; object-fit:cover; border-radius:4px;">
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
                <td><?= htmlspecialchars($p['price'] ?: $p['location'] ?: '—') ?></td>
                <td><?= $p['status'] === 'active' ? 'Yayında' : 'Taslak'; ?></td>
                <td>
                    <a href="product-edit.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm">Düzenle</a>
                    <a href="products.php?delete=<?= (int)$p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($products)): ?>
        <p style="color:#666;">Henüz ürün yok veya seçilen kategoride ürün yok. "Yeni ürün ekle" ile ekleyin.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
