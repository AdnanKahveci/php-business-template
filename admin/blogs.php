<?php
$admin_title = 'Blog Yazıları';
$current_admin_page = 'blogs';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();

if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM blogs WHERE id = ?")->execute([$id]);
    header('Location: blogs.php?deleted=1');
    exit;
}

$items = $pdo->query("SELECT b.*, c.name AS category_name FROM blogs b LEFT JOIN product_categories c ON c.id = b.category_id ORDER BY b.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert alert-success">Blog yazısı silindi.</div>
<?php endif; ?>
<?php if (!empty($_GET['saved'])): ?>
    <div class="alert alert-success">Blog yazısı kaydedildi.</div>
<?php endif; ?>

<div class="card">
    <h2>Blog Yazıları</h2>
    <p><a href="blog-edit.php" class="btn">Yeni blog yazısı</a></p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Görsel</th>
                <th>Başlık</th>
                <th>Kategori</th>
                <th>Tarih</th>
                <th>Durum</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $row): ?>
            <tr>
                <td><?= (int)$row['id'] ?></td>
                <td>
                    <?php if (!empty($row['image'])): ?>
                        <img src="../<?= htmlspecialchars($row['image']) ?>" alt="" style="width:80px; height:50px; object-fit:cover; border-radius:4px;">
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['category_name'] ?? '—') ?></td>
                <td><?= date('d.m.Y', strtotime($row['created_at'])) ?></td>
                <td><?= $row['status'] === 'active' ? 'Aktif' : 'Taslak'; ?></td>
                <td>
                    <a href="blog-edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm">Düzenle</a>
                    <a href="blogs.php?delete=<?= (int)$row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu yazıyı silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($items)): ?>
        <p style="color:#666;">Henüz blog yazısı yok.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
