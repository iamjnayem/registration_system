<?php 

namespace App\Repositories;

use Exception;

use App\Models\RegisteredUser;
use Illuminate\Support\Facades\Log;

class RegisteredUsersRepository{

    private $model;
    
    public function __construct(RegisteredUser $registeredUser)
    {
        $this->model  = $registeredUser;   
    }

    public function insertOne($data)
    {
        try{
            Log::info("Inserting " . json_encode($data));
            $this->model::insert($data);
        }catch(Exception $e)
        {
            formatErrorLog($e);
            return;
        }
    }


    public function getOne($data)
    {
        try{
            Log::info("Querying " . json_encode($data));
            return $this->model::where($data)->first();
        }catch(Exception $e)
        {
            formatErrorLog($e);
            return;
        }
    }
}