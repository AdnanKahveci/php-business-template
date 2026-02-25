<?php
$admin_title = 'Slider Görselleri';
$current_admin_page = 'slider';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = primevilla_pdo();

// Sil
if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM slider WHERE id = ?")->execute([$id]);
    header('Location: slider.php?deleted=1');
    exit;
}

$items = $pdo->query("SELECT * FROM slider ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert alert-success">Slider öğesi silindi.</div>
<?php endif; ?>
<?php if (!empty($_GET['saved'])): ?>
    <div class="alert alert-success">Slider kaydedildi.</div>
<?php endif; ?>
<?php if (!empty($_GET['bulk'])): ?>
    <div class="alert alert-success"><?= (int)$_GET['bulk'] ?> görsel slider'a eklendi.</div>
<?php endif; ?>

<div class="card">
    <h2>Slider Görselleri</h2>
    <p>
    <a href="slider-edit.php" class="btn">Yeni slider ekle</a>
    <a href="slider-bulk.php" class="btn" style="margin-left:8px;">Toplu görsel ekle</a>
</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Görsel</th>
                <th>Başlık / Alt başlık</th>
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
                <td>
                    <strong><?= htmlspecialchars($row['title'] ?? '') ?></strong>
                    <?php if (!empty($row['subtitle'])): ?>
                        <br><small><?= htmlspecialchars($row['subtitle']) ?></small>
                    <?php endif; ?>
                </td>
                <td><?= !empty($row['link']) ? htmlspecialchars($row['link']) : '—' ?></td>
                <td><?= (int)$row['sort_order'] ?></td>
                <td><?= (int)($row['is_active'] ?? 1) ? 'Aktif' : 'Pasif'; ?></td>
                <td>
                    <a href="slider-edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm">Düzenle</a>
                    <a href="slider.php?delete=<?= (int)$row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu slider öğesini silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($items)): ?>
        <p style="color:#666;">Henüz slider görseli yok. "Yeni slider ekle" ile ekleyin.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
