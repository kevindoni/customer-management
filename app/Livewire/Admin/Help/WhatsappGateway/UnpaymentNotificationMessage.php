<?php

namespace App\Livewire\Admin\Help\WhatsappGateway;

use Livewire\Component;

class UnpaymentNotificationMessage extends Component
{
    public $input = [];
    public function render()
    {
        $pesan = "Yth. Bp/Ibu %name%,
%address%
Customer ID: %customer_id%

Maaf telah terjadi kesalahan system.

Pembayaran anda:
Nomor Tagihan: %invoice_number%
ID Transaksi: %transaction_id%
Jumlah: *%bill%*

Telah dibatalkan.

Terima kasih telah menggunakan layanan internet kami.

*WindaNet*
Dk. Dukuh RT.02 RW.04 Tegalsari
Kec. Weru, Kab. Sukoharjo

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*";
        $this->input['namaPesan'] = 'Unpayment';
        $this->input['pesan'] = $pesan;
        return view('livewire.admin.help.whatsapp-gateway.unpayment-notification-message');
    }
}
