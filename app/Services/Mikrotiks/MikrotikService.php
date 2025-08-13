<?php

namespace App\Services\Mikrotiks;

use RouterOS\Query;
use RouterOS\Client;
use RouterOS\Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client as GuzzleClient;
use RouterOS\Exceptions\QueryException;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\ConfigException;
use RouterOS\Exceptions\ConnectException;
use App\Exceptions\ConnectMikrotikException;
use RouterOS\Exceptions\BadCredentialsException;
use App\Http\Resources\Mikrotik\TrafficInterfaceResource;

class MikrotikService
{

    protected const GET_PPP_SECRETS = 'rest/ppp/secret';
    protected const GET_ACTIVE_SECRETS = 'rest/ppp/active';
    protected const GET_PPP_PROFILES = 'rest/ppp/profile';
    protected const MONITORING_TRAFFICS = 'rest/interface/monitor-traffic';
    protected const GET_INTERFACES = 'rest/interface';
    protected const GET_INTERFACE_ETHERNET = 'rest/interface/ethernet';
    protected const GET_SYSTEM_RESOURCE = 'rest/system/resource';
    protected const GET_SYSTEM_CLOCK = 'rest/system/clock';
    private MikrotikRestApiService $mikrotikRestApiService;

    public function __construct()
    {
        $this->mikrotikRestApiService = new MikrotikRestApiService;
    }
    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     * @throws \Exception
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

    /** Test online mikrotik before add or edit server
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function testConnection($input)
    {
        $config = (new Config())
            ->set('host', $input['host'])
            ->set('port', (int) $input['port'])
            ->set('pass', $input['password'])
            ->set('user', $input['username'])
            ->set('timeout', $input['timeout'] ?? 2)
            ->set('ssl', $input['use_ssl']);
        $client = new Client($config);
        $query = (new Query('/system/resource/print'));
        $query = $client->query($query)->read();
        return [
            'success' => true,
            'result' => $query[0]
        ];
    }

    public function testConnectionWthRestApi($input)
    {
        $url = $input['use_ssl'] ? 'https://' : 'http://';
        $url = $url . $input['host'] . ':' . (int) $input['web_port'];
        $client = new GuzzleClient(['base_uri' => $url]);
        $response = $client->get(self::GET_SYSTEM_RESOURCE, [
            'verify' => false,
            'auth' => [$input['username'], $input['password']],
        ]);
        return [
            'success' => true,
            'result' => json_decode($response->getBody()->getContents(), true)
        ];
    }


    /** Get Interfaces on Mikrotik
     * $mikrotik  data from database -> host, port, username, password
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function getInterfaces($mikrotik)
    {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $response = $this->mikrotikRestApiService->getRequest($mikrotik, self::GET_INTERFACES);
            //dd( $response);
            if ($response['status_code'] != 200) return [];
            return $response['result'];
        } else {
            $client = $this->getMikrotik($mikrotik);
            $query = new Query('/interface/print');
            $query->where('type', 'ether');
            $query->where('type', 'vlan');
            $query->operations('|');
            return $client->query($query)->read();
        }
    }

    public function mikrotikEtherInterface($mikrotik)
    {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $response = $this->mikrotikRestApiService->getRequest($mikrotik, self::GET_INTERFACES);
            if ($response['status_code'] != 200) return [];
            // dd(collect($response['result'])->where('type', '!=', 'pppoe-in'));
            return collect($response['result'])->where('type', '!=', 'pppoe-in');
        } else {
            $client = $this->getMikrotik($mikrotik);
            $query = new Query('/interface/print');
            $query->where('type',  'pppoe-in');
            // $query->where('type', 'vlan');
            $query->operations('!=');
            return $client->query($query)->read();
        }
    }

    public function monitoringTraffic($mikrotik, $interface)
    {
        //if (version_compare($mikrotik->version, '7.9.0', '>=')) {
        //      $response = $this->mikrotikRestApiService->getRequest($mikrotik, self::MONITORING_TRAFFICS);
        //     if ($response['status_code'] != 200) return [];
        //     return $response['result'];
        //  } else {
        $client = $this->getMikrotik($mikrotik);
        $query = (new Query('/interface/monitor-traffic'))
            ->equal('interface', $interface)
            ->equal('once');
        return $client->query($query)->read();
        // }
    }

    /*public function monitoringTrafficById($mikrotik, $interface)
    {
        $client = $this->getMikrotik($mikrotik);
        $query = (new Query('/interface/monitor-traffic'))
            ->equal('.id', $interface)
            ->equal('once');
        return $client->query($query)->read();
    }*/

    /** Get Profiles on Mikrotik
     * $mikrotik  data from database -> host, port, username, password
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function getPppProfiles($mikrotik)
    {
        // return Cache::remember('ppp-profiles-mikrotik-' . $mikrotik->id, now()->addMinutes(15), function () use ($mikrotik) {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $response = $this->mikrotikRestApiService->getRequest($mikrotik, self::GET_PPP_PROFILES);
            if ($response['status_code'] != 200) return [];
            return $response['result'];
        } else {
            $client = $this->getMikrotik($mikrotik);
            $query = new Query('/ppp/profile/print');
            return $client->query($query)->read();
        }
        // });
        // $client = $this->getMikrotik($mikrotik);
        //$query = new Query('/ppp/profile/print');

        //return $client->query($query)->read();
    }

    /**
     * Summary of getAllUserSecrets
     * @param mixed $mikrotik
     */
    public function getAllUserSecrets($mikrotik)
    {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $response = $this->mikrotikRestApiService->getRequest($mikrotik, self::GET_PPP_SECRETS);
            if ($response['status_code'] != 200) return [];
            return $response['result'];
        } else {
            // return Cache::remember('user-secrets-mikrotik-' . $mikrotik->id, now()->addMinutes(15), function () use ($mikrotik) {
            $client = $this->getMikrotik($mikrotik);
            $query = new Query('/ppp/secret/print');
            return $client->query($query)->read();
            // });
        }
    }

    /** Get all active secret on Mikrotik
     * $mikrotik  data from database -> host, port, username, password
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function getActiveSecrets($mikrotik)
    {
        // return Cache::remember('active-user-secrets-mikrotik-' . $mikrotik->id, now()->addMinutes(15), function () use ($mikrotik) {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $response = $this->mikrotikRestApiService->getRequest($mikrotik, self::GET_ACTIVE_SECRETS);
            if ($response['status_code'] != 200) return [];
            return $response['result'];
        } else {
            $client = $this->getMikrotik($mikrotik);
            $query = new Query('/ppp/active/print');
            return $client->query($query)->read();
        }
        // });
    }

    /**
     * Get all Resources
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function getAllResources($mikrotik)
    {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $response = $this->mikrotikRestApiService->getRequest($mikrotik, self::GET_SYSTEM_RESOURCE);
            if ($response['status_code'] != 200) return [];
            //dd($response['result']['uptime']);
            return [$response['result']];
        } else {
            $client = $this->getMikrotik($mikrotik);
            $query = new Query('/system/resource/print');
            return $client->query($query)->read();
        }
    }


    /**
     * Get time
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function getTime($mikrotik)
    {
        if (version_compare($mikrotik->version, '7.9.0', '>=')) {
            $response = $this->mikrotikRestApiService->getRequest($mikrotik, self::GET_SYSTEM_CLOCK);
            if ($response['status_code'] != 200) return [];
            //  dd($response['result']);
            return [$response['result']];
        } else {
            $client = $this->getMikrotik($mikrotik);
            $query = new Query('/system/clock/print');
            return $client->query($query)->read();
        }
    }
}
