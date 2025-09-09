<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the packages.
     */
    public function index()
    {
        $packages = Package::with('company')
            ->latest()
            ->paginate(10);
            
        return view('superadmin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        $companies = Company::where('status', true)->get();
        return view('superadmin.packages.create', compact('companies'));
    }

    /**
     * Store a newly created package in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
            'staff_limit' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
        ]);

        // Calculate end date based on start date and duration
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = (clone $startDate)->addMonths($validated['duration_months']);

        // Check if company already has an active package
        $existingActivePackage = Package::where('company_id', $validated['company_id'])
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->exists();

        if ($existingActivePackage) {
            return back()
                ->withInput()
                ->with('warning', 'This company already has an active package. Please expire the current package first.');
        }

        // Create the package
        Package::create([
            'company_id' => $validated['company_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'duration_months' => $validated['duration_months'],
            'staff_limit' => $validated['staff_limit'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'features' => $validated['features'] ?? [],
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified package.
     */
    public function show(Package $package)
    {
        $package->load('company');
        return view('superadmin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(Package $package)
    {
        $companies = Company::where('status', true)->get();
        return view('superadmin.packages.edit', compact('package', 'companies'));
    }

    /**
     * Update the specified package in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'staff_limit' => 'required|integer|min:1',
            'status' => 'required|in:active,pending,expired,cancelled',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
        ]);

        $package->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'staff_limit' => $validated['staff_limit'],
            'status' => $validated['status'],
            'features' => $validated['features'] ?? [],
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified package from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    /**
     * Update package status to expired.
     */
    public function expire(Package $package)
    {
        if ($package->status !== 'expired') {
            $package->update(['status' => 'expired']);
            return back()->with('success', 'Package marked as expired.');
        }

        return back()->with('info', 'Package is already expired.');
    }
}
