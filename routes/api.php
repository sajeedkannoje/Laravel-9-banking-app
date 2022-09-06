<?php

use App\Http\Controllers\api\AuthorizationController;
use App\Http\Controllers\api\RegisterController;
use App\Http\Controllers\api\TwoFactorAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Twilio\Rest\Client;

Route::post('register', [AuthorizationController::class,'signUp'])->name('register');
Route::post('login', [AuthorizationController::class,'signIn'])->name('login');
Route::post('test-message', [AuthorizationController::class,'generateCode'])->name('test-message');

Route::post('two-factor-auth', [TwoFactorAuthController::class, 'store'])->name('2fa.store');
Route::post('two-factor-auth/resent', [TwoFactorAuthController::class, 'resend'])->name('2fa.resend');

Route::middleware('auth:sanctum','2fa')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/test', function(Request $request){
} );
