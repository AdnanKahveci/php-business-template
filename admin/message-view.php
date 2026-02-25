<?php
$admin_title = 'Mesaj detayı';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: messages.php');
    exit;
}

$pdo = primevilla_pdo();
$st = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
$st->execute([$id]);
$m = $st->fetch(PDO::FETCH_ASSOC);
if (!$m) {
    header('Location: messages.php');
    exit;
}

$pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$id]);
?>

<div class="card">
    <p><a href="messages.php">← Mesajlara dön</a></p>
    <h2><?= htmlspecialchars($m['subject']) ?></h2>
    <p><strong>Gönderen:</strong> <?= htmlspecialchars($m['name']) ?></p>
    <p><strong>E-posta:</strong> <a href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a></p>
    <p><strong>Telefon:</strong> <?= htmlspecialchars($m['phone'] ?? '-') ?></p>
    <p><strong>Tarih:</strong> <?= date('d.m.Y H:i', strtotime($m['created_at'])) ?></p>
    <hr style="margin: 16px 0;">
    <div><?= nl2br(htmlspecialchars($m['message'])) ?></div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
