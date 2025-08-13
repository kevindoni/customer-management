# Changelog

Semua perubahan penting pada Customer Management System akan didokumentasikan dalam file ini.

Format berdasarkan [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
dan project ini mengikuti [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.6] - 2025-08-13

### 🚀 Fitur Baru
- **Sistem Billing yang Diperbaiki**: Perbaikan pada fitur Add Invoice untuk stabilitas yang lebih baik
- **Tombol Hapus Invoice**: Fitur baru untuk menghapus invoice yang tidak diperlukan
- **Kirim Pesan ke Semua Pelanggan**: Kemampuan untuk mengirim pesan broadcast ke seluruh pelanggan
- **Catatan Pembayaran**: Sistem pencatatan dan tracking pembayaran yang lebih detail

### 🐛 Perbaikan Bug
- Perbaikan bug pada fitur Add Invoice yang menyebabkan error
- Optimasi performa pada sistem billing
- Perbaikan validasi input pada form pembayaran
- Stabilisasi sistem notifikasi WhatsApp

### 🔧 Perbaikan Teknis
- Update dependencies ke versi terbaru
- Optimasi query database untuk performa yang lebih baik
- Perbaikan security pada middleware autentikasi
- Enhancement pada sistem logging

### 📱 WhatsApp Gateway
- Perbaikan pada template pesan notifikasi
- Optimasi pengiriman pesan batch
- Enhancement pada monitoring status pengiriman

### 🖥️ Integrasi Mikrotik
- Perbaikan pada monitoring traffic interface
- Optimasi import/export data pelanggan
- Enhancement pada backup konfigurasi

## [2.0.5] - 2025-07-XX

### 🚀 Fitur Baru
- Integrasi WhatsApp Gateway dengan GriyaNet
- Sistem auto-isolir untuk pelanggan yang tidak membayar
- Monitoring real-time traffic Mikrotik
- Export invoice ke Excel dan PDF

### 🐛 Perbaikan Bug
- Perbaikan pada sistem billing otomatis
- Stabilisasi integrasi payment gateway
- Perbaikan pada manajemen user roles

## [2.0.4] - 2025-06-XX

### 🚀 Fitur Baru
- Sistem role dan permission yang fleksibel
- Integrasi payment gateway Tripay dan Midtrans
- Dashboard monitoring yang interaktif
- API untuk integrasi mobile app

### 🔧 Perbaikan Teknis
- Upgrade ke Laravel 12
- Implementasi Livewire 3
- Optimasi database queries
- Enhancement security features

## [2.0.3] - 2025-05-XX

### 🚀 Fitur Baru
- Manajemen paket internet (PPP dan Static IP)
- Sistem billing otomatis
- Import/export data dari Mikrotik
- Monitoring resource server

### 🐛 Perbaikan Bug
- Perbaikan pada sistem autentikasi
- Stabilisasi manajemen pelanggan
- Perbaikan pada form validasi

## [2.0.2] - 2025-04-XX

### 🚀 Fitur Baru
- Sistem manajemen pelanggan dasar
- Autentikasi multi-user
- Dashboard admin
- Manajemen paket internet

### 🔧 Perbaikan Teknis
- Implementasi Laravel framework
- Setup database structure
- Basic UI components

## [2.0.1] - 2025-03-XX

### 🚀 Fitur Baru
- Initial release
- Basic customer management
- Simple billing system
- User authentication

### 🔧 Perbaikan Teknis
- Project initialization
- Basic architecture setup
- Database design

## [2.0.0] - 2025-02-XX

### 🚀 Fitur Baru
- **Major Release**: Versi stabil pertama
- Sistem manajemen pelanggan lengkap
- Integrasi Mikrotik dasar
- Sistem billing sederhana

---

## Catatan Versi

- **Major**: Perubahan yang tidak kompatibel dengan versi sebelumnya
- **Minor**: Fitur baru yang kompatibel dengan versi sebelumnya
- **Patch**: Perbaikan bug dan peningkatan performa

## Kontribusi

Untuk menambahkan perubahan pada changelog, silakan buat Pull Request dengan format yang sesuai.

## Referensi

- [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
- [Semantic Versioning](https://semver.org/spec/v2.0.0.html)
