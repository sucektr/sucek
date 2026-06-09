# SUÇEK — Yayına Alma & Otomatik Deploy Kılavuzu

## Genel Bakış

Bu kılavuz projeyi GitHub'a yükleyip cPanel paylaşımlı sunucuya otomatik yayına alma sürecini adım adım anlatır.

```
Yerel geliştirme → git push → GitHub Actions → cPanel Sunucu
```

---

## 1. Sunucu Ön Hazırlık (cPanel)

### 1.1 Dizin Yapısı

cPanel'de `public_html` dışında, **üst dizine** projeyi yerleştirin:

```
/home/KULLANICI_ADI/
  sucek/                ← Laravel projesinin klonlanacağı yer
    app/
    public/             ← Web kökü olarak ayarlanacak
    ...
  public_html/          ← Varsayılan (kullanılmayacak)
```

### 1.2 SSH Anahtarı Oluşturma (cPanel → SSH Access)

1. cPanel → **SSH Access** → **Manage SSH Keys**
2. **Generate a New Key** → Key Name: `github_deploy` → Generate
3. Oluşan **public key**'i authorize edin (Authorization status: Authorized)
4. **Private key**'i görüntüleyin ve kopyalayın (GitHub Secret olarak eklenecek)

### 1.3 Web Kökünü Ayarlama

cPanel → **Domains** → Alan adınızı seçin → **Document Root** → `/home/KULLANICI_ADI/sucek/public` olarak güncelleyin.

> Alternatif: cPanel bu özelliği desteklemiyorsa `public_html/.htaccess` ile yönlendirme yapabilirsiniz (Bkz. Bölüm 6).

### 1.4 PHP Sürüm ve Yollarını Öğrenme

SSH terminaline bağlanıp şu komutları çalıştırın:

```bash
# PHP binary yolu
which php
# Örnek çıktı: /usr/local/bin/php
# ya da: /opt/cpanel/ea-php82/root/usr/bin/php

# Composer yolu
which composer
# Yoksa: find ~ -name "composer.phar" 2>/dev/null
```

Çıkan yolları not edin; GitHub Secrets'a ekleyeceksiniz.

### 1.5 İlk Kez Git Clone (SSH ile sunucuya bağlanın)

```bash
cd ~
git clone https://github.com/GITHUB_KULLANICI/REPO_ADI.git sucek
cd sucek

# .env dosyasını oluştur
cp .env.example .env
nano .env   # Üretim değerlerini girin (bkz. Bölüm 5)

# Uygulama anahtarı oluştur
php artisan key:generate

# Bağımlılıklar
composer install --no-dev --optimize-autoloader

# Migrasyon
php artisan migrate --force

# Storage symlink
php artisan storage:link

# İzinler
chmod -R 755 storage bootstrap/cache
```

---

## 2. GitHub Repository Kurulumu

### 2.1 Repoyu Oluşturma

```bash
# Projenin kök dizininde (c:\laragon\www\sucek)
git init
git branch -M main
git add .
git commit -m "İlk commit"
git remote add origin https://github.com/KULLANICI/sucek.git
git push -u origin main
```

### 2.2 GitHub Secrets Ekleme

**GitHub Repo → Settings → Secrets and variables → Actions → New repository secret**

| Secret Adı | Açıklama | Örnek Değer |
|---|---|---|
| `SSH_HOST` | Sunucu IP veya hostname | `192.168.1.1` ya da `sucek.com.tr` |
| `SSH_USERNAME` | cPanel kullanıcı adı | `sucekuser` |
| `SSH_PRIVATE_KEY` | SSH private key (tamamı) | `-----BEGIN OPENSSH PRIVATE KEY-----...` |
| `SSH_PORT` | SSH portu | `22` |
| `DEPLOY_PATH` | Sunucudaki proje klasörü | `/home/sucekuser/sucek` |
| `PHP_BIN` | PHP binary tam yolu | `/usr/local/bin/php` |
| `COMPOSER_BIN` | Composer tam yolu | `/usr/local/bin/composer` |

> **Not:** SSH private key'i eklerken `-----BEGIN OPENSSH PRIVATE KEY-----` başlığı ve `-----END OPENSSH PRIVATE KEY-----` sonu dahil tamamını yapıştırın.

---

## 3. Otomatik Deploy Akışı

`.github/workflows/deploy.yml` dosyası şu adımları otomatik çalıştırır:

```
git push origin main
       ↓
GitHub Actions tetiklenir
       ↓
1. Kodu checkout'la
2. Node.js 20 kur
3. npm ci → npm run build  (Vite asset'larını derle)
4. SCP: public/build/ → sunucu
5. SSH:
   - git pull origin main
   - composer install --no-dev
   - artisan config/route/view cache
   - artisan migrate --force
   - İzinleri düzenle
       ↓
✅ Yayında
```

---

## 4. Günlük Kullanım

```bash
# Yerel değişiklik yap
# Test et (http://sucek.test)
git add .
git commit -m "Açıklama"
git push origin main
# → GitHub Actions otomatik devreye girer (~3-5 dakika)
```

GitHub → **Actions** sekmesinden deployment durumunu takip edebilirsiniz.

---

## 5. Sunucu .env Yapılandırması

SSH ile bağlanıp `/home/KULLANICI/sucek/.env` dosyasını düzenleyin:

```env
APP_NAME="SUÇEK"
APP_ENV=production
APP_KEY=           # php artisan key:generate ile otomatik dolar
APP_DEBUG=false
APP_URL=https://sucek.com.tr

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=CPANEL_DB_ADI
DB_USERNAME=CPANEL_DB_KULLANICI
DB_PASSWORD=CPANEL_DB_SIFRE

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=mail.sucek.com.tr
MAIL_PORT=465
MAIL_USERNAME=info@sucek.com.tr
MAIL_PASSWORD=MAIL_SIFRE
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@sucek.com.tr"
MAIL_FROM_NAME="SUÇEK"

# Instagram API (varsa)
INSTAGRAM_ACCESS_TOKEN=
```

> **Önemli:** `.env` dosyası git'te yer almaz, sunucuda manuel oluşturulur ve asla değiştirilmez.

---

## 6. Alternatif: public_html Yönlendirmesi

cPanel document root değişikliğine izin vermiyorsa, `public_html/.htaccess` kullanın:

```apache
RewriteEngine On
RewriteRule ^(.*)$ /home/KULLANICI_ADI/sucek/public/$1 [L]
```

Ya da `public_html/index.php` içini şununla değiştirin:

```php
<?php
require '/home/KULLANICI_ADI/sucek/public/index.php';
```

---

## 7. Manuel Deploy (Acil Durum)

GitHub Actions çalışmazsa SSH ile manuel deploy:

```bash
ssh KULLANICI@SUNUCU
cd ~/sucek
git pull origin main
/usr/local/bin/php /usr/local/bin/composer install --no-dev --optimize-autoloader
/usr/local/bin/php artisan migrate --force
/usr/local/bin/php artisan config:cache
/usr/local/bin/php artisan route:cache
/usr/local/bin/php artisan view:cache
chmod -R 755 storage bootstrap/cache
```

---

## 8. Sorun Giderme

| Sorun | Çözüm |
|---|---|
| `500 Server Error` | `storage/logs/laravel.log` dosyasını kontrol et |
| Beyaz ekran | `APP_DEBUG=true` yap, hatayı gör, sonra `false`'a döndür |
| CSS/JS yüklenmiyor | `npm run build` çıktısını ve `public/build/` klasörünü kontrol et |
| Migrasyon hatası | DB bilgilerini `.env`'de kontrol et |
| `Permission denied` | `chmod -R 755 storage bootstrap/cache` çalıştır |
| GitHub Action başarısız | Actions sekmesinde log'lara bak; Secrets doğru girilmiş mi kontrol et |
| Composer bulunamıyor | `which composer` ile tam yolu bul, `COMPOSER_BIN` secret'ını güncelle |

---

## 9. Gereksinimler

| Gereksinim | Minimum |
|---|---|
| PHP | 8.1+ |
| MySQL | 5.7+ |
| Composer | 2.x |
| Node.js (CI'da) | 20.x |
| cPanel | SSH erişimi açık |
