<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
        
        // Check if user has admin role
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->hasRole('Super Admin')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $stats = [
            'total_companies' => Company::count(),
            'active_companies' => Company::where('status', true)->count(),
            'inactive_companies' => Company::where('status', false)->count(),
            'total_packages' => Package::count(),
            'active_packages' => Package::where('status', 'active')
                ->where('end_date', '>=', now())
                ->count(),
            'expired_packages' => Package::where('status', 'expired')
                ->orWhere('end_date', '<', now())
                ->count(),
            'total_staff' => User::role('Staff')->count(),
            'total_customers' => User::role('Customer')->count(),
        ];

        $recentCompanies = Company::latest()->take(5)->get();
        $recentPackages = Package::with('company')
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'recentCompanies', 'recentPackages'));
    }
}
