<?php
$admin_title = 'Ürün Kategorileri';
$current_admin_page = 'product-categories';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = primevilla_pdo();

// Sil
if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM product_categories WHERE id = ?")->execute([$id]);
    header('Location: product-categories.php?deleted=1');
    exit;
}

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) AS product_count FROM product_categories c ORDER BY c.sort_order ASC, c.name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert alert-success">Kategori silindi.</div>
<?php endif; ?>
<?php if (!empty($_GET['saved'])): ?>
    <div class="alert alert-success">Kategori kaydedildi.</div>
<?php endif; ?>

<div class="card">
    <h2>Ürün Kategorileri</h2>
    <p><a href="product-category-edit.php" class="btn">Yeni kategori ekle</a></p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Ad</th>
                <th>Slug</th>
                <th>Ürün sayısı</th>
                <th>Sıra</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $c): ?>
            <tr>
                <td><?= (int)$c['id'] ?></td>
                <td><?= htmlspecialchars($c['name']) ?></td>
                <td><code><?= htmlspecialchars($c['slug']) ?></code></td>
                <td><?= (int)$c['product_count'] ?></td>
                <td><?= (int)$c['sort_order'] ?></td>
                <td>
                    <a href="product-category-edit.php?id=<?= (int)$c['id'] ?>" class="btn btn-sm">Düzenle</a>
                    <?php if ((int)$c['product_count'] === 0): ?>
                        <a href="product-categories.php?delete=<?= (int)$c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?');">Sil</a>
                    <?php else: ?>
                        <span style="color:#999;">(Ürün var, silinemez)</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($categories)): ?>
        <p style="color:#666;">Henüz kategori yok. "Yeni kategori ekle" ile ekleyin.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
