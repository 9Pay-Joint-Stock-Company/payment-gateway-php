<?php

return [
    /*
    |--------------------------------------------------------------------------
    | NinePay Merchant Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your NinePay merchant credentials. These values
    | are used when making requests to the NinePay API.
    |
    | NINEPAY_ENDPOINT: full API endpoint URL for the target environment
    |
    */

    'merchant_id'  => env('NINEPAY_MERCHANT_ID', ''),
    'secret_key'   => env('NINEPAY_SECRET_KEY', ''),
    'checksum_key' => env('NINEPAY_CHECKSUM_KEY', ''),
    'endpoint'     => env('NINEPAY_ENDPOINT', ''),
];
