<?php

return [
    'AUTHENTICATION_OAUTH_CLIENT_ID' => env('NANICAS_AUTHENTICATION_OAUTH_CLIENT_ID'),
    'AUTHENTICATION_OAUTH_CLIENT_SECRET' => env('NANICAS_AUTHENTICATION_OAUTH_CLIENT_SECRET'),
    'AUTHENTICATION_CLIENT_ID' => env('NANICAS_AUTHENTICATION_CLIENT_ID'),
    'AUTHENTICATION_CLIENT_SECRET' => env('NANICAS_AUTHENTICATION_CLIENT_SECRET'),
    'AUTHENTICATION_API_URL' => env('NANICAS_AUTHENTICATION_API_URL'),
    'AUTHENTICATION_PERSONAL_TOKEN' => env('NANICAS_AUTHENTICATION_PERSONAL_TOKEN'),

    'PAINEL_API_URL' => env('NANICAS_PAINEL_API_URL'),
    'PAINEL_PERSONAL_TOKEN' => env('NANICAS_PAINEL_PERSONAL_TOKEN'),

    'AUTHORIZATION_API_URL' => env('NANICAS_AUTHORIZATION_API_URL'),
    'AUTHORIZATION_PERSONAL_TOKEN' => env('NANICAS_AUTHORIZATION_PERSONAL_TOKEN'),

    'SESSION_AUTH_KEY' => 'nanicas_auth',
    'SESSION_CLIENT_AUTH_KEY' => 'nanicas_client_auth',

    'DEFAULT_PERSONAL_TOKEN_MODEL' => Nanicas\Auth\Frameworks\Laravel\Models\PersonalToken::class,
    'DEFAULT_AUTHORIZATION_CLIENT' => Nanicas\Auth\Services\ThirdPartyAuthorizationService::class,
    'DEFAULT_AUTHENTICATION_CLIENT' => Nanicas\Auth\Services\ThirdPartyAuthenticationService::class,
];
