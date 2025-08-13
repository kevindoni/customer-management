<?php

namespace App\Services\Mikrotiks;

use Illuminate\Support\Facades\Log;
use App\Models\Servers\WanMonitoring;
use App\Models\Customers\CustomerPppPaket;
use App\Models\Servers\MikrotikClientHistory;
use App\Models\Servers\MikrotikMonitoring;



class MikrotikMonitoringService
{

    public function user_ppp_monitoring($data)
    {
        $customerPppPakets = CustomerPppPaket::where('username', $data['user'])->get();
        foreach ($customerPppPakets as $customerPppPaket) {
            $customerPaket = $customerPppPaket->customer_paket;
            if ($customerPaket->paket->mikrotik_id == $data['mikrotik_id']) {
                if ($data['status'] == 'up') {
                    $customerPaket->forceFill([
                        'online' => true,
                    ])->save();
                } else {
                    $customerPaket->forceFill([
                        'online' => false,
                    ])->save();
                }

                MikrotikClientHistory::create([
                    'mikrotik_id' => $data['mikrotik_id'],
                    'customer_paket_id' => $customerPaket->id,
                    'type' => 'ppp',
                    'history' => json_encode($data),
                    'status' => $data['status'],
                    'recorded_at' => now()
                ]);

                //   (new WhatsappMessageNotificationService())->clientStatus($ppp_paket, $this->input['status']);
            }
        }
    }

    public function wan_monitoring($data)
    {
        $mikrotikMonitoring = MikrotikMonitoring::where('mikrotik_id', $data['mikrotik_id'])->first();
        if (!$mikrotikMonitoring->disabled) {
            WanMonitoring::create([
                'mikrotik_id' =>  $data['mikrotik_id'],
                'interface_name' =>  $data['interface_name'],
                'rx_rate' => (int)$data['rx_byte'],
                'tx_rate' => (int)$data['tx_byte']
            ]);
        }
    }
}
