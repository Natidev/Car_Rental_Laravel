<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\OfficeRentAgreementController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleServiceRequestController;


Route::apiResource('branches', BranchController::class);

// Office Rent Agreement Routes
Route::apiResource('agreements', OfficeRentAgreementController::class);
Route::post('/agreements/{agreement}/approve', [OfficeRentAgreementController::class, 'approve']);
Route::post('/agreements/{agreement}/renew', [OfficeRentAgreementController::class, 'renew']);
Route::apiResource('vehicles', VehicleController::class);
Route::get('/vehicles/{vehicle}/service-check', [VehicleController::class, 'checkServiceNeed']);

Route::apiResource('service-requests', VehicleServiceRequestController::class);
Route::post('/service-requests/{serviceRequest}/approve', [VehicleServiceRequestController::class, 'approve']);
Route::post('/service-requests/{serviceRequest}/start', [VehicleServiceRequestController::class, 'startWork']);
Route::post('/service-requests/{serviceRequest}/complete', [VehicleServiceRequestController::class, 'complete']);
Route::post('/service-requests/{serviceRequest}/cancel', [VehicleServiceRequestController::class, 'cancel']);
