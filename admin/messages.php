<?php
$admin_title = 'İletişim Mesajları';
$current_admin_page = 'messages';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = primevilla_pdo();

// Okundu işaretle
if (isset($_GET['read']) && ctype_digit($_GET['read'])) {
    $id = (int)$_GET['read'];
    $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$id]);
    header('Location: messages.php');
    exit;
}

// Sil
if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$id]);
    header('Location: messages.php?deleted=1');
    exit;
}

$messages = $pdo->query("SELECT id, name, email, phone, subject, LEFT(message, 80) AS message_short, is_read, created_at FROM contact_messages ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert alert-success">Mesaj silindi.</div>
<?php endif; ?>

<div class="card">
    <h2>İletişim mesajları</h2>
    <?php if (empty($messages)): ?>
        <p>Henüz mesaj yok.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Ad</th>
                    <th>E-posta</th>
                    <th>Konu</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $m): ?>
                    <tr style="<?= $m['is_read'] ? '' : 'background:#f8f9fa;' ?>">
                        <td><?= date('d.m.Y H:i', strtotime($m['created_at'])) ?></td>
                        <td><?= htmlspecialchars($m['name']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a></td>
                        <td><?= htmlspecialchars($m['subject']) ?></td>
                        <td><?= $m['is_read'] ? 'Okundu' : '<strong>Yeni</strong>' ?></td>
                        <td>
                            <a href="message-view.php?id=<?= (int)$m['id'] ?>" class="btn btn-sm">Görüntüle</a>
                            <?php if (!$m['is_read']): ?>
                                <a href="?read=<?= (int)$m['id'] ?>" class="btn btn-sm">Okundu işaretle</a>
                            <?php endif; ?>
                            <a href="?delete=<?= (int)$m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
