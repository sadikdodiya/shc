<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\BrandController;
use App\Http\Controllers\Company\ProductController;
use App\Http\Controllers\Company\PartEntryController;

// Test route to verify routing is working
Route::get('/test-company-route', function () {
    return 'Company test route is working! This is from company.php';
});

// Test route without any middleware
Route::get('/test-company-route', function () {
    return 'Company test route is working!';
});

/*
|--------------------------------------------------------------------------
| Company Routes
|--------------------------------------------------------------------------
|
| Here is where you can register company admin routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "company" middleware group.
|
*/

Route::middleware(['auth', 'verified', 'role:CompanyAdmin'])->group(function () {
    // Company Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('company.dashboard');
    
    // Brands Resource
    Route::resource('brands', BrandController::class)->except(['show']);
    Route::patch('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])
         ->name('brands.toggle-status');
    
    // Products Resource
    Route::resource('products', ProductController::class);
    Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])
         ->name('products.toggle-status');
    
    // Fault Types Resource
    Route::resource('fault-types', \App\Http\Controllers\Company\FaultTypeController::class)->except(['show']);
    Route::patch('fault-types/{faultType}/toggle-status', [\App\Http\Controllers\Company\FaultTypeController::class, 'toggleStatus'])
         ->name('fault-types.toggle-status');
    
    // Areas Resource
    Route::resource('areas', \App\Http\Controllers\Company\AreaController::class);
    Route::patch('areas/{area}/toggle-status', [\App\Http\Controllers\Company\AreaController::class, 'toggleStatus'])
         ->name('areas.toggle-status');
    
    // Items Resource
    Route::apiResource('items', \App\Http\Controllers\Company\ItemController::class);
    Route::post('items/{item}/update-stock', [\App\Http\Controllers\Company\ItemController::class, 'updateStock'])
         ->name('items.update-stock');
    Route::get('items/categories', [\App\Http\Controllers\Company\ItemController::class, 'categories'])
         ->name('items.categories');
    
    // Part Entries (Stock Movements)
    Route::prefix('items/{item}')->group(function () {
        Route::resource('part-entries', PartEntryController::class)->except(['edit', 'update']);
        Route::get('part-entries/{partEntry}/edit', [PartEntryController::class, 'edit'])->name('part-entries.edit');
        Route::put('part-entries/{partEntry}', [PartEntryController::class, 'update'])->name('part-entries.update');
        Route::post('part-entries/update-stock', [PartEntryController::class, 'updateStock'])->name('part-entries.update-stock');
    });
    
    // Add other company resources here
});
