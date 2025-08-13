<?php

namespace App\Services\Mikrotiks;

use RouterOS\Query;
use RouterOS\Client;
use RouterOS\Config;
use Illuminate\Support\Str;
use App\Models\Servers\Mikrotik;
use App\Services\GeneralLogServices;
use Illuminate\Support\Facades\Cache;
use App\Models\Customers\CustomerPppPaket;

class MikrotikPppService
{
    protected const ADD_SECRET = 'rest/ppp/secret/add';
    protected const PPP_SECRET = 'rest/ppp/secret'; //ex get /ppp/secret/username or id, delete /ppp/secret/username or id
    protected const DELETE_SECRET = 'rest/ppp/secret/remove';
    protected const ACTIVE_SECRET = 'rest/ppp/active'; //ex get /ppp/active/username, delete /ppp/active/username
    protected const PPP_PROFILE = 'rest/ppp/profile'; //ex: ?name=Profile 3 Mbps
    private MikrotikRestApiService $mikrotikRestApiService;
    private GeneralLogServices $generalLogServices;
    public function __construct()
    {
        $this->mikrotikRestApiService = new MikrotikRestApiService;
        $this->generalLogServices = new GeneralLogServices;
    }
    /**
     * Summary of getMikrotik
     * @param mixed $mikrotik
     * @throws \Exception
     * @return Client
     */
    public function getMikrotik($mikrotik): Client
    {
        if (!$mikrotik) {
            throw new \Exception('Mikrotik not found.');
        }

        $config = (new Config())
            ->set('timeout', 2)
            ->set('host', $mikrotik->host)
            ->set('port', (int) $mikrotik->port)
            ->set('pass', $mikrotik->password)
            ->set('user', $mikrotik->username)
            ->set('ssl', (bool) $mikrotik->use_ssl);

        return new Client($config);
    }

    public function getPppSecret($mikrotik, CustomerPppPaket $customerPppPaket)
    {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $id = $customerPppPaket->secret_id;
            $pppSecrets = $this->mikrotikRestApiService->getRequest($mikrotik, self::PPP_SECRET . '?.id=' . $id);

            if ($pppSecrets['status_code'] != 200) {
                return [
                    'success' => false,
                    'message' => $pppSecrets['result']['detail'] ?? $pppSecrets['result']['message']
                ];
            }
            foreach ($pppSecrets['result'] as $pppSecret) {
                return [
                    'success' => true,
                    'result' => $pppSecret
                ];
            }

            return [
                'success' => false,
                'message' => 'User not found.'
            ];
        } else {
            $client = $this->getMikrotik($mikrotik);
            $pppSecrets = new Query('/ppp/secret/print');
            $pppSecrets->where('name', $customerPppPaket->username);
            $pppSecrets = $client->query($pppSecrets)->read();

            foreach ($pppSecrets as $pppSecret) {
                Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
                return [
                    'success' => true,
                    'result' => $pppSecret
                ];
            }
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }
    }

    /**
     * Summary of userSecretIsExist

     * Chek user secret exist, return true or false
     */
    public function userSecretIsExist($mikrotik, $username)
    {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $pppSecrets = $this->mikrotikRestApiService->getRequest($mikrotik, self::PPP_SECRET . '?name=' . $username);
            if ($pppSecrets['status_code'] != 200) return false;
            if (collect($pppSecrets['result'])->count()) {
                return true;
            } else {
                return false;
            }
        } else {
            $client = $this->getMikrotik($mikrotik);
            $pppSecrets = new Query('/ppp/secret/print');
            $pppSecrets->where('name', $username);
            $pppSecrets = $client->query($pppSecrets)->read();

            foreach ($pppSecrets as $pppSecret) {
                return true;
            }
            return false;
        }
    }

    /**
     * Summary of createSecret
     * @param \App\Models\Servers\Mikrotik $mikrotik
     * @param mixed $customerPaket
     * @param mixed $disabled
     * @param mixed $comment
     * @return array{message: mixed, success: bool|array{secret_id: mixed, success: bool}}
     */
    public function createSecret(Mikrotik $mikrotik, $customerPaket, $disabled, $comment)
    {
        $pppPaket = $customerPaket->customer_ppp_paket;
        $username = $pppPaket->username;
        $logAction = $this->generalLogServices::CREATE__PPP_SECRET_MIKROTIK;

        $username = $this->generateUsername($mikrotik, $username);
        $logDescription = 'Create ppp secret "' . $username . '" - ' . $customerPaket->user->full_name . ' on Server ' . $mikrotik->name;
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            //Mikrotik version 7.9.0 up
            $input = [
                "comment" => $comment,
                "name" => $username,
                "password" => $pppPaket->password_ppp,
                "profile" => $customerPaket->paket->paket_profile->profile_name,
                "service" => $pppPaket->ppp_type->name,
                'disabled' => $disabled
            ];
            $response = $this->mikrotikRestApiService->postRequest($mikrotik, self::ADD_SECRET, $input);

            if ($response['status_code'] != 200) {

                //Create Log if failed
                $this->generalLogServices->log_mikrotik(
                    $logAction,
                    $logDescription . ' from rest-api',
                    $response['result']['detail'] ?? $response['result']['message'],
                    false
                );
                return [
                    'success' => false,
                    'message' => $response['result']['detail'] ?? $response['result']['message']
                ];
            }
            // Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);

            //Create log if success
            $this->generalLogServices->log_mikrotik(
                $logAction,
                $logDescription . ' from rest-api',
                $response['result']['ret'],
                true
            );
            return [
                'success' => true,
                'secret_id' => $response['result']['ret']
            ];
        } else {
            $client = $this->getMikrotik($mikrotik);
            $query = (new Query('/ppp/secret/add'))
                ->equal('name', $username)
                ->equal('password', $pppPaket->password_ppp)
                ->equal('service', $pppPaket->ppp_type->name)
                ->equal('comment', $comment)
                ->equal('profile', $customerPaket->paket->paket_profile->profile_name)
                // ->equal('.id', $profile)
                ->equal('disabled', $disabled);


            $response = $client->query($query)->read();
            if (isset($response['after']['message'])) {
                //Create Log if failed
                $this->generalLogServices->log_mikrotik(
                    $logAction,
                    $logDescription . ' from api',
                    $response['after']['message'] ?? 'Something wrong',
                    false
                );
                return [
                    'success' => false,
                    'message' => $response['after']['message']
                ];
            } else if (isset($response['after']['ret'])) {
                // Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
                //Create log if success
                $this->generalLogServices->log_mikrotik(
                    $logAction,
                    $logDescription . ' from api',
                    $response['after']['ret'],
                    true
                );
                return [
                    'success' => true,
                    'secret_id' => $response['after']['ret']
                ];
            }
            return [
                'success' => false,
                'message' => $response
            ];
        }
    }

    private function generateUsername($mikrotik, $username)
    {
        //Check if exist generate new username
        $count = 1;
        // $username = $pppPaket->username;
        $generateUsername = $username;
        while ($this->userSecretIsExist($mikrotik, $generateUsername)) {
            //iF USER SECRET EXIST, CHANGE USERNAME
            $generateUsername = strtolower(str_replace(' ', '-', $username)) . $count;
            $count++;
            sleep(1);
        }

        return $generateUsername;
    }

    /**
     * Summary of updateUsernameAndPasswordSecret
     * @param mixed $mikrotik
     * @param mixed $id
     * @param mixed $new_username
     * @param mixed $new_password
     * @param mixed $ppp_type
     * @return array{message: mixed, success: bool|array{result: mixed, success: bool}|array{success: bool}}
     */
    public function updateUsernameAndPasswordSecret($mikrotik, CustomerPppPaket $customerPppPaket, $new_username, $new_password, $ppp_type)
    {
        $logAction = $this->generalLogServices::UPDATE_PPP_SECRET_MIKROTIK;
        $logDescription = $customerPppPaket->customer_paket->user->full_name . " on Mikrotik " . $mikrotik->name;
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $input = [
                'name' => $new_username,
                'password' => $new_password,
                'service' => $ppp_type
            ];
            $response = $this->mikrotikRestApiService->patchRequest($mikrotik, self::PPP_SECRET . '/' . $customerPppPaket->secret_id, $input);
            if ($response['status_code'] === 200) {
                //  Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
                //Create Log if success
                $this->generalLogServices->log_mikrotik(
                    $logAction,
                    "Update username and password ppp secret " . $logDescription . 'from rest-api',
                    $response['result'],
                    true
                );
                return [
                    'success' => true,
                    'result' => $response['result']
                ];
            } else if ($response['status_code'] === 404) {
                $disabled = $customerPppPaket->customer_paket->status === 'active' ? 'false' : 'true';
                $response = $this->createSecret($mikrotik, $customerPppPaket->customer_paket, $disabled, null);
                $customerPppPaket->forceFill([
                    'secret_id' => $response['secret_id']
                ])->save();

                //Create log update username and password if secret not found
                $this->generalLogServices->log_mikrotik(
                    $logAction,
                    "PPP secret not found, but we cretae new user secret to " . $logDescription . 'from rest-api',
                    $response,
                    true
                );
                return [
                    'success' => true
                ];
            } else {
                $response = $this->mikrotikRestApiService->getRequest($mikrotik, self::PPP_SECRET . '/' . $customerPppPaket->username);
                if ($response['status_code'] === 200) {
                    $customerPppPaket->forceFill([
                        'secret_id' => $response['result']['.id']
                    ])->save();

                    //Create log if secret_id not match with cm
                    $this->generalLogServices->log_mikrotik(
                        $logAction,
                        "PPP secret_id not match, we try to equalize to " . $logDescription . 'from rest-api',
                        $response,
                        true
                    );
                    return [
                        'success' => true
                    ];
                }
            }

            //Create log if failed
            $this->generalLogServices->log_mikrotik(
                $logAction,
                "Failed update username and password on " . $logDescription . 'from rest-api',
                $response,
                false
            );
            return [
                'success' => false,
                'message' => $response['result']['detail'] ?? $response['result']['message']
            ];
        } else {
            $client = $this->getMikrotik($mikrotik);
            $query = (new Query('/ppp/secret/set'))
                ->equal('.id', $customerPppPaket->secret_id)
                ->equal('name', $new_username)
                ->equal('password', $new_password)
                ->equal('service', $ppp_type);

            $response = $client->query($query)->read();
            if ($response === []) {
                //Create Log if success
                $this->generalLogServices->log_mikrotik(
                    $logAction,
                    "Update username and password ppp secret " . $logDescription . 'from api',
                    $response,
                    true
                );
                // Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
                return ['success' => true];
            }

            // $response = $response['after']['message'] ? $response['after']['message'] : $response['after'];
            $response = $response['after']['message'] ?? $response['after'];
            //Create Log if failed
            $this->generalLogServices->log_mikrotik(
                $logAction,
                "Update username and password ppp secret " . $logDescription . 'from api',
                $response,
                false
            );
            return ['success' => false, 'message' => $response];
        }
    }

    /**
     * Summary of updateProfileSecret
     * Update profile secret with delete active connection
     * @param mixed $mikrotik
     * @param \App\Models\Customers\CustomerPppPaket $customerPppPaket
     * @param mixed $profileName
     * @param mixed $comment
     * @return array{message: mixed, success: bool|array{result: mixed, success: bool}|array{success: bool}}
     */
    public function updateProfileSecret($mikrotik, $customerPppPaket, $profileName, $comment = null)
    {
        $logAction = $this->generalLogServices::UPDATE_PPP_PROFILE_SECRET_MIKROTIK;
        $logDescription = $customerPppPaket->username . ' to ' . $profileName . ' on ' . $customerPppPaket->customer_paket->user->full_name . " customer in Mikrotik " . $mikrotik->name;
        //Compare mikrotik version
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            //Mikrotik >= 7.9.0
            if (is_null($comment)) {
                $input = ['profile' => $profileName];
            } else {
                $input = ['profile' => $profileName, 'comment' => $comment];
            }

            $response = $this->mikrotikRestApiService->patchRequest($mikrotik, self::PPP_SECRET . '/' . $customerPppPaket->secret_id, $input);

            if ($response['status_code'] != 200) {
                $response = $response['result']['detail'] ?? $response['result']['message'];
                //Create Log if not found
                $this->generalLogServices->log_mikrotik(
                    $logAction,
                    "Update secret user profile " . $logDescription . 'from rest-api',
                    $response,
                    false
                );
                return [
                    'success' => false,
                    'message' => $response
                ];
            }
            $this->deleteActiveSecret($mikrotik, $customerPppPaket->username);
            //Create Log if success
            $this->generalLogServices->log_mikrotik(
                $logAction,
                "Delete active connection and Update secret user profile " . $logDescription . 'from rest-api',
                $response['result'],
                true
            );
            //Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
            return ['success' => true, 'result' => $response['result']];
        } else {
            //Mikrotik < 7.9.0
            $client = $this->getMikrotik($mikrotik);
            $query = new Query('/ppp/secret/set');
            $query->equal('.id', $customerPppPaket->secret_id);
            $query->equal('profile', $profileName);
            if (!is_null($comment)) $query->equal('comment', $comment);

            $response = $client->query($query)->read();
            if ($response === []) {
                $this->deleteActiveSecret($mikrotik, $customerPppPaket->username);
                // Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
                $this->generalLogServices->log_mikrotik(
                    $logAction,
                    "Delete active connection and Update secret user profile " . $logDescription . 'from api',
                    $response,
                    true
                );
                return ['success' => true];
            }

            $this->generalLogServices->log_mikrotik(
                $logAction,
                "Delete active connection and Update secret user profile " . $logDescription . 'from api',
                $response,
                false
            );

            $response = $response['after']['message'] ?? $response['after'];
            return ['success' => false, 'message' => $response];
        }
    }

    /**
     * Delete ppp secret include active connection
     * Summary of deleteSecret
     * @param mixed $mikrotik
     * @param \App\Models\Customers\CustomerPppPaket $customerPppPaket
     * @return array{message: mixed, success: bool|array{result: mixed, success: bool}|array{success: bool}}
     */
    public function deleteSecret($mikrotik, CustomerPppPaket $customerPppPaket)
    {
        $logAction = $this->generalLogServices::DELETE_PPP_SECRET_MIKROTIK;
        $logDescription = $customerPppPaket->username . ' on ' . $customerPppPaket->customer_paket->user->full_name . " customer in Mikrotik " . $mikrotik->name;

        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $input = [
                '.id' => $customerPppPaket->secret_id
            ];
            $response = $this->mikrotikRestApiService->postRequest($mikrotik, self::DELETE_SECRET, $input);
            if ($response['status_code'] != 200) {
                $response = $response['result']['detail'] ?? $response['result']['message'];

                //Create log if failed
                $this->generalLogServices->log_mikrotik(
                    $logAction,
                    "Delete secret with active connection " . $logDescription . 'from rest-api',
                    $response,
                    false
                );

                return [
                    'success' => false,
                    'message' => $response
                ];
            }
            $this->deleteActiveSecret($mikrotik, $customerPppPaket->username);
            //Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
            //Create log if success
            $this->generalLogServices->log_mikrotik(
                $logAction,
                "Delete secret with active connection " . $logDescription . 'from rest-api',
                $response['result'],
                true
            );
            return ['success' => true, 'result' => $response['result']];
        } else {
            $client = $this->getMikrotik($mikrotik);
            $secret = (new Query('/ppp/secret/remove'))
                ->where('name', $customerPppPaket->username)
                ->equal('.id', $customerPppPaket->secret_id);
            $this->deleteActiveSecret($mikrotik, $customerPppPaket->username);
            //Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
            $response = $client->query($secret)->read();

            if ($response === []) {
                $this->deleteActiveSecret($mikrotik, $customerPppPaket->username);
                //Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
                //Create log if success
                $this->generalLogServices->log_mikrotik(
                    $logAction,
                    "Delete secret with active connection " . $logDescription . 'from api',
                    $response,
                    true
                );
                return ['success' => true];
            }

            $response = $response['after']['message'] ?? $response['after'];
            //Create log if success
            $this->generalLogServices->log_mikrotik(
                $logAction,
                "Delete secret with active connection " . $logDescription . 'from api',
                $response,
                false
            );
            return ['success' => false, 'message' => $response];
        }
    }

    /**
     * Disable ppp secret with delete active connection
     * Summary of disableSecret
     * @param mixed $mikrotik
     * @param \App\Models\Customers\CustomerPppPaket $customerPppPaket
     * @param mixed $status
     * @return array{message: mixed, success: bool|array{result: mixed, success: bool}}
     */
    public function disableSecret($mikrotik, CustomerPppPaket $customerPppPaket, $status)
    {
        //dd($customerPppPaket);
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $input = ['disabled' => $status];
            $response = $this->mikrotikRestApiService->patchRequest($mikrotik, self::PPP_SECRET . '/' . $customerPppPaket->secret_id, $input);

            if ($response['status_code'] != 200) return ['success' => false, 'message' => $response['result']['detail'] ?? $response['result']['message']];
            if ($status === 'true') $this->deleteActiveSecret($mikrotik, $customerPppPaket->username);

            // Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
            return ['success' => true, 'result' => $response['result']];
        } else {
            $client = $this->getMikrotik($mikrotik);
            $disable = (new Query('/ppp/secret/set'))
                ->equal('.id', $customerPppPaket->secret_id)
                ->equal('disabled', $status);
            $response = $client->query($disable)->read();
            if ($response === []) {
                if ($status == 'true') $this->deleteActiveSecret($mikrotik, $customerPppPaket->username);
                //    Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
                return ['success' => true];
            }
            return ['success' => false, 'message' => $response['after']['message'] ? $response['after']['message'] : $response['after']];
        }
    }

    public function getActiveSecret($mikrotik, $username)
    {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $response = $this->mikrotikRestApiService->getRequest($mikrotik, self::ACTIVE_SECRET . '/' . $username);
            // dd($response);
            if ($response['status_code'] != 200) {
                return [
                    'success' => false,
                    'message' => $response['result']['detail'] ?? $response['result']['message']
                ];
            }
            if ($response['result'] === []) {
                return [
                    'success' => false,
                    'message' => 'User not found.'
                ];
            } else {
                return [
                    'success' => true,
                    'result' => $response['result']
                ];
            }
        } else {
            $client = $this->getMikrotik($mikrotik);
            $activeSecrets = new Query('/ppp/active/print');
            $activeSecrets->where('name', $username);
            $activeSecrets = $client->query($activeSecrets)->read();
            foreach ($activeSecrets as $activeSecret) {
                return [
                    'success' => true,
                    'result' => $activeSecret
                ];
            }
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }
    }
    /**
     * Summary of deleteActiveSecret
     * @param mixed $mikrotik
     * @param mixed $id
     */
    public function deleteActiveSecret($mikrotik, $username)
    {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $this->mikrotikRestApiService->deleteRequest($mikrotik, self::ACTIVE_SECRET, $username);
        } else {
            $response = $this->getActiveSecret($mikrotik, $username);
            if ($response['success']) {
                $client = $this->getMikrotik($mikrotik);
                $query = (new Query('/ppp/active/remove'))
                    ->equal('.id', $response['result']['.id']);
                $response = $client->query($query)->read();
            }
        }
    }

    public function getStatusActiveSecret($mikrotik, $username): bool
    {
        $response = $this->getActiveSecret($mikrotik, $username);
        //dd($response);
        return $response['success'] ? true : false;
    }

    public function updateCommentSecret($mikrotik, CustomerPppPaket $customerPppPaket, $comment)
    {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $input = ['comment' => $comment];
            $response = $this->mikrotikRestApiService->patchRequest($mikrotik, self::PPP_SECRET . '/' . $customerPppPaket->secret_id, $input);
            if ($response['status_code'] != 200) {
                return [
                    'success' => false,
                    'message' => $response['result']['detail'] ?? $response['result']['message']
                ];
            }
            $this->deleteActiveSecret($mikrotik, $customerPppPaket->username);
            Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
            return ['success' => true, 'result' => $response['result']];
        } else {
            $client = $this->getMikrotik($mikrotik);
            // $pppSecret = $this->getPppSecret($mikrotik, $customerPppPaket);
            // if (!$pppSecret['success']) return $pppSecret;
            $query = (new Query('/ppp/secret/set'))
                ->equal('.id', $customerPppPaket->secret_id)
                ->equal('comment', $comment);
            Cache::forget('user-secrets-mikrotik-' . $mikrotik->id);
            $response = $client->query($query)->read();
            if ($response === []) return ['success' => true];
            return ['success' => false, 'message' => $response['after']['message'] ? $response['after']['message'] : $response['after']];
        }
    }

    public function getProfile($mikrotik, $profile_name)
    {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $response = $this->mikrotikRestApiService->getRequest($mikrotik, self::PPP_PROFILE . '?name=' . $profile_name);
            return $response['result'];
        } else {
            // return dd($profile_name);
            $client = $this->getMikrotik($mikrotik);
            $profiles = new Query('/ppp/profile/print');
            $profiles->where('name', $profile_name);
            return  $client->query($profiles)->read();
        }
    }

    public function updateScriptPppProfile($paket)
    {
        $mikrotik = $paket->mikrotik;
        $profileIdMikrotik = $paket->mikrotik_ppp_profile_id;
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $input = [
                'on-up' => $this->onUpScript($mikrotik->id),
                'on-down' => $this->onDownScript($mikrotik->id)
            ];
            $response = $this->mikrotikRestApiService->patchRequest($mikrotik, self::PPP_PROFILE . '/' . $profileIdMikrotik, $input);
        } else {
            $client = $this->getMikrotik($mikrotik);
            $query = (new Query('/ppp/profile/set'))
                ->where('.id', $profileIdMikrotik)
                ->equal('.id', $profileIdMikrotik)
                ->equal('on-up', $this->onUpScript($mikrotik->id))
                ->equal('on-down', $this->onDownScript($mikrotik->id));

            Cache::forget('ppp-profiles-mikrotik-' . $mikrotik->id);
            $response = $client->query($query)->read();
        }
        return $response;
    }



    ///=============================================BELUM REST API ======================================


    public function create_profile_on_mikrotik_isnot_exist($mikrotik, $profile)
    {

        $profiles = $this->getProfile($mikrotik, $profile->profile_name);
        foreach ($profiles as $profile) {
            return [
                'status' => 'success',
                'profile_id' => $profile['.id']
            ];
        }

        $response = $this->createSimplePppProfile($mikrotik, $profile);
        if (isset($response['after']['ret'])) {
            return [
                'status' => 'success',
                'profile_id' => $response['after']['ret']
            ];
        } else {
            return [
                'status' => 'false',
                'message' => $response['after']['message']
            ];
        }
    }



    public function createSimplePppProfile($mikrotik, $profile)
    {
        $client = $this->getMikrotik($mikrotik);
        $query = (new Query('/ppp/profile/add'))
            ->equal('name', $profile->profile_name)
            ->equal('rate-limit', $profile->rate_limit)
            ->equal('comment', $profile->paket->price ?? 0)
            ->equal('on-up', $this->onUpScript($mikrotik->id))
            ->equal('on-down', $this->onDownScript($mikrotik->id));

        Cache::forget('ppp-profiles-mikrotik-' . $mikrotik->id);
        return $client->query($query)->read();
    }

    private function onUpScript($mikrotik_id)
    {
        $headerSecret = $this->header_script();
        $url = env('APP_URL');
        //  return '/tool fetch url="' . $url . '/api/mikrotik?action=ppp_status&user=$user&status=up" http-method=post  keep-result=no;';
        return '/tool fetch url="' . $url . '/mikrotik/webhook?monitoring=ppp-status&user=$user&status=up&mikrotik_id=' . $mikrotik_id . '" http-method=post  keep-result=no http-header-field="X-Mikrotik-Signature:' . $headerSecret . '";';
    }

    /**
     * Summary of onDownScript
     * @param mixed $mikrotik_id
     * @return string
     */
    private function onDownScript($mikrotik_id)
    {
        $headerSecret = $this->header_script();
        $url = env('APP_URL');
        return '/tool fetch url="' . $url . '/mikrotik/webhook?monitoring=ppp-status&user=$user&status=down&mikrotik_id=' . $mikrotik_id . '" http-method=post  keep-result=no http-header-field="X-Mikrotik-Signature:' . $headerSecret . '";';
    }

    private function header_script()
    {
        return hash('sha256', env('API_CLIENT_MIKROTIK'));
    }



    public function updateSimplePppProfile($paket, $newName)
    {
        $client = $this->getMikrotik($paket->mikrotik);
        $profiles = (new Query('/ppp/profile/print'))
            ->where('.id', $paket->mikrotik_ppp_profile_id);
        $profiles = $client->query($profiles)->read();
        // dd($profiles);
        foreach ($profiles as $profile) {
            $query = (new Query('/ppp/profile/set'))
                ->where('.id', $paket->mikrotik_ppp_profile_id)
                ->equal('.id', $profile['.id'])
                ->equal('name', $newName)
                ->equal('rate-limit', $paket->paket_profile->rate_limit)
                ->equal('on-up', $this->onUpScript($paket->mikrotik->id))
                ->equal('on-down', $this->onDownScript($paket->mikrotik->id));

            Cache::forget('ppp-profiles-mikrotik-' . $paket->mikrotik->id);
            return $client->query($query)->read();
        }
    }

    public function deletePppProfile($mikrotik, $profileName)
    {
        $client = $this->getMikrotik($mikrotik);
        $pppProfiles = $this->getProfile($mikrotik, $profileName);
        // dd($pppProfiles);
        foreach ($pppProfiles as $pppProfile) {

            $secret = (new Query('/ppp/profile/remove'))
                //  ->where('name', $profileName)
                ->equal('.id', $pppProfile['.id']);
            Cache::forget('ppp-profiles-mikrotik-' . $mikrotik->id);
            $client->query($secret)->read();
        }
    }



}
