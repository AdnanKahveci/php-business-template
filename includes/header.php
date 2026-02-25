<?php
if (!defined('BASE_ASSETS')) {
    require_once __DIR__ . '/../config/config.php';
}
require_once __DIR__ . '/../config/settings.php';
$siteBase = defined('SITE_BASE') ? SITE_BASE : (rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/') ?: '/');
$base = $siteBase . '/' . BASE_ASSETS;
$siteName = setting('site_name', SITE_NAME);
$siteTagline = setting('site_tagline', SITE_TAGLINE);
$contactPhone = setting('contact_phone');
$currentPage = $currentPage ?? 'home';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?><?= htmlspecialchars($siteName) ?></title>
    <link rel="icon" href="<?= $base ?>/assets/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/font-awesome-all.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/flaticon.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/owl.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/jquery.fancybox.min.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/animate.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/nice-select.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/color.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/global.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/blog.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/elpath.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/responsive.css" rel="stylesheet">
</head>
<body>
    <div class="boxed_wrapper">
        <div class="loader-wrap">
            <div class="preloader">
                <div class="preloader-close">x</div>
                <div id="handle-preloader" class="handle-preloader">
                    <div class="animation-preloader">
                        <div class="spinner"></div>
                        <div class="txt-loading">
                            <span data-text-preloader="P" class="letters-loading">P</span>
                            <span data-text-preloader="r" class="letters-loading">r</span>
                            <span data-text-preloader="i" class="letters-loading">i</span>
                            <span data-text-preloader="m" class="letters-loading">m</span>
                            <span data-text-preloader="e" class="letters-loading">e</span>
                            <span data-text-preloader="v" class="letters-loading">v</span>
                            <span data-text-preloader="i" class="letters-loading">i</span>
                            <span data-text-preloader="l" class="letters-loading">l</span>
                            <span data-text-preloader="l" class="letters-loading">l</span>
                            <span data-text-preloader="a" class="letters-loading">a</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
        (function(){
            function hidePreloader(){
                var e=document.querySelector('.loader-wrap');
                if(e)e.style.display='none';
            }
            if(document.readyState==='complete')setTimeout(hidePreloader,800);
            else window.addEventListener('load',function(){setTimeout(hidePreloader,800);});
            setTimeout(hidePreloader,3000);
        })();
        </script>

        <header class="main-header header-style-one">
            <div class="header-lower">
                <div class="large-container">
                    <div class="outer-box">
                        <div class="logo-box">
                            <figure class="logo"><a href="<?= $siteBase ?>/index.php"><img src="<?= ($logoUrl = setting('logo_url')) ? $siteBase . '/' . $logoUrl : $base . '/assets/images/logo.png' ?>" alt="<?= htmlspecialchars($siteName) ?>"></a></figure>
                        </div>
                        <div class="menu-area clearfix">
                            <div class="mobile-nav-toggler">
                                <i class="icon-bar"></i>
                                <i class="icon-bar"></i>
                                <i class="icon-bar"></i>
                            </div>
                            <nav class="main-menu navbar-expand-md navbar-light">
                                <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                    <ul class="navigation clearfix">
                                        <li class="<?= $currentPage === 'home' ? 'current ' : '' ?>dropdown"><a href="<?= $siteBase ?>/index.php">Ana Sayfa</a>
                                            <ul>
                                                <li><a href="<?= $siteBase ?>/index.php">Ana Sayfa 01</a></li>
                                                <li><a href="<?= $base ?>/index-2.html">Ana Sayfa 02</a></li>
                                                <li><a href="<?= $base ?>/index-3.html">Ana Sayfa 03</a></li>
                                                <li><a href="<?= $base ?>/index-onepage.html">OnePage</a></li>
                                                <li><a href="<?= $base ?>/index-rtl.html">RTL</a></li>
                                            </ul>
                                        </li>
                                        <li class="<?= in_array($currentPage, ['about','service']) ? 'current ' : '' ?>dropdown"><a href="<?= $siteBase ?>/about.php">Hakkımızda</a>
                                            <ul>
                                                <li><a href="<?= $siteBase ?>/about.php">Hakkımızda</a></li>
                                                <li><a href="<?= $siteBase ?>/service.php">Hizmetlerimiz</a></li>
                                                <li><a href="<?= $siteBase ?>/error.php">404</a></li>
                                            </ul>
                                        </li>
                                        <li class="<?= in_array($currentPage, ['project','project-details']) ? 'current ' : '' ?>"><a href="<?= $siteBase ?>/projeler">Projeler</a></li>
                                        <li class="<?= $currentPage === 'gallery' ? 'current ' : '' ?>"><a href="<?= $siteBase ?>/gallery.php">Galeri</a></li>
                                        <li class="<?= strpos($currentPage, 'element') !== false ? 'current ' : '' ?>dropdown"><a href="<?= $siteBase ?>/elements.php">Elements</a>
                                            <div class="megamenu">
                                                <div class="row clearfix">
                                                    <div class="col-xl-6 column">
                                                        <ul>
                                                            <li><h4>Elements 1</h4></li>
                                                            <li><a href="<?= $base ?>/about-element-1.html">About Block 01</a></li>
                                                            <li><a href="<?= $base ?>/about-element-2.html">About Block 02</a></li>
                                                            <li><a href="<?= $base ?>/about-element-3.html">About Block 03</a></li>
                                                            <li><a href="<?= $base ?>/feature-element-1.html">Feature Block 01</a></li>
                                                            <li><a href="<?= $base ?>/feature-element-2.html">Feature Block 02</a></li>
                                                            <li><a href="<?= $base ?>/Feature-element-3.html">Feature Block 03</a></li>
                                                            <li><a href="<?= $base ?>/Feature-element-4.html">Feature Block 04</a></li>
                                                            <li><a href="<?= $base ?>/service-element-1.html">Service Block 01</a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-xl-6 column">
                                                        <ul>
                                                            <li><h4>Elements 2</h4></li>
                                                            <li><a href="<?= $base ?>/service-element-2.html">Service Block 02</a></li>
                                                            <li><a href="<?= $base ?>/team-element-1.html">Team Block 01</a></li>
                                                            <li><a href="<?= $base ?>/team-element-2.html">Team Block 02</a></li>
                                                            <li><a href="<?= $base ?>/news-element-1.html">News Block 01</a></li>
                                                            <li><a href="<?= $base ?>/news-element-2.html">News Block 02</a></li>
                                                            <li><a href="<?= $base ?>/funfact-element-1.html">Funfact Block 01</a></li>
                                                            <li><a href="<?= $base ?>/funfact-element-2.html">Funfact Block 02</a></li>
                                                            <li><a href="<?= $base ?>/clients-element.html">Clients Block</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="<?= in_array($currentPage, ['blog','blog-details']) ? 'current ' : '' ?>"><a href="<?= $siteBase ?>/blog">Blog</a></li>
                                        <li class="<?= $currentPage === 'contact' ? 'current ' : '' ?>"><a href="<?= $siteBase ?>/contact.php">İletişim</a></li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <div class="nav-right">
                            <div class="support-box">
                                <div class="icon-box"><i class="far fa-phone"></i></div>
                                <span>Bizi arayın</span>
                                <h3><a href="tel:<?= preg_replace('/\s+/', '', $contactPhone) ?>"><?= htmlspecialchars($contactPhone ?: '—') ?></a></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sticky-header">
                <div class="large-container">
                    <div class="outer-box">
                        <div class="logo-box">
                            <figure class="logo"><a href="<?= $siteBase ?>/index.php"><img src="<?= ($logoUrl = setting('logo_url')) ? $siteBase . '/' . $logoUrl : $base . '/assets/images/logo.png' ?>" alt=""></a></figure>
                        </div>
                        <div class="menu-area clearfix">
                            <nav class="main-menu clearfix"></nav>
                        </div>
                        <div class="nav-right">
                            <div class="support-box">
                                <div class="icon-box"><i class="far fa-phone"></i></div>
                                <span>Bizi arayın</span>
                                <h3><a href="tel:<?= preg_replace('/\s+/', '', $contactPhone) ?>"><?= htmlspecialchars($contactPhone ?: '—') ?></a></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="mobile-menu">
            <div class="menu-backdrop"></div>
            <div class="close-btn"><i class="fas fa-times"></i></div>
            <nav class="menu-box">
                <div class="nav-logo"><a href="<?= $siteBase ?>/index.php"><img src="<?= ($mobileLogo = setting('logo_url')) ? $siteBase . '/' . $mobileLogo : $base . '/assets/images/logo-2.png' ?>" alt=""></a></div>
                <div class="menu-outer"></div>
                <div class="contact-info">
                    <h4>İletişim</h4>
                    <ul>
                        <li><?= htmlspecialchars(setting('contact_address', '—')) ?></li>
                        <li><a href="tel:<?= preg_replace('/\s+/', '', $contactPhone) ?>"><?= htmlspecialchars($contactPhone ?: '—') ?></a></li>
                        <li><a href="mailto:<?= htmlspecialchars(setting('contact_email')) ?>"><?= htmlspecialchars(setting('contact_email', '—')) ?></a></li>
                    </ul>
                </div>
            </nav>
        </div>
