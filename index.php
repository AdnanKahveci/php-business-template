<?php
$currentPage = 'home';
$pageTitle = 'Ana Sayfa';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/config/database.php';

$base = BASE_ASSETS;
$heroTitle = 'Hayalinizdeki Eve Kavuşun';
$sliderItems = [];
$featureItems = [];
$projectItems = [];
$blogItems = [];
$serviceItems = [];
$teamItems = [];
$testimonialItems = [];
$progressItems = [];
$amenityItems = [];
$counterItems = [];
$awardCardItems = [];
$fallbackProjects = ['project-1.jpg', 'project-2.jpg', 'project-3.jpg', 'project-4.jpg'];
try {
    $pdo = primevilla_pdo();
    $st = $pdo->query("SELECT setting_value FROM site_settings WHERE setting_key = 'home_hero' LIMIT 1");
    if ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $heroTitle = $row['setting_value'] ?: $heroTitle;
    }
    $sliderItems = $pdo->query("SELECT * FROM slider WHERE is_active = 1 ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);
    $featureItems = $pdo->query("SELECT * FROM features WHERE is_active = 1 ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);
    $projectItems = $pdo->query("SELECT * FROM products WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);
    $blogItems = $pdo->query("SELECT b.*, c.name AS category_name FROM blogs b LEFT JOIN product_categories c ON c.id = b.category_id WHERE b.status = 'active' ORDER BY b.created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    $serviceItems = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order, id LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    $teamItems = $pdo->query("SELECT * FROM team_members WHERE is_active = 1 ORDER BY sort_order, id LIMIT 4")->fetchAll(PDO::FETCH_ASSOC);
    $testimonialItems = $pdo->query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);
    $progressItems = $pdo->query("SELECT * FROM home_progress WHERE is_active = 1 ORDER BY sort_order, id LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    $amenityItems = $pdo->query("SELECT * FROM home_amenities WHERE is_active = 1 ORDER BY sort_order, id LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
    $counterItems = $pdo->query("SELECT * FROM home_counters WHERE is_active = 1 ORDER BY sort_order, id LIMIT 4")->fetchAll(PDO::FETCH_ASSOC);
    $awardCardItems = $pdo->query("SELECT * FROM award_cards WHERE is_active = 1 ORDER BY sort_order, id LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
if (empty($featureItems)) {
    $featureItems = [
        ['image' => $base . '/assets/images/resource/feature-1.jpg', 'title' => 'Deneyimli Ekip', 'link' => 'about.php'],
        ['image' => $base . '/assets/images/resource/feature-2.jpg', 'title' => 'Premium Konutlar', 'link' => 'projeler'],
        ['image' => $base . '/assets/images/resource/feature-3.jpg', 'title' => 'İdeal Konumlar', 'link' => 'projeler'],
    ];
}
if (empty($sliderItems)) {
    $sliderItems = [
        ['image' => $base . '/assets/images/banner/banner-1.jpg', 'title' => $heroTitle, 'subtitle' => 'Hayalinizdeki eve kavuşun.', 'link' => 'projeler'],
        ['image' => $base . '/assets/images/banner/banner-2.jpg', 'title' => 'Kaliteli Yaşam Alanları', 'subtitle' => 'Premium mimari ile tasarlanmış konutlar.', 'link' => 'projeler'],
        ['image' => $base . '/assets/images/banner/banner-3.jpg', 'title' => 'Güvenilir Emlak Hizmeti', 'subtitle' => 'Satılık ve kiralık seçenekler.', 'link' => 'contact.php'],
    ];
}
require_once __DIR__ . '/includes/header.php';
?>

        <!-- banner-section (Amortez benzeri, arka plan resmi olmadan) -->
        <section class="banner-section style-one p_relative">
            <div class="banner-carousel owl-theme owl-carousel owl-dots-none">
                <?php foreach ($sliderItems as $slide): ?>
                <?php $slideBg = !empty($slide['image']) ? 'url(' . htmlspecialchars($siteBase . '/' . $slide['image']) . ')' : 'linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%)'; ?>
                <div class="slide-item p_relative pt_240 pb_280" style="background: <?= $slideBg ?> center/cover no-repeat;">
                    <div class="large-container">
                        <div class="content-box p_relative d_block z_5 centred">
                            <?php if (!empty($slide['title'])): ?><h1 class="color_white d_block fs_68 lh_76 mb_35 fw_exbold"><?= htmlspecialchars($slide['title']) ?></h1><?php endif; ?>
                            <?php if (!empty($slide['subtitle'])): ?><h2 class="color_white d_block fs_36 lh_46 mb_50 fw_light"><?= htmlspecialchars($slide['subtitle']) ?></h2><?php endif; ?>
                            <?php
                            $rawLink = $slide['link'] ?? '';
                            if (empty($rawLink)) { $sLink = $siteBase . '/projeler'; }
                            elseif (strpos($rawLink, 'http') === 0 || $rawLink[0] === '#') { $sLink = $rawLink; }
                            else { $sLink = $siteBase . '/' . ltrim($rawLink, '/'); }
                            ?>
                            <div class="btn-box clearfix">
                                <a href="<?= htmlspecialchars($sLink) ?>" class="theme-btn btn-one">Projeleri Gör<i class="icon-3"></i></a>
                                <a href="<?= $siteBase ?>/projeler" class="theme-btn btn-two">Keşfet<i class="icon-3"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <ul class="banner-social clearfix">
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                <li><a href="#"><i class="fab fa-pinterest-p"></i></a></li>
            </ul>
        </section>
        <!-- banner-section end -->

        <!-- feature-section (Amortez benzeri) -->
        <section class="feature-section p_relative sec-pad centred" id="feature">
            <div class="large-container">
                <div class="sec-title mb_45">
                    <div class="icon-box p_relative d_block fs_14 lh_20 mb_25"><i class="icon-4"></i></div>
                    <span class="sub-title p_relative d_block fs_14 lh_25 mb_10"><?= htmlspecialchars(setting('site_name')) ?> Özellikleri</span>
                    <h2 class="p_relative d_block fs_50 lh_60 fw_exbold">Kalıcı Yapılar <br />Özenle İnşa Edilir</h2>
                </div>
                <div class="row clearfix">
                    <?php foreach ($featureItems as $idx => $feat): ?>
                    <?php $featImg = !empty($feat['image']) ? (strpos($feat['image'], 'uploads/') === 0 ? $siteBase . '/' . $feat['image'] : $feat['image']) : ''; ?>
                    <?php 
                    $rawLink = $feat['link'] ?? '';
                    $featLink = !empty($rawLink) ? htmlspecialchars($rawLink) : '#';
                    if ($featLink !== '#' && strpos($featLink, '#') !== 0 && strpos($featLink, 'http') !== 0) {
                        $featLink = $siteBase . '/' . ltrim($featLink, '/');
                    }
                    ?>
                    <?php $featTitle = htmlspecialchars($feat['title'] ?? ''); ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 feature-block">
                        <div class="feature-block-one wow fadeInUp animated" data-wow-delay="<?= $idx * 300 ?>ms" data-wow-duration="1500ms">
                            <div class="inner-box p_relative d_block">
                                <?php if ($featImg): ?><figure class="image-box p_relative d_block"><img src="<?= $featImg ?>" alt="<?= $featTitle ?>"></figure><?php endif; ?>
                                <div class="text p_absolute pt_35 pr_15 pb_35 pl_15 bg_white tran_5">
                                    <h3 class="p_relative d_block fs_24 lh_30 fw_sbold"><a href="<?= $featLink ?>" class="d_iblock color_black"><?= $featTitle ?: '—' ?></a></h3>
                                </div>
                                <div class="overlay-content p_absolute pt_35 pr_15 pb_35 pl_15 bg_yellow tran_5">
                                    <h3 class="p_relative d_block fs_24 lh_30 fw_sbold"><a href="<?= $featLink ?>" class="p_relative d_iblock color_white"><?= $featTitle ?: '—' ?></a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <!-- feature-section end -->

        <section class="about-section bg-color-1 p_relative sec-pad" id="about">
            <div class="bg-layer p_absolute" style="background-image: url(<?= $base ?>/assets/images/shape/shape-1.png);"></div>
            <div class="large-container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                        <div class="content_block_1">
                            <div class="content-box p_relative d_block mr_90">
                                <div class="sec-title mb_25">
                                    <div class="icon-box p_relative d_block fs_14 lh_20 mb_15"><i class="icon-4"></i></div>
                                    <h2 class="p_relative d_block fs_50 lh_60 fw_exbold"><?= htmlspecialchars(setting('site_name')) ?> ile Güvenilir Emlak</h2>
                                </div>
                                <div class="text p_relative d_block mb_30">
                                    <p>Hayalinizdeki evi bulmanız için yanınızdayız. Primevilla olarak satılık ve kiralık konut, iş yeri ve arsa seçenekleri sunuyoruz.</p>
                                </div>
                                <div class="inner-box centred p_relative d_block mb_50">
                                    <div class="row clearfix">
                                        <div class="col-lg-4 col-md-6 col-sm-12 single-column">
                                            <div class="single-item p_relative d_block">
                                                <div class="icon-box p_relative d_iblock fs_45 lh_80 b_radius_50 bg_white color_black tran_5 mb_10"><i class="icon-5"></i></div>
                                                <h5 class="d_block fs_18 lh_30 fw_sbold">Konut</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 single-column">
                                            <div class="single-item p_relative d_block">
                                                <div class="icon-box p_relative d_iblock fs_45 lh_80 b_radius_50 bg_white color_black tran_5 mb_10"><i class="icon-6"></i></div>
                                                <h5 class="d_block fs_18 lh_30 fw_sbold">İş Yeri</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 single-column">
                                            <div class="single-item p_relative d_block before-none">
                                                <div class="icon-box p_relative d_iblock fs_40 lh_80 b_radius_50 bg_white color_black tran_5 mb_10"><i class="icon-7"></i></div>
                                                <h5 class="d_block fs_18 lh_30 fw_sbold">Arsa</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-box">
                                    <a href="<?= $siteBase ?>/about.php" class="theme-btn btn-one">Hakkımızda<i class="icon-3"></i></a>
                                    <a href="<?= $siteBase ?>/contact.php" class="theme-btn btn-one" style="margin-left:12px;">İletişim<i class="icon-3"></i></a>
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
        <!-- about-section end -->

        <!-- project-section -->
        <section class="project-section p_relative sec-pad centred">
            <div class="large-container">
                <div class="sec-title mb_55">
                    <div class="icon-box p_relative d_block fs_14 lh_20 mb_25"><i class="icon-4"></i></div>
                    <span class="sub-title p_relative d_block fs_14 lh_25 mb_10"><?= htmlspecialchars(setting('site_name')) ?> projeleri</span>
                    <h2 class="p_relative d_block fs_50 lh_60 fw_exbold">Öne Çıkan Projeler</h2>
                </div>
                <div class="four-item-carousel owl-carousel owl-theme owl-dots-none">
                    <?php
                    $projList = !empty($projectItems) ? $projectItems : array_map(function($i) {
                        return ['title' => 'Örnek Proje ' . $i, 'slug' => '', 'image' => ''];
                    }, range(1, 4));
                    foreach ($projList as $i => $p):
                        $pImg = !empty($p['image']) ? ($siteBase . '/' . $p['image']) : ($base . '/assets/images/project/' . ($fallbackProjects[$i % 4] ?? 'project-1.jpg'));
                        $pUrl = !empty($p['slug']) ? $siteBase . '/projeler/' . htmlspecialchars($p['slug']) : $siteBase . '/projeler';
                    ?>
                    <div class="project-block-one">
                        <div class="inner-box p_relative d_block">
                            <figure class="image-box p_relative d_block">
                                <img src="<?= htmlspecialchars($pImg) ?>" alt="<?= htmlspecialchars($p['title'] ?? '') ?>">
                                <h2 class="fs_100 lh_100 fw_light color_white p_absolute z_2 tran_5"><?= sprintf('%02d', $i + 1) ?></h2>
                            </figure>
                            <div class="text p_relative d_block pt_40">
                                <h3 class="p_relative d_block fs_24 lh_30 fw_sbold"><a href="<?= $pUrl ?>" class="p_relative d_iblock color_black"><?= htmlspecialchars($p['title'] ?? '') ?></a></h3>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <!-- project-section end -->

        <!-- skills-section -->
        <section class="skills-section p_relative" style="background-image: url(<?= $base ?>/assets/images/background/skills-1.jpg);">
            <div class="large-container">
                <div class="skills-inner p_relative d_block sec-pad">
                    <div class="row clearfix">
                        <div class="col-lg-5 col-md-12 col-sm-12 content-column">
                            <div class="content_block_2">
                                <div class="content-box p_relative d_block mr_50">
                                    <div class="sec-title light mb_45">
                                        <div class="icon-box p_relative d_block fs_14 lh_20 mb_10"><i class="icon-4"></i></div>
                                        <h2 class="p_relative d_block fs_50 lh_60 fw_exbold color_white mb_15"><?= htmlspecialchars(setting('skills_title', 'Keyifli Yaşam Alanları')) ?></h2>
                                        <p class="d_block color_white"><?= htmlspecialchars(setting('skills_desc', 'Hayalinizdeki eve kavuşmak için yanınızdayız. Güvenilir emlak danışmanlığı ve profesyonel hizmet anlayışımızla fark yaratıyoruz.')) ?></p>
                                    </div>
                                    <div class="progress-inner">
                                        <?php
                                        $defProgress = [['title'=>'Yenilikçi Fikirler','percent'=>80],['title'=>'Yapı Kalitesi','percent'=>98],['title'=>'İç Mekan Planlama','percent'=>72]];
                                        $progList = !empty($progressItems) ? $progressItems : $defProgress;
                                        foreach (array_slice($progList, 0, 3) as $pr): ?>
                                        <div class="progress-box p_relative d_block">
                                            <div class="bar-box p_relative d_block pl_160">
                                                <h5 class="d_block p_absolute fs_18 lh_25 fw_medium color_white"><?= htmlspecialchars($pr['title'] ?? '') ?></h5>
                                                <div class="bar">
                                                    <div class="bar-inner count-bar" data-percent="<?= (int)($pr['percent'] ?? 0) ?>%"></div>
                                                    <div class="count-text p_absolute fs_14 color_white lh_30 fw_medium"><?= (int)($pr['percent'] ?? 0) ?>%</div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-12 col-sm-12 inner-column">
                            <div class="inner-content centred wow fadeInRight animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                                <div class="row clearfix">
                                    <?php
                                    $defAmenities = [['title'=>'Otopark','icon_num'=>1],['title'=>'Banyo','icon_num'=>2],['title'=>'Geniş Odalar','icon_num'=>3],['title'=>'Paket Hizmetler','icon_num'=>4],['title'=>'Spor Alanı','icon_num'=>5],['title'=>'Ortak Kullanım','icon_num'=>6]];
                                    $amList = !empty($amenityItems) ? $amenityItems : $defAmenities;
                                    foreach (array_slice($amList, 0, 6) as $idx => $am):
                                        $iconNum = (int)($am['icon_num'] ?? ($idx + 1));
                                        if ($iconNum < 1 || $iconNum > 6) $iconNum = 1;
                                        $mb = $idx < 3 ? 'mb_30' : '';
                                    ?>
                                    <div class="col-lg-4 col-md-6 col-sm-12 single-column">
                                        <div class="single-item p_relative d_block bg_white pt_35 pr_20 pb_30 pl_20 <?= $mb ?>">
                                            <div class="icon-box p_relative d_iblock mb_15"><img src="<?= $base ?>/assets/images/icons/icon-<?= $iconNum ?>.png" alt=""></div>
                                            <h6 class="d_block fs_16 lh_25 fw_bold"><?= htmlspecialchars($am['title'] ?? '') ?></h6>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="funfact-inner p_relative d_block pb_110 pt_70">
                    <div class="row clearfix">
                        <?php
                        $defCounters = [['number'=>137,'suffix'=>'','label'=>'Kat & Ünite','icon_class'=>'icon-14'],['number'=>95,'suffix'=>'+','label'=>'Satılan Konut','icon_class'=>'icon-15'],['number'=>256,'suffix'=>'','label'=>'Toplam Proje','icon_class'=>'icon-16'],['number'=>140,'suffix'=>'+','label'=>'Mutlu Müşteri','icon_class'=>'icon-17']];
                        $cntList = !empty($counterItems) ? $counterItems : $defCounters;
                        foreach (array_slice($cntList, 0, 4) as $ci => $cnt): ?>
                        <div class="col-lg-3 col-md-6 col-sm-12 counter-block">
                            <div class="counter-block-one wow slideInUp animated" data-wow-delay="<?= $ci * 200 ?>ms" data-wow-duration="1500ms">
                                <div class="inner-box p_relative d_block pl_110">
                                    <div class="icon-box p_absolute d_iblock fs_80 lh_70 color_white tran_5"><i class="<?= htmlspecialchars($cnt['icon_class'] ?? 'icon-14') ?>"></i></div>
                                    <div class="count-outer count-box p_relative d_block fs_70 lh_70 color_white fw_exbold mb_5">
                                        <span class="count-text" data-speed="1500" data-stop="<?= (int)($cnt['number'] ?? 0) ?>">0</span><?php if (!empty($cnt['suffix'])): ?><span><?= htmlspecialchars($cnt['suffix']) ?></span><?php endif; ?>
                                    </div>
                                    <h5 class="p_relative d_block fs_18 lh_20 color_white"><?= htmlspecialchars($cnt['label'] ?? '') ?></h5>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- skills-section end -->

        <!-- award-section -->
        <section class="award-section p_relative sec-pad centred">
            <div class="large-container">
                <div class="inner-container p_relative d_block">
                    <div class="award-box p_absolute bg_white p_10 centred z_1 b_shadow_6">
                        <div class="inner p_relative d_block blue_bg pt_45 pr_30 pb_40 pl_30">
                            <figure class="award-image p_relative d_block mb_5"><img src="<?= $base ?>/assets/images/icons/award-1.png" alt=""></figure>
                            <h2 class="d_block fs_34 lh_45 fw_bold color_white mb_10"><?= htmlspecialchars(setting('award_box_title', setting('site_name'))) ?></h2>
                            <p class="d_block fs_16 lh_24 color_white"><?= htmlspecialchars(setting('award_box_subtitle') ?: "En İyi Gayrimenkul Danışmanı '24") ?></p>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <?php
                        $defAward = [['title'=>'Uzman Kadromuzla Güvenilir Hizmet','subtitle'=>(setting('site_name')?:'').' Farkı','image'=>'','link'=>$siteBase.'/about.php'],['title'=>'Gayrimenkulde Lider Firma','subtitle'=>(setting('site_name')?:'').' Farkı','image'=>'','link'=>$siteBase.'/projeler']];
                        $awardList = !empty($awardCardItems) ? $awardCardItems : $defAward;
                        foreach ($awardList as $ai => $ac):
                            $acImg = !empty($ac['image']) ? (strpos($ac['image'], 'uploads/') === 0 ? $siteBase . '/' . $ac['image'] : $ac['image']) : $base . '/assets/images/resource/award-' . (($ai % 2) + 1) . '.jpg';
                            $acLink = !empty($ac['link']) ? $ac['link'] : ($ai === 0 ? $siteBase.'/about.php' : $siteBase.'/projeler');
                            $acLinkText = (strpos($acLink, 'about') !== false) ? 'Hakkımızda' : ((strpos($acLink, 'proje') !== false) ? 'Projelerimiz' : (mb_substr($ac['title'] ?? 'Detay', 0, 20)));
                            $acSub = $ac['subtitle'] ?? (setting('site_name') . ' Farkı');
                        ?>
                        <div class="col-lg-6 col-md-6 col-sm-12 award-block">
                            <div class="award-block-one">
                                <div class="inner-box">
                                    <div class="sec-title mb_55">
                                        <div class="icon-box p_relative d_block fs_14 lh_20 mb_25"><i class="icon-4"></i></div>
                                        <span class="sub-title p_relative d_block fs_14 lh_25 mb_10"><?= htmlspecialchars($acSub) ?></span>
                                        <h2 class="p_relative d_block fs_50 lh_60 fw_exbold"><?= nl2br(htmlspecialchars($ac['title'] ?? '')) ?></h2>
                                    </div>
                                    <div class="image-box p_relative d_block">
                                        <figure class="image p_relative d_block"><img src="<?= htmlspecialchars($acImg) ?>" alt=""></figure>
                                        <div class="text p_absolute pt_35 pr_15 pb_35 pl_15 bg_white tran_5">
                                            <h3 class="p_relative d_block fs_24 lh_30 fw_sbold"><a href="<?= htmlspecialchars($acLink) ?>" class="d_iblock color_black"><?= htmlspecialchars($acLinkText) ?></a></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- award-section end -->

        <!-- service-section -->
        <section class="service-section p_relative sec-pad bg-color-1">
            <div class="pattern-layer p_absolute l_0 t_0 r_0" style="background-image: url(<?= $base ?>/assets/images/shape/shape-2.png);"></div>
            <div class="large-container">
                <div class="row clearfix">
                    <div class="col-lg-5 col-md-12 col-sm-12 content-column">
                        <div class="content_block_1">
                            <div class="content-box p_relative d_block mr_90">
                                <div class="sec-title mb_25">
                                    <div class="icon-box p_relative d_block fs_14 lh_20 mb_15"><i class="icon-4"></i></div>
                                    <h2 class="p_relative d_block fs_50 lh_60 fw_exbold"><?= htmlspecialchars(setting('service_title', 'Satılık ve Kiralık Konut Hizmetleri')) ?></h2>
                                </div>
                                <div class="text p_relative d_block mb_40">
                                    <p><?= htmlspecialchars(setting('service_desc', 'Konut, iş yeri ve arsa arayanlar için kapsamlı emlak danışmanlığı sunuyoruz. Hayalinizdeki eve ulaşmanız için profesyonel destek sağlıyoruz.')) ?></p>
                                </div>
                                <div class="btn-box">
                                    <a href="<?= $siteBase ?>/service.php" class="theme-btn btn-one">Hizmetlerimiz<i class="icon-3"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12 col-sm-12 inner-column">
                        <div class="inner-content centred">
                            <div class="row">
                                <?php
                                $defServices = [['title'=>'Satılık Konut','image'=>'','link'=>'projeler'],['title'=>'Kiralık Konut','image'=>'','link'=>'projeler'],['title'=>'İş Yeri & Arsa','image'=>'','link'=>'projeler']];
                                $svcList = !empty($serviceItems) ? $serviceItems : $defServices;
                                foreach (array_slice($svcList, 0, 3) as $si => $svc):
                                    $svcImg = !empty($svc['image']) ? (strpos($svc['image'], 'uploads/') === 0 ? $siteBase . '/' . $svc['image'] : $svc['image']) : $base . '/assets/images/service/service-' . ($si + 1) . '.jpg';
                                    $svcLink = !empty($svc['link']) ? (strpos($svc['link'], 'http') === 0 ? $svc['link'] : $siteBase . '/' . ltrim($svc['link'], '/')) : $siteBase . '/projeler';
                                ?>
                                <div class="col-lg-4 col-md-6 col-sm-12 service-block">
                                    <div class="service-block-one wow fadeInUp animated" data-wow-delay="<?= $si * 300 ?>ms" data-wow-duration="1500ms">
                                        <div class="inner-box p_relative d_block">
                                            <figure class="image-box p_relative d_block tran_5"><img src="<?= htmlspecialchars($svcImg) ?>" alt=""></figure>
                                            <div class="text p_absolute l_0 b_0 pb_35 z_2 tran_5">
                                                <h2 class="p_relative d_block fs_30 lh_40 color_white"><a href="<?= htmlspecialchars($svcLink) ?>" class="p_relative d_iblock color_white"><?= htmlspecialchars($svc['title'] ?? '') ?></a></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- service-section end -->

        <!-- team-section -->
        <section class="team-section p_relative sec-pad centred">
            <div class="large-container">
                <div class="sec-title mb_50">
                    <div class="icon-box p_relative d_block fs_14 lh_20 mb_25"><i class="icon-4"></i></div>
                    <span class="sub-title p_relative d_block fs_14 lh_25 mb_10">Uzman ekibimiz</span>
                    <h2 class="p_relative d_block fs_50 lh_60 fw_exbold">Ekibimiz</h2>
                </div>
                <div class="row clearfix">
                    <?php
                    $defTeam = [['name'=>'Ekip Üyesi','designation'=>'Gayrimenkul Danışmanı','image'=>'','phone'=>setting('contact_phone')],['name'=>'Ekip Üyesi','designation'=>'Proje Uzmanı','image'=>'','phone'=>setting('contact_phone')],['name'=>'Ekip Üyesi','designation'=>'Kıdemli Danışman','image'=>'','phone'=>setting('contact_phone')],['name'=>'Ekip Üyesi','designation'=>'İş Geliştirme','image'=>'','phone'=>setting('contact_phone')]];
                    $tmList = !empty($teamItems) ? $teamItems : $defTeam;
                    foreach (array_slice($tmList, 0, 4) as $ti => $tm):
                        $tmImg = !empty($tm['image']) ? (strpos($tm['image'], 'uploads/') === 0 ? $siteBase . '/' . $tm['image'] : $tm['image']) : $base . '/assets/images/team/team-' . ($ti + 1) . '.jpg';
                        $tmPhone = $tm['phone'] ?? setting('contact_phone');
                    ?>
                    <div class="col-lg-3 col-md-6 col-sm-12 team-block">
                        <div class="team-block-one wow fadeInUp animated" data-wow-delay="<?= $ti * 200 ?>ms" data-wow-duration="1500ms">
                            <div class="inner-box p_relative d_block bg_white tran_5">
                                <div class="image-box p_relative d_block">
                                    <figure class="image"><img src="<?= htmlspecialchars($tmImg) ?>" alt=""></figure>
                                    <ul class="social-links clearfix p_absolute bg_white w_50 tran_5 t_0 pt_15 pb_15">
                                        <li class="p_relative d_block fs_16 mb_10"><a href="#" class="d_iblock hov_color"><i class="fab fa-twitter"></i></a></li>
                                        <li class="p_relative d_block fs_16 mb_10"><a href="#" class="d_iblock hov_color"><i class="fab fa-facebook-f"></i></a></li>
                                        <li class="p_relative d_block fs_16 mb_10"><a href="#" class="d_iblock hov_color"><i class="fab fa-linkedin-in"></i></a></li>
                                        <li class="p_relative d_block fs_16"><a href="#" class="d_iblock hov_color"><i class="fab fa-pinterest-p"></i></a></li>
                                    </ul>
                                    <div class="support-box p_absolute l_35 blue_bg pt_12 pb_12 pr_10 pl_10 tran_5">
                                        <h4 class="d_block fs_20 lh_30 color_white fw_bold"><i class="far fa-phone"></i><a href="tel:<?= preg_replace('/\s+/', '', $tmPhone) ?>" class="d_iblock color_white ml_10"><?= htmlspecialchars($tmPhone ?: '—') ?></a></h4>
                                    </div>
                                </div>
                                <div class="lower-content p_relative d_block pt_40 pb_35 pl_20 pr_20 tran_5">
                                    <h3 class="d_block fs_24 lh_30 fw_sbold mb_6"><a href="#" class="d_iblock color_black hov_color"><?= htmlspecialchars($tm['name'] ?? '') ?></a></h3>
                                    <span class="designation p_relative d_block fs_16 lh_25"><?= htmlspecialchars($tm['designation'] ?? '') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <!-- team-section end -->

        <!-- testimonial-section -->
        <section class="testimonial-section p_relative sec-pad centred" style="background-image: url(<?= $base ?>/assets/images/background/testimonial-bg.jpg);">
            <div class="auto-container">
                <div class="sec-title mb_40">
                    <div class="icon-box p_relative d_block fs_14 lh_20 mb_25"><i class="icon-4"></i></div>
                    <span class="sub-title p_relative d_block fs_14 lh_25 mb_10 fw_sbold color_white">Müşterilerimiz ne diyor</span>
                </div>
                <div class="inner-content">
                    <div class="single-item-carousel owl-carousel owl-theme owl-nav-none">
                        <?php
                        $defTest = [['quote'=>'Primevilla ile ev alma sürecimiz çok sorunsuz geçti. Profesyonel ve samimi ekibe teşekkürler.','author_name'=>'Mutlu Müşteri','designation'=>'Emlak Sahibi','image'=>''],['quote'=>'Güvenilir ve şeffaf bir hizmet anlayışı. Emlak alımında her adımda yanımızda oldular.','author_name'=>'Mutlu Müşteri','designation'=>'Yatırımcı','image'=>'']];
                        $testList = !empty($testimonialItems) ? $testimonialItems : $defTest;
                        foreach ($testList as $tei => $te):
                            $teImg = !empty($te['image']) ? (strpos($te['image'], 'uploads/') === 0 ? $siteBase . '/' . $te['image'] : $te['image']) : $base . '/assets/images/resource/testimonial-' . (($tei % 2) + 1) . '.jpg';
                        ?>
                        <div class="testimonial-content">
                            <div class="text p_relative d_block pt_45 pr_100 pb_45 pl_100 mb_55">
                                <div class="shape p_absolute"></div>
                                <p class="fs_24 lh_38 color_white"><?= htmlspecialchars($te['quote'] ?? '') ?></p>
                            </div>
                            <div class="quote p_relative d_block fs_60 lh_60 mb_40"><i class="far fa-quote-right"></i></div>
                            <div class="author-box p_relative d_iblock pl_75 pt_3 pb_9">
                                <figure class="author-thumb p_absolute l_0 t_0 w_60 h_60 b_radius_50"><img src="<?= htmlspecialchars($teImg) ?>" alt=""></figure>
                                <h5 class="d_block fs_18 lh_25 color_white fw_bold mb_3"><?= htmlspecialchars($te['author_name'] ?? '') ?></h5>
                                <span class="designation p_relative d_block fs_14 lh_20 color_white"><?= htmlspecialchars($te['designation'] ?? '') ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- testimonial-section end -->

        <!-- news-section -->
        <section class="news-section p_relative sec-pad">
            <div class="large-container">
                <div class="sec-title centred mb_55">
                    <div class="icon-box p_relative d_block fs_14 lh_20 mb_25"><i class="icon-4"></i></div>
                    <span class="sub-title p_relative d_block fs_14 lh_25 mb_10">Emlak ve yaşam haberleri</span>
                    <h2 class="p_relative d_block fs_50 lh_60 fw_exbold">Son Blog Yazıları</h2>
                </div>
                <div class="row clearfix">
                    <?php if (!empty($blogItems)): ?>
                        <?php foreach ($blogItems as $idx => $post): ?>
                        <?php
                        $bImg = !empty($post['image']) ? $siteBase . '/' . $post['image'] : $base . '/assets/images/news/news-' . (($idx % 3) + 1) . '.jpg';
                        $bUrl = $siteBase . '/blog/' . htmlspecialchars($post['slug']);
                        $bCat = $post['category_name'] ?? 'Genel';
                        $bDate = date('d M Y', strtotime($post['created_at']));
                        $bExcerpt = !empty($post['excerpt']) ? $post['excerpt'] : strip_tags($post['content'] ?? '');
                        if (mb_strlen($bExcerpt) > 120) $bExcerpt = mb_substr($bExcerpt, 0, 117) . '...';
                        ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 news-block">
                        <div class="news-block-one wow fadeInUp animated" data-wow-delay="<?= $idx * 100 ?>ms" data-wow-duration="1500ms">
                            <div class="inner-box bg-color-1">
                                <div class="image-box">
                                    <figure class="image"><a href="<?= $bUrl ?>"><img src="<?= htmlspecialchars($bImg) ?>" alt="<?= htmlspecialchars($post['title']) ?>"></a></figure>
                                    <span class="post-date"><?= $bDate ?></span>
                                </div>
                                <div class="lower-content">
                                    <ul class="post-info clearfix">
                                        <li class="admin"><a href="<?= $bUrl ?>"><?= htmlspecialchars($post['author'] ?? 'Admin') ?></a></li>
                                        <li><i class="fal fa-folder"></i><a href="<?= $bUrl ?>"><?= htmlspecialchars($bCat) ?></a></li>
                                    </ul>
                                    <h3><a href="<?= $bUrl ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                                    <p><?= htmlspecialchars($bExcerpt) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 news-block">
                        <div class="news-block-one wow fadeInUp animated">
                            <div class="inner-box bg-color-1">
                                <div class="image-box">
                                    <figure class="image"><a href="<?= $siteBase ?>/blog"><img src="<?= $base ?>/assets/images/news/news-1.jpg" alt=""></a></figure>
                                    <span class="post-date"><?= date('d M Y') ?></span>
                                </div>
                                <div class="lower-content">
                                    <h3><a href="<?= $siteBase ?>/blog">Blog yazılarımız yakında...</a></h3>
                                    <p>Emlak ve gayrimenkul dünyasından haberler, ipuçları ve rehberler.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="more-btn centred">
                    <a href="<?= $siteBase ?>/blog" class="theme-btn btn-one">Daha fazla yazı<i class="icon-3"></i></a>
                </div>
            </div>
        </section>
        <!-- news-section end -->

        <!-- subscribe-section -->
        <section class="subscribe-section p_relative pl_60 pr_60">
            <div class="bg-shape p_absolute l_0 b_0"></div>
            <div class="outer-container p_relative bg_yellow pt_60 pb_60">
                <div class="pattern-layer p_absolute l_0 b_0 r_0" style="background-image: url(<?= $base ?>/assets/images/shape/shape-3.png);"></div>
                <div class="large-container">
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-12 col-sm-12 text-column">
                            <div class="text">
                                <h2 class="d_block fs_44 lh_50 color_white fw_exbold"><?= htmlspecialchars(setting('subscribe_title', 'Güncellemeleri Kaçırmayın, Şimdi Abone Olun!')) ?></h2>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 form-column">
                            <div class="form-inner p_relative d_block ml_40 mt_8">
                                <form action="<?= $siteBase ?>/contact.php" method="get" class="d_iblock">
                                    <input type="hidden" name="newsletter" value="1">
                                    <div class="form-group p_relative d_block bg_white mr-0 p_13 b_radius_3 pr_100">
                                        <input type="email" name="email" placeholder="E-posta adresiniz..." required>
                                        <button type="submit"><i class="fas fa-envelope-open"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- subscribe-section end -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
