<?php
/**
 * Amortez şablonundan sayfa içeriğini çıkarır (header ve footer arası).
 * Asset yollarını $base (Amortez) ile düzeltir, .html linklerini .php yapar.
 */
function primevilla_amortez_content($amortez_file, $base = 'Amortez') {
    $path = __DIR__ . '/../Amortez/' . $amortez_file;
    if (!is_file($path)) return '<p class="p_relative pt_110 pb_120">Sayfa bulunamadı.</p>';
    $html = file_get_contents($path);
    if ($html === false) return '';
    if (preg_match('/<!-- End Mobile Menu -->\s*(.*?)\s*<!-- main-footer -->/s', $html, $m)) {
        $content = $m[1];
    } elseif (preg_match('/<\/header\s*>(.*?)<footer\s+class="main-footer"/s', $html, $m)) {
        $content = $m[1];
    } else {
        if (preg_match('/<body[^>]*>(.*?)<\/body>/s', $html, $m)) {
            $content = $m[1];
            $content = preg_replace('/^.*?<\/header\s*>/s', '', $content);
            $content = preg_replace('/<footer\s+class="main-footer".*$/s', '', $content);
        } else {
            return '<p>İçerik ayrılamadı.</p>';
        }
    }
    $content = preg_replace('/\b(href|src)=(["\'])assets\//', '$1=$2' . $base . '/assets/', $content);
    $content = preg_replace('/url\(\s*(["\']?)assets\//', 'url($1' . $base . '/assets/', $content);
    $replace_pages = ['index', 'about', 'contact', 'service', 'project', 'project-2', 'project-details', 'blog', 'blog-2', 'blog-details', 'error'];
    foreach ($replace_pages as $p) {
        $content = preg_replace('/href=(["\'])(' . preg_quote($p, '/') . ')\.html\b/', 'href=$1$2.php', $content);
    }
    return trim($content);
}
