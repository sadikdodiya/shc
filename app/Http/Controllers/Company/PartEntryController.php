<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\PartEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function index(Item $item)
    {
        $this->authorize('view', $item);
        
        $entries = $item->partEntries()
            ->with('user')
            ->latest()
            ->paginate(15);
            
        return view('company.items.part-entries.index', [
            'item' => $item,
            'entries' => $entries
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function create(Item $item)
    {
        $this->authorize('update', $item);
        
        return view('company.items.part-entries.create', [
            'item' => $item,
            'entry' => new PartEntry()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Item $item)
    {
        $this->authorize('update', $item);
        
        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        // For out entries, check if there's enough stock
        if ($validated['type'] === 'out' && $item->stock_quantity < $validated['quantity']) {
            return back()
                ->withInput()
                ->with('error', 'Insufficient stock for this transaction.');
        }
        
        DB::beginTransaction();
        
        try {
            // Create the part entry
            $entry = $item->partEntries()->create([
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'reference' => $validated['reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'user_id' => Auth::id(),
            ]);
            
            // Update the item's stock
            $item->update([
                'stock_quantity' => $validated['type'] === 'in'
                    ? $item->stock_quantity + $validated['quantity']
                    : $item->stock_quantity - $validated['quantity']
            ]);
            
            DB::commit();
            
            return redirect()
                ->route('company.items.show', $item)
                ->with('success', 'Stock updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating part entry: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'An error occurred while updating the stock. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @param  \App\Models\PartEntry  $partEntry
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item, PartEntry $partEntry)
    {
        $this->authorize('view', $partEntry);
        
        return view('company.items.part-entries.show', [
            'item' => $item,
            'entry' => $partEntry->load('user')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @param  \App\Models\PartEntry  $partEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item, PartEntry $partEntry)
    {
        $this->authorize('update', $partEntry);
        
        return view('company.items.part-entries.edit', [
            'item' => $item,
            'entry' => $partEntry
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @param  \App\Models\PartEntry  $partEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item, PartEntry $partEntry)
    {
        $this->authorize('update', $partEntry);
        
        // Don't allow updates to entries older than 1 day
        if ($partEntry->created_at->diffInDays(now()) > 1) {
            return back()
                ->with('error', 'Cannot update entries older than 1 day.');
        }
        
        $validated = $request->validate([
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        $partEntry->update($validated);
        
        return redirect()
            ->route('company.items.part-entries.show', [$item, $partEntry])
            ->with('success', 'Entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @param  \App\Models\PartEntry  $partEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item, PartEntry $partEntry)
    {
        $this->authorize('delete', $partEntry);
        
        // Don't allow deletion of entries older than 1 day
        if ($partEntry->created_at->diffInDays(now()) > 1) {
            return back()
                ->with('error', 'Cannot delete entries older than 1 day.');
        }
        
        DB::beginTransaction();
        
        try {
            // Get the quantity to adjust the stock
            $quantity = $partEntry->quantity;
            $type = $partEntry->type;
            
            // Delete the entry
            $partEntry->delete();
            
            // Update the item's stock
            $item->update([
                'stock_quantity' => $type === 'in'
                    ? $item->stock_quantity - $quantity
                    : $item->stock_quantity + $quantity
            ]);
            
            DB::commit();
            
            return redirect()
                ->route('company.items.show', $item)
                ->with('success', 'Entry deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting part entry: ' . $e->getMessage());
            
            return back()
                ->with('error', 'An error occurred while deleting the entry. Please try again.');
        }
    }
    
    /**
     * Update the item's stock directly (for quick updates from the item show page).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function updateStock(Request $request, Item $item)
    {
        $this->authorize('update', $item);
        
        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);
        
        // For out entries, check if there's enough stock
        if ($validated['type'] === 'out' && $item->stock_quantity < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock for this transaction.'
            ], 422);
        }
        
        DB::beginTransaction();
        
        try {
            // Create the part entry
            $entry = $item->partEntries()->create([
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'notes' => $validated['notes'] ?? null,
                'user_id' => Auth::id(),
            ]);
            
            // Update the item's stock
            $item->update([
                'stock_quantity' => $validated['type'] === 'in'
                    ? $item->stock_quantity + $validated['quantity']
                    : $item->stock_quantity - $validated['quantity']
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully.',
                'data' => [
                    'entry' => $entry->load('user'),
                    'item' => $item->fresh()
                ]
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating stock: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the stock. Please try again.'
            ], 500);
        }
    }
}
