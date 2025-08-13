<?php

namespace App\Services;

use App\Models\Pakets\PaketProfile;
use App\Services\Mikrotiks\MikrotikService;
use App\Services\Mikrotiks\MikrotikPppService;



class PaketProfileService
{
    private MikrotikService $mikrotikService;
    private MikrotikPppService $mikrotikPppService;
    private GeneralLogServices $generalLogServices;


    public function __construct()
    {
        $this->mikrotikService = new MikrotikService;
        $this->mikrotikPppService = new MikrotikPppService;
        $this->generalLogServices = new GeneralLogServices;
    }

    /**
     * Summary of create_profile_on_mikrotik_isnot_exist
     * Create profile on mikrotik if not exist
     * @param mixed $paket
     * @return array{message: string, success: bool|array{paket_id: mixed, success: bool}}
     */
    public function create_profile_on_mikrotik_isnot_exist($paket)
    {
        try {
            $mikrotik = $paket->mikrotik;
            $paketProfile = $paket->paket_profile;
            $profileName = $paketProfile->profile_name;
            $profiles = $this->mikrotikPppService->getProfile($mikrotik, $profileName);

            //Syncronize paket profile id with mikrotik
            foreach ($profiles as $profile) {
                $paket->forceFill([
                    'mikrotik_ppp_profile_id' => $profile['.id']
                ])->save();

                return [
                    'success' => true,
                    'paket_id' => $paket->id,
                ];
            }

            $response = $this->mikrotikPppService->createSimplePppProfile($mikrotik, $paketProfile);
            if (isset($response['after']['ret'])) {
                $paket->forceFill([
                    'mikrotik_ppp_profile_id' => $response['after']['ret']
                ])->save();
            }
            return [
                'paket_id' => $paket->id,
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Summary of update_user_profile_in_mikrotik
     * Update profile user secret on mikrotik
     * @param mixed $paket
     * @param mixed $paket_profile_id
     * @return string|\Exception
     */
    public function update_user_profile_in_mikrotik($paket, $paket_profile_id)
    {
        $paket_profile = PaketProfile::find($paket_profile_id);
        foreach ($paket->customer_pakets->where('status', '!=', 'pending') as $customer_paket) {
            if (!$customer_paket->paket->mikrotik->disabled) {
                try {
                    if ($customer_paket->isPpp()) {
                        $this->mikrotikPppService->updateProfileSecret($customer_paket->paket->mikrotik, $customer_paket->customer_ppp_paket, $paket_profile->profile_name);
                    } else {
                    }
                } catch (\Exception $e) {
                    return $e;
                }
            }
        }
        return 'success';
    }
}
