<?php

use Google\Client as GoogleClient;
use Google\Service\Oauth2;

if (!function_exists('initGoogleClient')) {
    function initGoogleClient()
    {
        $client = new GoogleClient();
        $client->setClientId(getenv('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(base_url('auth/google/callback'));
        $client->addScope('email');
        $client->addScope('profile');
        
        return $client;
    }
}

if (!function_exists('getGoogleUserInfo')) {
    function getGoogleUserInfo($accessToken)
    {
        $client = initGoogleClient();
        $client->setAccessToken($accessToken);
        
        $oauth2 = new Oauth2($client);
        return $oauth2->userinfo->get();
    }
}

if (!function_exists('getGoogleAuthUrl')) {
    function getGoogleAuthUrl()
    {
        $client = initGoogleClient();
        return $client->createAuthUrl();
    }
} 