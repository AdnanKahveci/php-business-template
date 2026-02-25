<?php
$currentPage = 'blog';
$pageTitle = 'Blog';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/config/database.php';

$base = BASE_ASSETS;
$siteBase = defined('SITE_BASE') ? SITE_BASE : (rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/') ?: '/');
$fullBase = $siteBase . '/' . BASE_ASSETS;

$blogs = [];
$catFilter = isset($_GET['cat']) && ctype_digit($_GET['cat']) ? (int)$_GET['cat'] : null;
try {
    $pdo = primevilla_pdo();
    $sql = "SELECT b.*, c.name AS category_name FROM blogs b LEFT JOIN product_categories c ON c.id = b.category_id WHERE b.status = 'active'";
    $params = [];
    if ($catFilter) {
        $sql .= " AND b.category_id = ?";
        $params[] = $catFilter;
    }
    $sql .= " ORDER BY b.created_at DESC";
    $st = $pdo->prepare($sql);
    $st->execute($params);
    $blogs = $st->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

require_once __DIR__ . '/includes/header.php';
?>

        <!-- Page Title -->
        <section class="page-title p_relative pt_250 pb_170 centred" style="background-image: url(<?= ($bg = setting('page_title_bg')) ? $siteBase . '/' . $bg : $fullBase . '/assets/images/background/page-title.jpg' ?>);">
            <div class="bg-layer p_absolute r_100 t_0" style="background-image: url(<?= $fullBase ?>/assets/images/background/page-title-2.png);"></div>
            <div class="large-container">
                <div class="content-box p_relative d_block z_5">
                    <div class="title mb_25">
                        <h1 class="d_block fs_68 lh_76 color_white fw_exbold">Blog</h1>
                    </div>
                    <ul class="bread-crumb clearfix">
                        <li class="p_relative d_iblock fs_16 color_white pl_45 pr_30 mr_10"><i class="fas fa-home"></i><a href="<?= $siteBase ?>/index.php" class="color_white hov_color">Ana Sayfa</a></li>
                        <li class="p_relative d_iblock fs_16 color_white">Blog</li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- End Page Title -->

        <!-- news-section -->
        <section class="news-section p_relative sec-pad">
            <div class="large-container">
                <div class="row clearfix">
                    <?php foreach ($blogs as $idx => $post): ?>
                    <?php
                    $img = !empty($post['image']) ? $siteBase . '/' . $post['image'] : $fullBase . '/assets/images/news/news-1.jpg';
                    $catName = $post['category_name'] ?? 'Genel';
                    $postUrl = $siteBase . '/blog/' . htmlspecialchars($post['slug']);
                    $date = date('d M Y', strtotime($post['created_at']));
                    $excerpt = !empty($post['excerpt']) ? $post['excerpt'] : (strip_tags($post['content'] ?? ''));
                    if (mb_strlen($excerpt) > 120) $excerpt = mb_substr($excerpt, 0, 117) . '...';
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 news-block">
                        <div class="news-block-one wow fadeInUp animated" data-wow-delay="<?= ($idx % 3) * 100 ?>ms" data-wow-duration="1500ms">
                            <div class="inner-box bg-color-1 mb_80">
                                <div class="image-box">
                                    <figure class="image"><a href="<?= $postUrl ?>"><img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($post['title']) ?>"></a></figure>
                                    <span class="post-date"><?= $date ?></span>
                                </div>
                                <div class="lower-content">
                                    <ul class="post-info clearfix">
                                        <li class="admin">
                                            <a href="<?= $postUrl ?>"><?= htmlspecialchars($post['author'] ?? 'Admin') ?></a>
                                        </li>
                                        <li>
                                            <i class="fal fa-folder"></i>
                                            <a href="<?= $postUrl ?>"><?= htmlspecialchars($catName) ?></a>
                                        </li>
                                    </ul>
                                    <h3><a href="<?= $postUrl ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                                    <p><?= htmlspecialchars($excerpt) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (empty($blogs)): ?>
                <p class="centred" style="padding:60px 0; color:#666;">Henüz blog yazısı bulunmuyor.</p>
                <?php endif; ?>
            </div>
        </section>
        <!-- news-section end -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
