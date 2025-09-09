<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
    }

    /**
     * Display a listing of staff members.
     */
    public function index()
    {
        $company = auth()->user()->company;
        $staff = $company->users()
            ->with('roles')
            ->whereHas('roles', function($q) {
                $q->where('name', '!=', 'customer');
            })
            ->latest()
            ->paginate(15);

        return view('company.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $company = auth()->user()->company;
        $roles = Role::whereNotIn('name', ['super-admin', 'company'])
            ->where('company_id', $company->id)
            ->pluck('name', 'id');
            
        return view('company.staff.create', compact('roles'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(StoreStaffRequest $request)
    {
        $company = auth()->user()->company;
        
        // Check if company has reached staff limit based on package
        if ($company->hasReachedStaffLimit()) {
            return redirect()->back()
                ->with('error', 'You have reached the maximum number of staff allowed in your package.');
        }

        return DB::transaction(function () use ($request, $company) {
            // Create the staff user
            $user = new User($request->validated());
            $user->password = Hash::make($request->password);
            $user->company_id = $company->id;
            $user->save();

            // Assign roles
            $user->syncRoles($request->roles);

            // Handle document uploads
            if ($request->hasFile('documents')) {
                $this->uploadDocuments($user, $request->file('documents'));
            }

            // Send email verification
            $user->sendEmailVerificationNotification();

            return redirect()->route('company.staff.index')
                ->with('success', 'Staff member created successfully. An email has been sent for verification.');
        });
    }

    /**
     * Display the specified staff member.
     */
    public function show(User $staff)
    {
        $this->authorize('view', $staff);
        $staff->load('roles');
        
        return view('company.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(User $staff)
    {
        $this->authorize('update', $staff);
        
        $company = auth()->user()->company;
        $roles = Role::whereNotIn('name', ['super-admin', 'company'])
            ->where('company_id', $company->id)
            ->pluck('name', 'id');
            
        $staff->load('roles');
        
        return view('company.staff.edit', compact('staff', 'roles'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(UpdateStaffRequest $request, User $staff)
    {
        $this->authorize('update', $staff);

        return DB::transaction(function () use ($request, $staff) {
            // Update user data
            $staff->fill($request->validated());
            
            // Update password if provided
            if ($request->filled('password')) {
                $staff->password = Hash::make($request->password);
            }
            
            $staff->save();

            // Sync roles
            $staff->syncRoles($request->roles);

            // Handle document uploads
            if ($request->hasFile('documents')) {
                $this->uploadDocuments($staff, $request->file('documents'));
            }

            return redirect()->route('company.staff.show', $staff)
                ->with('success', 'Staff member updated successfully.');
        });
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(User $staff)
    {
        $this->authorize('delete', $staff);

        // Prevent deleting self
        if ($staff->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        // Soft delete the staff member
        $staff->delete();

        return redirect()->route('company.staff.index')
            ->with('success', 'Staff member has been deleted successfully.');
    }

    /**
     * Upload documents for a staff member.
     */
    protected function uploadDocuments(User $user, array $files): void
    {
        foreach ($files as $file) {
            $path = $file->store("companies/{$user->company_id}/staff/{$user->id}/documents", 'public');
            
            $user->documents()->create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
        }
    }
}
