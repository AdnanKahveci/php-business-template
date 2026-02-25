<?php
$currentPage = 'gallery';
$pageTitle = 'Galeri';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/config/database.php';

$pdo = primevilla_pdo();
$galleryItems = [];
try {
    $galleryItems = $pdo->query("SELECT * FROM gallery_images ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

require_once __DIR__ . '/includes/header.php';
$base = BASE_ASSETS;
?>

        <!-- Page Title -->
        <section class="page-title p_relative pt_250 pb_170 centred" style="background-image: url(<?= ($bg = setting('page_title_bg')) ? $bg : $base . '/assets/images/background/page-title.jpg' ?>);">
            <div class="bg-layer p_absolute r_100 t_0" style="background-image: url(<?= $base ?>/assets/images/background/page-title.png);"></div>
            <div class="large-container">
                <div class="content-box p_relative d_block z_5">
                    <div class="title mb_25">
                        <h1 class="d_block fs_68 lh_76 color_white fw_exbold">Galeri</h1>
                    </div>
                    <ul class="bread-crumb clearfix">
                        <li class="p_relative d_iblock fs_16 color_white pl_45 pr_30 mr_10"><i class="fas fa-home"></i><a href="<?= $siteBase ?>/index.php" class="color_white hov_color">Ana Sayfa</a></li>
                        <li class="p_relative d_iblock fs_16 color_white">Galeri</li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- End Page Title -->

        <!-- gallery-section -->
        <section class="project-section p_relative sec-pad centred">
            <div class="large-container">
                <div class="sec-title mb_40">
                    <div class="icon-box p_relative d_block fs_14 lh_20 mb_25"><i class="icon-4"></i></div>
                    <span class="sub-title p_relative d_block fs_14 lh_25 mb_10">Projelerimizden ve blogdan seçilmiş kareler</span>
                    <h2 class="p_relative d_block fs_50 lh_60 fw_exbold">Galeri</h2>
                </div>
                <div class="row clearfix gallery-page-grid">
                    <?php if ($galleryItems): ?>
                        <?php foreach ($galleryItems as $i => $g): ?>
                            <?php
                                $imgUrl = strpos($g['image'], 'uploads/') === 0
                                    ? $siteBase . '/' . $g['image']
                                    : $base . '/assets/images/news/gallery-1.jpg';
                            ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12 project-block">
                                <div class="project-block-one">
                                    <div class="inner-box p_relative d_block mb_30">
                                        <figure class="image-box p_relative d_block gallery-thumb">
                                            <a href="<?= htmlspecialchars($imgUrl) ?>" data-fancybox="gallery" data-caption="<?= htmlspecialchars($g['title'] ?? '') ?>">
                                                <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($g['title'] ?? '') ?>">
                                            </a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="centred" style="padding:60px 20px; font-size:18px; color:#666;">Henüz galeri görseli eklenmedi. Admin panelinden galeriye görsel ekleyebilirsiniz.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <!-- gallery-section end -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>

