<?php

use Laravel\Socialite\Facades\Socialite;
use Luketowers\Azureadsso\Controllers\AuthController;

Route::get('luketowers/azureadsso/login/microsoft', function () {
    return Socialite::driver('azure')->redirect();
})->middleware('web');

Route::get('luketowers/azureadsso/login/microsoft/callback', [AuthController::class, 'handleOauthResponse'])->middleware('web');
