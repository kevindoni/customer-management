<?php

namespace App\Livewire\Actions\Pakets;

use Illuminate\Support\Str;
use App\Models\Pakets\Paket;
use App\Models\Pakets\PaketProfile;
use Illuminate\Support\Facades\Log;
use App\Services\PaketProfileService;
//use App\Services\Mikrotiks\MikrotikPppService;
use App\Jobs\Customers\UpdateUserSecretProfileMikrotikJob;
//use App\Services\GeneralLogServices;

class PaketAction
{
   // private MikrotikPppService $pppService;
    private PaketProfileService $paketProfileService;
  //  private GeneralLogServices $generalLogServices;
    public function __construct()
    {
      //  $this->pppService = new MikrotikPppService;
        $this->paketProfileService = new PaketProfileService;
    }

    /**
     * Summary of add_paket
     * @param array $input
     * @return array{message: string, success: array{paket_id: mixed, success: bool|bool}}
     */
    public function add_paket(array $input)
    {
        $paket = $this->store_paket($input);
        $response = $this->paketProfileService->create_profile_on_mikrotik_isnot_exist($paket);

        if (!$response['success']) {
            $paket->forceFill([
                'paket_profile_id' => 1,
                'mikrotik_ppp_profile_id' => '*FFFFFFFE'
            ])->save();
        }
        return $response;
    }

    /**
     * Summary of store_paket
     * Create paket on database
     * @param array $input
     * @return Paket
     */
    private function store_paket(array $input)
    {
        return Paket::create([
            'slug' => str(Str::random(10))->slug(),
            'name' => $input['name'],
            'paket_profile_id' => $input['selectedProfile'],
            'mikrotik_id' => $input['selectedServer'],
            'price' => $input['price'],
            'trial_days' => $input['trial_days'] ?? 0,
            'description' => $input['description'] ?? null,
        ]);
    }

    public function update_paket(Paket $paket, array $input)
    {
        $paketProfileId = $paket->paket_profile_id;
        $paket->forceFill([
            'name' => $input['name'],
            'price' => $input['price'],
            'trial_days' => $input['trial_days'],
            //'show_on_customer' => $input['checkbox_show_on_customer'] ?? false,
            'description' => $input['description'] ?? null,
        ])->save();

        if ($paketProfileId != $input['selectedProfile']) {
            $paket->forceFill([
                'paket_profile_id' => $input['selectedProfile'],
            ])->save();
            dispatch(new UpdateUserSecretProfileMikrotikJob($paket, $input['selectedProfile']))->onQueue('default');
        }

        $response = $this->paketProfileService->create_profile_on_mikrotik_isnot_exist($paket);

        if ($paketProfileId != $input['selectedProfile'] && $response['success']) {
            dispatch(new UpdateUserSecretProfileMikrotikJob($paket, $input['selectedProfile']))->onQueue('default');
            Log::info('update user on mikrotik');
        }

        return $response;
    }

    /*
    public function create_profile_on_mikrotik_isnot_exist($paket)
    {
        try {
            $profiles = $this->pppService->getProfile($paket->mikrotik, $paket->paket_profile->profile_name);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
        foreach ($profiles as $profile) {
            $paket->forceFill([
                'mikrotik_ppp_profile_id' => $profile['.id']
            ])->save();
            //$response['paket_id'] = $paket->id;
            //  $response['success'] = true;

            return [
                'paket_id' => $paket->id,
                'success' => true,
            ];
        }

        try {
            $response = $this->pppService->createSimplePppProfile($paket->mikrotik, $paket->paket_profile);
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
            // return $e;
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    */

    /*
    public function update_user_profile_in_mikrotik($paket, $paket_profile_id)
    {
        $paket_profile = PaketProfile::find($paket_profile_id);
        foreach ($paket->customer_pakets->where('status', '!=', 'pending') as $customer_paket) {
            if (!$customer_paket->paket->mikrotik->disabled) {
                try {
                    if ($customer_paket->isPpp()) {
                        // dispatch(new UpdateUserSecretProfileMikrotikJob($customer_paket->paket->mikrotik, $customer_paket->ppp_paket->username, $paket_profile->profile_name))->onQueue('default');
                        // $this->pppService->deleteActiveSecret($customer_paket->paket->mikrotik, $customer_paket->ppp_paket->username);
                        $this->pppService->updateProfileSecret($customer_paket->paket->mikrotik, $customer_paket->customer_ppp_paket, $paket_profile->profile_name);
                    } else {
                    }
                    // return 'success';
                } catch (\Exception $e) {
                    return $e;
                }
            }
            // return 'success';
        }

        return 'success';
    }
        */


    /**
     * Validate delete mikrotik from admin panel.
     *
     * @param  array<string, string>  $input
     */
    /*
    public function delete_paket(Paket $paket, array $input)
    {
        if ($input['selectedPaket'] != $paket->id) {
            if (count($paket->customer_pakets)) {
                foreach ($paket->customer_pakets as $customer_paket) {
                    $mikrotik = $customer_paket->paket->mikrotik;

                    $profileName = $customer_paket->paket->paket_profile->profile_name;
                    $internetService = $customer_paket->internet_service->value;
                    try {
                        if ($internetService == 'ppp') {
                            $this->pppService->updateProfileSecret($mikrotik, $customer_paket->customer_ppp_paket, $profileName);
                        } else if ($internetService == 'ip_static') {
                            //
                        }
                        $customer_paket->update([
                            'paket_id' => $input['selectedPaket']
                        ]);
                        return 'success';
                    } catch (\Exception $e) {
                        return $e;
                    }
                }
            }
            $paket->forceDelete();
            return 'success';
        } else {
            if (count($paket->customer_pakets)) {
                foreach ($paket->customer_pakets as $customer_paket) {
                    $mikrotik = $customer_paket->paket->mikrotik;
                    $internetService = $customer_paket->internet_service->value;
                    try {
                        if ($internetService === 'ppp') {
                            $this->pppService->deleteSecret($mikrotik, $customer_paket->customer_ppp_paket);
                            $customer_paket->customer_ppp_paket->forceDelete();
                        } else if ($internetService == 'ip_static') {
                            // $customerPaket->ip_static_paket->forceDelete();
                        }

                        return 'success';
                    } catch (\Exception $e) {
                        return $e;
                    }
                }
            }
            $paket->forceDelete();
            return 'success';
        }
    }
    */


    /**
     * Summary of importProfile
     * Create profile from profile mikrotik
     * @param mixed $profile
     * @return PaketProfile
     */
    public function importProfile($profile)
    {
        return PaketProfile::create([
            'slug' => str($profile['name'] . '-' . Str::random(4))->slug(),
            'profile_name' => $profile['name'],
            'local_address' => $profile['local-address'] ?? null,
            'remote_address' => $profile['remote-address'] ?? null,
            'dns_server' => $profile['dns-server'] ?? null,
            'use_ipv6' => $profile['use-ipv6'] ?? 'yes',
            'use_mpls' => $profile['use-mpls'] ?? 'default',
            'use_encryption' => $profile['use-compression'] ?? 'default',
            'use_compression' => $profile['use-encryption'] ?? 'default',
            'rate_limit' => $profile['rate-limit'] ?? null,
            'rasio_cir' => $profile['name'],
            'session_timeout' => $profile['session-timeout'] ?? 0,
            'idle_timeout' => $profile['idle-timeout'] ?? 0,
            'only_one' => $profile['only-one'] ?? 'default',
            'insert_queue_before' => $profile['insert-queue-before'] ?? 'bottom',
            'parent_queue' => $profile['parent-queue'] ?? 'none',
            'queue_type' => $profile['queue-type'] ?? 'default',
            // 'script_on_up' => $profile['on-up'] ?? null,
            //'script_on_down' => $profile['on-down'] ?? null,
            'disabled' => false,
            'description' => $profile['comment'] ?? null,

        ]);
    }

    /**
     * Summary of importPaket
     * Create paket from mikrotik
     * @param mixed $mikrotikID
     * @param mixed $profile
     * @param mixed $price
     * @return Paket
     */
    public function importPaket($mikrotikID, $profile, $price)
    {
        $input = array_merge([
            'name' => $profile->profile_name,
            'selectedProfile' => $profile->id,
            'selectedServer' => $mikrotikID,
            'price' =>  $price
        ]);
        $paket = $this->store_paket($input);
        return $paket;
    }
}
