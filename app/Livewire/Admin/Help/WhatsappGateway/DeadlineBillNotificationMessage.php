<?php

namespace App\Livewire\Admin\Help\WhatsappGateway;

use Livewire\Component;

class DeadlineBillNotificationMessage extends Component
{
    public $input = [];
    public function render()
    {
        $pesan = "Yth. Bp/Ibu %name%,
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

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*";
        $this->input['namaPesan'] = 'Deadline Bill';
        $this->input['pesan'] = $pesan;
        return view('livewire.admin.help.whatsapp-gateway.deadline-bill-notification-message');
    }
}
