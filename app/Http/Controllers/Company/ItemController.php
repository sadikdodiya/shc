<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Item::class);
        
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search');
        $status = $request->input('status');
        $category = $request->input('category');
        $lowStock = $request->boolean('low_stock');
        
        $items = Item::query()
            ->where('company_id', Auth::user()->company_id)
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($category, function($query, $category) {
                $query->where('category', $category);
            })
            ->when($lowStock, function($query) {
                $query->whereRaw('stock_quantity <= minimum_stock');
            })
            ->orderBy('name')
            ->paginate($perPage);
        
        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Item::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('items', 'code')
                    ->where('company_id', Auth::user()->company_id)
            ],
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0|gte:purchase_price',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);
        
        // Auto-generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = 'ITM-' . strtoupper(Str::random(8));
        }
        
        $validated['company_id'] = Auth::user()->company_id;
        
        $item = Item::create($validated);
        
        return response()->json([
            'message' => 'Item created successfully',
            'data' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $this->authorize('view', $item);
        
        // Load related data if needed
        $item->load(['company', 'partEntries' => function($query) {
            $query->latest()->limit(10);
        }]);
        
        return response()->json([
            'data' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('items', 'code')
                    ->ignore($item->id)
                    ->where('company_id', Auth::user()->company_id)
            ],
            'description' => 'nullable|string',
            'category' => 'sometimes|required|string|max:100',
            'unit' => 'sometimes|required|string|max:20',
            'purchase_price' => 'sometimes|required|numeric|min:0',
            'selling_price' => 'sometimes|required|numeric|min:0|gte:purchase_price',
            'minimum_stock' => 'sometimes|required|integer|min:0',
            'status' => 'sometimes|required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);
        
        $item->update($validated);
        
        return response()->json([
            'message' => 'Item updated successfully',
            'data' => $item->fresh()
        ]);
    }
    
    /**
     * Update the stock quantity of the specified resource.
     */
    public function updateStock(Request $request, Item $item)
    {
        $this->authorize('updateStock', $item);
        
        $validated = $request->validate([
            'quantity' => 'required|integer|not_in:0',
            'type' => 'required|in:in,out',
            'notes' => 'nullable|string',
        ]);
        
        // Check if we have enough stock for outbound
        if ($validated['type'] === 'out' && $item->stock_quantity < abs($validated['quantity'])) {
            return response()->json([
                'message' => 'Insufficient stock',
                'errors' => [
                    'quantity' => ['Insufficient stock available']
                ]
            ], 422);
        }
        
        // Update stock
        $item->updateStock(abs($validated['quantity']), $validated['type']);
        
        // Create part entry record (will be implemented in PartEntryController)
        // $partEntry = $item->partEntries()->create([
        //     'user_id' => Auth::id(),
        //     'quantity' => $validated['quantity'],
        //     'type' => $validated['type'],
        //     'notes' => $validated['notes'] ?? null,
        // ]);
        
        return response()->json([
            'message' => 'Stock updated successfully',
            'data' => [
                'item' => $item->fresh(),
                // 'part_entry' => $partEntry
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);
        
        try {
            $item->delete();
            
            return response()->json([
                'message' => 'Item deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cannot delete item. It may have associated records.',
                'error' => $e->getMessage()
            ], 422);
        }
    }
    
    /**
     * Get the categories for the items.
     */
    public function categories()
    {
        $categories = Item::where('company_id', Auth::user()->company_id)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter();
            
        return response()->json([
            'data' => $categories
        ]);
    }
}
