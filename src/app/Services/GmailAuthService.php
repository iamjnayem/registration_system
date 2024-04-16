<?php

namespace App\Services;

use Google_Client;

use Exception;
use Illuminate\Support\Facades\Log;

class GmailAuthService
{

    protected $credentialsFile;
    protected $tokenFile;


    public function __construct()
    {
        $this->credentialsFile = config_path('credentials.json');
        $this->tokenFile = config_path('token.json');
    }

    public function createClient()
    {
        try {
            $client = new Google_Client();
            $client->setApplicationName(config('app.name'));
            $client->setScopes(env('GMAIL_SEND_SCOPE'));
            $client->setAuthConfig($this->credentialsFile);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            if (file_exists($this->tokenFile)) {
                $accessToken = json_decode(file_get_contents($this->tokenFile), true);
                $client->setAccessToken($accessToken);
            }


            if ($client->isAccessTokenExpired()) {

                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                } else {

                    $authUrl = $client->createAuthUrl();
                    printf("Open the following link in your browser:\n%s\n", $authUrl);
                    print 'Enter verification code: ';
                    $authCode = trim(fgets(STDIN));


                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    $client->setAccessToken($accessToken);


                    if (array_key_exists('error', $accessToken)) {
                        throw new Exception(join(', ', $accessToken));
                    }
                }

                if (!file_exists(dirname($this->tokenFile))) {
                    mkdir(dirname($this->tokenFile), 0700, true);
                }
                file_put_contents($this->tokenFile, json_encode($client->getAccessToken()));
            }
           

            return $client;
        } catch (Exception $e) {
            formatErrorLog($e);
            return;
        }
    }
}
