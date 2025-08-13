<?php

namespace App\Services\WhatsappGateway;

use Exception;
use App\Models\User;
use App\Models\Websystem;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\WhatsappGatewayGeneral;

class GatewayApiService
{

    public static function initializeRequest($endpoint, $input)
    {

        $guzzle = new Client(['base_uri' => 'http://' . config('wa-griyanet.server_url'), 'verify' => false]);
        try {
            //$user = Auth::user();
            $company = Websystem::first();
            $response = $guzzle->post($endpoint, [
                "json" => [
                    'name' => $company->title ?? config('app.name'),
                    'email' => $company->email,
                    'password' => $input['password'] ?? null,
                    'domain' => config('app.url'),
                    'company' => $company->title ?? config('app.name'),
                ]
            ]);

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

    public static function createRequest($endpoint)
    {
        $client = new Client(['base_uri' => 'http://' . config('wa-griyanet.server_url'), 'verify' => false]);
        try {
            $response = $client->get($endpoint.'/create', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('API_CLIENT_SECRET'),
                    'Accept' => 'application/json'
                ],
            ]);
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

    public static function addRequest($endpoint, array $input)
    {
        $client = new Client(['base_uri' => 'http://' . config('wa-griyanet.server_url'), 'verify' => false]);
        try {
            $response = $client->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('API_CLIENT_SECRET'),
                    'Accept' => 'application/json'
                ],
                "json" => $input
            ]);
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

    public static function updateRequest($endpoint, $id, array $input)
    {
        $client = new Client(['base_uri' => 'http://' . config('wa-griyanet.server_url'), 'verify' => false]);
        try {
            $response = $client->put($endpoint . '/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('API_CLIENT_SECRET'),
                    'Accept' => 'application/json'
                ],
                "json" => $input
            ]);

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
    public static function deleteRequest($endpoint, $id, array $input = null)
    {
        $guzzle = new Client(['base_uri' => 'http://' . config('wa-griyanet.server_url'), 'verify' => false]);
        try {
            return $guzzle->delete($endpoint . '/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('API_CLIENT_SECRET'),
                    'Accept' => 'application/json'
                ],
                "json" => $input
            ]);
            //dd($raw_response);
            // return json_decode($raw_response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // dd($e);
            return $e->getResponse();
        }
    }


    public static function getRequest($endpoint, array $input = null)
    {
        $client = new Client(['base_uri' => 'http://' . config('wa-griyanet.server_url')]);
        try {
            $response = $client->get($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('API_CLIENT_SECRET'),
                    'Accept' => 'application/json'
                ],
                "json" => $input
            ]);
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

    public static function showRequest($endpoint, $id)
    {
        $guzzle = new Client(['base_uri' => 'http://' . config('wa-griyanet.server_url')]);
        try {
            $response = $guzzle->get($endpoint . '/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('API_CLIENT_SECRET'),
                    'Accept' => 'application/json'
                ],
            ]);
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

    public static function sendMessage($endpoint, array $input)
    {
        $send_wa = $endpoint.'?sender=' . $input['sender'] . '&number=' . $input['number'] . '&message=' . $input['message'];
        $client = new Client(['base_uri' => 'http://' . config('wa-griyanet.server_url'), 'verify' => false]);
        try {
            $response = $client->post($send_wa, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('API_CLIENT_MESSAGE'),
                    'Accept' => 'application/json'
                ],
            ]);
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


    public static function sendNotificationMessage($endpoint, array $input)
    {
        $client = new Client(['base_uri' => 'http://' . config('wa-griyanet.server_url')]);
        try {
            $response = $client->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('API_CLIENT_MESSAGE'),
                    'Accept' => 'application/json'
                ],
               "json" => $input
            ]);
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
