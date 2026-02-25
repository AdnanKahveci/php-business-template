# Primevilla

Amortez şablonuna benzer yapıda, veritabanı ve admin paneli olan **Primevilla** emlak sitesi.

## Gereksinimler

- PHP 7.4+
- MySQL 5.7+ / MariaDB (XAMPP ile gelir)
- Amortez klasörü (CSS, JS, resimler) proje içinde kalmalı

## Kurulum

1. **XAMPP** ile Apache ve MySQL’i başlatın.

2. **Kurulum sayfasını** açın:
   ```
   http://localhost/primevilla/install/install.php
   ```
   "Kurulumu Başlat" butonuna tıklayın. Bu işlem:
   - `primevilla` veritabanını oluşturur
   - Tabloları (admin_users, site_settings, contact_messages, pages) oluşturur
   - Varsayılan admin kullanıcısını ekler

3. **Admin giriş bilgileri:**
   - URL: `http://localhost/primevilla/admin/login.php`
   - Kullanıcı: **admin**
   - Şifre: **admin123**
   - İlk girişten sonra şifrenizi değiştirmeniz önerilir (şu an admin panelinden şifre değiştirme sayfası yok; ileride eklenebilir).

4. **Güvenlik:** Kurulum bittikten sonra `install` klasörünü silin veya `install/install.php` dosyasını kaldırın.

## Site yapısı

| Dosya / Klasör      | Açıklama |
|---------------------|----------|
| `index.php`         | Ana sayfa |
| `about.php`         | Hakkımızda (içerik veritabanından) |
| `contact.php`       | İletişim formu |
| `process-contact.php` | İletişim formu gönderimi → veritabanına kayıt |
| `admin/`            | Yönetim paneli (giriş, dashboard, mesajlar, ayarlar) |
| `config/`           | Veritabanı ve genel ayarlar |
| `includes/`         | Site header/footer (Amortez stil) |
| `Amortez/`          | Şablon asset’leri (CSS, JS, resimler) – referans template |
| `install/`          | Kurulum script’i (kurulumdan sonra silinebilir) |

## Veritabanı ayarları

Varsayılan (XAMPP): `config/database.php`

- **Host:** localhost  
- **Veritabanı:** primevilla  
- **Kullanıcı:** root  
- **Şifre:** (boş)

Farklı sunucu veya şifre kullanıyorsanız `config/database.php` ve `install/install.php` içindeki `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` değerlerini güncelleyin.

## Admin paneli

- **Dashboard:** Özet (toplam / okunmamış mesaj sayısı).
- **Mesajlar:** İletişim formundan gelen mesajlar listelenir, okundu işaretlenir, silinebilir.
- **Ayarlar:** Site adı, slogan, telefon, e-posta, adres, footer metni, (opsiyonel) Google Map iframe.

Site adı, iletişim bilgileri ve footer metni bu ayarlardan alınır; header ve footer’da kullanılır.

## Notlar

- Tasarım ve asset’ler **Amortez** template’inden kullanılmaktadır; site adı ve menü Primevilla’ya göre düzenlenmiştir.
- Logo ve görselleri kendi markanıza göre `Amortez/assets/images/` altında değiştirebilirsiniz.
- İleride admin panele: şifre değiştirme, sayfa içeriklerini (Hakkımızda vb.) düzenleme, emlak ilanları modülü eklenebilir.
