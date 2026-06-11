<?php

return [
    'key'        => env('ILETIMERKEZI_KEY', ''),
    'secret'     => env('ILETIMERKEZI_SECRET', ''),
    'originator' => env('ILETIMERKEZI_ORIGINATOR', 'SUCEK'),
    'api_url'    => 'https://api.iletimerkezi.com/v1/send-sms/json',
    'enabled'    => env('SMS_ENABLED', true),
];
