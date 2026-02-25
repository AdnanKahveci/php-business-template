<?php
$admin_title = 'Görseller';
$current_admin_page = 'gallery';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();

if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM gallery_images WHERE id = ?")->execute([$id]);
    header('Location: gallery.php?deleted=1');
    exit;
}

$items = $pdo->query("SELECT * FROM gallery_images ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert alert-success">Görsel silindi.</div>
<?php endif; ?>
<?php if (!empty($_GET['saved'])): ?>
    <div class="alert alert-success">Görsel kaydedildi.</div>
<?php endif; ?>

<div class="card">
    <h2>Görseller</h2>
    <p>Blog sayfasındaki "Photo Gallery" bölümünde görünecektir.</p>
    <p><a href="gallery-edit.php" class="btn">Yeni görsel ekle</a></p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Görsel</th>
                <th>Başlık</th>
                <th>Link</th>
                <th>Sıra</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $row): ?>
            <tr>
                <td><?= (int)$row['id'] ?></td>
                <td>
                    <img src="../<?= htmlspecialchars($row['image']) ?>" alt="" style="width:80px; height:60px; object-fit:cover; border-radius:4px;">
                </td>
                <td><?= htmlspecialchars($row['title'] ?? '') ?></td>
                <td><?= !empty($row['link']) ? htmlspecialchars($row['link']) : '—' ?></td>
                <td><?= (int)$row['sort_order'] ?></td>
                <td>
                    <a href="gallery-edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm">Düzenle</a>
                    <a href="gallery.php?delete=<?= (int)$row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istiyor musunuz?');">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($items)): ?>
        <p style="color:#666;">Henüz görsel yok.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
