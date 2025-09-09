<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('brand')
            ->whereHas('brand', function($query) {
                $query->where('company_id', Auth::user()->company_id);
            })
            ->latest()
            ->paginate(10);

        return view('company.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::forCompany(Auth::user()->company_id)
            ->active()
            ->pluck('name', 'id');

        return view('company.products.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => [
                'required',
                'exists:brands,id',
                Rule::exists('brands', 'id')->where(function ($query) {
                    $query->where('company_id', Auth::user()->company_id);
                })
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Product::create($validated);

        return redirect()
            ->route('company.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $this->authorize('view', $product);
        return view('company.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        
        $brands = Brand::forCompany(Auth::user()->company_id)
            ->active()
            ->pluck('name', 'id');

        return view('company.products.edit', compact('product', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'brand_id' => [
                'required',
                'exists:brands,id',
                Rule::exists('brands', 'id')->where(function ($query) {
                    $query->where('company_id', Auth::user()->company_id);
                })
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $product->update($validated);

        return redirect()
            ->route('company.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        // Check if product has associated complaints
        if ($product->complaints()->exists()) {
            return back()->with('error', 'Cannot delete product with associated complaints.');
        }

        $product->delete();

        return redirect()
            ->route('company.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(Product $product)
    {
        $this->authorize('update', $product);
        
        $product->update([
            'status' => $product->status === 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'Product status updated successfully.');
    }
}
