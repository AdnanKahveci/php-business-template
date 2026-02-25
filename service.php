<?php
$currentPage = 'service';
$pageTitle = 'Hizmetlerimiz';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/amortez-content.php';
$base = BASE_ASSETS;
echo primevilla_amortez_content('service.html', $base);
require_once __DIR__ . '/includes/footer.php';
