<?php
/**
 * Primevilla - Genel yapılandırma
 * Site adı ve Amortez şablonu asset yolu
 */

/**
 * Türkçe karakterleri İngilizce karşılıklarına çevirip URL-uyumlu slug üretir (utf8mb4 uyumlu).
 * Örn: "Güzel Ev" → "guzel-ev", "İş Yeri" → "is-yeri", "Çocuk Odası" → "cocuk-odasi"
 */
function primevilla_slugify(string $text): string {
    $tr = ['ı' => 'i', 'ğ' => 'g', 'ü' => 'u', 'ş' => 's', 'ö' => 'o', 'ç' => 'c',
           'İ' => 'i', 'Ğ' => 'g', 'Ü' => 'u', 'Ş' => 's', 'Ö' => 'o', 'Ç' => 'c'];
    $s = strtr(trim($text), $tr);
    $s = mb_strtolower($s, 'UTF-8');
    $s = preg_replace('/[^a-z0-9\s\-]/u', '', $s);
    $s = preg_replace('/[\s\-]+/', '-', trim($s, " \t\n\r\0\x0B-"));
    return $s !== '' ? $s : 'sayfa';
}

define('SITE_NAME', 'Primevilla');
define('SITE_TAGLINE', 'Emlak & Gayrimenkul');
define('BASE_ASSETS', 'Amortez'); // CSS, JS, resimler Amortez klasöründen

// Site kök URL yolu - projeler/blog alt sayfalarında da doğru link için
$docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
$appDir = str_replace('\\', '/', realpath(__DIR__ . '/..'));
if ($docRoot && $appDir && strpos($appDir, $docRoot) === 0) {
    $rel = trim(substr($appDir, strlen($docRoot)), '/');
    define('SITE_BASE', $rel === '' ? '/' : '/' . $rel);
} else {
    define('SITE_BASE', '/');
}

// Kök URL (ör: http://localhost/primevilla)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
define('BASE_URL', $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/'));
define('BASE_PATH', __DIR__ . '/..');
define('UPLOAD_DIR', BASE_PATH . '/uploads');
define('UPLOAD_URL', 'uploads');

// Oturum (admin için)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
