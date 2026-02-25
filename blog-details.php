<?php
$currentPage = 'blog-details';
$pageTitle = 'Blog Detay';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/config/database.php';

$base = BASE_ASSETS;
$siteBase = defined('SITE_BASE') ? SITE_BASE : (rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/') ?: '/');
$fullBase = $siteBase . '/' . BASE_ASSETS;

$pdo = primevilla_pdo();
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : null;
$post = null;
if ($slug && preg_match('/^[a-z0-9-]+$/', $slug)) {
    $st = $pdo->prepare("SELECT b.*, c.name AS category_name FROM blogs b LEFT JOIN product_categories c ON c.id = b.category_id WHERE b.slug = ? AND b.status = 'active'");
    $st->execute([$slug]);
    $post = $st->fetch(PDO::FETCH_ASSOC);
}
if (!$post) {
    header('Location: ' . (defined('SITE_BASE') ? rtrim(SITE_BASE, '/') : '') . '/blog');
    exit;
}

$pageTitle = $post['title'];

// Kategoriler (product_categories) + blog sayısı
$categories = $pdo->query("
    SELECT c.id, c.name,
    (SELECT COUNT(*) FROM blogs WHERE category_id = c.id AND status = 'active') AS cnt
    FROM product_categories c
    ORDER BY c.name
")->fetchAll(PDO::FETCH_ASSOC);

// Galeri görselleri
$galleryImages = $pdo->query("SELECT * FROM gallery_images ORDER BY sort_order, id LIMIT 9")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';

$img = !empty($post['image']) ? $siteBase . '/' . $post['image'] : $fullBase . '/assets/images/news/news-11.jpg';
$date = date('d M Y', strtotime($post['created_at']));
?>

        <!-- Page Title -->
        <section class="page-title p_relative pt_250 pb_170 centred" style="background-image: url(<?= ($bg = setting('page_title_bg')) ? $siteBase . '/' . $bg : $fullBase . '/assets/images/background/page-title.jpg' ?>);">
            <div class="bg-layer p_absolute r_100 t_0" style="background-image: url(<?= $fullBase ?>/assets/images/background/page-title-2.png);"></div>
            <div class="large-container">
                <div class="content-box p_relative d_block z_5">
                    <div class="title mb_25">
                        <h1 class="d_block fs_68 lh_76 color_white fw_exbold"><?= htmlspecialchars($post['title']) ?></h1>
                    </div>
                    <ul class="bread-crumb clearfix">
                        <li class="p_relative d_iblock fs_16 color_white pl_45 pr_30 mr_10"><i class="fas fa-home"></i><a href="index.php" class="color_white hov_color">Ana Sayfa</a></li>
                        <li class="p_relative d_iblock fs_16 color_white pr_30 mr_10"><a href="<?= $siteBase ?>/blog" class="color_white hov_color">Blog</a></li>
                        <li class="p_relative d_iblock fs_16 color_white"><?= htmlspecialchars($post['title']) ?></li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- End Page Title -->

        <!-- sidebar-page-container -->
        <section class="sidebar-page-container sec-pad">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-8 col-md-12 col-sm-12 content-side">
                        <div class="blog-details-content">
                            <div class="news-block-two">
                                <div class="inner-box">
                                    <div class="image-box">
                                        <figure class="image"><img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($post['title']) ?>"></figure>
                                        <span class="post-date"><?= $date ?></span>
                                    </div>
                                    <div class="lower-content">
                                        <ul class="post-info clearfix">
                                            <li class="admin">
                                                <a href="<?= $siteBase ?>/blog"><?= htmlspecialchars($post['author'] ?? 'Admin') ?></a>
                                            </li>
                                            <?php if (!empty($post['category_name'])): ?>
                                            <li>
                                                <i class="fal fa-folder"></i>
                                                <a href="<?= $siteBase ?>/blog"><?= htmlspecialchars($post['category_name']) ?></a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                        <div class="text">
                                            <h2><?= htmlspecialchars($post['title']) ?></h2>
                                            <?php if (!empty($post['content'])): ?>
                                                <div class="lh_30"><?= $post['content'] ?></div>
                                            <?php elseif (!empty($post['excerpt'])): ?>
                                                <p><?= nl2br(htmlspecialchars($post['excerpt'])) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 sidebar-side">
                        <div class="blog-sidebar">
                            <div class="sidebar-widget sidebar-search">
                                <div class="widget-title">
                                    <h3>Blog Ara</h3>
                                </div>
                                <div class="form-inner">
                                    <form action="<?= $siteBase ?>/blog" method="get" class="search-form">
                                        <div class="form-group">
                                            <input type="search" name="q" placeholder="Blog ara...">
                                            <button type="submit"><i class="icon-28"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="sidebar-widget category-widget">
                                <div class="widget-title">
                                    <h3>Kategoriler</h3>
                                </div>
                                <div class="widget-content">
                                    <ul class="category-list">
                                        <?php foreach ($categories as $cat): ?>
                                        <li><a href="<?= $siteBase ?>/blog?cat=<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?><span>(<?= (int)$cat['cnt'] ?>)</span></a></li>
                                        <?php endforeach; ?>
                                        <?php if (empty($categories)): ?>
                                        <li><a href="<?= $siteBase ?>/blog">Genel<span>(0)</span></a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="sidebar-widget gallery-widget">
                                <div class="widget-title">
                                    <h3>Görseller</h3>
                                </div>
                                <ul class="image-list clearfix">
                                    <?php foreach ($galleryImages as $g): ?>
                                    <?php $gUrl = strpos($g['image'], 'uploads/') === 0 ? $siteBase . '/' . $g['image'] : $fullBase . '/assets/images/news/gallery-1.jpg'; ?>
                                    <?php $gLink = !empty($g['link']) ? $g['link'] : 'projeler'; ?>
                                    <li>
                                        <figure class="image"><a href="<?= htmlspecialchars($gLink) ?>"><img src="<?= htmlspecialchars($gUrl) ?>" alt="<?= htmlspecialchars($g['title'] ?? '') ?>"></a></figure>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if (empty($galleryImages)): ?>
                                <p style="color:#888; font-size:0.9rem;">Henüz görsel eklenmedi.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- sidebar-page-container end -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
