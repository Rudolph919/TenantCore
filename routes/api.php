<?php

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
    });
});
