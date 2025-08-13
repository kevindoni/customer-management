<?php

namespace Database\Seeders;

use App\Models\WhatsappGateway\WhatsappBootMessage;
use Illuminate\Database\Seeder;

class WhatsappBootMessageTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $welcome_message = 'Apa yang bisa saya bantu %name%

        pilih menu berikut:
        %menu%';

        //=================Create Template message============================

        $menu = WhatsappBootMessage::create([
            'whatsapp_boot_message_id' => null,
            'name' => 'Welcome Message',
            'message' => $welcome_message,
            'action_message' => null,
            'end_message' => false,
            'disabled' => false
        ]);

        $menu_paket_message = '%menu%
        0. Menu sebelumnya';

        $menuPaket = WhatsappBootMessage::create([
            'whatsapp_boot_message_id' => $menu->id,
            'command_number' => 1,
            'name' => 'Menu Paket',
            'message' =>  $menu_paket_message,
            'action_message' => null,
            'end_message' => false,
            'disabled' => false
        ]);

        $menu_tagihan_message = 'Berikut info tagihan anda.

        %bills%
        
        Jumlah tagihan: %total-bills%
            
        Anda dapat melakukan pembayaran melalui:
        %account-banks%
            
        Terima kasih telah mempercayakan kebutuhan internet anda kepada kami.';

        $menuTagihan = WhatsappBootMessage::create([
            'whatsapp_boot_message_id' => $menu->id,
            'command_number' => 2,
            'name' => 'Info Tagihan',
            'message' => $menu_tagihan_message,
            'action_message' => 'info_tagihan',
            'end_message' => true,
            'disabled' => false
        ]);


        $ubah_ssid_message = 'Masukkan ssid baru wifi anda

        0. Menu sebelumnya';

        WhatsappBootMessage::create([
            'whatsapp_boot_message_id' => $menuPaket->id,
            'command_number' => 1,
            'name' => 'Ubah SSID',
            'message' => $ubah_ssid_message,
            'action_message' => 'change_ssid',
            'end_message' => false,
            'disabled' => false
        ]);

        $ubah_sandi_message = 'Masukkan sandi baru wifi anda

        0. Menu sebelumnya';

        WhatsappBootMessage::create([
            'whatsapp_boot_message_id' => $menuPaket->id,
            'command_number' => 2,
            'name' => 'Ubah Sandi Wifi',
            'message' => $ubah_sandi_message,
            'action_message' => 'change_password_wifi',
            'end_message' => false,
            'disabled' => false
        ]);

        $info_paket_message = 'Berikut kami sampaikan rincian paket anda:

        %pakets%
        
        Terima kasih telah mempercayakan layanan internet anda kepada kami. Kami berkomitmen untuk selalu memberikan pelayanan yang terbaik.';

        WhatsappBootMessage::create([
            'whatsapp_boot_message_id' => $menuPaket->id,
            'command_number' => 3,
            'name' => 'Info Paket',
            'message' => $info_paket_message,
            'action_message' => 'info_paket',
            'end_message' => true,
            'disabled' => false
        ]);

        $info_wifi_message = '%info_wifi%';
        WhatsappBootMessage::create([
            'whatsapp_boot_message_id' => $menuPaket->id,
            'command_number' => 4,
            'name' => 'Info Wifi',
            'message' => $info_wifi_message,
            'action_message' => 'info_wifi',
            'end_message' => true,
            'disabled' => false
        ]);
    }
}
