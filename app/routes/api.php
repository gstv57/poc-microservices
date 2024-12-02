<?php

use App\Http\Controllers\API\Actions\{FindRequestScraping, RequestScraping};
use App\Http\Controllers\API\OpportunityController;
use App\Http\Controllers\Auth\{LoginController, LogoutController, RegisterController};
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');
Route::post('/logout', LogoutController::class)->name('logout');

Route::middleware('auth:sanctum')->group(function () {
    Route::resources([
        'opportunity' => OpportunityController::class,
    ]);
    Route::prefix('scraping')->group(function () {
        Route::post('request', RequestScraping::class)->name('scraping.request');
        Route::get('request/{hash}', FindRequestScraping::class)->name('scraping.find');
    });
});
