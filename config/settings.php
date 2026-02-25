<?php
/**
 * Veritabanından site ayarlarını döndürür (önbelleklenmiş).
 */
function primevilla_settings() {
    static $settings = null;
    if ($settings === null) {
        if (!defined('SITE_NAME')) require_once __DIR__ . '/config.php';
        try {
            require_once __DIR__ . '/database.php';
            $pdo = primevilla_pdo();
            $rows = $pdo->query("SELECT setting_key, setting_value FROM site_settings")->fetchAll(PDO::FETCH_ASSOC);
            $settings = [];
            foreach ($rows as $r) {
                $settings[$r['setting_key']] = $r['setting_value'];
            }
        } catch (Exception $e) {
            $settings = [
                'site_name' => SITE_NAME,
                'site_tagline' => SITE_TAGLINE ?? 'Emlak & Gayrimenkul',
                'contact_phone' => '',
                'contact_email' => '',
                'contact_address' => '',
                'footer_text' => '© ' . date('Y') . ' Primevilla.',
                'google_map_embed' => '',
            ];
        }
    }
    return $settings;
}

function setting($key, $default = '') {
    $s = primevilla_settings();
    return $s[$key] ?? $default;
}
