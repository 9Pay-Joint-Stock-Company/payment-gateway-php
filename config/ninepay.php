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
    | env: SANDBOX or PRODUCTION
    |
    */

    'merchant_id'  => env('NINEPAY_MERCHANT_ID', ''),
    'secret_key'   => env('NINEPAY_SECRET_KEY', ''),
    'checksum_key' => env('NINEPAY_CHECKSUM_KEY', ''),
    'env'          => env('NINEPAY_ENV', 'SANDBOX'),
];
