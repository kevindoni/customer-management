<?php

namespace App\Models\WhatsappGateway;

use Illuminate\Database\Eloquent\Model;

class WhatsappGatewayGeneral extends Model
{
    public $guarded = [];

    public function enable()
    {
        $this->disabled = false;
        $this->save();
    }

    public function disable()
    {
        $this->disabled = true;
        $this->save();
    }

    public function sendWaAdminEnable()
    {
        $this->send_wa_admin = true;
        $this->save();
    }

    public function sendWaAdminDisable()
    {
        $this->send_wa_admin = false;
        $this->save();
    }

    public function isDisable()
    {
        return $this->disabled;
    }


    public function bootNumberMessage()
    {
        return $this->whatsapp_number_boot;
    }


    public function notificationNumberMessage()
    {
        return $this->whatsapp_number_notification;
    }
    
}
