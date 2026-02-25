<?php
$currentPage = 'element';
$pageTitle = 'Elements';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/includes/header.php';
$base = BASE_ASSETS;
?>
        <section class="page-title p_relative pt_250 pb_170 centred" style="background-image: url(<?= ($bg = setting('page_title_bg')) ? $bg : $base . '/assets/images/background/page-title.jpg' ?>);">
            <div class="bg-layer p_absolute r_100 t_0" style="background-image: url(<?= $base ?>/assets/images/background/page-title.png);"></div>
            <div class="large-container">
                <div class="content-box p_relative d_block z_5">
                    <div class="title mb_25">
                        <h1 class="d_block fs_68 lh_76 color_white fw_exbold">Elements</h1>
                    </div>
                    <ul class="bread-crumb clearfix">
                        <li class="p_relative d_iblock fs_16 color_white pl_45 pr_30 mr_10"><i class="fas fa-home"></i><a href="index.php" class="color_white hov_color">Ana Sayfa</a></li>
                        <li class="p_relative d_iblock fs_16 color_white">Elements</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="about-section bg-color-1 p_relative sec-pad">
            <div class="large-container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                        <h2 class="mb_25">Şablon element sayfaları</h2>
                        <p class="mb_30">Aşağıdaki linkler Amortez şablonundaki element sayfalarına gider.</p>
                        <div class="row clearfix">
                            <div class="col-md-6">
                                <h4 class="mb_15">Elements 1</h4>
                                <ul class="links-list clearfix">
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
                            <div class="col-md-6">
                                <h4 class="mb_15">Elements 2</h4>
                                <ul class="links-list clearfix">
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
                </div>
            </div>
        </section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
