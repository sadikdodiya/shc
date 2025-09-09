<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\PackageController;

// Public routes that don't require authentication
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    // Test route for email verification
    Route::get('/test-verification', function () {
        // Create a test user if none exists
        $user = App\Models\User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'phone' => '+1234567890',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => null,
            ]
        );

        // Ensure the user has the 'user' role
        if (!$user->hasRole('user')) {
            $role = Spatie\Permission\Models\Role::firstOrCreate(['name' => 'user']);
            $user->assignRole($role);
        }

        // Send verification email
        $user->sendEmailVerificationNotification();
        
        return 'Verification email sent to ' . $user->email;
    });

    // Authentication routes
    require __DIR__.'/auth.php';
});

// Apply 'verified' middleware to all authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Home route after login
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('Super Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->hasRole('CompanyAdmin')) {
            return redirect()->route('company.dashboard');
        } elseif (auth()->user()->hasRole('Staff')) {
            return redirect()->route('staff.dashboard');
        } elseif (auth()->user()->hasRole('Customer')) {
            return redirect()->route('customer.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:Super Admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Company Management
    Route::resource('companies', CompanyController::class);
    
    // Package Management
    Route::resource('packages', PackageController::class);
    
    // Additional admin routes can be added here
});

// Route groups are now defined in RouteServiceProvider
// to keep the routes organized in separate files
