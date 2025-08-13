# Customer Management System v2.0.6

Sistem manajemen pelanggan yang komprehensif untuk ISP (Internet Service Provider) dengan fitur billing, monitoring Mikrotik, dan integrasi WhatsApp Gateway.

## 🚀 Fitur Utama

### 👥 Manajemen Pelanggan
- Pendaftaran dan profil pelanggan lengkap
- Manajemen paket internet (PPP dan Static IP)
- Sistem billing dan invoice otomatis
- Notifikasi WhatsApp untuk pelanggan
- Auto-isolir untuk pelanggan yang tidak membayar

### 🖥️ Integrasi Mikrotik
- Monitoring real-time traffic interface
- Import/export pelanggan dan paket
- Manajemen user PPP dan static IP
- Monitoring WAN dan resource server
- Backup dan restore konfigurasi

### 💰 Sistem Billing
- Invoice otomatis berdasarkan paket
- Multiple payment gateway (Tripay, Midtrans)
- Sistem diskon dan pembayaran cicilan
- Export invoice ke Excel/PDF
- Notifikasi pembayaran via WhatsApp

### 📱 WhatsApp Gateway
- Integrasi dengan GriyaNet WhatsApp Gateway
- Notifikasi otomatis untuk billing
- Pesan broadcast ke semua pelanggan
- Template pesan yang dapat dikustomisasi
- Monitoring status pengiriman

### 🔐 Keamanan & Role
- Sistem role dan permission yang fleksibel
- Multi-user dengan hak akses berbeda
- Audit log untuk semua aktivitas
- Middleware keamanan yang robust

## 🛠️ Teknologi

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Livewire 3, Alpine.js, Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Charts**: ApexCharts
- **Queue**: Redis/Database
- **Cache**: Redis/Memcached

## 📋 Persyaratan Sistem

- PHP 8.2 atau lebih tinggi
- Composer 2.0+
- MySQL 8.0+ atau PostgreSQL 13+
- Redis (opsional, untuk queue dan cache)
- Web server (Apache/Nginx)

## 🚀 Instalasi

1. **Clone repository**
```bash
git clone https://github.com/kevindoni/customer-management.git
cd customer-management
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi database**
```bash
php artisan migrate
php artisan db:seed
```

5. **Build assets**
```bash
npm run build
```

6. **Jalankan aplikasi**
```bash
php artisan serve
```

## 🔧 Konfigurasi

### Mikrotik
- Tambahkan server Mikrotik di menu Settings > Servers
- Konfigurasi API credentials
- Setup monitoring interface

### WhatsApp Gateway
- Daftar device di GriyaNet WhatsApp Gateway
- Scan QR code untuk koneksi
- Konfigurasi template pesan

### Payment Gateway
- Setup Tripay atau Midtrans
- Konfigurasi API keys
- Test pembayaran

## 📚 Dokumentasi

- [User Guide](docs/user-guide.md)
- [API Documentation](docs/api.md)
- [Deployment Guide](docs/deployment.md)
- [Troubleshooting](docs/troubleshooting.md)

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Kontak

- **Developer**: Kevin Doni
- **Email**: [email protected]
- **GitHub**: [@kevindoni](https://github.com/kevindoni)

## 🙏 Terima Kasih

Terima kasih kepada semua kontributor dan komunitas yang telah mendukung pengembangan sistem ini.

---

**Version**: 2.0.6  
**Last Updated**: August 2025  
**Status**: Stable Release