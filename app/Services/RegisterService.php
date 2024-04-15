<?php

namespace App\Services;

use Exception;
use App\Jobs\SendMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Jobs\ProcessRegistrationRequest;
use App\Utilities\Enum\RegistrationStatusEnum;
use App\Repositories\RegisteredUsersRepository;
use App\Utilities\Enum\EmailSentStatusEnum;

class RegisterService
{

    private $registerQueue;
    private $emailQueue;
    private RegisteredUsersRepository $registeredUserRepository;
    private EmailService $emailService;


    public function __construct(RegisteredUsersRepository $registeredUserRepository, EmailService $emailService)
    {
        $this->registerQueue = config('queue.queue_list.process_registration_request');
        $this->emailQueue = config('queue.queue_list.send_mail_via_gmail');
        $this->registeredUserRepository = $registeredUserRepository;
        $this->emailService = $emailService;
    }

    public function register($request)
    {

        try {

            $email = $request->validated('email');
            Redis::rpush($this->registerQueue, $email);

            ProcessRegistrationRequest::dispatch()->onQueue($this->registerQueue);

            $data = [
                'message' => 'Thank you for registration. You will get an email when the accout is active.',
            ];
            return getResponseStatus('200', $data);

        } catch (Exception $e) {
            formatErrorLog($e);
            return getResponseStatus('500');
        }

    }

    public function fetchFromCache()
    {

        try{
            $length = Redis::llen($this->registerQueue);

            if ($length == 0) {
                return;
            }

            $email = Redis::lpop($this->registerQueue);

            $data = [
                'email' => $email,
                'registration_status' => RegistrationStatusEnum::REQUESTED,
            ];

            $this->registeredUserRepository->insertOne($data);

            SendMail::dispatch($email)->onQueue($this->emailQueue);

        }catch(Exception $e)
        {
            formatErrorLog($e);
            return;
        }
       

    }


    public function sendMail($email)
    {
        $data = [
            'email' => $email,
            'registration_status' => RegistrationStatusEnum::REQUESTED,
        ];

        $requestedUser = $this->registeredUserRepository->getOne($data);

        $to = $email;
        $subject = 'Registration Confirmation';
        $body = 'Welcome to Gigalogy xyz service.';

        $isMailSent = $this->emailService->sendMail($to, $subject, $body);

        if($isMailSent == false)
        {
            $requestedUser->is_email_sent = EmailSentStatusEnum::FAILED;
            Log::error("Sending Email to " . $email . " has failed.");
        }

        else if ($isMailSent == true)
        {
            $requestedUser->is_email_sent = EmailSentStatusEnum::SENT;
            Log::error("Sending Email to " . $email . " has succeed.");
        }

        $requestedUser->registration_status = RegistrationStatusEnum::ACTIVATED;
        $requestedUser->save();

        
    }
}
