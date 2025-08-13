<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\WhatsappGateway\WhatsappNotificationMessage;

class WhatsappNotificationMessageTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pesan_pembayaran_lunas = '*WindaNet*

Pelanggan WindaNet yang terhormat,
Berikut kami sampaikan detail pembayaran anda:

Atas Nama : Bp/Ibu *%name%*
Customer-ID : %customer_id%
Alamat : %address%
Nomor Tagihan: %invoice_number%
ID Transaksi: *%transaction_id%*
Paket Internet : *%paket%*
Periode : *%periode%*
Nominal : *%bill%*
Teller : %teller%
Tanggal : %payment_time%
Metode pembayaran: %payment_methode%


Terima kasih telah mempercayakan kebutuhan internetnya kepada kami. Kami berkomitmen untuk selalu memberikan yang terbaik untuk pelanggan.
Mohon maaf jika ada penulisan nama yang salah. Anda dapat menghubungi kami untuk pembetulan nama.';

        $pay_later = '*WindaNet*

Pelanggan WindaNet yang terhormat,
Berikut kami sampaikan detail pembayaran mundur anda:

Atas Nama : Bp/Ibu *%name%*
Customer-ID : %customer_id%
Alamat : %address%
Nomor Tagihan: %invoice_number%
ID Transaksi: *%transaction_id%*
Paket Internet : *%paket%*
Periode : *%periode%*
Nominal : *%bill%*
Teller : %teller%
Metode pembayaran: *%payment_methode%*
Jatuh Tempo: *%paylater%*


Terima kasih telah mempercayakan kebutuhan internetnya kepada kami. Kami berkomitmen untuk selalu memberikan yang terbaik untuk pelanggan.
Mohon maaf jika ada penulisan nama yang salah. Anda dapat menghubungi kami untuk pembetulan nama.';

        $pesan_unpayment = 'Yth. Bp/Ibu %name%,
%address%
Customer ID: %customer_id%

Maaf telah terjadi kesalahan system.

Pembayaran anda:
Nomor Tagihan: *%invoice_number%*
ID Transaksi: *%transaction_id%*
Jumlah: *%bill%*

Telah dibatalkan.

Terima kasih telah menggunakan layanan internet kami.

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';


        $pesan_peringatan_pembayaran = '
Pengingat Tagihan

Yth. Bp/Ibu %name%,
%address%
Customer ID: %customer_id%

%day% adalah batas akhir pembayaran paket %paket-name% internet anda. Saat ini anda memiliki %count-bill% tagihan yang belum dibayar:

%pakets%
*Jumlah tagihan: %total-bill%*

Lakukan pembayaran sebelum jatuh tempo untuk menghindari isolir otomatis oleh system kami.

Terima kasih telah menggunakan layanan internet kami.

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';

        $pesan_peringatan_deadline = 'Yth. Bp/Ibu %name%,
%address%
Customer ID: %customer_id%

Hari ini adalah batas akhir pembayaran paket %paket-name% internet anda. Saat ini anda memiliki %count-bill% tagihan yang belum dibayar:

%pakets%
*Jumlah tagihan: %total-bill%*

Segera lakukan pembayaran sebelum jatuh tempo untuk menghindari isolir otomatis oleh system kami.
Anda dapat melakukan pembayaran melalui:
%account-bank%
Terima kasih telah menggunakan layanan internet kami.

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';



        $register_new_customer = 'Yth. Bp/Ibu %name%,
%address%

Terima kasih telah melakukan pendaftaran di sistem kami.
Berikut layanan yang dapat anda pilih:

%pakets%

Jika anda ingin menggunakan layanan kami, silahkan hubungi 085726455588.

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';

        $activation_new_customer = 'Yth. Bp/Ibu %name%,
%address%

Aktivasi akun berhasil. Berikut detail informasi akun anda:

Nama: %name%
Alamat: %address%
Customer ID: %customer_id%
Email: %email%
Customer Service: %customer_service%

Simpan informasi ini untuk pengajuan layanan atau saat terjadi gangguan layanan.

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';

        $deactivation_customer = 'Yth. Bp/Ibu %name%,
%address%
Customer ID: %customer_id%

Dengan berat hati kami menonaktifkan akun anda. Jika tidak keberatan, silahkan kirim masukkan anda untuk perbaikan pada layanan kami.

Terima kasih telah menjadi pelanggan kami selama ini.

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';

        $add_paket = 'Yth. Bp/Ibu %name%,
%address%
Customer ID: %customer_id%

Pendaftaran paket berhasil, berikut info layanan paket anda:

Paket: %paket_name%
Harga: %paket_price%

Biaya Langganan: %bill% per bulan

silahkan tunggu beberapa saat karna paket anda masih dalam proses aktivasi.

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';

        $activation_paket = 'Yth. Bp/Ibu %name%,
%address%
Customer ID: %customer_id%

Paket anda sudah berhasil diaktivasi, selamat menikmati layanan internet dari kami.

Batas bayar: Setiap tanggal %deadline%
Nama CS: %cs_name%
Contact: %cs_contact%

Jika terjadi kendala pada jaringan, silahkan hubungi CS kami.

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';

        $enable_paket = 'Yth. Bp/Ibu %name%,
%address%
Customer ID: %customer_id%

Paket anda sudah diaktifkan, selamat menikmati layanan internet kami kembali.

Info paket:
Paket: *%paket_name%*
Tagihan: *%bill%* per bulan
Status: *%status%*
Tanggal Aktivasi: %activation_date%

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';
        $admin_disable_paket = 'Info Paket Customer

Name: %name%
Alamat: %address%
Paket: %paket_name%
Status: *%status_admin%*';

        $disable_paket = 'Yth. Bp/Ibu %name%,
%address%
Customer ID: %customer_id%

Paket anda sudah dinon-aktifkan, kami tidak mengenakan biaya layanan selama paket anda dinon-aktifkan.

Info paket:
Paket: *%paket_name%*
Status: *%status%*

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';

        $notif_admin_payment = 'Informasi Pembayaran

Nama: %name%
Alamat: %address%
Customer ID: %customer_id%
Email: %email%
Nominal: %bill%
Periode: %periode%
Methode: %payment_methode%
Teller: %teller%
Waktu: %payment_time%

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';

        $notif_admin_unpayment = 'Informasi Pembayaran

Terjadi kesalahan system pembayaran.

Nama: %name%
Alamat:%address%
Periode: %periode%
Nomor Tagihan: *%invoice_number%*
ID Transaksi: *%transaction_id%*
Jumlah: *%bill%*

Telah dibatalkan.

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';

        $pesan_informasi_isolir = 'Yth. Bp/Ibu %name%,
%address%
Customer ID: %customer_id%

Saat ini koneksi internet anda sedang terblokir. Anda memiliki %count-bill% tagihan yang belum dibayar:

%pakets%
*Jumlah tagihan: %total-bill%*

Segera lakukan pembayaran untuk menikmati layananan internet kembali.
Anda dapat melakukan pembayaran melalui:
%account-bank%
Terima kasih telah menggunakan layanan internet kami.

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*';

$pesan_informasi_isolir_admin = '
Informasi Tagihan dan Isolir

Tagihan akan datang:
%customer_bills%

Isolir
%customer_isolirs%
';

        //=================Create Template message============================


        WhatsappNotificationMessage::create([
            'slug' => 'payment',
            'name' => 'Pembayaran Lunas',
            'message' => $pesan_pembayaran_lunas,
            'description' =>  'Pesan yang dikirim saat pelanggan melakukan pembayaran.',
            'default' => true,
            'disabled' => false
        ]);

        WhatsappNotificationMessage::create([
            'slug' => 'unpayment',
            'name' => 'Unpayment',
            'message' => $pesan_unpayment,
            'description' =>  'Pesan yang dikirim saat admin melakukan pembatalan pembayaran.',
            'default' => true,
            'disabled' => false
        ]);


        WhatsappNotificationMessage::create([
            'slug' => 'warning_bill',
            'name' => 'Warning Due Date',
            'message' => $pesan_peringatan_pembayaran,
            'description' =>  'Pesan yang dikirim untuk memberitahukan bahwa tagihan mereka akan jatuh tempo.',
            'default' => true,
            'disabled' => false
        ]);

        WhatsappNotificationMessage::create([
            'slug' => 'deadline_bill',
            'name' => 'Deadline Bill',
            'message' => $pesan_peringatan_deadline,
            'description' =>  'Pesan yang dikirim untuk memberitahukan bahwa tagihan mereka sudah jatuh tempo.',
            'default' => true,
            'disabled' => false
        ]);


        WhatsappNotificationMessage::create([
            'slug' => 'disable_paket',
            'name' => 'Disable Paket',
            'message' => $disable_paket,
            'description' =>  'Pesan yang dikirim saat paket di non-aktifkan.',
            'default' => true,
            'disabled' => false
        ]);
        WhatsappNotificationMessage::create([
            'slug' => 'enable_paket',
            'name' => 'Enable Paket',
            'message' => $enable_paket,
            'description' =>  'Pesan yang dikirim saat paket di aktifkan.',
            'default' => true,
            'disabled' => false
        ]);
        WhatsappNotificationMessage::create([
            'slug' => 'activation_paket',
            'name' => 'Activation Paket',
            'message' => $activation_paket,
            'description' =>  'Pesan yang dikirim saat aktivasi paket.',
            'default' => true,
            'disabled' => false
        ]);
        WhatsappNotificationMessage::create([
            'slug' => 'add_paket',
            'name' => 'Add Paket',
            'message' => $add_paket,
            'description' =>  'Pesan yang dikirim saat penambahan paket customer.',
            'default' => true,
            'disabled' => false
        ]);
        WhatsappNotificationMessage::create([
            'slug' => 'deactivation_customer',
            'name' => 'Deactivation Customer',
            'message' => $deactivation_customer,
            'description' =>  'Pesan yang dikirim saat customer di non-aktifkan.',
            'default' => true,
            'disabled' => false
        ]);
        WhatsappNotificationMessage::create([
            'slug' => 'activation_customer',
            'name' => 'Activation Customer',
            'message' => $activation_new_customer,
            'description' =>  'Pesan yang dikirim saat aktivasi customer.',
            'default' => true,
            'disabled' => false
        ]);
        WhatsappNotificationMessage::create([
            'slug' => 'register_customer',
            'name' => 'Register Customer',
            'message' => $register_new_customer,
            'description' =>  'Pesan yang dikirim saat registrasi customer.',
            'default' => true,
            'disabled' => false
        ]);


        WhatsappNotificationMessage::create([
            'slug' => 'paylater',
            'name' => 'Pay Later',
            'message' => $pay_later,
            'description' =>  'Pesan yang dikirim saat permintaan pay later.',
            'default' => true,
            'disabled' => false
        ]);

        WhatsappNotificationMessage::create([
            'slug' => 'notif_admin_payment',
            'name' => 'Notif Admin Payment',
            'message' => $notif_admin_payment,
            'description' =>  'Notif admin saat ada transaksi pembayaran.',
            'default' => true,
            'disabled' => false
        ]);

        WhatsappNotificationMessage::create([
            'slug' => 'notif_admin_paylater',
            'name' => 'Notif Admin Paylater',
            'message' => '',
            'description' =>  'Notif admin saat ada transaksi pembayaran mundur.',
            'default' => true,
            'disabled' => false
        ]);

        WhatsappNotificationMessage::create([
            'slug' => 'notif_admin_unpayment',
            'name' => 'Notif Admin Unpayment',
            'message' => $notif_admin_unpayment,
            'description' =>  'Notif admin saat ada pembatalan transaksi pembayaran.',
            'default' => true,
            'disabled' => false
        ]);

        WhatsappNotificationMessage::create([
            'slug' => 'isolir_paket',
            'name' => 'Isolir Paket Customer',
            'message' => $pesan_informasi_isolir,
            'description' =>  'Pemberitahuan kepada customer saat koneksi internet terblokir.',
            'default' => true,
            'disabled' => false
        ]);

        WhatsappNotificationMessage::create([
            'slug' => 'notif_admin_disable_paket',
            'name' => 'Notif Admin Disable/Enable/Activastion Paket ',
            'message' => $admin_disable_paket,
            'description' =>  'Pemberitahuan kepada admin saat paket pelanggan di matikan.',
            'default' => true,
            'disabled' => false
        ]);

        WhatsappNotificationMessage::create([
            'slug' => 'notif_admin_bill_and_isolir',
            'name' => 'Notifikasi Admin Info Pembayaran dan Isolir',
            'message' => $pesan_informasi_isolir_admin,
            'description' =>  'Pesan informasi tagihan dan client isolir yang dikirim ke admin.',
            'default' => true,
            'disabled' => false
        ]);
    }
}
