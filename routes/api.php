<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Warehouse\InventoryItemController;
use App\Http\Controllers\Warehouse\StockController;
use App\Http\Controllers\Warehouse\StockTransferController;
use App\Http\Controllers\Warehouse\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'userLogin']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'warehouses'], function () {

        Route::get('inventory', [InventoryItemController::class, 'index']); // inventory items listing with filters options First API Required

        Route::post('/stock-transfers', [StockTransferController::class, 'stockTransfer']);
        Route::get('/{id}/inventory', [WarehouseController::class, 'inventory']);
        Route::put('/stock', [StockController::class, 'updateStock']);
    });
});
