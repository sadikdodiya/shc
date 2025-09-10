<?php

use Illuminate\Support\Facades\Route;

// Test route
Route::get('/test-web-route', function () {
    return 'Basic web route is working!';
});
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\PackageController;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\SimpleAuthController;

// Test authentication and role assignment
Route::get('/test-auth', function () {
    // Check if user is authenticated
    if (!auth()->check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Not authenticated'
        ], 401);
    }
    
    $user = auth()->user();
    
    return [
        'status' => 'success',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name')
        ],
        'is_admin' => $user->hasRole('Super Admin')
    ];
});

// Test admin access
Route::get('/test-admin-access', function () {
    // Check if user is authenticated
    if (!auth()->check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Not authenticated'
        ], 401);
    }
    
    $user = auth()->user();
    
    return [
        'status' => 'success',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name')
        ],
        'is_admin' => $user->hasRole('Super Admin'),
        'can_access_admin' => $user->can('view admin dashboard')
    ];
});

// Debug route to check user roles and permissions
Route::get('/debug/user-info', function () {
    $user = auth()->check() ? auth()->user() : null;
    
    if (!$user) {
        return [
            'status' => 'not_authenticated',
            'message' => 'No user is currently logged in'
        ];
    }
    
    return [
        'status' => 'authenticated',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name')
        ],
        'all_roles' => \Spatie\Permission\Models\Role::all()->pluck('name'),
        'all_permissions' => \Spatie\Permission\Models\Permission::all()->pluck('name')
    ];
});

// Temporary route to create admin user (remove in production)
Route::get('/create-admin', function () {
    try {
        // Create or get the Super Admin role
        $role = \Spatie\Permission\Models\Role::firstOrCreate(
            ['name' => 'Super Admin'],
            ['guard_name' => 'web']
        );

        // Create admin user
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Assign role to user
        $admin->assignRole('Super Admin');

        return [
            'status' => 'success',
            'message' => 'Admin user created successfully',
            'email' => 'admin@example.com',
            'password' => 'password',
            'roles' => $admin->getRoleNames(),
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
});

// Include all auth routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';

// Logout Route - Must be POST method and named 'logout'
Route::post('/logout', [SimpleAuthController::class, 'logout'])
    ->middleware('web', 'auth')
    ->name('logout');

// Add GET route for logout for testing (not recommended for production)
Route::get('/logout', [SimpleAuthController::class, 'logout'])
    ->middleware('web', 'auth')
    ->name('logout.get');

// Public routes that don't require authentication
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    // Test route to check current user's roles and permissions
    Route::get('/check-user-roles', function () {
        if (!auth()->check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $user = auth()->user();
        
        return response()->json([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'all_roles' => \Spatie\Permission\Models\Role::all()->pluck('name'),
            'is_admin' => $user->hasRole('Super Admin')
        ]);
    })->middleware('auth');

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

// Temporary debug admin route (remove in production)
Route::get('/admin/debug-dashboard', function () {
    $user = auth()->user();
    
    if (!$user) {
        return response('Not authenticated', 401);
    }
    
    // Manually check if user is admin
    $isAdmin = $user->hasRole('Super Admin');
    
    return [
        'status' => 'success',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => $isAdmin,
            'roles' => $user->getRoleNames(),
            'all_permissions' => $user->getAllPermissions()->pluck('name')
        ]
    ];
})->middleware(['auth', 'verified']);

// Admin routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Company Management
    Route::resource('companies', CompanyController::class);
    
    // Package Management
    Route::resource('packages', PackageController::class);
    Route::post('packages/{package}/expire', [PackageController::class, 'expire'])->name('packages.expire');
    
    // Additional admin routes can be added here
});

// Route groups are now defined in RouteServiceProvider
// to keep the routes organized in separate files

// Authentication routes
Route::post('/logout', [SimpleAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Test route to check if routes are being registered
Route::get('/test-route', function () {
    return 'Test route is working!';
});

// Test company route without any middleware
Route::get('/company/test', function () {
    return 'Company test route is working!';
});

// Test company dashboard route directly in web.php with all middleware
Route::get('/company/dashboard-test', [\App\Http\Controllers\Company\DashboardController::class, 'index'])
    ->middleware(['web', 'auth', 'verified', 'role:CompanyAdmin'])
    ->name('company.dashboard.test');

// Test company dashboard route without role middleware
Route::get('/company/dashboard-test-no-role', [\App\Http\Controllers\Company\DashboardController::class, 'index'])
    ->middleware(['web', 'auth', 'verified'])
    ->name('company.dashboard.test.no-role');
