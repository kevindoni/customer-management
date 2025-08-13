<?php

namespace App\Livewire\Admin\Help\WhatsappGateway;

use Livewire\Component;

class WarningBillNotificationMessage extends Component
{
    public $input = [];
    public function render()
    {
        $pesan = "Pengingat Tagihan:

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

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*";
        $this->input['namaPesan'] = 'Warning Due Date';
        $this->input['pesan'] = $pesan;
        return view('livewire.admin.help.whatsapp-gateway.warning-bill-notification-message');
    }
}
