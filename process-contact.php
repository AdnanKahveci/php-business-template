<?php
/**
 * İletişim formu işleyici – veritabanına kaydeder ve yönlendirir.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

$name = isset($_POST['name']) ? trim(preg_replace('/[^\p{L}\p{N}\s\-\.]/u', '', $_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$phone = isset($_POST['phone']) ? trim(preg_replace('/[^0-9\+\s\-]/', '', $_POST['phone'])) : '';
$subject = isset($_POST['subject']) ? trim(preg_replace('/[^\p{L}\p{N}\s\-\.\,\?\!]/u', '', $_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(preg_replace('/(From:|To:|BCC:|CC:|Content-Type:)/i', '', $_POST['message'])) : '';

$ok = ($name !== '' && $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) && $subject !== '' && $message !== '');

if ($ok) {
    try {
        $pdo = primevilla_pdo();
        $st = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $st->execute([$name, $email, $phone, $subject, $message]);
    } catch (PDOException $e) {
        $ok = false;
    }
}

header('Location: contact.php?message=' . ($ok ? 'success' : 'error'));
exit;
