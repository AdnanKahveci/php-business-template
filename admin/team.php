<?php
$admin_title = 'Ekip';
$current_admin_page = 'team';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = primevilla_pdo();
if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $pdo->prepare("DELETE FROM team_members WHERE id = ?")->execute([(int)$_GET['delete']]);
    header('Location: team.php?deleted=1'); exit;
}
$items = $pdo->query("SELECT * FROM team_members ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success">Ekip üyesi silindi.</div><?php endif; ?>
<?php if (!empty($_GET['saved'])): ?><div class="alert alert-success">Ekip üyesi kaydedildi.</div><?php endif; ?>

<div class="card">
    <h2>Ekip Üyeleri</h2>
    <p>Ana sayfadaki ekip bölümünü buradan yönetin.</p>
    <p><a href="team-edit.php" class="btn">Yeni ekip üyesi ekle</a></p>
    <table>
        <thead>
            <tr><th>#</th><th>Görsel</th><th>Ad</th><th>Unvan</th><th>Telefon</th><th>Sıra</th><th>İşlem</th></tr>
        </thead>
        <tbody>
            <?php foreach ($items as $row): ?>
            <tr>
                <td><?= (int)$row['id'] ?></td>
                <td><?php if (!empty($row['image'])): ?><img src="../<?= htmlspecialchars($row['image']) ?>" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:50%;"><?php else: ?>—<?php endif; ?></td>
                <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['designation'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                <td><?= (int)$row['sort_order'] ?></td>
                <td>
                    <a href="team-edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm">Düzenle</a>
                    <a href="team.php?delete=<?= (int)$row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($items)): ?><p style="color:#666;">Henüz ekip üyesi yok.</p><?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
