<?php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ReportImageController;
use Illuminate\Support\Facades\Route;

// Project routes
Route::apiResource('projects', ProjectController::class);

// Report routes
Route::get('projects/{project}/reports', [ReportController::class, 'index']);
Route::post('reports', [ReportController::class, 'store']);
Route::get('reports/{report}', [ReportController::class, 'show']);
Route::delete('reports/{report}', [ReportController::class, 'destroy']);

// Report image routes
Route::post('report-images', [ReportImageController::class, 'store']);
Route::delete('report-images/{reportImage}', [ReportImageController::class, 'destroy']);

// API routes can be added here if needed in the future 