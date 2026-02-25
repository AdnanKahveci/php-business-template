<?php
$admin_title = 'Hizmetler';
$current_admin_page = 'services';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = primevilla_pdo();
if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM services WHERE id = ?")->execute([$id]);
    header('Location: services.php?deleted=1');
    exit;
}
$items = $pdo->query("SELECT * FROM services ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success">Hizmet silindi.</div><?php endif; ?>
<?php if (!empty($_GET['saved'])): ?><div class="alert alert-success">Hizmet kaydedildi.</div><?php endif; ?>

<div class="card">
    <h2>Hizmetler</h2>
    <p>Ana sayfadaki hizmet bölümündeki 3 kartı buradan yönetin.</p>
    <p><a href="service-edit.php" class="btn">Yeni hizmet ekle</a></p>
    <table>
        <thead>
            <tr><th>#</th><th>Görsel</th><th>Başlık</th><th>Link</th><th>Sıra</th><th>İşlem</th></tr>
        </thead>
        <tbody>
            <?php foreach ($items as $row): ?>
            <tr>
                <td><?= (int)$row['id'] ?></td>
                <td><?php if (!empty($row['image'])): ?><img src="../<?= htmlspecialchars($row['image']) ?>" alt="" style="width:80px;height:50px;object-fit:cover;border-radius:4px;"><?php else: ?>—<?php endif; ?></td>
                <td><?= htmlspecialchars($row['title'] ?? '') ?></td>
                <td><?= !empty($row['link']) ? htmlspecialchars($row['link']) : '—' ?></td>
                <td><?= (int)$row['sort_order'] ?></td>
                <td>
                    <a href="service-edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm">Düzenle</a>
                    <a href="services.php?delete=<?= (int)$row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($items)): ?><p style="color:#666;">Henüz hizmet yok. "Yeni hizmet ekle" ile ekleyin.</p><?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
