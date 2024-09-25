<?php

use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])
    ->name('subscriptions.subscribe');
Route::get('/subscriber/confirm/{token}', [SubscriberController::class, 'confirm'])
    ->name('subscriptions.confirm');
