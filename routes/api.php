<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'name' => 'TenantCore API',
            'version' => 'v1',
        ]);
    });
});
