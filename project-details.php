<?php
$currentPage = 'project-details';
$pageTitle = 'Proje Detay';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/config/database.php';

$pdo = primevilla_pdo();
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : null;
$product = null;
if ($slug && preg_match('/^[a-z0-9-]+$/', $slug)) {
    $st = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN product_categories c ON c.id = p.category_id WHERE p.slug = ? AND p.status = 'active'");
    $st->execute([$slug]);
    $product = $st->fetch(PDO::FETCH_ASSOC);
}
if (!$product) {
    header('Location: projeler');
    exit;
}

$pageTitle = $product['title'];
require_once __DIR__ . '/includes/header.php';
$img = !empty($product['image']) ? $siteBase . '/' . $product['image'] : $base . '/assets/images/project/project-21.jpg';
?>

        <!-- Page Title -->
        <section class="page-title p_relative pt_250 pb_170 centred" style="background-image: url(<?= ($bg = setting('page_title_bg')) ? $siteBase . '/' . $bg : $base . '/assets/images/background/page-title.jpg' ?>);">
            <div class="bg-layer p_absolute r_100 t_0" style="background-image: url(<?= $base ?>/assets/images/background/page-title.png);"></div>
            <div class="large-container">
                <div class="content-box p_relative d_block z_5">
                    <div class="title mb_25">
                        <h1 class="d_block fs_68 lh_76 color_white fw_exbold"><?= htmlspecialchars($product['title']) ?></h1>
                    </div>
                    <ul class="bread-crumb clearfix">
                        <li class="p_relative d_iblock fs_16 color_white pl_45 pr_30 mr_10"><i class="fas fa-home"></i><a href="<?= $siteBase ?>/index.php" class="color_white hov_color">Ana Sayfa</a></li>
                        <li class="p_relative d_iblock fs_16 color_white pr_30 mr_10"><a href="<?= $siteBase ?>/projeler" class="color_white hov_color">Projeler</a></li>
                        <li class="p_relative d_iblock fs_16 color_white"><?= htmlspecialchars($product['title']) ?></li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- End Page Title -->

        <!-- project-details -->
        <section class="project-details p_relative">
            <div class="large-container">
                <div class="project-details-content sec-pad p_relative">
                    <div class="row clearfix">
                        <div class="col-lg-7 col-md-12 col-sm-12 image-column">
                            <figure class="image-box p_relative d_block"><img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($product['title']) ?>"></figure>
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 content-column">
                            <div class="content-box p_relative d_block ml_30">
                                <h2 class="d_block fs_30 lh_40 fw_exbold mb_25">Proje Özeti</h2>
                                <?php if (!empty($product['description'])): ?>
                                    <h3 class="d_block fs_24 lh_30 mb_16"><?= htmlspecialchars($product['description']) ?></h3>
                                <?php endif; ?>
                                <?php if (!empty($product['content'])): ?>
                                    <div class="lh_30 mb_50"><?= $product['content'] ?></div>
                                <?php elseif (empty($product['description'])): ?>
                                    <p class="lh_30 mb_50"><?= htmlspecialchars($product['title']) ?> projesi hakkında detaylı bilgi için bizimle iletişime geçin.</p>
                                <?php endif; ?>
                                <ul class="project-info clearfix p_relative d_block mb_70">
                                    <?php if (!empty($product['category_name'])): ?>
                                    <li class="p_relative d_block fs_18 lh_30 mb_20"><h5 class="d_iblock fs_18 fw_sbold w_200">Kategori</h5><?= htmlspecialchars($product['category_name']) ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($product['location'])): ?>
                                    <li class="p_relative d_block fs_18 lh_30 mb_20"><h5 class="d_iblock fs_18 fw_sbold w_200">Konum</h5><?= htmlspecialchars($product['location']) ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($product['area'])): ?>
                                    <li class="p_relative d_block fs_18 lh_30 mb_20"><h5 class="d_iblock fs_18 fw_sbold w_200">Alan</h5><?= htmlspecialchars($product['area']) ?> m²</li>
                                    <?php endif; ?>
                                    <?php if (!empty($product['price'])): ?>
                                    <li class="p_relative d_block fs_18 lh_30 <?= empty($product['area']) ? '' : 'mb_20' ?>"><h5 class="d_iblock fs_18 fw_sbold w_200">Fiyat</h5><?= htmlspecialchars($product['price']) ?></li>
                                    <?php endif; ?>
                                </ul>
                                <div class="btn-box">
                                    <a href="<?= $siteBase ?>/contact.php" class="theme-btn btn-one mr_11">İletişim<i class="icon-3"></i></a>
                                    <a href="<?= $siteBase ?>/projeler" class="theme-btn bg_yellow">Tüm Projeler<i class="icon-3"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- project-details end -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
