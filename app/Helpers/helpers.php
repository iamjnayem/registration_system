<?php

/**
 * getResponseStatus function
 * @param integer $code
 * @param mixed $data
 * @param array $errors
 * @return array
 */

use Illuminate\Support\Facades\Log;

if (!function_exists('getResponseStatus')) {
    function getResponseStatus($code = 500, $data = null, $errors = [], $options = [])
    {
        $response = [
            'status' => $code,
            'status_title' => getStatusTitle($code),
            'errors' => $errors,
            'timestamp' => time(),
            'data' => $data,
        ];
        return $response;
    }

}

/**
 * getStatusTitle function
 * @param integer $code
 * @return array
 */

if (!function_exists('getStatusTitle')) {
    function getStatusTitle($code)
    {
        $map = [

            '200' => 'success',
            '500' => 'something went wrong',
            '422' => 'unable to process request',
        ];

        if (isset($map[$code])) {
            return $map[$code];
        } else {
            return "failed";
        }
    }

}


if (!function_exists('formatErrorLog')) {
    function formatErrorLog(Exception $e)
    {
        Log::error($e->getMessage() . " " . " is at " . $e->getFile() . " " . " is at " . $e->getLine());
    }
}
