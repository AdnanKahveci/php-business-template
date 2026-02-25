<?php
$footerGallery = [];
try {
    require_once __DIR__ . '/../config/database.php';
    $pdoFooter = primevilla_pdo();
    $footerGallery = $pdoFooter->query("SELECT * FROM gallery_images ORDER BY sort_order, id LIMIT 9")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
?>

        <footer class="main-footer">
            <div class="large-container">
                <div class="footer-top">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget logo-widget">
                                <figure class="footer-logo"><a href="<?= isset($siteBase) ? $siteBase : '' ?>/index.php"><img src="<?= ($footerLogo = setting('footer_logo_url')) ? (isset($siteBase) ? $siteBase : (rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/') ?: '/')) . '/' . $footerLogo : (isset($base) ? $base : BASE_ASSETS) . '/assets/images/footer-logo.png' ?>" alt=""></a></figure>
                                <div class="text">
                                    <p><?= htmlspecialchars($siteTagline ?? setting('site_tagline')) ?></p>
                                </div>
                                <div class="location-box">
                                    <div class="icon-box"><i class="fal fa-map-marker-alt"></i></div>
                                    <p><?= htmlspecialchars(setting('contact_address', '—')) ?></p>
                                </div>
                                <div class="support-box">
                                    <div class="icon-box"><i class="far fa-phone"></i></div>
                                    <span>Telefon</span>
                                    <h3><a href="tel:<?= preg_replace('/\s+/', '', setting('contact_phone')) ?>"><?= htmlspecialchars(setting('contact_phone', '—')) ?></a></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget links-widget">
                                <div class="widget-title"><h3>Hizmetler & Sayfalar</h3></div>
                                <div class="widget-content">
                                    <ul class="links-list clearfix">
                                        <li><a href="<?= isset($siteBase) ? $siteBase : '' ?>/index.php">Ana Sayfa</a></li>
                                        <li><a href="<?= isset($siteBase) ? $siteBase : '' ?>/about.php">Hakkımızda</a></li>
                                        <li><a href="<?= isset($siteBase) ? $siteBase : '' ?>/service.php">Hizmetler</a></li>
                                        <li><a href="<?= isset($siteBase) ? $siteBase : '' ?>/projeler">Projeler</a></li>
                                        <li><a href="<?= isset($siteBase) ? $siteBase : '' ?>/gallery.php">Galeri</a></li>
                                        <li><a href="<?= isset($siteBase) ? $siteBase : '' ?>/blog">Blog</a></li>
                                        <li><a href="<?= isset($siteBase) ? $siteBase : '' ?>/contact.php">İletişim</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget gallery-widget">
                                <div class="widget-title"><h3>Galeri</h3></div>
                                <div class="widget-content">
                                    <ul class="image-list clearfix">
                                        <?php foreach ($footerGallery as $g): ?>
                                        <?php
                                            $gUrl = strpos($g['image'], 'uploads/') === 0
                                                ? (isset($siteBase) ? $siteBase : '') . '/' . $g['image']
                                                : (isset($base) ? $base : BASE_ASSETS) . '/assets/images/news/gallery-1.jpg';
                                        ?>
                                        <li>
                                            <figure class="image">
                                                <a href="<?= htmlspecialchars($gUrl) ?>" data-fancybox="footer-gallery" data-caption="<?= htmlspecialchars($g['title'] ?? '') ?>">
                                                    <img src="<?= htmlspecialchars($gUrl) ?>" alt="<?= htmlspecialchars($g['title'] ?? '') ?>">
                                                </a>
                                            </figure>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php if (empty($footerGallery)): ?>
                                        <p style="color:#888; font-size:0.9rem;">Henüz galeri görseli eklenmedi.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom clearfix centred">
                    <div class="copyright">
                        <p><?= nl2br(htmlspecialchars(setting('footer_text', '© ' . date('Y') . ' ' . (isset($siteName) ? $siteName : SITE_NAME)))) ?></p>
                    </div>
                </div>
            </div>
        </footer>

        <button class="scroll-top scroll-to-target" data-target="html">
            <span class="fal fa-long-arrow-up"></span>
        </button>
    </div>

    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/jquery.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/popper.min.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/owl.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/wow.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/validation.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/jquery.fancybox.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/appear.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/scrollbar.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/isotope.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/jquery.paroller.min.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/jquery.nice-select.min.js"></script>
    <script src="<?= (isset($base) ? $base : BASE_ASSETS) ?>/assets/js/script.js"></script>
</body>
</html>
