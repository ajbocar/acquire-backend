<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\MyCustomMiddleware;

Route::post('/login', [LoginController::class, 'login']);

Route::get('/callback', function () {
    $http = new GuzzleHttp\Client;
    $response = $http->post('http://localhost/oauth/token', [
        'form_params' => [
            'grant_type' => 'password',
            'client_id' => '2',
            'client_secret' => 'r5xCmVLdJk0L44lfl33tMOjAxPvfqIjnHpOaPb1i',
            'username' => 'test@example.com',
            'password' => 'Password.1234',
            'scope' => ''
        ]
    ]);

    return json_decode((string) $response->getBody(), true);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//Route::apiResource('customers', CustomerController::class)->middleware(MyCustomMiddleware::class,'auth:api');
Route::apiResource('customers', CustomerController::class)->middleware('auth:api');