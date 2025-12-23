<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\AdminPurchaseController;

// Auth routes
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);


// Admin routes with role
Route::middleware(['auth:sanctum','role:admin'])->group(function(){
    Route::get('/admin/dashboard', function(){
        return response()->json(['message'=>'Admin Dashboard']);
    });

     // Categories routes
    Route::post('/admin/categories', [CategoryController::class,'store']);
    Route::get('/admin/categories', [CategoryController::class,'index']);
    Route::delete('/admin/categories/{id}', [CategoryController::class,'destroy']);

    // Subcategories routes
    Route::post('/admin/subcategories', [SubcategoryController::class,'store']);
    Route::get('/admin/subcategories', [SubcategoryController::class,'index']);
    Route::get('/admin/subcategories/{id}', [SubcategoryController::class,'show']);
    Route::put('/admin/subcategories/{id}', [SubcategoryController::class,'update']);
    Route::delete('/admin/subcategories/{id}', [SubcategoryController::class,'destroy']);

    // Suppliers routes
    Route::post('/admin/suppliers', [SupplierController::class,'store']);
    Route::get('/admin/suppliers', [SupplierController::class,'index']);
    Route::get('/admin/suppliers/{id}', [SupplierController::class,'show']);
    Route::put('/admin/suppliers/{id}', [SupplierController::class,'update']);
    Route::delete('/admin/suppliers/{id}', [SupplierController::class,'destroy']);

    // Product routes
    Route::get('admin/products', [ProductController::class,'list']);
    Route::get('admin/products/{id}', [ProductController::class,'show']);
    Route::post('admin/products', [ProductController::class,'store']);
    Route::post('admin/products/update/{id}', [ProductController::class,'update']);
    Route::delete('admin/products/{id}', [ProductController::class,'delete']);

    // Product routes
    Route::post('/admin/purchase', [AdminPurchaseController::class, 'store']);
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




