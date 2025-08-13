<?php

return [
    'development' => [
            /**
             * Integration mode
             */
            'mode' => env('TRIPAY_MODE'),

            /**
             * Merchant code
             */
            'merchant_code' => env('TRIPAY_MERCHANT_CODE'),

            /**
             * Api Key
             */
            'api_key' => env('TRIPAY_SANDBOX_API_KEY'),

            /**
             * Private Key
             */
            'private_key' => env('TRIPAY_SANDBOX_PRIVATE_KEY'),

            /**
             * Additional Guzzle options
             */
            'guzzle_options' => []
    ],

    'production' => [
            /**
             * Integration mode
             */
            'mode' => env('TRIPAY_MODE'),

            /**
             * Merchant code
             */
            'merchant_code' => env('TRIPAY_MERCHANT_CODE'),

            /**
             * Api Key
             */
            'api_key' => env('TRIPAY_PRODUCTION_API_KEY'),

            /**
             * Private Key
             */
            'private_key' => env('TRIPAY_PRODUCTION_PRIVATE_KEY'),

            /**
             * Additional Guzzle options
             */
            'guzzle_options' => []
    ],


];
