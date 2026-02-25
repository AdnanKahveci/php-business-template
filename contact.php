<?php
$currentPage = 'contact';
$pageTitle = 'İletişim';
$form_message = isset($_GET['message']) ? trim($_GET['message']) : '';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/includes/header.php';

$base = BASE_ASSETS;
?>

        <section class="page-title p_relative pt_250 pb_170 centred" style="background-image: url(<?= ($bg = setting('page_title_bg')) ? $bg : $base . '/assets/images/background/page-title.jpg' ?>);">
            <div class="bg-layer p_absolute r_100 t_0" style="background-image: url(<?= $base ?>/assets/images/background/page-title-2.png);"></div>
            <div class="large-container">
                <div class="content-box p_relative d_block z_5">
                    <div class="title mb_25">
                        <h1 class="d_block fs_68 lh_76 color_white fw_exbold">İletişim</h1>
                    </div>
                    <ul class="bread-crumb clearfix">
                        <li class="p_relative d_iblock fs_16 color_white pl_45 pr_30 mr_10"><i class="fas fa-home"></i><a href="index.php" class="color_white hov_color">Ana Sayfa</a></li>
                        <li class="p_relative d_iblock fs_16 color_white">İletişim</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="contact-style-three p_relative pt_110 pb_120">
            <div class="large-container">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12 col-sm-12 form-column">
                        <div class="form-inner">
                            <h2 class="p_relative d_iblock fs_30 lh_40 fw_exbold mb_45 pb_8">Mesaj gönderin</h2>
                            <?php if ($form_message === 'success'): ?>
                            <div class="alert mb_20 p_13 b_radius_3" style="background:#d4edda;color:#155724;">
                                Mesajınız alındı. En kısa sürede size dönüş yapacağız.
                            </div>
                            <?php elseif ($form_message === 'error'): ?>
                            <div class="alert mb_20 p_13 b_radius_3" style="background:#f8d7da;color:#721c24;">
                                Gönderim sırasında bir hata oluştu. Lütfen tüm alanları doldurup tekrar deneyin.
                            </div>
                            <?php endif; ?>
                            <form method="post" action="process-contact.php" id="contact-form" class="default-form">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group mb_20">
                                        <input type="text" name="name" placeholder="Ad Soyad" required>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group mb_20">
                                        <input type="email" name="email" placeholder="E-posta" required>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group mb_20">
                                        <input type="text" name="phone" placeholder="Telefon">
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group mb_20">
                                        <input type="text" name="subject" placeholder="Konu" required>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group mb_20">
                                        <textarea name="message" placeholder="Mesajınız" required></textarea>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group message-btn mr-0">
                                        <button class="theme-btn btn-one" type="submit" name="submit">Gönder<i class="icon-3"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 info-column">
                        <div class="info-inner p_relative d_block ml_65">
                            <h2 class="p_relative d_iblock fs_30 lh_40 fw_exbold mb_35 pb_8">İletişim bilgileri</h2>
                            <p class="mb_45">Sorularınız için bize ulaşabilirsiniz.</p>
                            <ul class="info-list">
                                <li class="p_relative d_block pl_140 mb_30 pb_45">
                                    <i class="fal fa-map-marker-alt"></i>
                                    <span class="p_relative d_block fs_18 lh_28 fw_normal mb_7">Adres</span>
                                    <h5 class="d_block fs_18 fw_sbold"><?= htmlspecialchars(setting('contact_address', '—')) ?></h5>
                                </li>
                                <li class="p_relative d_block pl_140 mb_30 pb_45">
                                    <i class="fal fa-phone"></i>
                                    <span class="p_relative d_block fs_18 lh_28 fw_normal mb_7">Telefon</span>
                                    <h5 class="d_block fs_18 fw_sbold"><a href="tel:<?= preg_replace('/\s+/', '', setting('contact_phone')) ?>" class="d_iblock color_black hov_color"><?= htmlspecialchars(setting('contact_phone', '—')) ?></a></h5>
                                </li>
                                <li class="p_relative d_block pl_140 pb_45">
                                    <i class="fal fa-envelope-open-text"></i>
                                    <span class="p_relative d_block fs_18 lh_28 fw_normal mb_7">E-posta</span>
                                    <h5 class="d_block fs_18 fw_sbold"><a href="mailto:<?= htmlspecialchars(setting('contact_email')) ?>" class="d_iblock color_black hov_color"><?= htmlspecialchars(setting('contact_email', '—')) ?></a></h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
