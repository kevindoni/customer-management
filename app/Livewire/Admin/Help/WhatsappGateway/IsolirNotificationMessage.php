<?php

namespace App\Livewire\Admin\Help\WhatsappGateway;

use Livewire\Component;

class IsolirNotificationMessage extends Component
{
    public $input = [];
    public function render()
    {
        $pesan = "Yth. Bp/Ibu %name%,
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

*Pesan ini dikirim otomatis oleh system, anda tidak perlu membalasnya*";
        $this->input['namaPesan'] = 'Isolir Paket Customer';
        $this->input['pesan'] = $pesan;
        return view('livewire.admin.help.whatsapp-gateway.isolir-notification-message');
    }
}
