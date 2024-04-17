<?php

namespace App\Services;

// use Google\Service\Gmail\Message;
use Google\Service\Gmail;
use Exception;

class EmailService
{

    protected GmailAuthService $gmailAuthService;

    public function __construct(GmailAuthService $gmailAuthService)
    {
        $this->gmailAuthService = $gmailAuthService;
    }

    public function sendMail($to, $subject, $body)
    {

        try {
            $client = $this->gmailAuthService->createClient();
            $service = new Gmail($client);

            $message = new  \Google\Service\Gmail\Message();
            $rawMessage = "To: $to\r\n";
            $rawMessage .= "Subject: Gigalogy Registration\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= "Welcome To Gigalogy";

            $message->setRaw(base64_encode($rawMessage));
            $service->users_messages->send('me', $message);
            return true;
        } catch (Exception $e) {
            formatErrorLog($e);
            return false;
        }
    }
}
