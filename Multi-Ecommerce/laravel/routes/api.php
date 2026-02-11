<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Vendor\ProfileController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Vendor\VendorStockController;
use App\Http\Controllers\Admin\AdminPurchaseController;
use App\Http\Controllers\Vendor\VendorReturnController;
use App\Http\Controllers\Vendor\VendorPurchaseController;
use App\Http\Controllers\Vendor\CustomerProductController;
use App\Http\Controllers\Vendor\CustomerOrderReturnController;

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
    Route::get('/admin/purchase', [AdminPurchaseController::class, 'index']);
    Route::get('/admin/stock', [AdminPurchaseController::class, 'stockIndex']);
    Route::delete('/admin/purchase/{id}', [AdminPurchaseController::class, 'deletePurchase']);
    Route::delete('/admin/stock/{id}', [AdminPurchaseController::class, 'deleteStock']);

    // Supplier return product
    Route::post('/admin/purchase/return', [AdminPurchaseController::class, 'supplierReturn']);
    Route::get('/admin/purchase/return-history', [AdminPurchaseController::class, 'returnHistory']);


    // Sales Report Routes
    Route::get('/sales-report', [SalesReportController::class, 'index']);
    Route::patch('/sales-report/{id}/status', [SalesReportController::class, 'updateStatus']);
    Route::delete('/sales-report/{id}', [SalesReportController::class, 'destroy']);

    // Sales return Report Routes
    Route::get('/sales-returns', [SalesReportController::class, 'returnList']);
    Route::delete('/sales-returns/{id}', [SalesReportController::class, 'destroyReturn']);


    // vendor user routes
    Route::get('/admin/vendors', [UserController::class, 'index']);
    Route::delete('/admin/vendors/{id}', [UserController::class, 'destroy']);

    // customer user rotues
    Route::get('/admin/customers', [UserController::class, 'customers']);
    Route::delete('/admin/customers/{id}', [UserController::class, 'destroy']);

});

// Customer order place korar route (Public)
 Route::get('/track-order', [OrderController::class, 'trackOrder']);
Route::post('/customer/place-order', [OrderController::class, 'storeOrder']);
Route::get('marketplace/all-products', [CustomerProductController::class, 'getAllProducts']);

// Vendor routes with role
Route::middleware(['auth:sanctum','role:vendor'])->group(function(){
    Route::get('/vendor/dashboard', function(){
        return response()->json(['message'=>'Vendor Dashboard']);
    });
    Route::get('/vendor/admin-stocks', [VendorStockController::class, 'index']);
    Route::post('/vendor/purchases', [VendorPurchaseController::class, 'store']);
    Route::get('/vendor/purchases', [VendorPurchaseController::class, 'index']);
    Route::delete('/vendor/purchases/{id}', [VendorPurchaseController::class, 'destroy']);
    Route::get('/vendor/my-stocks', [VendorStockController::class, 'myStock']);
    Route::delete('/vendor/stocks/{id}',[VendorStockController::class, 'destroyVendorStock']);


    Route::get('/vendor/returns', [VendorReturnController::class, 'index']);
    Route::post('/vendor/returns', [VendorReturnController::class, 'store']);
    Route::delete('/vendor/returns/{id}', [VendorReturnController::class, 'destroy']);


    // ✅ Customer Products Routes (Alada Alada)
    Route::get('/vendor/customer-products', [CustomerProductController::class, 'index']);
    Route::post('/vendor/customer-products', [CustomerProductController::class, 'store']);
    Route::get('/vendor/customer-products/{id}', [CustomerProductController::class, 'show']);
    Route::put('/vendor/customer-products/{id}', [CustomerProductController::class, 'update']);
    Route::delete('/vendor/customer-products/{id}', [CustomerProductController::class, 'destroy']);
    Route::get('vendor/get-stocks', [CustomerProductController::class, 'getVendorStocks']);


    Route::get('/vendor/orders', [OrderController::class, 'index']);
    Route::post('/vendor/order-status/{id}', [OrderController::class, 'updateStatus']);
    Route::delete('/vendor/order-delete/{id}', [OrderController::class, 'destroy']);
    Route::post('/vendor/return-status/{id}', [CustomerOrderReturnController::class, 'updateReturnStatus']);
    Route::get('/vendor/customer-returns', [CustomerOrderReturnController::class, 'vendorReturnList']);
    
});




// Customer routes with role
Route::middleware(['auth:sanctum','role:customer'])->group(function(){
    Route::get('/customer/dashboard', function(){
        return "Customer Dashboard";
    });

    Route::get('/customer/my-orders', [OrderController::class, 'customerOrders']);
    Route::post('/customer/order-cancel/{id}', [OrderController::class, 'cancelOrder']);
    Route::post('/customer/order-return', [CustomerOrderReturnController::class, 'storeReturnRequest']);
    Route::get('/customer/my-returns', [CustomerOrderReturnController::class, 'myReturns']);
    Route::delete('/customer/return-cancel/{id}', [CustomerOrderReturnController::class, 'cancelReturn']);
});




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', [ProfileController::class, 'index']);
    Route::post('/user/profile-update', [ProfileController::class, 'updateProfile']);
});




