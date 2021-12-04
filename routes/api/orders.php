<?php

use App\Http\Controllers\API\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('orders', [OrderController::class, 'create']);
});
