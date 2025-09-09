<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the company dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $company = Auth::user()->company;
        
        $stats = [
            'brands' => \App\Models\Brand::forCompany($company->id)->count(),
            'products' => 0, // Will be updated when Product model is created
            'complaints' => 0, // Will be updated when Complaint model is created
            'staff' => 0, // Will be updated when Staff/User model is enhanced
        ];

        $recentActivities = []; // Will be populated with recent activities

        return view('company.dashboard', [
            'company' => $company,
            'stats' => $stats,
            'recentActivities' => $recentActivities
        ]);
    }
}
