<?php
$admin_title = 'Site ayarları';
$current_admin_page = 'settings';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = primevilla_pdo();
$saved = false;
$error = '';

$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$image_keys = [
    'logo_url'          => ['file' => 'logo', 'name' => 'logo.png'],
    'footer_logo_url'   => ['file' => 'footer_logo', 'name' => 'footer-logo.png'],
    'about_image_1'     => ['file' => 'about_image_1', 'name' => 'about-1.jpg'],
    'about_image_2'     => ['file' => 'about_image_2', 'name' => 'about-2.jpg'],
    'page_title_bg'     => ['file' => 'page_title_bg', 'name' => 'page-title.jpg'],
];

function save_setting($pdo, $key, $value) {
    $st = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    $st->execute([$key, $value]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $textKeys = ['site_name', 'site_tagline', 'contact_phone', 'contact_email', 'contact_address', 'footer_text', 'google_map_embed', 'award_box_title', 'award_box_subtitle', 'service_title', 'service_desc', 'subscribe_title'];
    foreach ($textKeys as $k) {
        if (array_key_exists($k, $_POST)) {
            save_setting($pdo, $k, trim($_POST[$k]));
        }
    }

    if (!is_dir(UPLOAD_DIR)) {
        @mkdir(UPLOAD_DIR, 0755, true);
    }

    foreach ($image_keys as $settingKey => $info) {
        $inputName = $info['file'];
        if (!empty($_FILES[$inputName]['tmp_name']) && is_uploaded_file($_FILES[$inputName]['tmp_name'])) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES[$inputName]['tmp_name']);
            if (!in_array($mime, $allowed_types)) {
                $error = 'Sadece resim dosyaları (JPG, PNG, GIF, WebP) yüklenebilir.';
                continue;
            }
            $baseName = pathinfo($info['name'], PATHINFO_FILENAME);
            $ext = strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION) ?: pathinfo($info['name'], PATHINFO_EXTENSION) ?: 'png');
            $ext = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? $ext : 'png';
            $fileName = $baseName . '.' . $ext;
            $dest = UPLOAD_DIR . '/' . $fileName;
            if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $dest)) {
                save_setting($pdo, $settingKey, UPLOAD_URL . '/' . $fileName);
            }
        }
    }

    if (!$error) $saved = true;
}

$settings = [];
$rows = $pdo->query("SELECT setting_key, setting_value FROM site_settings")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    $settings[$r['setting_key']] = $r['setting_value'];
}
$get = function($k) use ($settings) { return $settings[$k] ?? ''; };
?>

<?php if ($saved): ?>
    <div class="alert alert-success">Ayarlar kaydedildi.</div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:20px;">
        <h2 style="margin:0;">Site ayarları</h2>
        <button type="submit" form="settings-form" class="btn">Tümünü kaydet</button>
    </div>
    <form id="settings-form" method="post" action="" enctype="multipart/form-data">
        <h3 style="margin-top:0; margin-bottom:12px;">Logo ve görseller</h3>
        <p style="color:#666; margin-bottom:16px;">Header ve footer logosu ile sayfa görselleri. Yeni dosya seçip kaydederek güncelleyebilirsiniz.</p>
        <table style="max-width: 640px;">
            <tr>
                <td style="vertical-align:top; padding-right:16px; width:200px;"><label>Üst logo (header)</label><br><small>PNG önerilir, yükseklik ~46px</small></td>
                <td>
                    <?php if ($get('logo_url')): ?>
                        <p><img src="../<?= htmlspecialchars($get('logo_url')) ?>?t=<?= time() ?>" alt="" style="max-height:50px; max-width:200px; border:1px solid #eee; padding:4px; border-radius:6px;"></p>
                    <?php else: ?>
                        <p style="color:#888;">Henüz logo yüklenmedi.</p>
                    <?php endif; ?>
                    <input type="file" name="logo" accept="image/*">
                </td>
            </tr>
            <tr>
                <td style="vertical-align:top; padding-right:16px;"><label>Footer logo</label></td>
                <td>
                    <?php if ($get('footer_logo_url')): ?>
                        <p><img src="../<?= htmlspecialchars($get('footer_logo_url')) ?>?t=<?= time() ?>" alt="" style="max-height:50px; max-width:200px; border:1px solid #eee; padding:4px; border-radius:6px;"></p>
                    <?php else: ?>
                        <p style="color:#888;">Henüz logo yüklenmedi.</p>
                    <?php endif; ?>
                    <input type="file" name="footer_logo" accept="image/*">
                </td>
            </tr>
            <tr>
                <td style="vertical-align:top; padding-right:16px;"><label>Sayfa başlık arka plan</label><br><small>Üst bölüm arka planı</small></td>
                <td>
                    <?php if ($get('page_title_bg')): ?>
                        <p><img src="../<?= htmlspecialchars($get('page_title_bg')) ?>?t=<?= time() ?>" alt="" style="max-width:200px; max-height:80px; object-fit:cover; border:1px solid #eee; border-radius:6px;"></p>
                    <?php endif; ?>
                    <input type="file" name="page_title_bg" accept="image/*">
                </td>
            </tr>
            <tr>
                <td style="vertical-align:top; padding-right:16px;"><label>Hakkımızda görsel 1</label></td>
                <td>
                    <?php if ($get('about_image_1')): ?>
                        <p><img src="../<?= htmlspecialchars($get('about_image_1')) ?>?t=<?= time() ?>" alt="" style="max-width:200px; max-height:120px; object-fit:cover; border:1px solid #eee; border-radius:6px;"></p>
                    <?php endif; ?>
                    <input type="file" name="about_image_1" accept="image/*">
                </td>
            </tr>
            <tr>
                <td style="vertical-align:top; padding-right:16px;"><label>Hakkımızda görsel 2</label></td>
                <td>
                    <?php if ($get('about_image_2')): ?>
                        <p><img src="../<?= htmlspecialchars($get('about_image_2')) ?>?t=<?= time() ?>" alt="" style="max-width:200px; max-height:120px; object-fit:cover; border:1px solid #eee; border-radius:6px;"></p>
                    <?php endif; ?>
                    <input type="file" name="about_image_2" accept="image/*">
                </td>
            </tr>
        </table>

        <h3 style="margin-top:28px; margin-bottom:12px;">İletişim ve metinler</h3>
        <table style="max-width: 640px;">
            <tr>
                <td><label for="site_name">Site adı</label></td>
                <td><input type="text" id="site_name" name="site_name" value="<?= htmlspecialchars($get('site_name')) ?>" style="width:100%; padding:8px;"></td>
            </tr>
            <tr>
                <td><label for="site_tagline">Slogan</label></td>
                <td><input type="text" id="site_tagline" name="site_tagline" value="<?= htmlspecialchars($get('site_tagline')) ?>" style="width:100%; padding:8px;"></td>
            </tr>
            <tr>
                <td><label for="contact_phone">Telefon</label></td>
                <td><input type="text" id="contact_phone" name="contact_phone" value="<?= htmlspecialchars($get('contact_phone')) ?>" style="width:100%; padding:8px;"></td>
            </tr>
            <tr>
                <td><label for="contact_email">E-posta</label></td>
                <td><input type="email" id="contact_email" name="contact_email" value="<?= htmlspecialchars($get('contact_email')) ?>" style="width:100%; padding:8px;"></td>
            </tr>
            <tr>
                <td><label for="contact_address">Adres</label></td>
                <td><input type="text" id="contact_address" name="contact_address" value="<?= htmlspecialchars($get('contact_address')) ?>" style="width:100%; padding:8px;"></td>
            </tr>
            <tr>
                <td><label for="footer_text">Footer metni</label></td>
                <td><input type="text" id="footer_text" name="footer_text" value="<?= htmlspecialchars($get('footer_text')) ?>" style="width:100%; padding:8px;"></td>
            </tr>
            <tr>
                <td><label for="google_map_embed">Google Map iframe (opsiyonel)</label></td>
                <td><textarea id="google_map_embed" name="google_map_embed" rows="3" style="width:100%; padding:8px;"><?= htmlspecialchars($get('google_map_embed')) ?></textarea></td>
            </tr>
            <tr><td colspan="2"><p style="margin:20px 0 0 0;"><button type="submit" class="btn">Tümünü kaydet</button></p></td></tr>
            <tr><td colspan="2"><h3 style="margin-top:20px;">Ana sayfa bölüm metinleri</h3></td></tr>
            <tr>
                <td><label for="award_box_title">Ödül kutusu başlık</label></td>
                <td><input type="text" id="award_box_title" name="award_box_title" value="<?= htmlspecialchars($get('award_box_title')) ?>" placeholder="<?= htmlspecialchars(setting('site_name')) ?>" style="width:100%; padding:8px;"></td>
            </tr>
            <tr>
                <td><label for="award_box_subtitle">Ödül kutusu alt metin</label></td>
                <td><input type="text" id="award_box_subtitle" name="award_box_subtitle" value="<?= htmlspecialchars($get('award_box_subtitle')) ?>" placeholder="En İyi Gayrimenkul Danışmanı '24" style="width:100%; padding:8px;"></td>
            </tr>
            <tr>
                <td><label for="service_title">Hizmet bölümü başlık</label></td>
                <td><input type="text" id="service_title" name="service_title" value="<?= htmlspecialchars($get('service_title')) ?>" placeholder="Satılık ve Kiralık Konut Hizmetleri" style="width:100%; padding:8px;"></td>
            </tr>
            <tr>
                <td><label for="service_desc">Hizmet bölümü açıklama</label></td>
                <td><textarea id="service_desc" name="service_desc" rows="2" style="width:100%; padding:8px;"><?= htmlspecialchars($get('service_desc')) ?></textarea></td>
            </tr>
            <tr>
                <td><label for="subscribe_title">Abone bölümü başlık</label></td>
                <td><input type="text" id="subscribe_title" name="subscribe_title" value="<?= htmlspecialchars($get('subscribe_title')) ?>" placeholder="Güncellemeleri Kaçırmayın, Şimdi Abone Olun!" style="width:100%; padding:8px;"></td>
            </tr>
        </table>
        <div class="form-actions">
            <button type="submit" class="btn">Tümünü kaydet</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
