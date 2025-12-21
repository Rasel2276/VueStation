<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\CategoryController;

// Auth routes
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);


// Admin routes with role
Route::middleware(['auth:sanctum','role:admin'])->group(function(){
    Route::get('/admin/dashboard', function(){
        return response()->json(['message'=>'Admin Dashboard']);
    });

    Route::post('/admin/categories', [CategoryController::class,'store']);
    Route::get('/admin/categories', [CategoryController::class,'index']);
    Route::delete('/admin/categories/{id}', [CategoryController::class,'destroy']);
});

// Vendor routes with role
Route::middleware(['auth:sanctum','role:vendor'])->group(function(){
    Route::get('/vendor/dashboard', function(){
        return "Vendor Dashboard";
    });
});

// Customer routes with role
Route::middleware(['auth:sanctum','role:customer'])->group(function(){
    Route::get('/customer/dashboard', function(){
        return "Customer Dashboard";
    });
});




