<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::forCompany(Auth::user()->company_id)
            ->latest()
            ->paginate(10);

        return view('company.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['company_id'] = Auth::user()->company_id;

        Brand::create($validated);

        return redirect()
            ->route('company.brands.index')
            ->with('success', 'Brand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        $this->authorize('view', $brand);
        return view('company.brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        $this->authorize('update', $brand);
        return view('company.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $this->authorize('update', $brand);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $brand->update($validated);

        return redirect()
            ->route('company.brands.index')
            ->with('success', 'Brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $this->authorize('delete', $brand);
        
        // Check if brand has products
        if ($brand->products()->exists()) {
            return back()->with('error', 'Cannot delete brand with associated products.');
        }

        $brand->delete();

        return redirect()
            ->route('company.brands.index')
            ->with('success', 'Brand deleted successfully.');
    }

    /**
     * Toggle brand status
     */
    public function toggleStatus(Brand $brand)
    {
        $this->authorize('update', $brand);
        
        $brand->update([
            'status' => $brand->status === 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'Brand status updated successfully.');
    }
}
