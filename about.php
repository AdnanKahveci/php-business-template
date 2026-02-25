<?php
$currentPage = 'about';
$pageTitle = 'Hakkımızda';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/includes/header.php';

$base = BASE_ASSETS;
$aboutTitle = 'Hakkımızda';
$aboutContent = '<p>Primevilla olarak güvenilir gayrimenkul hizmeti sunuyoruz.</p>';
try {
    require_once __DIR__ . '/config/database.php';
    $pdo = primevilla_pdo();
    $st = $pdo->query("SELECT title, content FROM pages WHERE slug = 'about' LIMIT 1");
    if ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $aboutTitle = $row['title'];
        $aboutContent = $row['content'] ?: $aboutContent;
    }
} catch (Exception $e) {}
?>

        <section class="page-title p_relative pt_250 pb_170 centred" style="background-image: url(<?= ($bg = setting('page_title_bg')) ? $bg : $base . '/assets/images/background/page-title.jpg' ?>);">
            <div class="bg-layer p_absolute r_100 t_0" style="background-image: url(<?= $base ?>/assets/images/background/page-title.png);"></div>
            <div class="large-container">
                <div class="content-box p_relative d_block z_5">
                    <div class="title mb_25">
                        <h1 class="d_block fs_68 lh_76 color_white fw_exbold"><?= htmlspecialchars($aboutTitle) ?></h1>
                    </div>
                    <ul class="bread-crumb clearfix">
                        <li class="p_relative d_iblock fs_16 color_white pl_45 pr_30 mr_10"><i class="fas fa-home"></i><a href="index.php" class="color_white hov_color">Ana Sayfa</a></li>
                        <li class="p_relative d_iblock fs_16 color_white"><?= htmlspecialchars($aboutTitle) ?></li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="about-section bg-color-1 p_relative sec-pad">
            <div class="bg-layer p_absolute" style="background-image: url(<?= $base ?>/assets/images/shape/shape-1.png);"></div>
            <div class="large-container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                        <div class="content_block_1">
                            <div class="content-box p_relative d_block mr_90">
                                <div class="sec-title mb_25">
                                    <div class="icon-box p_relative d_block fs_14 lh_20 mb_15"><i class="icon-4"></i></div>
                                    <h2 class="p_relative d_block fs_50 lh_60 fw_exbold"><?= htmlspecialchars(setting('site_name')) ?> – Güvenilir Emlak Ortağınız</h2>
                                </div>
                                <div class="text p_relative d_block mb_30">
                                    <?= $aboutContent ?>
                                </div>
                                <div class="btn-box">
                                    <a href="contact.php" class="theme-btn btn-one">İletişime geçin<i class="icon-3"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                        <div class="image-box p_relative d_block pl_140 pt_90">
                            <figure class="image image-1 p_relative paroller"><img src="<?= ($img1 = setting('about_image_1')) ? $img1 : $base . '/assets/images/resource/about-1.jpg' ?>" alt=""></figure>
                            <figure class="image image-2 p_absolute paroller-2"><img src="<?= ($img2 = setting('about_image_2')) ? $img2 : $base . '/assets/images/resource/about-2.jpg' ?>" alt=""></figure>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
