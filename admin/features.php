<?php
$admin_title = 'Özellik Kartları';
$current_admin_page = 'features';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();

if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM features WHERE id = ?")->execute([$id]);
    header('Location: features.php?deleted=1');
    exit;
}

$items = $pdo->query("SELECT * FROM features ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert alert-success">Özellik silindi.</div>
<?php endif; ?>
<?php if (!empty($_GET['saved'])): ?>
    <div class="alert alert-success">Özellik kaydedildi.</div>
<?php endif; ?>

<div class="card">
    <h2>Özellik Kartları</h2>
    <p>Ana sayfadaki feature bölümündeki kartları buradan yönetin (görsel, başlık, link).</p>
    <p><a href="feature-edit.php" class="btn">Yeni özellik ekle</a></p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Görsel</th>
                <th>Başlık</th>
                <th>Link</th>
                <th>Sıra</th>
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
                        <img src="../<?= htmlspecialchars($row['image']) ?>" alt="" style="width:120px; height:60px; object-fit:cover; border-radius:4px;">
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['title'] ?? '') ?></td>
                <td><?= !empty($row['link']) ? htmlspecialchars($row['link']) : '—' ?></td>
                <td><?= (int)$row['sort_order'] ?></td>
                <td><?= (int)($row['is_active'] ?? 1) ? 'Aktif' : 'Pasif'; ?></td>
                <td>
                    <a href="feature-edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm">Düzenle</a>
                    <a href="features.php?delete=<?= (int)$row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu özelliği silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($items)): ?>
        <p style="color:#666;">Henüz özellik yok. "Yeni özellik ekle" ile ekleyin.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
