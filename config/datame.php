<?php

return [
    'test_api_token' => env('TEST_API_TOKEN', null),
    'rucaptcha_key' => env('RU_CAPTCHA_KEY', null),
    'za_chestnyi_biznes' => env('ZA_CHESTNYI_BIZNES', null),
    'moneta' => [
        'payment_password' => env('MONETA_PAYMENT_PASSWORD', null),
        'user' => env('MONETA_USER', null),
        'password' => env('MONETA_PASSWORD', null),
        'wsdl-attr' => env('MONETA_WSDL_ATTR', null),
        'wsdl' => env('MONETA_WSDL', null),
        'moneta_debug' => env('DEBUG_MONETA_CLIENT', false),
    ],
];