<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Tenant;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'name' => 'TenantCore API',
            'version' => 'v1',
        ]);
    });

    Route::middleware('tenant')->group(function () {
        Route::get('/tenant', function () {
            return response()->json(app(Tenant::class)->only(['id', 'name', 'slug']));
        });

        Route::post('/login', [LoginController::class, 'store']);
        Route::post('/register', [RegisterController::class, 'store']);
        Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth:sanctum');
        Route::get('/me', fn () => response()->json(request()->user()->only(['id', 'name', 'email'])))->middleware('auth:sanctum');
    });
});
