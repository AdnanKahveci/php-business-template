<?php
$admin_title = 'Ana Sayfa - Skills Bölümü';
$current_admin_page = 'home-skills';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = primevilla_pdo();

function save_setting($pdo, $k, $v) {
    $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)")->execute([$k, $v]);
}

$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    save_setting($pdo, 'skills_title', trim($_POST['skills_title'] ?? ''));
    save_setting($pdo, 'skills_desc', trim($_POST['skills_desc'] ?? ''));
    if (isset($_POST['progress'])) {
        foreach ($_POST['progress'] as $i => $p) {
            $title = trim($p['title'] ?? '');
            $percent = (int)($p['percent'] ?? 0);
            $id = (int)($p['id'] ?? 0);
            if ($id) {
                $pdo->prepare("UPDATE home_progress SET title=?, percent=?, sort_order=? WHERE id=?")->execute([$title, $percent, $i, $id]);
            } elseif ($title) {
                $pdo->prepare("INSERT INTO home_progress (title, percent, sort_order) VALUES (?,?,?)")->execute([$title, $percent, $i]);
            }
        }
    }
    if (isset($_POST['amenities'])) {
        foreach ($_POST['amenities'] as $i => $a) {
            $title = trim($a['title'] ?? '');
            $icon_num = (int)($a['icon_num'] ?? 1);
            if ($icon_num < 1 || $icon_num > 6) $icon_num = 1;
            $id = (int)($a['id'] ?? 0);
            if ($id) {
                $pdo->prepare("UPDATE home_amenities SET title=?, icon_num=?, sort_order=? WHERE id=?")->execute([$title, $icon_num, $i, $id]);
            } elseif ($title) {
                $pdo->prepare("INSERT INTO home_amenities (title, icon_num, sort_order) VALUES (?,?,?)")->execute([$title, $icon_num, $i]);
            }
        }
    }
    if (isset($_POST['counters'])) {
        foreach ($_POST['counters'] as $i => $c) {
            $number = (int)($c['number'] ?? 0);
            $suffix = trim($c['suffix'] ?? '');
            $label = trim($c['label'] ?? '');
            $icon = trim($c['icon_class'] ?? 'icon-14');
            $id = (int)($c['id'] ?? 0);
            if ($id) {
                $pdo->prepare("UPDATE home_counters SET number=?, suffix=?, label=?, icon_class=?, sort_order=? WHERE id=?")->execute([$number, $suffix, $label, $icon, $i, $id]);
            } elseif ($label) {
                $pdo->prepare("INSERT INTO home_counters (number, suffix, label, icon_class, sort_order) VALUES (?,?,?,?,?)")->execute([$number, $suffix, $label, $icon, $i]);
            }
        }
    }
    $saved = true;
}

$settings = [];
foreach ($pdo->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ('skills_title','skills_desc')")->fetchAll(PDO::FETCH_ASSOC) as $r) {
    $settings[$r['setting_key']] = $r['setting_value'];
}
$get = fn($k) => $settings[$k] ?? '';

$progressItems = [];
$amenityItems = [];
$counterItems = [];
try {
    $progressItems = $pdo->query("SELECT * FROM home_progress ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);
    $amenityItems = $pdo->query("SELECT * FROM home_amenities ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);
    $counterItems = $pdo->query("SELECT * FROM home_counters ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
while (count($progressItems) < 3) $progressItems[] = ['id'=>0,'title'=>'','percent'=>0];
while (count($amenityItems) < 6) $amenityItems[] = ['id'=>0,'title'=>'','icon_num'=>1];
while (count($counterItems) < 4) $counterItems[] = ['id'=>0,'number'=>0,'suffix'=>'','label'=>'','icon_class'=>'icon-14'];

require_once __DIR__ . '/includes/header.php';
?>
<?php if ($saved): ?><div class="alert alert-success">Kaydedildi.</div><?php endif; ?>

<div class="card">
    <h2>Skills Bölümü</h2>
    <p>Ana sayfadaki "Keyifli Yaşam Alanları" bölümünü düzenleyin: başlık, ilerleme çubukları, amenity ikonları ve sayaçlar.</p>
    <form method="post">
        <h3>Bölüm başlığı</h3>
        <div class="form-group">
            <label>Başlık</label>
            <input type="text" name="skills_title" value="<?= htmlspecialchars($get('skills_title') ?: 'Keyifli Yaşam Alanları') ?>">
        </div>
        <div class="form-group">
            <label>Açıklama</label>
            <textarea name="skills_desc" rows="2"><?= htmlspecialchars($get('skills_desc') ?: 'Hayalinizdeki eve kavuşmak için yanınızdayız.') ?></textarea>
        </div>

        <h3 style="margin-top:24px;">İlerleme çubukları (3 adet)</h3>
        <?php foreach (array_slice($progressItems, 0, 3) as $i => $p): ?>
        <div class="form-group" style="display:flex;gap:12px;align-items:center;">
            <input type="hidden" name="progress[<?= $i ?>][id]" value="<?= (int)($p['id']??0) ?>">
            <input type="text" name="progress[<?= $i ?>][title]" value="<?= htmlspecialchars($p['title']??'') ?>" placeholder="Başlık" style="flex:1;">
            <input type="number" name="progress[<?= $i ?>][percent]" value="<?= (int)($p['percent']??0) ?>" min="0" max="100" placeholder="%" style="width:80px;">
        </div>
        <?php endforeach; ?>

        <h3 style="margin-top:24px;">Amenity ikonları (6 adet) - icon-1.png ... icon-6.png</h3>
        <?php foreach (array_slice($amenityItems, 0, 6) as $i => $a): ?>
        <div class="form-group" style="display:flex;gap:12px;align-items:center;">
            <input type="hidden" name="amenities[<?= $i ?>][id]" value="<?= (int)($a['id']??0) ?>">
            <input type="text" name="amenities[<?= $i ?>][title]" value="<?= htmlspecialchars($a['title']??'') ?>" placeholder="Başlık" style="flex:1;">
            <label>İkon:</label>
            <select name="amenities[<?= $i ?>][icon_num]">
                <?php for ($n=1;$n<=6;$n++): ?><option value="<?= $n ?>" <?= (($a['icon_num']??1)==$n)?'selected':'' ?>>icon-<?= $n ?>.png</option><?php endfor; ?>
            </select>
        </div>
        <?php endforeach; ?>

        <h3 style="margin-top:24px;">Sayaçlar (4 adet)</h3>
        <?php foreach (array_slice($counterItems, 0, 4) as $i => $c): ?>
        <div class="form-group" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <input type="hidden" name="counters[<?= $i ?>][id]" value="<?= (int)($c['id']??0) ?>">
            <input type="number" name="counters[<?= $i ?>][number]" value="<?= (int)($c['number']??0) ?>" placeholder="Sayı" style="width:80px;">
            <input type="text" name="counters[<?= $i ?>][suffix]" value="<?= htmlspecialchars($c['suffix']??'') ?>" placeholder="Sonek (+)" style="width:60px;">
            <input type="text" name="counters[<?= $i ?>][label]" value="<?= htmlspecialchars($c['label']??'') ?>" placeholder="Etiket" style="flex:1;">
            <select name="counters[<?= $i ?>][icon_class]">
                <option value="icon-14" <?= (($c['icon_class']??'')==='icon-14')?'selected':'' ?>>icon-14</option>
                <option value="icon-15" <?= (($c['icon_class']??'')==='icon-15')?'selected':'' ?>>icon-15</option>
                <option value="icon-16" <?= (($c['icon_class']??'')==='icon-16')?'selected':'' ?>>icon-16</option>
                <option value="icon-17" <?= (($c['icon_class']??'')==='icon-17')?'selected':'' ?>>icon-17</option>
            </select>
        </div>
        <?php endforeach; ?>

        <p style="margin-top:20px;"><button type="submit" class="btn">Kaydet</button></p>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
