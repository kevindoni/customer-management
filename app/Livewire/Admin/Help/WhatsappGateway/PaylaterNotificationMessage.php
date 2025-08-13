<?php

namespace App\Livewire\Admin\Help\WhatsappGateway;

use Livewire\Component;

class PaylaterNotificationMessage extends Component
{
    public $input = [];
    public function render()
    {
        $pesan = "*WindaNet*

Pelanggan WindaNet yang terhormat,
Berikut kami sampaikan detail pembayaran mundur anda:

Atas Nama : Bp/Ibu *%name%*
Customer-ID : %customer_id%
Alamat : %address%
Nomor Tagihan: %invoice_number%
ID Transaksi: %transaction_id%
Paket Internet : *%paket%*
Periode : *%periode%*
Nominal : *%bill%*
Teller : %teller%
Metode pembayaran: *%payment_methode%*
Jatuh Tempo: *%paylater%*


Terima kasih telah mempercayakan kebutuhan internetnya kepada kami. Kami berkomitmen untuk selalu memberikan yang terbaik untuk pelanggan.
Mohon maaf jika ada penulisan nama yang salah. Anda dapat menghubungi kami untuk pembetulan nama.";
        $this->input['namaPesan'] = 'Paylater';
        $this->input['pesan'] = $pesan;
        return view('livewire.admin.help.whatsapp-gateway.paylater-notification-message');
    }
}
