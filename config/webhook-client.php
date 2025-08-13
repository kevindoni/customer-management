<?php

return [
    'configs' => [
        [
            'name' => 'mikrotik',
            'signing_secret' => env('API_CLIENT_MIKROTIK'),
            'signature_header_name' => 'X-Mikrotik-Signature',
            'signature_validator' => App\Handler\MikrotikSignature::class,
            'webhook_profile' => \Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile::class,
            'webhook_response' => \Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
            'store_headers' => [],
            'process_webhook_job' => App\Handler\ProcessMikrotikWebhook::class,
        ],

        [
            'name' => 'whatsapp-gateway',
            'signing_secret' => env('API_CLIENT_MESSAGE'),
            'signature_header_name' => 'X-Griyanet-Signature',
            'signature_validator' => App\Handler\GriyanetSignature::class,
            'webhook_profile' => \Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile::class,
            'webhook_response' => \Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
            'store_headers' => [],
            'process_webhook_job' => App\Handler\ProcessWhatsappWebhook::class,
        ],

        [
            'name' => 'tripay',
            'signing_secret' => env('TRIPAY_MODE') == 'development' ? env('TRIPAY_SANDBOX_PRIVATE_KEY') : env('TRIPAY_PRODUCTION_PRIVATE_KEY'),
            'signature_header_name' => 'X-Callback-Signature',
            'signature_validator' => App\Handler\TripaySignature::class,
            'webhook_profile' => \Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile::class,
            'webhook_response' => \Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
            'store_headers' => [],
            'process_webhook_job' => App\Handler\ProcessTripayWebhook::class,
        ],
    ],
   // 'api_key' => env('API_KEY', 'edwMYkp3swj2BNGZVcENKlAdb6brAYX2'),

    /*
     * The integer amount of days after which models should be deleted.
     *
     * It deletes all records after 30 days. Set to null if no models should be deleted.
     */
    'delete_after_days' => 30,

    /*
     * Should a unique token be added to the route name
     */
    'add_unique_token_to_route_name' => false,
];
