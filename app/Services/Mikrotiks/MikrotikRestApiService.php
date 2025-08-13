<?php

namespace App\Services\Mikrotiks;

use Exception;
use GuzzleHttp\Client;
use App\Models\Servers\Mikrotik;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MikrotikRestApiService
{
    private function getMikrotik($mikrotik):Client
    {
        $url =$mikrotik->use_ssl ? 'https://' : 'http://';
        $url = $url. $mikrotik->host.':'.(int) $mikrotik->web_port;
       // $connection = $mikrotik->use_ssl ? 'https://' : 'http://';
       return new Client(['base_uri' => $url]);
    }


    public function getRequest(Mikrotik $mikrotik, $endpoint)
    {
        //dd($input);
        $client = $this->getMikrotik($mikrotik);
       // $url = $mikrotik->use_ssl ? 'https://' : 'http://' . $mikrotik->host.':'.$mikrotik->web_port;
       // $client = new Client(['base_uri' => $url]);
        try {
            $response = $client->get($endpoint, [
                'verify' => false,
                'auth' => [$mikrotik->username, $mikrotik->password],
                // 'json' => $input
            ]);
            // dd($response);
            return [
                'status_code' => $response->getStatusCode(),
                'result' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return [
                'status_code' => $e->getResponse()->getStatusCode(),
                'result' => json_decode($e->getResponse()->getBody()->getContents(), true)
            ];
        }
    }

    public function postRequest(Mikrotik $mikrotik, $endpoint, array $input)
    {
        $client = $this->getMikrotik($mikrotik);
        try {
            $response = $client->post(
                $endpoint,
                [
                    'verify' => false,
                    'auth' => [$mikrotik->username, $mikrotik->password],
                    'json' => $input
                ]

            );
            return [
                'status_code' => $response->getStatusCode(),
                'result' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return [
                'status_code' => $e->getResponse()->getStatusCode(),
                'result' => json_decode($e->getResponse()->getBody()->getContents(), true)
            ];
        }
    }

    public function putRequest(Mikrotik $mikrotik, $endpoint, array $input)
    {
        $client = $this->getMikrotik($mikrotik);
        try {
            $response = $client->put(
                $endpoint,
                [
                    'verify' => false,
                    'auth' => [$mikrotik->username, $mikrotik->password],
                    'json' => $input
                ]

            );
            return [
                'status_code' => $response->getStatusCode(),
                'result' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return [
                'status_code' => $e->getResponse()->getStatusCode(),
                'result' => json_decode($e->getResponse()->getBody()->getContents(), true)
            ];
        }
    }

    public function patchRequest(Mikrotik $mikrotik, $endpoint, array $input)
    {
        $client = $this->getMikrotik($mikrotik);
        try {
            $response = $client->patch(
                $endpoint,
                [
                    'verify' => false,
                    'auth' => [$mikrotik->username, $mikrotik->password],
                    'json' => $input
                ]

            );
            return [
                'status_code' => $response->getStatusCode(),
                'result' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return [
                'status_code' => $e->getResponse()->getStatusCode(),
                'result' => json_decode($e->getResponse()->getBody()->getContents(), true)
            ];
        }
    }

    public function deleteRequest(Mikrotik $mikrotik, $endpoint, $id)
    {
        $client = $this->getMikrotik($mikrotik);
        try {
            $response = $client->delete($endpoint . '/' . $id, [
                'verify' => false,
                'auth' => [$mikrotik->username, $mikrotik->password],
            ]);
            // dd($response);
            return [
                'status_code' => $response->getStatusCode(),
                'result' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return [
                'status_code' => $e->getResponse()->getStatusCode(),
                'result' => json_decode($e->getResponse()->getBody()->getContents(), true)
            ];
        }
    }
}
