# Environment Setup Guide - Customer Management System v2.0.6

Panduan lengkap untuk setup environment variables pada Customer Management System.

## 📋 Prerequisites

Sebelum memulai setup environment, pastikan Anda telah:
- [ ] Install PHP 8.2+ dan Composer
- [ ] Install MySQL/PostgreSQL
- [ ] Install Redis (opsional)
- [ ] Clone repository Customer Management System
- [ ] Install dependencies dengan `composer install`

## 🚀 Quick Setup

### 1. Copy Environment File
```bash
# Dari root project
cp env.example .env
```

### 2. Generate Application Key
```bash
php artisan key:generate
```

### 3. Update Database Configuration
Edit file `.env` dan sesuaikan dengan database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=customer_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run Migrations
```bash
php artisan migrate
php artisan db:seed
```

## 🔧 Detailed Configuration

### Application Settings
```env
APP_NAME="Customer Management System"
APP_ENV=local                    # local, staging, production
APP_DEBUG=true                   # false untuk production
APP_URL=http://localhost:8000   # URL aplikasi Anda
APP_VERSION=2.0.6
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id                   # Bahasa Indonesia
```

### Database Configuration
```env
# MySQL (Default)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=customer_management
DB_USERNAME=root
DB_PASSWORD=

# PostgreSQL (Alternative)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=customer_management
DB_USERNAME=postgres
DB_PASSWORD=
```

### Redis Configuration (Optional)
```env
# Untuk Queue dan Cache
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0

# Update Queue dan Cache driver
QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
```

### WhatsApp Gateway (GriyaNet)
```env
WA_GRIYANET_API_URL=https://api.griyanet.id
WA_GRIYANET_API_KEY=your_api_key_here
WA_GRIYANET_DEVICE_ID=your_device_id
WA_GRIYANET_WEBHOOK_SECRET=your_webhook_secret
WA_GRIYANET_RATE_LIMIT=100
WA_GRIYANET_RETRY_ATTEMPTS=3
```

**Cara mendapatkan credentials:**
1. Daftar di [GriyaNet WhatsApp Gateway](https://griyanet.id)
2. Buat device baru
3. Scan QR code untuk koneksi
4. Copy API key dan device ID dari dashboard

### Payment Gateway Configuration

#### Tripay
```env
TRIPAY_API_KEY=your_tripay_api_key
TRIPAY_PRIVATE_KEY=your_tripay_private_key
TRIPAY_MERCHANT_CODE=your_merchant_code
TRIPAY_API_URL=https://tripay.co.id/api/
TRIPAY_CALLBACK_URL=https://yourdomain.com/api/tripay/callback
```

#### Midtrans
```env
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_IS_PRODUCTION=false    # true untuk production
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### Billing System Configuration
```env
BILLING_AUTO_CREATE_INVOICE=true
BILLING_REMINDER_DAYS=7         # Hari sebelum deadline
BILLING_GRACE_PERIOD_DAYS=3     # Grace period setelah deadline
BILLING_AUTO_ISOLIR=true        # Auto isolir otomatis
BILLING_ISOLIR_AFTER_DAYS=10    # Isolir setelah X hari
BILLING_DEFAULT_CURRENCY=IDR
BILLING_TAX_RATE=11             # Pajak dalam persen
BILLING_DISCOUNT_ENABLED=true
```

### Auto-Isolir Configuration
```env
AUTOISOLIR_ENABLED=true
AUTOISOLIR_CHECK_INTERVAL=3600  # Check setiap jam
AUTOISOLIR_NOTIFICATION_ENABLED=true
AUTOISOLIR_WHATSAPP_NOTIFICATION=true
AUTOISOLIR_EMAIL_NOTIFICATION=false
```

### Monitoring Configuration
```env
MONITORING_ENABLED=true
MONITORING_INTERVAL=300         # 5 menit
MONITORING_RETENTION_DAYS=30    # Simpan data 30 hari
MONITORING_ALERT_THRESHOLD=80   # Alert jika > 80%
MONITORING_EMAIL_ALERTS=false
```

### Security Configuration
```env
SESSION_SECURE_COOKIE=false     # true untuk HTTPS
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
PASSWORD_TIMEOUT=10800          # 3 jam
LOGIN_MAX_ATTEMPTS=5            # Maksimal login attempts
LOGIN_LOCKOUT_DURATION=900      # Lockout 15 menit
```

### Export Configuration
```env
EXPORT_MAX_ROWS=10000           # Maksimal rows per export
EXPORT_CHUNK_SIZE=1000          # Chunk size untuk memory
EXPORT_TEMP_PATH=storage/app/temp/exports
EXPORT_RETENTION_HOURS=24       # Hapus file setelah 24 jam
```

### Company Information
```env
COMPANY_NAME="Your Company Name"
COMPANY_ADDRESS="Your Company Address"
COMPANY_PHONE="+62-xxx-xxx-xxxx"
COMPANY_EMAIL="info@yourcompany.com"
COMPANY_WEBSITE="https://yourcompany.com"
```

## 🌍 Environment-Specific Configuration

### Local Development
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
LOG_LEVEL=debug
DEBUGBAR_ENABLED=true
TELESCOPE_ENABLED=true
```

### Staging Environment
```env
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://staging.yourdomain.com
LOG_LEVEL=info
DEBUGBAR_ENABLED=false
TELESCOPE_ENABLED=false
```

### Production Environment
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
LOG_LEVEL=error
DEBUGBAR_ENABLED=false
TELESCOPE_ENABLED=false
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 🔐 Security Best Practices

### 1. Environment Variables
- Jangan commit file `.env` ke repository
- Gunakan `.env.example` sebagai template
- Set `APP_DEBUG=false` di production
- Gunakan strong passwords untuk database

### 2. Database Security
```env
# Gunakan user khusus, bukan root
DB_USERNAME=customer_management_user
DB_PASSWORD=strong_password_here

# Restrict database access
# Hanya berikan permission yang diperlukan
```

### 3. API Keys
```env
# Jangan share API keys
# Gunakan environment variables
# Rotate keys secara berkala
# Monitor usage dan access logs
```

## 🧪 Testing Configuration

### 1. Test Database Connection
```bash
php artisan tinker
# Test database connection
DB::connection()->getPdo();
```

### 2. Test WhatsApp Gateway
```bash
php artisan tinker
# Test WhatsApp connection
app(\App\Services\WhatsappGateway\GatewayApiService::class)->testConnection();
```

### 3. Test Payment Gateway
```bash
php artisan tinker
# Test Tripay connection
app(\App\Services\Payments\PaymentGatewayService::class)->testTripayConnection();
```

## 🚨 Troubleshooting

### Common Issues

#### 1. Database Connection Failed
```bash
# Check database service
sudo systemctl status mysql

# Check credentials
mysql -u username -p

# Check database exists
SHOW DATABASES;
```

#### 2. WhatsApp Gateway Not Working
```bash
# Check API key
# Verify device is connected
# Check webhook URL
# Monitor logs
tail -f storage/logs/laravel.log
```

#### 3. Payment Gateway Issues
```bash
# Verify API keys
# Check callback URLs
# Test in sandbox mode first
# Monitor payment logs
```

### Debug Commands
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Check configuration
php artisan config:show

# Check routes
php artisan route:list

# Check environment
php artisan env
```

## 📚 Additional Resources

- [Laravel Environment Configuration](https://laravel.com/docs/configuration)
- [GriyaNet WhatsApp Gateway Documentation](https://docs.griyanet.id)
- [Tripay API Documentation](https://tripay.co.id/developer)
- [Midtrans Documentation](https://docs.midtrans.com)

## 🆘 Support

Jika mengalami masalah dengan setup environment:

1. **Check Logs**: `storage/logs/laravel.log`
2. **GitHub Issues**: [Create Issue](https://github.com/kevindoni/customer-management/issues)
3. **Documentation**: Lihat file README.md dan RELEASE_NOTES.md
4. **Community**: [GitHub Discussions](https://github.com/kevindoni/customer-management/discussions)

---

**Note**: Pastikan untuk backup file `.env` sebelum melakukan perubahan dan test semua konfigurasi sebelum deploy ke production.
