<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    Log::info('root path');

    return view('welcome');
});

Route::get('/api/reqheaders', function () {
    $headers = request()->headers->all();
    Log::info('request_headers', $headers);

    return response()->json([
        'headers' => $headers
    ]);
});

Route::get('/rolldice', function () {
    $result = random_int(1, 6);
    Log::info($result);

    return response()->json([
        'roll' => $result
    ]);
});
