<?php


use App\Http\Controllers\Api\TelemetryController;
use Illuminate\Support\Facades\Route;

# Implementar middleware de autenticação de dispositivo 
Route::group([], function () {
    Route::post('/telemetry', [TelemetryController::class, 'receiveTelemetry'])->name('telemetry.receive');
    Route::post('/telemetry/batch', [TelemetryController::class, 'receiveBatchTelemetry'])->name('telemetry.receiveBatch');
});
