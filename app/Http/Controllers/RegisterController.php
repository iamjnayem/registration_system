<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\RegisterService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\RegistrationRequest;

class RegisterController extends Controller
{
    private RegisterService $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }



    /**
     * register controller to handle registration request
     */
    public function register(RegistrationRequest $request)
    {
        try{
            Log::info("incoming request => " . json_encode($request->all()));
            $response = $this->registerService->register($request);
            Log::info("final response => " . json_encode($response));
            return $response;

        }catch(Exception $e)
        {
            formatErrorLog($e);
            return getResponseStatus('500');
        }
    }
}

