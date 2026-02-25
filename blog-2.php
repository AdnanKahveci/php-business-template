<?php
$currentPage = 'blog';
$pageTitle = 'Blog Listesi';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/amortez-content.php';
$base = BASE_ASSETS;
echo primevilla_amortez_content('blog-2.html', $base);
require_once __DIR__ . '/includes/footer.php';
