<?php
$admin_title = 'Ödül Kartları';
$current_admin_page = 'award-cards';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = primevilla_pdo();
if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $pdo->prepare("DELETE FROM award_cards WHERE id = ?")->execute([(int)$_GET['delete']]);
    header('Location: award-cards.php?deleted=1'); exit;
}
$items = $pdo->query("SELECT * FROM award_cards ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success">Ödül kartı silindi.</div><?php endif; ?>
<?php if (!empty($_GET['saved'])): ?><div class="alert alert-success">Ödül kartı kaydedildi.</div><?php endif; ?>

<div class="card">
    <h2>Ödül Kartları</h2>
    <p>Ana sayfadaki ödül bölümündeki 2 kartı buradan yönetin. Ödül kutusu metni <a href="settings.php">Ayarlar</a> sayfasından düzenlenir.</p>
    <p><a href="award-card-edit.php" class="btn">Yeni ödül kartı ekle</a></p>
    <table>
        <thead>
            <tr><th>#</th><th>Görsel</th><th>Başlık</th><th>Alt başlık</th><th>Link</th><th>Sıra</th><th>İşlem</th></tr>
        </thead>
        <tbody>
            <?php foreach ($items as $row): ?>
            <tr>
                <td><?= (int)$row['id'] ?></td>
                <td><?php if (!empty($row['image'])): ?><img src="../<?= htmlspecialchars($row['image']) ?>" alt="" style="width:80px;height:50px;object-fit:cover;"><?php else: ?>—<?php endif; ?></td>
                <td><?= htmlspecialchars($row['title'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['subtitle'] ?? '') ?></td>
                <td><?= !empty($row['link']) ? htmlspecialchars($row['link']) : '—' ?></td>
                <td><?= (int)$row['sort_order'] ?></td>
                <td>
                    <a href="award-card-edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm">Düzenle</a>
                    <a href="award-cards.php?delete=<?= (int)$row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($items)): ?><p style="color:#666;">Henüz ödül kartı yok.</p><?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
