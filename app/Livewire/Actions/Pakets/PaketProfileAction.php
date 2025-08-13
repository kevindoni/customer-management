<?php

namespace App\Livewire\Actions\Pakets;

use Illuminate\Support\Str;
use App\Models\Pakets\PaketProfile;
use App\Services\Mikrotiks\MikrotikPppService;
use App\Services\Mikrotiks\MikrotikIpStaticService;

class PaketProfileAction
{
    private MikrotikPppService $pppService;
    private MikrotikIpStaticService $ipStaticService;
    public function __construct()
    {
        $this->pppService = new MikrotikPppService;
        $this->ipStaticService = new MikrotikIpStaticService;
    }
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function add_profile(array $input): PaketProfile
    {
        $ratelimit =  $input['max_limit'] . ' ' . $input['burst_limit'] . ' ' . $input['burst_threshold'] . ' ' . $input['burst_time'] . ' ' . $input['priority'] . ' ' . $input['limit_at'];
        return PaketProfile::create([
            'slug' => str(Str::random(10))->slug(),
            'profile_name' => $input['profile_name'],
            'rate_limit' => $ratelimit,
        ]);
    }

    public function update_profile(PaketProfile $paketProfile, array $input)
    {
        // $oldName = $paketProfile->profile_name;
        $ratelimit =  $input['max_limit'] . ' ' . $input['burst_limit'] . ' ' . $input['burst_threshold'] . ' ' . $input['burst_time'] . ' ' . $input['priority'] . ' ' . $input['limit_at'];
        $paketProfile->forceFill([
            'profile_name' => $input['profile_name'],
            'rate_limit' => $ratelimit,
        ])->save();
        foreach ($paketProfile->pakets as $paket) {
            if (!$paket->mikrotik->disabled) {
                try {
                    $mikrotik = $paket->mikrotik;
                    foreach ($paket->customer_static_pakets as $customer_static_paket) {
                        $this->ipStaticService->updateSimpleQueue($mikrotik, $customer_static_paket->simpleque_id, $paket->paket_profile, $customer_static_paket->ip_address);
                    }
                    $this->pppService->updateSimplePppProfile($paket, $input['profile_name']);
                } catch (\Exception $e) {
                    return ['status' => 'error', 'message' => $e->getMessage()];
                }
            }
        }

        return ['status' => 'success', 'paketProfile' => $paketProfile];
    }


    public function delete_profile(PaketProfile $paketProfile, array $input)
    {
        if ($input['selectedPaketProfile'] != $paketProfile->id) {
            $movingPaketProfile = PaketProfile::find($input['selectedPaketProfile']);
            foreach ($paketProfile->pakets as $paket) {
                $mikrotik = $paket->mikrotik;
                $createProfile = (new MikrotikPppService())->create_profile_on_mikrotik_isnot_exist($mikrotik,  $movingPaketProfile);
                if ($createProfile['status'] == 'success') {
                    $response = $this->delete_ppp_profile($paket);
                    // dd($response);
                    if ($response == 'success') {
                        foreach ($paket->customer_static_pakets as $customer_static_paket) {
                            $this->ipStaticService->updateSimpleQueue($mikrotik, $customer_static_paket->simpleque_id, $movingPaketProfile, $customer_static_paket->ip_address);
                        }

                        foreach ($paket->customer_ppp_pakets as $customer_ppp_paket) {
                            $this->pppService->updateProfileSecret($mikrotik, $customer_ppp_paket,  $movingPaketProfile->profile_name);
                        }
                        $paket->forceFill([
                            'paket_profile_id' => $input['selectedPaketProfile'],
                            'mikrotik_ppp_profile_id' => $createProfile['profile_id'],
                        ])->save();
                    } else {
                        return $response;
                    }
                }
            }
        } else {
            foreach ($paketProfile->pakets as $paket) {
                $mikrotik = $paket->mikrotik;
                foreach ($paket->customer_static_pakets as $customer_static_paket) {
                    $this->ipStaticService->deleteIpFromArpByIp($mikrotik, $customer_static_paket->ip_address);
                    $this->ipStaticService->deleteIpFromSimpleQueueByIp($mikrotik, $customer_static_paket->ip_address);
                }

                foreach ($paket->customer_ppp_pakets as $customer_ppp_paket) {
                    //$this->pppService->deleteActiveSecret($mikrotik, $ppp_paket->username);
                    $this->pppService->deleteSecret($mikrotik, $customer_ppp_paket);
                }
                $this->delete_ppp_profile($paket);
            }
        }

        if($input['checkbox_permanent_delete']){
            $paketProfile->forceDelete();
        } else {
            $paketProfile->delete();
        }

        return 'success';
    }
    /*
    public function delete_customer_paket(CustomerPaket $customerPaket, $deletePermanent)
    {
        $mikrotik = $customerPaket->paket->mikrotik;
        $internetService = $customerPaket->internet_service->value;

        if ($deletePermanent) {
            if ($internetService == 'ppp') {
                $pppPaket = $customerPaket->ppp_paket;
                $secretID =  $pppPaket->secret_id;
                $username =  $pppPaket->username;
                if (!is_null($secretID)) {
                    try {
                        $this->pppService->deleteSecret($mikrotik, $secretID, $username);
                    } catch (\Exception $e) {
                        return ['status' => 'error', 'message' => $e->getMessage()];
                    }
                }
                $pppPaket->forceDelete();
            }
            if ($internetService == 'ip_static') {
                $ipStaticPaket = $customerPaket->ip_static_paket;
                $simplequeID = $ipStaticPaket->simpleque_id;
                $arpID = $ipStaticPaket->arp_id;
                try {
                    if (!is_null($simplequeID)) {
                        $this->ipStaticService->deleteIpFromSimpleQueue($mikrotik, $simplequeID);
                    }
                    if (!is_null($arpID)) {
                        $this->ipStaticService->deleteIpFromArp($mikrotik, $arpID);
                    }
                } catch (\Exception $e) {
                    return  $e->getMessage();
                }
                $ipStaticPaket->forceDelete();
            }
            $customerPaket->forceDelete();
            return 'success';
        } else {
            if ($internetService == 'ppp') {
                $pppPaket = $customerPaket->ppp_paket;
                $secretID =  $pppPaket->secret_id;
                $username =  $pppPaket->username;
                if (!is_null($secretID)) {
                    try {
                        $this->pppService->disableSecret($mikrotik, $secretID, $username, 'true');
                    } catch (\Exception $e) {
                        return  $e->getMessage();
                    }
                }
                $pppPaket->delete();
                return 'success';
            }
            if ($internetService == 'ip_static') {
                $ipStaticPaket = $customerPaket->ip_static_paket;

                $arpID = $ipStaticPaket->arp_id;
                if (!is_null($arpID)) {
                    try {
                        $this->ipStaticService->disableIpFromArp($mikrotik, $arpID, 'true');
                    } catch (\Exception $e) {
                        return  $e->getMessage();
                    }
                }
                // /
                $ipStaticPaket->delete();
            }
            $customerPaket->delete();
            return 'success';
        }
    }

     public function delete_paket(Paket $paket, $deletePermanent)
    {
        if ($deletePermanent) {
            $paket->forceDelete();
        } else {
            $paket->delete();
        }
        return 'success';
    }
*/


    public function delete_ppp_profile($paket)
    {
        // return dd($paket->mikrotik);
        $mikrotik = $paket->mikrotik;
        try {
            $this->pppService->deletePppProfile($mikrotik, $paket->paket_profile->profile_name);
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
            // return 'failed';
        }
    }
}
