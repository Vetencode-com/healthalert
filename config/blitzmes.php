<?php

return [
    /**
     * Session is representation of Blitzmes device
     */
    'session' => env('BLITZMES_SESSION', ''),

    /**
     * API Key can make you have access to blitzmes service
     */
    'api_key' => env('BLITZMES_API_KEY', 'abcd'),

    /**
     * Is Production is state for app environment is in production or development
     */
    'is_production' => env('BLITZMES_IS_PRODUCTION', true),

    /**
     * Prefix format is country phone code
     */
    'prefix_format' => env('BLITZMES_PREFIX_FORMAT', '62'),

    /**
     * Blitzmes timezone is used to set time replacer, but now only availble in indonesian language
     */
    'timezone' => env('BLITZMES_TIMEZONE', 'Asia/Jakarta'),
];
