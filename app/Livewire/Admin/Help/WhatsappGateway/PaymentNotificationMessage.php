<?php

namespace App\Livewire\Admin\Help\WhatsappGateway;

use Livewire\Component;

class PaymentNotificationMessage extends Component
{
    public $input = [];
    public function render()
    {
        $pesan = "Pelanggan %company% yang terhormat,
Berikut kami sampaikan detail pembayaran anda:

Atas Nama : Bp/Ibu *%name%*
Customer-ID : %customer_id%
Alamat : %address%
Nomor Tagihan: %invoice_number%
ID Transaksi: %transaction_id%
Paket Internet : *%paket%*
Periode : *%periode%*
Nominal : *%bill%*
Teller : %teller%
Tanggal : %payment_time%
Metode pembayaran: %payment_methode%

Terima kasih telah mempercayakan kebutuhan internetnya kepada kami. Kami berkomitmen untuk
selalu memberikan yang terbaik untuk pelanggan.
Mohon maaf jika ada penulisan nama yang salah. Anda dapat menghubungi kami untuk pembetulan
nama.";
        $this->input['namaPesan'] = 'Pembayaran Lunas';
        $this->input['pesan'] = $pesan;
        return view('livewire.admin.help.whatsapp-gateway.payment-notification-message');
    }
}
