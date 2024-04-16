<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'email sending service',
        'status' => 'up & running'
    ]);
});


