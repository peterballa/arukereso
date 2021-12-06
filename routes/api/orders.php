<?php

use App\Http\Controllers\API\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('orders/create', [OrderController::class, 'create']);
    Route::post('orders/query', [OrderController::class, 'query']);
    Route::post('orders/updateStatus/{order}', [OrderController::class, 'updateStatus']);
});
