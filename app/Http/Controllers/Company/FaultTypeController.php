<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\FaultType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FaultTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', FaultType::class);
        
        $faultTypes = FaultType::where('company_id', Auth::user()->company_id)
            ->latest()
            ->paginate(10);
            
        return view('company.fault-types.index', compact('faultTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', FaultType::class);
        
        return view('company.fault-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', FaultType::class);
        
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fault_types')
                    ->where('company_id', Auth::user()->company_id)
            ],
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        $faultType = new FaultType($validated);
        $faultType->company_id = Auth::user()->company_id;
        $faultType->save();
        
        return redirect()
            ->route('company.fault-types.index')
            ->with('success', 'Fault type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FaultType $faultType)
    {
        $this->authorize('view', $faultType);
        
        return view('company.fault-types.show', compact('faultType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FaultType $faultType)
    {
        $this->authorize('update', $faultType);
        
        return view('company.fault-types.edit', compact('faultType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FaultType $faultType)
    {
        $this->authorize('update', $faultType);
        
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fault_types')
                    ->ignore($faultType->id)
                    ->where('company_id', Auth::user()->company_id)
            ],
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        $faultType->update($validated);
        
        return redirect()
            ->route('company.fault-types.index')
            ->with('success', 'Fault type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FaultType $faultType)
    {
        $this->authorize('delete', $faultType);
        
        try {
            $faultType->delete();
            return redirect()
                ->route('company.fault-types.index')
                ->with('success', 'Fault type deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('company.fault-types.index')
                ->with('error', 'Cannot delete fault type. It may be associated with existing complaints.');
        }
    }
    
    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(FaultType $faultType)
    {
        $this->authorize('toggleStatus', $faultType);
        
        $faultType->toggleStatus();
        
        $status = $faultType->status === 'active' ? 'activated' : 'deactivated';
        
        return redirect()
            ->back()
            ->with('success', "Fault type {$status} successfully.");
    }
}
