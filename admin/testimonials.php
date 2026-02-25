<?php
$admin_title = 'Müşteri Yorumları';
$current_admin_page = 'testimonials';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = primevilla_pdo();
if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $pdo->prepare("DELETE FROM testimonials WHERE id = ?")->execute([(int)$_GET['delete']]);
    header('Location: testimonials.php?deleted=1'); exit;
}
$items = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success">Yorum silindi.</div><?php endif; ?>
<?php if (!empty($_GET['saved'])): ?><div class="alert alert-success">Yorum kaydedildi.</div><?php endif; ?>

<div class="card">
    <h2>Müşteri Yorumları</h2>
    <p>Ana sayfadaki müşteri yorumları carousel bölümünü buradan yönetin.</p>
    <p><a href="testimonial-edit.php" class="btn">Yeni yorum ekle</a></p>
    <table>
        <thead>
            <tr><th>#</th><th>Yazar</th><th>Unvan</th><th>Yorum (özet)</th><th>Sıra</th><th>İşlem</th></tr>
        </thead>
        <tbody>
            <?php foreach ($items as $row): ?>
            <tr>
                <td><?= (int)$row['id'] ?></td>
                <td><?= htmlspecialchars($row['author_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['designation'] ?? '') ?></td>
                <td><?= htmlspecialchars(mb_substr($row['quote'] ?? '', 0, 60)) ?>...</td>
                <td><?= (int)$row['sort_order'] ?></td>
                <td>
                    <a href="testimonial-edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm">Düzenle</a>
                    <a href="testimonials.php?delete=<?= (int)$row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($items)): ?><p style="color:#666;">Henüz yorum yok.</p><?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
