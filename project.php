<?php
$currentPage = 'project';
$pageTitle = 'Projeler';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/config/database.php';

$pdo = primevilla_pdo();
$categoryId = isset($_GET['category_id']) && ctype_digit($_GET['category_id']) ? (int)$_GET['category_id'] : null;

$categories = [];
try {
    $categories = $pdo->query("SELECT id, name FROM product_categories ORDER BY sort_order, name")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

$sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN product_categories c ON c.id = p.category_id WHERE p.status = 'active'";
$params = [];
if ($categoryId) {
    $sql .= " AND p.category_id = ?";
    $params[] = $categoryId;
}
$sql .= " ORDER BY p.sort_order ASC, p.created_at DESC";
$st = $pdo->prepare($sql);
$st->execute($params);
$products = $st->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
$base = BASE_ASSETS;
$fallbackImages = ['project-1.jpg','project-2.jpg','project-3.jpg','project-4.jpg','project-15.jpg','project-16.jpg','project-17.jpg','project-18.jpg'];
?>

        <!-- Page Title -->
        <section class="page-title p_relative pt_250 pb_170 centred" style="background-image: url(<?= ($bg = setting('page_title_bg')) ? $bg : $base . '/assets/images/background/page-title.jpg' ?>);">
            <div class="bg-layer p_absolute r_100 t_0" style="background-image: url(<?= $base ?>/assets/images/background/page-title.png);"></div>
            <div class="large-container">
                <div class="content-box p_relative d_block z_5">
                    <div class="title mb_25">
                        <h1 class="d_block fs_68 lh_76 color_white fw_exbold">Projelerimiz</h1>
                    </div>
                    <ul class="bread-crumb clearfix">
                        <li class="p_relative d_iblock fs_16 color_white pl_45 pr_30 mr_10"><i class="fas fa-home"></i><a href="<?= $siteBase ?>/index.php" class="color_white hov_color">Ana Sayfa</a></li>
                        <li class="p_relative d_iblock fs_16 color_white">Projeler</li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- End Page Title -->

        <!-- project-section -->
        <section class="project-section p_relative sec-pad centred">
            <div class="large-container">
                <?php if (!empty($categories)): ?>
                <div class="mb_40 centred">
                    <form method="get" action="<?= $siteBase ?>/projeler" class="d_iblock">
                        <select name="category_id" onchange="this.form.submit()" class="mr_15" style="padding:10px 20px; border-radius:6px;">
                            <option value="">Tüm kategoriler</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= (int)$cat['id'] ?>" <?= $categoryId === (int)$cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
                <?php endif; ?>

                <div class="row clearfix">
                    <?php if ($products): ?>
                        <?php $i = 1; foreach ($products as $p): ?>
                            <?php
                            $img = !empty($p['image']) ? $p['image'] : $base . '/assets/images/project/' . $fallbackImages[($i - 1) % count($fallbackImages)];
                            $hasMargin = $i <= 4 ? 'mb_75' : '';
                            ?>
                            <div class="col-lg-3 col-md-6 col-sm-12 project-block">
                                <div class="project-block-one">
                                    <div class="inner-box p_relative d_block <?= $hasMargin ?>">
                                        <figure class="image-box p_relative d_block">
                                            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                                            <h2 class="fs_100 lh_100 fw_light color_white p_absolute z_2 tran_5"><?= sprintf('%02d', $i) ?></h2>
                                        </figure>
                                        <div class="text p_relative d_block pt_40">
                                            <h3 class="p_relative d_block fs_24 lh_30 fw_sbold"><a href="<?= $siteBase ?>/projeler/<?= htmlspecialchars($p['slug']) ?>" class="p_relative d_iblock color_black"><?= htmlspecialchars($p['title']) ?></a></h3>
                                            <?php if (!empty($p['location'])): ?><p class="fs_14 mt_10"><?= htmlspecialchars($p['location']) ?></p><?php endif; ?>
                                            <?php if (!empty($p['price'])): ?><p class="fs_14 fw_sbold"><?= htmlspecialchars($p['price']) ?></p><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="centred" style="padding:60px 20px; font-size:18px; color:#666;">Bu kriterlere uygun proje bulunamadı. Admin panelinden ürün ekleyebilirsiniz.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <!-- project-section end -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
