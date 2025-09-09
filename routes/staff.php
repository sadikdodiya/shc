<?php

use App\Http\Controllers\Staff\AttendanceController;
use App\Http\Controllers\Staff\ComplaintController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\PaymentController;
use App\Http\Controllers\Api\Staff\AttendanceApiController;
use App\Http\Controllers\Api\Staff\ComplaintApiController;
use App\Http\Controllers\Api\Staff\PaymentApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Staff API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for staff functionality.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" and "auth:sanctum" middleware groups.
|
*/

// Staff dashboard routes
Route::prefix('staff')->middleware(['auth:sanctum', 'verified', 'role:staff'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, '__invoke']);
    
    // Web Attendance routes
    Route::prefix('attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('staff.attendance.index');
        Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('staff.attendance.clock-in');
        Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('staff.attendance.clock-out');
        Route::get('/status', [AttendanceController::class, 'status'])->name('staff.attendance.status');
        Route::get('/{id}', [AttendanceController::class, 'show'])->name('staff.attendance.show');
        Route::get('/{id}/edit', [AttendanceController::class, 'edit'])->name('staff.attendance.edit');
        Route::put('/{id}', [AttendanceController::class, 'update'])->name('staff.attendance.update');
    });
    
    // Web Complaint routes
    Route::prefix('complaints')->group(function () {
        Route::get('/', [ComplaintController::class, 'index'])->name('staff.complaints.index');
        Route::get('/{id}', [ComplaintController::class, 'show'])->name('staff.complaints.show');
        Route::put('/{id}/status', [ComplaintController::class, 'updateStatus'])->name('staff.complaints.status');
        Route::post('/{id}/remarks', [ComplaintController::class, 'storeRemark'])->name('staff.complaints.remarks.store');
        Route::get('/stats', [ComplaintController::class, 'getStats'])->name('staff.complaints.stats');
        Route::get('/recent', [ComplaintController::class, 'getRecentComplaints'])->name('staff.complaints.recent');
    });
    
    // Web Payment routes
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('staff.payments.index');
        Route::get('/{id}', [PaymentController::class, 'show'])->name('staff.payments.show');
        Route::get('/stats', [PaymentController::class, 'getPaymentStats'])->name('staff.payments.stats');
        Route::get('/history', [PaymentController::class, 'getPaymentHistory'])->name('staff.payments.history');
    });
    
    // API Routes
    Route::prefix('api')->group(function () {
        // Attendance API routes
        Route::prefix('attendance')->group(function () {
            Route::get('/', [AttendanceApiController::class, 'index'])->name('api.staff.attendance.index');
            Route::post('/clock-in', [AttendanceApiController::class, 'clockIn'])->name('api.staff.attendance.clock-in');
            Route::post('/clock-out', [AttendanceApiController::class, 'clockOut'])->name('api.staff.attendance.clock-out');
            Route::get('/status', [AttendanceApiController::class, 'getStatus'])->name('api.staff.attendance.status');
            Route::get('/history', [AttendanceApiController::class, 'history'])->name('api.staff.attendance.history');
            Route::get('/summary', [AttendanceApiController::class, 'summary'])->name('api.staff.attendance.summary');
        });
        
        // Complaint API routes
        Route::prefix('complaints')->group(function () {
            Route::get('/', [ComplaintApiController::class, 'index'])->name('api.staff.complaints.index');
            Route::get('/{id}', [ComplaintApiController::class, 'show'])->name('api.staff.complaints.show');
            Route::put('/{id}/status', [ComplaintApiController::class, 'updateStatus'])->name('api.staff.complaints.status');
            Route::post('/{id}/remarks', [ComplaintApiController::class, 'addRemarkToComplaint'])->name('api.staff.complaints.remarks.store');
            Route::get('/stats', [ComplaintApiController::class, 'getStatistics'])->name('api.staff.complaints.stats');
        });
        
        // Payment API routes
        Route::prefix('payments')->group(function () {
            Route::get('/', [PaymentApiController::class, 'index'])->name('api.staff.payments.index');
            Route::get('/{id}', [PaymentApiController::class, 'show'])->name('api.staff.payments.show');
            Route::get('/stats', [PaymentApiController::class, 'getStatistics'])->name('api.staff.payments.stats');
            Route::get('/recent', [PaymentApiController::class, 'getRecentPayments'])->name('api.staff.payments.recent');
        });
    });
});
