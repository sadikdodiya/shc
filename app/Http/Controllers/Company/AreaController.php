<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Area::class);
        
        $areas = Area::forCompany(Auth::user()->company_id)
            ->withCount(['staff', 'complaints'])
            ->latest()
            ->paginate(10);
            
        return Inertia::render('Company/Areas/Index', [
            'areas' => $areas,
            'can' => [
                'create' => Auth::user()->can('create', Area::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Area::class);
        
        return Inertia::render('Company/Areas/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Area::class);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:10'],
            'address' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);
        
        $area = Auth::user()->company->areas()->create($validated);
        
        return redirect()->route('company.areas.index')
            ->with('success', 'Area created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        $this->authorize('view', $area);
        
        $area->loadCount(['staff', 'complaints']);
        
        return Inertia::render('Company/Areas/Show', [
            'area' => $area,
            'can' => [
                'update' => Auth::user()->can('update', $area),
                'delete' => Auth::user()->can('delete', $area),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        $this->authorize('update', $area);
        
        return Inertia::render('Company/Areas/Edit', [
            'area' => $area,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $this->authorize('update', $area);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:10'],
            'address' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ]);
        
        $area->update($validated);
        
        return redirect()->route('company.areas.index')
            ->with('success', 'Area updated successfully!');
    }

    /**
     * Toggle the status of the specified area.
     */
    public function toggleStatus(Area $area)
    {
        $this->authorize('toggleStatus', $area);
        
        $area->toggleStatus();
        
        $status = $area->status === 'active' ? 'activated' : 'deactivated';
        
        return back()->with('success', "Area {$status} successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $this->authorize('delete', $area);
        
        try {
            $area->delete();
            return back()->with('success', 'Area deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete area. It may have associated staff or complaints.');
        }
    }
}
